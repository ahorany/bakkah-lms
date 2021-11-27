<div class="explore-mega-menu">

    <button class="btn btn-secondary dropdown-toggle px-4 d-none d-xl-block" type="button" id="ExploreMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> {{__('education.explore')}}</button>
    <div class="menu-wrapper">
            <?php
                use Jenssegers\Agent\Agent;
                $agent = new Agent();
            ?>
            <div class="dropdown-menu" aria-labelledby="ExploreMenu">
                @if($agent->isDesktop())
                    @foreach($course_menus as $course_menu)
                        <button onclick="window.location = '{{ route('education.courses', $course_menu->slug) }}{{ '-courses' }}' " class="dropdown-item {{$loop->index==0?'active':''}}">
                            <span>{{$course_menu->trans_name}}</span>
                            <i class="fas fa-chevron-right"></i>
                            <div class="sub-menu">
                                <h3>{{__('education.courses')}}</h3>
                                <ul>
                                    @foreach($course_menu->postMorph as $postMorph)
                                        @if(isset($postMorph->postable->slug))
                                            @if($postMorph->postable->show_in_website==1)
                                                <li class="{{$course_menu->postMorph->count() > 10?'big-menu':''}}">
                                                    <a href="{{route('education.courses.single', ['slug'=>$postMorph->postable->slug])}}">
                                                        <span>{{$postMorph->postable->trans_title}}</span>
                                                    </a>
                                                </li>
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </button>
                    @endforeach

                    <a class="dropdown-item" href="{{route('education.training-schedule')}}">
                        <span>{{__('education.Training Schedule')}}</span>
                    </a>
                    {{-- <a class="dropdown-item" href="{{route('education.static.partners')}}">
                        <span>{{__('education.Partners')}}</span>
                    </a> --}}
                @endif
            {{--d-sm-none--}}
            <a href="{{route('education.courses')}}" class="btn btn-secondary">{{__('education.explore_all_courses')}}</a>

        </div>
    </div>
</div>
