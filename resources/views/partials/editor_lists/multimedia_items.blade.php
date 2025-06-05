@foreach ($mediaPosts as $index => $post)
    <div class="multimedia-item">
        <div class="multimedia-item-left">
            <div class="item-cell num-cell">{{ $loop->iteration + ($page ?? 0) * 10 }}</div>
            <div class="item-cell multimedia-preview-cell">
                <div class="multimedia-cover-wrapper">
                    <img src="{{ asset('images/icons/hw/media-form-icon.svg') }}" class="event-cover" alt="icon">
                </div>
                <div class="multimedia-preview-text-wrapper">
                    <p class="text-big">{{ $post->name }}</p>
                    <p class="text-hint description-text">{{ $post->description ?: 'Нет описания' }}</p>
                </div>
            </div>
        </div>
        <div class="multimedia-actions">
            @php
                $medias = [];
                foreach ($post->medias as $media) {
                    $fileUrl = Storage::url($media->file);
                    $fileExtension = pathinfo($media->file, PATHINFO_EXTENSION);

                    if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $type = 'image';
                    } elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg'])) {
                        $type = 'video';
                    } elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg'])) {
                        $type = 'audio';
                    } else {
                        $type = 'unsupported';
                    }

                    $medias[] = [
                        'url' => $fileUrl,
                        'type' => $type,
                        'ext' => $fileExtension
                    ];
                }
            @endphp

            <a href="#" class="list-action-btn edit-post-btn"
               data-post-id="{{ $post->id }}"
               data-post-name="{{ $post->name }}"
               data-post-description="{{ $post->description }}"
               data-post-medias='@json($medias)'>
                <img src="{{ asset('images/icons/edit-primary.svg') }}" class="icon-24" alt="edit-icon">
            </a>
            <a href="#" class="list-action-btn delete-post-btn"
               data-post-id="{{ $post->id }}"
               data-post-name="{{ $post->name }}">
                <img src="{{ asset('images/icons/trash-primary-red.svg') }}" class="icon-24" alt="delete-icon">
            </a>
        </div>
    </div>
@endforeach
