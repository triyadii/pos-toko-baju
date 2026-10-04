<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\BarangVarian;

class WaWebhookController extends Controller
{
    private $waApiUrl;
    private $waToken;
    private $groqApiUrl;
    private $groqToken;
    private $groqModel;

    public function __construct()
    {
        $this->waApiUrl = env('WA_API_URL');
        $this->waToken = env('WA_API_KEY');
        $this->groqApiUrl = env('GROQ_API_URL');
        $this->groqToken = env('GROQ_API_KEY');
        $this->groqModel = env('GROQ_MODEL');
    }

    public function handle(Request $request)
    {
        Log::info('Incoming WA Webhook:', $request->all());

        // Extract message and sender number from different possible formats
        $payload = $request->all();
        $messageText = $payload['message'] ?? $payload['text'] ?? $payload['body'] ?? null;
        $sender = $payload['from'] ?? $payload['sender'] ?? $payload['number'] ?? $payload['senderId'] ?? null;
        $fromMe = $payload['fromMe'] ?? false;

        if (!$messageText || !$sender || $fromMe) {
            return response()->json(['status' => 'ignored', 'reason' => 'Missing data or fromMe is true']);
        }

        if (str_contains($sender, '@g.us') || str_contains($sender, 'status')) {
            return response()->json(['status' => 'ignored', 'reason' => 'Group/Status message']);
        }
        
        // Use senderId exactly as received from WA Gateway
        // $sender = str_replace(['@c.us', '@lid', '@s.whatsapp.net'], '', $sender);

        try {
            $context = $this->getDatabaseContext();
            $reply = $this->askGroq($messageText, $context);

            $this->sendWaMessage($sender, $reply);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('WA Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function getDatabaseContext()
    {
        $varians = BarangVarian::with(['barang.jenisBarang', 'warna', 'ukuran', 'stok'])->get();
        
        if ($varians->isEmpty()) {
            return "Saat ini tidak ada data produk di database.";
        }

        $context = "Berikut adalah daftar produk yang tersedia di toko baju kami beserta stok dan harganya:\n\n";
        foreach ($varians as $v) {
            $nama = $v->barang->nama_barang ?? 'Unknown';
            $jenis = $v->barang->jenisBarang->nama_jenis_barang ?? '-';
            $warna = $v->warna->nama_warna ?? '-';
            $ukuran = $v->ukuran->nama_ukuran ?? '-';
            $harga = $v->harga_jual ?? $v->barang->harga_jual ?? 0;
            $stok = $v->stok->jumlah_stok ?? 0;

            $context .= "- Produk: $nama\n";
            $context .= "  Jenis: $jenis\n";
            $context .= "  Varian: Warna $warna, Ukuran $ukuran\n";
            $context .= "  Harga: Rp " . number_format($harga, 0, ',', '.') . "\n";
            $context .= "  Stok: $stok\n\n";
        }

        return $context;
    }

    private function askGroq($userMessage, $context)
    {
        $systemPrompt = "Kamu adalah asisten virtual (customer service) untuk sebuah toko baju. "
            . "Tugasmu adalah menjawab pertanyaan pelanggan berdasarkan data produk yang diberikan. "
            . "Jika ada pertanyaan yang tidak terkait dengan produk, jawab dengan sopan bahwa kamu hanya bisa membantu seputar produk toko. "
            . "Gunakan bahasa Indonesia yang ramah, profesional, dan mudah dipahami. "
            . "PENTING: Jangan pernah merender data dalam bentuk Markdown Table (tabel). Selalu gunakan format list/daftar (bullet points) agar rapi dan mudah dibaca di WhatsApp.\n\n"
            . "Data Produk:\n" . $context;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->groqToken,
            'Content-Type' => 'application/json',
        ])->post($this->groqApiUrl, [
            'model' => $this->groqModel,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userMessage],
            ],
            'temperature' => 0.7,
            'max_tokens' => 500,
        ]);

        if ($response->successful()) {
            return $response->json('choices.0.message.content') ?? 'Maaf, saya tidak dapat memproses jawaban saat ini.';
        }

        Log::error('Groq API Error: ' . $response->body());
        throw new \Exception('Failed to get response from Groq AI');
    }

    private function sendWaMessage($number, $message)
    {
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'X-API-KEY' => $this->waToken,
            'X-CSRF-TOKEN' => '',
            'Content-Type' => 'application/json',
        ])->post($this->waApiUrl . '/send', [
            'number' => $number,
            'message' => $message,
        ]);

        if (!$response->successful()) {
            Log::error('WA Send Error: ' . $response->body());
            throw new \Exception('Failed to send WA message: ' . $response->status());
        }
    }
}
