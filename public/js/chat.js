// Chat JavaScript functionality
class ChatApp {
    constructor() {
        this.currentConversationId = this.getMetaContent('conversation-id');
        this.lastMessageId = parseInt(this.getMetaContent('last-message-id')) || 0;
        this.userId = this.getMetaContent('user-id');
        this.pollingInterval = null;
        this.typingTimer = null;
        this.isTyping = false;
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.setupCSRFToken();
        
        if (this.currentConversationId) {
            this.startPolling();
            this.scrollToBottom();
        }
    }
    
    getMetaContent(name) {
        const meta = document.querySelector(`meta[name="${name}"]`);
        return meta ? meta.getAttribute('content') : null;
    }
    
    setupCSRFToken() {
        const token = this.getMetaContent('csrf-token');
        if (token && window.fetch) {
            // Store token for fetch requests
            this.csrfToken = token;
        }
    }
    
    setupEventListeners() {
        // Envío de mensajes
        const messageForm = document.getElementById('messageForm');
        if (messageForm) {
            messageForm.addEventListener('submit', (e) => this.handleMessageSubmit(e));
        }
        
        // Teclas en el input de mensaje
        const messageInput = document.getElementById('messageInput');
        if (messageInput) {
            messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    messageForm.dispatchEvent(new Event('submit'));
                }
            });
            
            // Indicador de escritura
            messageInput.addEventListener('input', () => this.handleTyping());
        }
        
        // Click en conversaciones
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const conversationId = item.dataset.conversationId;
                this.loadConversation(conversationId);
            });
        });
    }
    
    async handleMessageSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const messageInput = document.getElementById('messageInput');
        const content = messageInput.value.trim();
        
        if (!content) return;
        
        // Limpiar input inmediatamente
        messageInput.value = '';
        
        // Mostrar mensaje temporalmente
        this.addTemporaryMessage(content);
        
        try {
            const response = await fetch('/chat/send', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': this.getMetaContent('csrf-token'),
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Remover mensaje temporal y agregar el real
                this.removeTemporaryMessage();
                this.addMessage(data.html);
                this.updateLastMessageId(data.message.id);
                this.scrollToBottom();
            } else {
                this.showError('Error al enviar mensaje');
                this.removeTemporaryMessage();
                messageInput.value = content; // Restaurar mensaje
            }
        } catch (error) {
            console.error('Error sending message:', error);
            this.showError('Error de conexión');
            this.removeTemporaryMessage();
            messageInput.value = content; // Restaurar mensaje
        }
    }
    
    addTemporaryMessage(content) {
        const container = document.getElementById('messagesContainer');
        const tempMessage = document.createElement('div');
        tempMessage.className = 'message message-own temporary-message';
        tempMessage.innerHTML = `
            <div class="message-content">
                <div class="message-bubble">
                    <p class="message-text">${this.escapeHtml(content)}</p>
                    <div class="message-meta">
                        <span class="message-time">Enviando...</span>
                        <span class="message-status">
                            <span class="sending-indicator">⏳</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="message-avatar message-avatar-own">
                ${document.querySelector('.user-avatar').textContent}
            </div>
        `;
        container.appendChild(tempMessage);
        this.scrollToBottom();
    }
    
    removeTemporaryMessage() {
        const tempMessage = document.querySelector('.temporary-message');
        if (tempMessage) {
            tempMessage.remove();
        }
    }
    
    addMessage(html) {
        const container = document.getElementById('messagesContainer');
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html;
        const messageElement = wrapper.firstElementChild;
        
        if (messageElement) {
            messageElement.classList.add('new-message');
            container.appendChild(messageElement);
        }
    }
    
    startPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
        }
        
        this.pollingInterval = setInterval(() => {
            this.checkForNewMessages();
        }, 3000); // Poll every 3 seconds
    }
    
    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
    }
    
    async checkForNewMessages() {
        if (!this.currentConversationId) return;
        
        try {
            const response = await fetch(`/chat/messages?conversation_id=${this.currentConversationId}&last_message_id=${this.lastMessageId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getMetaContent('csrf-token')
                }
            });
            
            const data = await response.json();
            
            if (data.messages && data.messages.length > 0) {
                const container = document.getElementById('messagesContainer');
                const wrapper = document.createElement('div');
                wrapper.innerHTML = data.html;
                
                // Agregar cada nuevo mensaje
                Array.from(wrapper.children).forEach(messageElement => {
                    messageElement.classList.add('new-message');
                    container.appendChild(messageElement);
                });
                
                this.updateLastMessageId(data.last_message_id);
                this.scrollToBottom();
                
                // Reproducir sonido de notificación (opcional)
                this.playNotificationSound();
            }
        } catch (error) {
            console.error('Error checking for new messages:', error);
        }
    }
    
    updateLastMessageId(messageId) {
        this.lastMessageId = messageId;
        const meta = document.querySelector('meta[name="last-message-id"]');
        if (meta) {
            meta.setAttribute('content', messageId);
        }
    }
    
    scrollToBottom() {
        const container = document.getElementById('messagesContainer');
        if (container) {
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 100);
        }
    }
    
    loadConversation(conversationId) {
        window.location.href = `/chat?conversation=${conversationId}`;
    }
    
    handleTyping() {
        if (this.typingTimer) {
            clearTimeout(this.typingTimer);
        }
        
        if (!this.isTyping) {
            this.isTyping = true;
            // Aquí podrías enviar una notificación de "escribiendo"
        }
        
        this.typingTimer = setTimeout(() => {
            this.isTyping = false;
            // Aquí podrías enviar una notificación de "dejó de escribir"
        }, 1000);
    }
    
    showError(message) {
        // Mostrar notificación de error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'chat-error';
        errorDiv.textContent = message;
        errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ef4444;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        `;
        
        document.body.appendChild(errorDiv);
        
        setTimeout(() => {
            errorDiv.remove();
        }, 3000);
    }
    
    playNotificationSound() {
        // Reproducir sonido sutil de notificación
        try {
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DE');
            audio.volume = 0.1;
            audio.play().catch(() => {
                // Ignore errors (might not have permission to play)
            });
        } catch (e) {
            // Ignore audio errors
        }
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Funciones globales para compatibilidad
function loadConversation(conversationId) {
    if (window.chatApp) {
        window.chatApp.loadConversation(conversationId);
    } else {
        window.location.href = `/chat?conversation=${conversationId}`;
    }
}

function refreshMessages() {
    if (window.chatApp) {
        window.chatApp.checkForNewMessages();
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.chatApp = new ChatApp();
});

// Limpiar al salir de la página
window.addEventListener('beforeunload', function() {
    if (window.chatApp) {
        window.chatApp.stopPolling();
    }
});

// Agregar estilos CSS dinámicamente
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .sending-indicator {
        animation: pulse 1s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .temporary-message {
        opacity: 0.7;
    }
    
    .temporary-message .message-bubble {
        background: #6b7280 !important;
    }
`;
document.head.appendChild(style);
