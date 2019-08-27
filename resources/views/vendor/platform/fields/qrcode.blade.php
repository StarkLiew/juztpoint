@component($typeForm,get_defined_vars())


 {!! QrCode::size(250)->generate($attributes['value']) !!}


@endcomponent