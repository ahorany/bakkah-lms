<nav id="navbar" class="navbar navbar-expand-xl navbar-light bg-white p-0">
    <div class="container-fluid">
        <h1 itemprop="headline">
            <a class="navbar-brand" href="{{route('education.index')}}" title="{{__('education.header_title')}}">
                <img src="{{CustomAsset('images/logo.png')}}" alt="{{__('education.header_title')}}">
            </a>
        </h1>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="d-flex">
                <div class="explore-mega-menu">
                    <a href="{{route('education.courses')}}" class="btn btn-secondary d-sm-none">{{__('education.explore_all_courses')}}</a>
                    <button class="btn btn-primary dropdown-toggle mx-4 d-none d-sm-block" type="button" id="ExploreMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> {{__('education.explore')}}</button>
                    <div class="menu-wrapper">

                        <div class="dropdown-menu" aria-labelledby="ExploreMenu">

                            @foreach($course_menus as $course_menu)
                                <button class="dropdown-item">
                                    <span>{{$course_menu->trans_name}}</span>
                                    <i class="fas fa-chevron-right"></i>
                                    <div class="sub-menu">
                                        <h3>{{__('education.courses')}}</h3>
                                        <ul>
                                            @foreach($course_menu->postMorph as $postMorph)
                                                @if(isset($postMorph->postable->upload->file))
                                                    <li>
                                                        <a href="{{route('education.courses.single', ['course_id'=>$postMorph->postable->id])}}">
                                                            <img src="{{CustomAsset('upload/thumb100/'.$postMorph->postable->upload->file)}}" title="{{$postMorph->postable->upload->title}}" alt="{{$postMorph->postable->trans_title}}">
                                                            <span>{{$postMorph->postable->trans_title}}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </button>
                            @endforeach

                        </div>
                    </div>
                </div>

                <form class="form-inline my-2 my-lg-0">
                    <div class="input-group">
                        <i class="fas fa-search"></i>
                        <input type="text" class="form-control" placeholder="{{__('consulting.Search for service')}}">
                        <!--<div class="input-group-append">
                            <button class="btn btn-primary px-4" type="button"></button>
                        </div>-->
                    </div>
                </form>
            </div>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('education.static.knowledge-center')}}">{{__('education.Knowledge Center')}}</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto d-none">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Knowledge Hub
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Knowledge Center</a>
                        <a class="dropdown-item" href="#">Weninars</a>
                        <a class="dropdown-item" href="#">REports</a>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">For Corporate</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-shopping-cart"></i>
                        <span>2</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-login" href="#">
                        <i class="fas fa-user"></i> Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-primary" href="#">Join for Free</a>
                </li>

            </ul>

        </div>
    </div>
</nav> <!-- /#navbar -->
