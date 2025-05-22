<div class="comment-item">
    <div class="comment-header">

        <div class="comment-author-avatar-wrapper">
            <img src="{{ $comment->createdBy->icon ? Storage::url($comment->createdBy->icon) : asset('images/default_template/ava_cover.png') }}"
                 alt="{{ $comment->createdBy->nickname }}" class="avatar">
        </div>

        <a href="{{ route('profile.index', ['nickname' => $comment->createdBy->nickname]) }}">
            {{ '@' . $comment->createdBy->nickname }}
        </a>

        <p class="text-hint comment-hint-text">·</p>

        <p class="text-hint comment-hint-text">{{ $comment->created_at->diffForHumans() }}</p>
    </div>

    <div class="comment-text-wrapper">
        <p>{{ $comment->comment }}</p>
    </div>

</div>
