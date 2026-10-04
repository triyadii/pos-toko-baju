<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WaServiceController extends Controller
{
    private $apiUrl;
    private $token;

    public function __construct()
    {
        $this->apiUrl = env('WA_API_URL');
        $this->token = env('WA_API_KEY');
    }

    private function getHeaders()
    {
        return [
            'accept' => 'application/json',
            'X-API-KEY' => $this->token,
            'X-CSRF-TOKEN' => '',
        ];
    }

    public function index(Request $request)
    {
        $status = null;
        try {
            $response = Http::withHeaders($this->getHeaders())->get($this->apiUrl . '/status');
            $status = $response->json();
        } catch (\Exception $e) {
            $status = ['error' => 'Could not connect to WA Service: ' . $e->getMessage()];
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'success', 'data' => $status]);
        }
        
        return view('waservice.index', compact('status'));
    }

    public function chats(Request $request)
    {
        $chats = [];
        try {
            $chatsResponse = Http::withHeaders($this->getHeaders())->get($this->apiUrl . '/chats');
            if ($chatsResponse->successful() && isset($chatsResponse->json()['data'])) {
                $chats = $chatsResponse->json()['data'];
            }
        } catch (\Exception $e) {
            // handle error if needed
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'success', 'data' => $chats]);
        }
        
        return view('waservice.chats', compact('chats'));
    }

    public function start(Request $request)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->post($this->apiUrl . '/start', []);
            if ($response->successful()) {
                return back()->with('success', 'WA Service start request sent successfully!');
            }
            return back()->with('error', 'Failed to start WA Service. Server responded with: ' . $response->status());
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function send(Request $request)
    {
        $request->validate([
            'number' => 'required|string',
            'message' => 'required|string',
        ]);

        try {
            $headers = $this->getHeaders();
            $headers['Content-Type'] = 'application/json';
            
            $response = Http::withHeaders($headers)->post($this->apiUrl . '/send', [
                'number' => $request->number,
                'message' => $request->message,
            ]);

            if ($response->successful()) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['status' => 'success', 'message' => 'Message sent successfully!']);
                }
                return back()->with('success', 'Message sent successfully!');
            }
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Failed to send message: ' . $response->status()], 400);
            }
            return back()->with('error', 'Failed to send message. Server responded with: ' . $response->status());
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function getChatHistory($senderId)
    {
        try {
            // Because URL might have @ symbol, it's safer to pass as path param directly
            $response = Http::withHeaders($this->getHeaders())->get($this->apiUrl . '/chats/' . urlencode($senderId));
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch history'], 400);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
