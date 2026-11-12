@props(['title', 'subtitle' => null])

<div class="section-heading">
    <h2>{{ $title }}</h2>
    @if ($subtitle)
        <p>{{ $subtitle }}</p>
    @endif
</div>
