<div class="comment-item">
    <div class="comment-author">
        <img src="{{ $comment->createdBy->icon ? Storage::url($comment->createdBy->icon) : asset('images/default_template/ava_cover.png') }}"
             alt="{{ $comment->createdBy->nickname }}" class="avatar">
        <a href="{{ route('profile.index', ['nickname' => $comment->createdBy->nickname]) }}">
            {{ '@' . $comment->createdBy->nickname }}
        </a>
        <small>{{ $comment->created_at->format('d.m.Y H:i') }}</small>
    </div>
    <p>{{ $comment->comment }}</p>
</div>