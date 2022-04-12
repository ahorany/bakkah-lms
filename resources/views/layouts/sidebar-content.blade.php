<?php
$course_collect = (collect($course->contents)->groupBy('is_aside'));
//    dd($course_collect);
?>

<style>
    #sidebarMenu{
        display: none !important;
        /* display: none; */
    }
</style>

@if (isset($course_collect[0]))
 <nav id="sidebar-content" class="col-md-3 col-lg-3 col-xl-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky">
        <ul class="nav flex-column postition-relative">
            <li class="nav-item">
                <a class="nav-link dashboard-home" aria-current="page" href="{{route('user.home')}}">
                   <span class="d-flex">
                       <span style="width: 30px; margin-right: 5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 36 36">
                                <path id="Path_132" data-name="Path 132"
                                    d="M34.5,36h-12A1.5,1.5,0,0,1,21,34.5v-12A1.5,1.5,0,0,1,22.5,21h12A1.5,1.5,0,0,1,36,22.5v12A1.5,1.5,0,0,1,34.5,36Zm-12-13.5v12h12l0-12Zm12-7.5h-12A1.5,1.5,0,0,1,21,13.5V1.5A1.5,1.5,0,0,1,22.5,0h12A1.5,1.5,0,0,1,36,1.5v12A1.5,1.5,0,0,1,34.5,15ZM22.5,1.5v12h12l0-12ZM13.5,36H1.5A1.5,1.5,0,0,1,0,34.5v-12A1.5,1.5,0,0,1,1.5,21h12A1.5,1.5,0,0,1,15,22.5v12A1.5,1.5,0,0,1,13.5,36ZM1.5,22.5v12h12l0-12Zm12-7.5H1.5A1.5,1.5,0,0,1,0,13.5V1.5A1.5,1.5,0,0,1,1.5,0h12A1.5,1.5,0,0,1,15,1.5v12A1.5,1.5,0,0,1,13.5,15ZM1.5,1.5v12h12l0-12Z" />
                            </svg>
                       </span>
                       <span>Dashboard</span>
                   </span>
                </a>
             </li>
            @foreach($course_collect[0] as $key => $section)
                <li class="nav-item-contents {{  $section->id == $content->section->id ? 'active' : ''  }}">
                   <div class="dropdown-sidebar {{  $section->id == $content->section->id ? 'active' : ''  }}">
                    <a href="#">
                        <span class="title">
                            <span>{{$section->title}}</span>
                        </span>
                        <span class="drop-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24.37" height="16.233" viewBox="0 0 24.37 16.233">
                                <path id="Line_Arrow_Right" data-name="Line Arrow Right" d="M3.967,0,0,4.048,12.185,16.233,24.37,4.048,20.4,0,12.185,8.3Z"/>
                            </svg>
                        </span>
                    </a>
                    @isset($section->contents)
                       <ul>
                           @foreach($section->contents as $k => $c)

                              <?php
                               $preview_url = Gate::allows('preview-gate') && request()->preview == true  ? '?preview=true' : '';
                               if($c->post_type != 'exam'){
                                   $url = CustomRoute('user.course_preview', $c->id).$preview_url;
                               }else{
                                   if(Gate::allows('preview-gate') && request()->preview == true){
                                       $url = CustomRoute('training.add_questions', $c->id).$preview_url;
                                   }
                                   else{
                                       $url = CustomRoute('user.exam', $c->id).$preview_url;
                                   }
                               }
                               ?>

                               <?php
                               $content_show = false;
                               $popup_pay_status = false;
                               if( isset($c->user_contents[0])  ){
                                   $content_show = true;
                               }else if ($section->post_type != "section"){
                                   if ($c->paid_status == 504 && $c->status == 1 ){ // if free
                                       $content_show = true;
                                   }else if( $c->status == 1 || (isset($course->users[0]) && $course->users[0]->pivot->paid_status == 503 && $c->paid_status == 503) ){ // if content paid and user pay course
                                       $content_show = true;
                                   }else{ // if course paid and user not pay course
                                       $popup_pay_status = true; // preview pop up pay now
                                   }
                               }else if ( $content->status == 1 || (isset($course->users[0]) && $section->post_type == 'gift' && $section->gift->open_after <= $course->users[0]->pivot->progress) ){
                                   $content_show = true;
                               }
                               ?>


                              <li class="nav-item {{  $c->id == $content->id ? 'active' : ''  }}">
                                 <a
                                    @if($content_show)
                                    href="{{$url}}" class="nav-link"
                                    @elseif($popup_pay_status)
                                    href="#" class="nav-link disabled-content" onclick="pupupPay(event,'{!! PAY_COURSE_BAKKAH_URL . $course->ref_id !!}')"
                                    @else
                                    href="#" class="nav-link disabled-content" onclick="return false"
                                     @endif
                                 >
                                    <span class="d-flex">
                                        <span style="width: 30px; margin-right: 5px;">
                                            <img style="filter: opacity(0.7);" width="100%" src="{{CustomAsset('icons/'.$c->post_type.'.svg')}}" alt="{{$c->title}}">
                                        </span>
                                        <span>{{$c->title}}</span>
                                    </span>
                                 </a>
                              </li>
                           @endforeach
                      </ul>
                    @endisset
                </div>
               </li>
            @endforeach
        </ul>
    </div>
</nav>
@endif
