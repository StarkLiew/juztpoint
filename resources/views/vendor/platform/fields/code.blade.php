@component($typeForm,get_defined_vars())
    <div
            data-controller="fields--code"
            data-fields--code-language="{{$language}}"
            data-fields--code-line-numbers="{{$lineNumbers}}"
            data-fields--code-default-Theme="{{$defaultTheme}}"
    >
        <div class="code b pos-rlt w-100" style="min-height: {{ $attributes['height'] }}"></div>
        <input type="hidden" @include('platform::partials.fields.attributes', ['attributes' => $attributes])>
    </div>
@endcomponent