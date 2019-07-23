<div id="accardion-{{$templateSlug}}" class="accardion">
    @foreach($manyForms as $name => $forms)
        <div class="accardion-heading b-b @if ($loop->index) collapsed @endif"
             id="heading-{{str_slug($name)}}"
             data-toggle="collapse"
             data-target="#collapse-{{str_slug($name)}}"
             aria-expanded="true"
             aria-controls="collapse-{{str_slug($name)}}">
            <h6 class="btn btn-link btn-group-justified pt-2 pb-2 mb-0 pr-0 pl-0 v-center">
                <span class="icon-accardion text-xs m-r-xs"></span> {!! $name !!}
            </h6>
        </div>

        <div id="collapse-{{str_slug($name)}}" class="b-b mt-2 collapse @if (!$loop->index) show @endif"
             aria-labelledby="heading-{{str_slug($name)}}"
             data-parent="#accardion-{{$templateSlug}}">
                @foreach($forms as $form)
                    {!! $form !!}
                @endforeach
        </div>
    @endforeach
</div>