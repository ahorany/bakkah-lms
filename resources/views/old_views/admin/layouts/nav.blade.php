<style>
    .navbar-hidden {
        visibility: hidden;
    }
    a.sidebar-close {
        display: block;
        width: 30px;
        height: 30px;
        background: #343a40;
        color: #fff;
        position: absolute;
        right: 0;
        z-index: 99999;
        text-align: center;
        line-height: 30px;
    }
</style>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <a class="sidebar-open d-md-none" href="#"><i class="fas fa-bars"></i></a>
    <!-- Right navbar links -->
    <ul class="nav navbar-nav ml-auto">

{{--        <li class="nav-item">--}}
{{--<!--            --><?//= GetSiteLang(); ?>--}}
{{--        </li>--}}
        <li class="nav-item navbar-hidden">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"><i
                    class="fas fa-th-large"></i></a>
        </li>

        <li class="nav-item dropdown">
                <span class="cart-wrapper profile-img">
                    <?php
                    $url = '';
                    if(auth()->user()->upload) {
                        $url = auth()->user()->upload->file;
                        $url = CustomAsset('upload/full/'. $url);
                    }else {
                        $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . auth()->user()->trans_name;
                    }
                    ?>
                    <img style="width:40px;height:40px;object-fit:cover;border-radius: 50%;" src="{{$url}}" />
                </span>

            <a style="display: inline-block" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{auth()->user()->trans_name}}
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="{{ route('user.info') }}">{{ __('admin.Profile') }}</a>
              <a class="dropdown-item" href="{{ route('user.change_password') }}">{{ __('admin.Change Password') }}</a>
              <a class="dropdown-item text-capitalize" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
                {{ __('admin.logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            </div>
          </li>
    </ul>



</nav>
<!-- /.navbar -->
