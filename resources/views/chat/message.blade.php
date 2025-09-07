<div class="message-item {{ $message->user_id == Auth::id() ? 'sent' : 'received' }}" data-message-id="{{ $message->id }}">
    @if($message->user_id != Auth::id())
        <strong>{{ $message->user->name }}:</strong>
    @endif
    {{ $message->content }}
    <small class="message-time">{{ $message->created_at->format('H:i') }}</small>
</div>

