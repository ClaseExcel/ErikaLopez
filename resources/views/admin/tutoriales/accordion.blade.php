<div class="accordion-item">
    <h2 class="accordion-header" id="flush-heading-list-{{ $key }}">
    <button class="accordion-button shadow-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse-list-{{ $key }}" aria-expanded="false" aria-controls="flush-collapse-list-{{ $key }}">
        <i class="fas fa-play-circle"></i>&nbsp; {{ $title}}
    </button>
    </h2>
    <div id="flush-collapse-list-{{ $key }}" class="accordion-collapse collapse" aria-labelledby="flush-heading-list-{{ $key }}" data-bs-parent="#accordionFlushExample">
        <div class="accordion-body">
            <iframe class="w-100"
            src="https://drive.google.com/file/d/{{ $code }}/preview"
            height="500" allow="autoplay" controls="false"
            sandbox="allow-scripts allow-same-origin" allowfullscreen></iframe>
        </div>
    </div>
</div>