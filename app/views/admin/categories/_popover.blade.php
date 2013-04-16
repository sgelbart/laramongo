@if(method_exists($instance, 'imageUrl'))
    <img src="{{$instance->imageUrl()}}"/>
@endif

<div class="technical-features">
    <p>
        <b>Nome:</b>
        {{ $instance->name }}
    </p>

    @if($ancestor)
        <p>
            <b>Pai:</b>
            {{ $ancestor->name }}
        </p>
    @endif
</div>
