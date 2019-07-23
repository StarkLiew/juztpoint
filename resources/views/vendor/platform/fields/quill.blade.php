@component($typeForm,get_defined_vars())
    <div data-controller="fields--quill" data-theme="{{$theme ?? 'inlite'}}">
        <div id="toolbar"></div>
        <div class="quill b wrapper position-relative" id="quill-wrapper-{{$id}}" style="min-height: {{ $attributes['height'] }}">
        </div>
        <input type="hidden" @include('platform::partials.fields.attributes', ['attributes' => $attributes])>
    </div>
@endcomponent