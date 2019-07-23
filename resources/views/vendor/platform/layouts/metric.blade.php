<div class="padder-v b-b">
    @isset($title)
        <h4 class="font-thin text-black mb-0">{{ __($title) }}</h4>
    @endisset
    <div class="row padder-v">
        @foreach($metrics as $key => $metric)
            <div class="col  @if(!$loop->last) b-r @endif">
                <small class="text-muted block mb-1">{{ __($key) }}</small>
                <p class="h4 mb-1 text-black font-thin">{{ $metric['keyValue'] }}</p>

                @isset($metric['keyDiff'])
                    @if((float)$metric['keyDiff'] < 0)
                        <small class="text-danger">{{ $metric['keyDiff'] }} % <i class="icon-arrow-down"></i></small>
                    @elseif((float)$metric['keyDiff'] == 0)
                        <small class="text-muted">{{ $metric['keyDiff'] }} % <i class="icon-refresh"></i></small>
                    @else
                        <small class="text-success">{{ $metric['keyDiff'] }} % <i class="icon-arrow-up"></i></small>
                    @endif
                @endisset
            </div>
        @endforeach
    </div>
</div>
