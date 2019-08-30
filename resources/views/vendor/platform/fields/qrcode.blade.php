@component($typeForm,get_defined_vars())

  @if(!empty($attributes['value']))
   {!! QrCode::size(250)->generate($attributes['value']) !!}
  @endif

@endcomponent