<footer class="py-5 consulting-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="widget">
                    <div id="nav_menu-13" class="widget ">
                        <h3 class="footer-title">{{__('education.Quick Links')}}</h3>
                        <ul id="menu-quick-links" class="menu">
                            @foreach($consulting_footer_menus as $consulting_footer_menu)
                                <li class="menu-item">
                                    <?php
                                    $args = [];
                                    if(!is_null($consulting_footer_menu->route_param)){
                                        $args = array_merge($args, json_decode($consulting_footer_menu->route_param, true));
                                    }
                                    ?>
                                    <a href="{{route($consulting_footer_menu->route_name, $args)}}" aria-current="page">
                                        <i class="fas fa-chevron-right"></i>
                                        <span>{{$consulting_footer_menu->trans_title}}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-md-flex justify-content-between">
                <div class="widget w-100">
                    <div id="bakkah_knowledge_center-4">
                        <h3 class="footer-title"><a href="{{route('consulting.static.knowledge-center', ['post_type'=>'consulting-insights'])}}">{{__('consulting.Insights')}}</a></h3>
                        <ul>
                            @foreach($footerRecentArticles as $recentArticle)
                                <li class="cat-post-item">
                                    <a href="{{route('consulting.static.knowledge-center.single', ['slug'=>$recentArticle->slug])}}" rel="bookmark">
                                        {{$recentArticle->title}}</a>
                                    <p class="post-date">{{$recentArticle->published_date}}</p>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </div> <!-- /.widget -->
            </div>
            <div class="col-md-3">
                <div class="widget">
                    <div id="text-8" class="widget widget_text">
                        <h3 class="footer-title">{{__('education.Contact Us')}}</h3>
                        <div class="textwidget">
                            {!! __('education.contact_us') !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
        {{--    </div>--}}
        <div class="copyright">
            <div class="container">
                <hr>
                <div class="d-flex justify-content-between">
                    <p class="text-center m-0">{!! __('education.bakkah_inc_all_rights_reserved', ['year'=>YEAR]) !!}</p>

                    @include(FRONT.'.Html.social')

                </div>

            </div>
        </div>
    </div>
</footer>

<div class="back-to-top consulting">
    <i class="fas fa-chevron-up"></i>
</div>
