<div class="menu-overlay"></div>
<nav id="navbar" class="navbar navbar-expand-xl navbar-light bg-white p-0">
    <div class="container-fluid">
        <h1 itemprop="headline">
            <a class="navbar-brand" href="{{route('education.index')}}" title="{{__('education.header_title')}}">
                <img src="{{CustomAsset('images/logo.png')}}" alt="{{__('education.header_title')}}">
            </a>
        </h1>
        <a href="{{route('education.courses')}}" class="btn btn-primary btn-sm d-sm-none">{{__('education.explore_all_courses')}}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item d-none">
                    <a class="nav-link hot-deals" href="{{route('education.hot-deals')}}">{{__('education.Hot Deals')}}</a>
                </li>
                @include(FRONT.'.Html.switch-lang')
            </ul>

        </div>
    </div>
</nav> <!-- /#navbar -->
