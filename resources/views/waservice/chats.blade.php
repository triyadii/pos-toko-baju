@extends('layouts.app')

@section('content')
<div id="kt_app_content_container" class="app-container container-xxl">
    <div class="row g-5 g-xl-8">
        <div class="col-12">
            <!-- Chat History Panel -->
            <div class="card mb-5 mb-xl-8">
                <div class="card-header align-items-center border-0 mt-4">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="fw-bold mb-2 text-gray-900">Riwayat Chat</span>
                        <span class="text-muted fw-semibold fs-7">Riwayat percakapan WhatsApp yang terekam</span>
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $groupedChats = collect($chats ?? [])->groupBy('sender_id')->sortByDesc(function ($group) {
                            return $group->max('created_at');
                        });
                        $firstUserId = $groupedChats->keys()->first();
                    @endphp

                    <div class="row g-0">
                        <!-- Left Pane: Contact List -->
                        <div class="col-md-4 col-lg-3 pe-4">
                            <div class="border rounded bg-body" style="height: 600px; overflow-y: auto;">
                                <div class="list-group list-group-flush" id="chat-list-tab" role="tablist">
                                    @forelse($groupedChats as $senderId => $messages)
                                    @php
                                        $lastMsg = $messages->sortByDesc('created_at')->first();
                                        $senderName = $lastMsg['sender_name'] ?? $senderId;
                                        $time = isset($lastMsg['created_at']) ? \Carbon\Carbon::parse($lastMsg['created_at'])->format('H:i') : '';
                                        $isActive = $senderId === $firstUserId ? 'active' : '';
                                    @endphp
                                    <a class="list-group-item list-group-item-action {{ $isActive }} p-4 border-bottom" data-sender-id="{{ $senderId }}" href="javascript:void(0);">
                                        <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px symbol-circle me-3">
                                                    <span class="symbol-label bg-light-primary text-primary fw-bold fs-5">{{ substr($senderName, 0, 1) }}</span>
                                                </div>
                                                <h5 class="mb-0 fw-bold text-truncate" style="max-width: 150px;">{{ $senderName }}</h5>
                                            </div>
                                            <small class="text-muted fs-8">{{ $time }}</small>
                                        </div>
                                        <div class="text-muted fs-7 text-truncate ms-14">
                                            @if($lastMsg['role'] == 'assistant')
                                                <i class="ki-duotone ki-check-all fs-6 text-primary me-1"><span class="path1"></span><span class="path2"></span></i>
                                            @endif
                                            {{ Str::limit($lastMsg['content'], 40) }}
                                        </div>
                                    </a>
                                    @empty
                                    <div class="p-5 text-center text-muted">Belum ada obrolan</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Right Pane: Chat Messages -->
                        <div class="col-md-8 col-lg-9 ps-4">
                            <div class="border rounded bg-body-secondary position-relative" style="height: 600px; overflow: hidden;">
                                <div class="chat-container d-flex flex-column gap-5" id="chat-messages-container" style="height: 530px; padding: 2rem !important; overflow-y: auto;">
                                    <div class="text-center text-muted p-3 rounded mt-5 mx-auto bg-body" style="max-width: 350px; font-size: 0.85rem;">
                                        Silakan pilih kontak di sebelah kiri untuk melihat riwayat obrolan lengkap.
                                    </div>
                                </div>
                                
                                <!-- Loading overlay -->
                                <div id="chat-loading" class="position-absolute w-100 h-100 top-0 start-0 d-flex justify-content-center align-items-center d-none" style="background: rgba(var(--bs-body-bg-rgb), 0.7); z-index: 10;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                
                                <!-- Footer Input Real Input -->
                                <div class="p-3 d-flex align-items-center border-top bg-body" style="position: absolute; bottom: 0; width: 100%; height: 70px;">
                                    <input type="text" id="chat-input-message" class="form-control form-control-solid me-3 border-0 bg-body-secondary" style="border-radius: 8px;" placeholder="Ketik pesan untuk membalas..." disabled>
                                    <button type="button" id="chat-btn-send" class="btn btn-primary btn-icon disabled" style="border-radius: 50%; width: 45px; height: 45px;"><i class="ki-duotone ki-send fs-2"><span class="path1"></span><span class="path2"></span></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .markdown-chat p:last-child {
        margin-bottom: 0;
    }
    .markdown-chat ul {
        padding-left: 20px;
        margin-bottom: 10px;
    }
    .markdown-chat ul:last-child {
        margin-bottom: 0;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let activeSenderId = null;
        let pollingInterval = null;
        let isScrolledToBottom = true;
        const chatContainer = document.getElementById('chat-messages-container');
        const inputMessage = document.getElementById('chat-input-message');
        const btnSend = document.getElementById('chat-btn-send');
        
        // Handle scroll to check if user scrolled up
        chatContainer.addEventListener('scroll', function() {
            isScrolledToBottom = (chatContainer.scrollHeight - chatContainer.scrollTop - chatContainer.clientHeight) < 50;
        });
        
        // Handle Send Message
        function sendMessage() {
            const message = inputMessage.value.trim();
            if (!message || !activeSenderId) return;
            
            // Disable while sending
            inputMessage.disabled = true;
            btnSend.classList.add('disabled');
            
            // Optimistic UI update
            const html = `
            <div class="d-flex justify-content-end mb-4 opacity-50" id="optimistic-msg">
                <div class="p-3 rounded text-inverse-primary shadow-sm bg-primary" style="max-width: 80%; border-radius: 10px 0 10px 10px !important; line-height: 1.6;">
                    <div class="fs-6 markdown-chat">${message.replace(/\n/g, '<br>')}</div>
                    <div class="text-end opacity-75 fs-8 mt-1">Mengirim...</div>
                </div>
            </div>`;
            chatContainer.insertAdjacentHTML('beforeend', html);
            scrollToBottom();
            
            fetch("{{ route('waservice.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    number: activeSenderId,
                    message: message
                })
            })
            .then(res => res.json())
            .then(data => {
                inputMessage.value = '';
                inputMessage.disabled = false;
                btnSend.classList.remove('disabled');
                inputMessage.focus();
                
                // Fetch to clear optimistic msg and get real state
                fetchChatHistory(false);
                fetchAllChats(false);
            })
            .catch(err => {
                alert('Gagal mengirim pesan');
                inputMessage.disabled = false;
                btnSend.classList.remove('disabled');
                const optMsg = document.getElementById('optimistic-msg');
                if (optMsg) optMsg.remove();
            });
        }
        
        btnSend.addEventListener('click', sendMessage);
        inputMessage.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            }
        });
        
        function fetchAllChats(showLoading) {
            fetch("{{ route('waservice.chats') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(res => {
                if (showLoading && activeSenderId) {
                    document.getElementById('chat-loading').classList.add('d-none');
                }
                
                if (res.status === 'success' && res.data) {
                    const allChats = res.data;
                    renderContactList(allChats);
                }
            })
            .catch(err => {
                console.error(err);
            });
        }
        
        function fetchChatHistory(showLoading) {
            if (!activeSenderId) return;
            
            fetch(`{{ url('waservice/chat-history') }}/${encodeURIComponent(activeSenderId)}`)
                .then(response => response.json())
                .then(res => {
                    if (showLoading) document.getElementById('chat-loading').classList.add('d-none');
                    
                    if (res.status === 'success' && res.data) {
                        renderChats(res.data);
                    }
                })
                .catch(err => {
                    console.error(err);
                    if (showLoading) document.getElementById('chat-loading').classList.add('d-none');
                });
        }
        
        function renderContactList(chats) {
            const grouped = {};
            chats.forEach(c => {
                if (!grouped[c.sender_id]) grouped[c.sender_id] = [];
                grouped[c.sender_id].push(c);
            });
            
            const senderIds = Object.keys(grouped).sort((a, b) => {
                const lastA = new Date(grouped[a][grouped[a].length - 1].created_at);
                const lastB = new Date(grouped[b][grouped[b].length - 1].created_at);
                return lastB - lastA;
            });
            
            let html = '';
            if (senderIds.length === 0) {
                html = '<div class="p-5 text-center text-muted">Belum ada obrolan</div>';
            } else {
                senderIds.forEach(senderId => {
                    const messages = grouped[senderId];
                    const lastMsg = messages[messages.length - 1];
                    const senderName = lastMsg.sender_name || senderId;
                    
                    let time = '';
                    if (lastMsg.created_at) {
                        time = new Date(lastMsg.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                    }
                    
                    const isActive = senderId == activeSenderId ? 'active' : '';
                    const checkIcon = lastMsg.role === 'assistant' ? '<i class="ki-duotone ki-check-all fs-6 text-primary me-1"><span class="path1"></span><span class="path2"></span></i>' : '';
                    let contentLimit = (lastMsg.content || '').substring(0, 40);
                    
                    html += `
                    <a class="list-group-item list-group-item-action ${isActive} p-4 border-bottom contact-link" data-sender-id="${senderId}" href="javascript:void(0);">
                        <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40px symbol-circle me-3">
                                    <span class="symbol-label bg-light-primary text-primary fw-bold fs-5">${senderName.substring(0, 1)}</span>
                                </div>
                                <h5 class="mb-0 fw-bold text-truncate" style="max-width: 150px;">${senderName}</h5>
                            </div>
                            <small class="text-muted fs-8">${time}</small>
                        </div>
                        <div class="text-muted fs-7 text-truncate ms-14">
                            ${checkIcon} ${contentLimit}
                        </div>
                    </a>`;
                });
            }
            
            const chatListTab = document.getElementById('chat-list-tab');
            
            // Only update DOM if HTML changed to prevent flicker and losing scroll
            if (chatListTab.innerHTML !== html) {
                chatListTab.innerHTML = html;
                attachContactListeners();
            }
        }
        
        function attachContactListeners() {
            const contactLinks = document.querySelectorAll('#chat-list-tab .contact-link');
            contactLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Don't do anything if clicking the already active contact
                    if (activeSenderId == this.getAttribute('data-sender-id')) return;
                    
                    document.querySelectorAll('#chat-list-tab .list-group-item').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                    
                    activeSenderId = this.getAttribute('data-sender-id');
                    chatContainer.innerHTML = '';
                    document.getElementById('chat-loading').classList.remove('d-none');
                    
                    inputMessage.removeAttribute('disabled');
                    btnSend.classList.remove('disabled');
                    
                    isScrolledToBottom = true;
                    fetchChatHistory(true);
                });
            });
        }
        
        // Initial setup for the server-rendered contacts
        const initialLinks = document.querySelectorAll('#chat-list-tab .list-group-item');
        initialLinks.forEach(link => link.classList.add('contact-link'));
        attachContactListeners();
        
        if (initialLinks.length > 0) {
            initialLinks[0].click();
        }
        
        // Start polling for both chats and active chat history immediately
        pollingInterval = setInterval(() => {
            fetchAllChats(false);
            fetchChatHistory(false);
        }, 3000);
        
        function renderChats(messages) {
            messages.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
            
            let html = '';
            messages.forEach(chat => {
                const time = new Date(chat.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                const date = new Date(chat.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short'});
                const parsedContent = marked.parse(chat.content || '');
                
                if (chat.role === 'assistant') {
                    html += `
                    <div class="d-flex justify-content-end mb-4">
                        <div class="p-3 rounded text-inverse-primary shadow-sm bg-primary" style="max-width: 80%; border-radius: 10px 0 10px 10px !important; line-height: 1.6;">
                            <div class="fs-6 markdown-chat">${parsedContent}</div>
                            <div class="text-end opacity-75 fs-8 mt-1">${date} ${time}</div>
                        </div>
                    </div>`;
                } else {
                    html += `
                    <div class="d-flex justify-content-start mb-4">
                        <div class="p-3 rounded text-gray-900 shadow-sm bg-body" style="max-width: 80%; border-radius: 0 10px 10px 10px !important; line-height: 1.6;">
                            <div class="fs-6 markdown-chat">${parsedContent}</div>
                            <div class="text-end text-muted fs-8 mt-1">${date} ${time}</div>
                        </div>
                    </div>`;
                }
            });
            
            const optMsg = document.getElementById('optimistic-msg');
            if (optMsg && chatContainer.innerHTML.includes('optimistic-msg')) {
               // Wait for real state
            } else if (chatContainer.innerHTML !== html) {
                chatContainer.innerHTML = html;
                if (isScrolledToBottom) {
                    scrollToBottom();
                }
            }
        }
        
        function scrollToBottom() {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
</script>
@endsection
