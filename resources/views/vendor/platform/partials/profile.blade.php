<div class="wrapper v-center">
    <div class="dropdown col no-padder">
        <a href="#" class="nav-link p-0 v-center" data-toggle="dropdown">
                    <span class="thumb-xs avatar m-r-xs">
                        <img src="{{Auth::user()->getAvatar()}}" class="b b-dark bg-light">
                    </span>
            <span class="ml-2" style="width:125px;font-size: 0.82857rem;">
                <span class="text-ellipsis">{{Auth::user()->getNameTitle()}}</span>
                <span class="text-muted d-block text-ellipsis">{{Auth::user()->getSubTitle()}}</span>
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow bg-white">

            {!! Dashboard::menu()->render('Profile','platform::partials.dropdownMenu') !!}

            @if(Dashboard::menu()->container->where('location','Profile')->isNotEmpty())
                <div class="dropdown-divider"></div>
            @endif

            @if(Auth::user()->hasAccess('platform.systems.index'))
                <a href="{{ route('platform.systems.index') }}" class="dropdown-item">
                    <i class="icon-settings m-r-xs" aria-hidden="true"></i>
                    <span>{{ __('Systems') }}</span>
                </a>
            @endif

            @if(\Orchid\Access\UserSwitch::isSwitch())
                <a href="#"
                   class="dropdown-item"
                   data-controller="layouts--form"
                   data-action="layouts--form#submitByForm"
                   data-layouts--form-id="return-original-user"
                >
                    <i class="icon-logout m-r-xs" aria-hidden="true"></i>
                    <span>{{ __('Back to my account') }}</span>
                </a>
                <form id="return-original-user"
                      class="hidden"
                      data-controller="layouts--form"
                      data-action="layouts--form#submit"
                      action="{{ route('platform.switch.logout') }}"
                      method="POST">
                    @csrf
                </form>
            @else
                <a href="{{ route('platform.logout') }}"
                   class="dropdown-item"
                   data-controller="layouts--form"
                   data-action="layouts--form#submitByForm"
                   data-layouts--form-id="logout-form"
                   dusk="logout-button">
                    <i class="icon-logout m-r-xs" aria-hidden="true"></i>
                    <span>{{ __('Sign out') }}</span>
                </a>
                <form id="logout-form"
                      class="hidden"
                      action="{{ route('platform.logout') }}"
                      method="POST"
                      data-controller="layouts--form"
                      data-action="layouts--form#submit"
                >
                    @csrf
                </form>
            @endif

        </div>
    </div>

    <div class="pull-right text-center" data-turbolinks-permanent>
        <a href="{{ route('platform.notifications') }}" class="nav-link icon no-padder">
            <i class="icon-bell"></i>
            @if(count($notifications) > 0)
                <span class="badge badge-sm up bg-danger pull-right-xs text-white">
                {{ count($notifications) < 10 ? count($notifications) : '+'}}
            </span>
            @endif
        </a>
    </div>
</div>