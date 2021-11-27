<a class="dropdown-item text-capitalize" href="{{ route('users.edit_password', ['user'=>auth()->user()->id]) }}">
    {{ __('app.change-password') }}
</a>

<a class="dropdown-item text-capitalize" href="{{ route('users.edit', ['user'=>auth()->user()->id, 'post_type'=>auth()->user()->post_type]) }}">
    {{ __('app.my-profile') }}
</a>

{{--<a class="dropdown-item text-capitalize" href="{{ route('change.langs', ['lang'=>__('app.trans-symbol')]) }}" title="{{__('app.trans')}} {{__('app.to_ar')}}">
	{{__('app.another_lang')}}
</a>--}}

<a class="dropdown-item text-capitalize" href="{{ route('logout') }}"
   onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
    {{ __('app.logout') }}
</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
