@if (empty($path))
    <img src="{{ asset('/assets/media/misc/no_image.svg') }}" class="datatable-image" />
@else
    <a target="_blank" href="{{ imageS3($path) }}">
        <img src="{{ imageS3($path) }}" class="datatable-image"> </a>
@endif
