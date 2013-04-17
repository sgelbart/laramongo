<div class='image-tagging' data-image-tagging='{{ $content->_id }}'>

    {{ $content->render() }}
    
    {{-- The form bellow will be placed over the image dinamically, thanks to image_tagging.js --}}
    <div class="popover-tagging popover top">
        <div class="arrow"></div>
        <h3 class="popover-title">{{ l('content.tag_image') }}</h3>
        <div class="popover-content">
            @include( 'admin.contents._image_tag_form' )
        </div>
    </div>

</div>
