@if (session()->has('flash_notification.message'))
    <div class="alert alert-{{ session('flash_notification.level') }}">
        <button type="button"
                class="close"
                data-dismiss="alert"
                aria-hidden="true">&times;
        </button>
        {!! session('flash_notification.message') !!}

        @yield('flash_notification.sub_message')
    </div>
@endif
<div id="dashboard-alerts"></div>

@empty(!$errors->count())
    <div class="alert alert-danger" role="alert">
        <strong>{{  __('Oh snap!') }}</strong>
        {{ __('Change a few things up and try submitting again.') }}
        <ul class="m-t-xs">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif