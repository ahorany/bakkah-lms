@extends('layouts.app')

@section('useHead')
    <title>{{$exam->exam->content->title}} {{ __('education.Review Exam') }} | {{ __('home.DC_title') }}</title>
@endsection

@section('style')
    <style>
        .custom-radio .radio-mark:after , .incorrect-radio + .radio-mark:after{
            border: none;
            border-radius: 50%;
            width: 14px !important;
            height: 14px !important;
<<<<<<< HEAD
        }
        .custom-radio .radio-mark:after{
=======
            left: 10px !important;
        }
        .correct-radio .radio-mark:after{
>>>>>>> 5aee7bdf4b69cde92c235e18d65656aacf915732
            background: #06ae60;
        }
       .incorrect-radio + .radio-mark:after {
            background: red;
        }
    </style>
@endsection

@section('content')


    <div class="d-flex mobile-show" style="align-items: center;">
        <h3 class="m-0 title_file_old">{{ $exam->exam->content->course->trans_title }}</h3>
    </div>

    <div class="d-flex p-3" style="justify-content: space-between; align-items:center; flex-wrap: wrap;">
        <h2 class="m-0"><i class="fas fa-graduation-cap"></i> {{$exam->exam->content->title}}</h2>
        <a style="width: 85px;" href="{{CustomRoute('user.exam',$exam->exam->content->id)}}" class="cyan form-control">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="back" style="vertical-align: middle;" width="35%" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
                    <path d="M8.66,30.08c0.27-1.02,1-1.72,1.72-2.43c4.76-4.75,9.51-9.51,14.27-14.26c1.15-1.15,2.78-1.32,4.01-0.44  c1.42,1.03,1.67,3.1,0.54,4.45c-0.11,0.13-0.22,0.25-0.34,0.37c-2.77,2.77-5.53,5.56-8.34,8.31c-0.61,0.6-1.37,1.04-2.06,1.54  c0.1,0,0.26,0,0.42,0c9.65,0,19.3,0,28.95,0c1.02,0,1.94,0.24,2.65,1.04c1.53,1.75,0.67,4.45-1.61,4.98  c-0.37,0.09-0.77,0.1-1.15,0.1c-9.64,0.01-19.27,0-28.91,0c-0.16,0-0.33,0-0.53,0c0.05,0.06,0.07,0.1,0.1,0.11  c1.08,0.43,1.93,1.17,2.73,1.99c2.55,2.57,5.1,5.13,7.66,7.69c0.7,0.7,1.14,1.49,1.12,2.5c-0.03,1.21-0.56,2.1-1.66,2.61  c-1.08,0.5-2.13,0.38-3.1-0.31c-0.24-0.17-0.44-0.38-0.65-0.58c-4.63-4.63-9.25-9.25-13.88-13.88c-0.78-0.78-1.62-1.51-1.94-2.62  C8.66,30.85,8.66,30.47,8.66,30.08z"/>
                </svg>
            </span>
            <span>back</span>
        </a>
    </div>


    {{-- @php
        if( !is_null($next)){
            if( $next->post_type != 'exam' ) {
                $next_url = CustomRoute('user.course_preview',$next->id);
            }else{
                $next_url =  CustomRoute('user.exam',$next->id);
            }
        }

        if(!is_null($previous)){
            if($previous->post_type != 'exam'){
                $previous_url = CustomRoute('user.course_preview',$previous->id);
            }else{
                $previous_url =  CustomRoute('user.exam',$previous->id);
            }
        }
    @endphp

    <div class="dash-header d-flex justify-content-between review">
        @include('pages.templates.breadcrumb', [
            'course_id'=>$exam->exam->content->course->id,
            'course_title'=>$exam->exam->content->course->trans_title,
            'content_title'=>$exam->exam->content->title,
            // 'content_title'=>__('education.Exam'),
        ])
        <div class="parent_next_prev">
            @if($previous)
                <button title="{{$previous->title}}" class="next_prev" onclick="location.href =  '{{$previous_url}}'">
                    <svg id="Group_103" data-name="Group 103" xmlns="http://www.w3.org/2000/svg" width="14.836" height="24.835" viewBox="0 0 14.836 24.835">
                        <path id="Path_99" data-name="Path 99" d="M161.171,218.961a1.511,1.511,0,0,1-1.02-.4l-11.823-10.909a1.508,1.508,0,0,1,0-2.215l11.823-10.912a1.508,1.508,0,0,1,2.045,2.215l-10.625,9.8,10.625,9.8a1.508,1.508,0,0,1-1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="#fff"/>
                    </svg>
                    <span>{{__('education.Previous')}}</span>
                </button>
            @endif

            @if($next)
                <button title="{{$next->title}}" onmouseleave="hide_next()" onmouseenter="show_next()" class="next next_prev" onclick="location.href = '{{$next_url}}'">
                    <span>{{__('education.Next')}}</span>
                    <svg id="Group_104" data-name="Group 104" xmlns="http://www.w3.org/2000/svg" width="14.836" height="24.835" viewBox="0 0 14.836 24.835">
                        <path id="Path_99" data-name="Path 99" d="M149.351,218.961a1.511,1.511,0,0,0,1.02-.4l11.823-10.909a1.508,1.508,0,0,0,0-2.215l-11.823-10.912a1.508,1.508,0,0,0-2.045,2.215l10.625,9.8-10.625,9.8a1.508,1.508,0,0,0,1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="#fff"/>
                    </svg>
                </button>
            @endif
        </div>
    </div> --}}

    <div class="row mx-0">
        @if(session()->has('status'))
            <div style="background: #fb4400;color: #fff; padding: 20px;font-size: 1rem">{{session()->get('msg')}}</div>
        @endif

        <div class="col-xl-9 col-lg-8 mb-4 mb-lg-0">
            @foreach($exam->exam->content->questions as $question)
             <div id="question_{{$loop->iteration}}" class="card p-30 q-card {{($loop->last) ? ' ' : 'mb-3'}}">
                <div class="q-number">
                    <div>
                        <span>Q{{$loop->iteration}}/{{count($exam->exam->content->questions)}}</span>
                        <span class="line"></span>
                        @php
                            $answers = 0;
                        @endphp
                        @foreach($question->answers as $answer)
                            @if($answer->check_correct == 1)
                                @php
                                    $answers++;
                                @endphp
                            @endif
                        @endforeach
                        <small>({{$question->mark}} {{$answers == 1 ? 'Mark' : 'Marks'}} )</small>
                    </div>
                </div>
                <h3>{!! $question->title!!}</h3>
                 @foreach($question->answers as $answer)
                     <label class="custom-radio @foreach($exam->user_answers as $user_answer) @if($user_answer->id == $answer->id ) @if($answer->check_correct == 0) border_false @else border_true @endif @endif @endforeach"
                      > {{$answer->title}}
                         <input type="checkbox" disabled="true" @foreach($exam->user_answers as $user_answer) @if($user_answer->id == $answer->id ) @if($answer->check_correct == 0) checked class="incorrect-radio" @else checked @endif   @endif @endforeach>
                         <span class="radio-mark"></span>
                     </label>
                 @endforeach

                    <div class="correct_feedback">
                        <h4 class="mb-0">Your mark in this question: </h4>
                        <?php $lock = false; ?>
                        @foreach($exam->user_questions as $q)
                            @if($q->id == $question->id)
                                <?php $lock = true; ?>
                                <div>
                                    {{ $q->pivot->mark . '/' . $q->mark }}  {{$answers == 1 ? 'Mark' : 'Marks'}}
                                </div>
                            @endif
                        @endforeach

                        @if(count($exam->user_questions) > 0 && !$lock)
                            {{ '0' . '/' . $question->mark }}  {{$answers == 1 ? 'Mark' : 'Marks'}}
                        @endif
                    </div>

                  <div class="correct_feedback correct_answer">
                     <h4 class="mb-0">Correct Answer: </h4>
                     @foreach($question->answers as $answer)
                         @if($answer->check_correct == 1)
                             <div>
                                 {{  $answer->title }}
                             </div>
                         @endif
                     @endforeach
                </div>

                 @if($question->feedback)
                     <div class="correct_feedback">
                         <h4 class="mb-0">Feedback: </h4>
                         <p class="m-0">{{  $question->feedback }}</p>
                     </div>
                 @endif

             </div>
            @endforeach

                <a href="{{CustomRoute('user.exam',$exam->exam->content->id)}}" class="form-control main-color exit-preview">Exit Review</a>

        </div>
        <div class="col-xl-3 col-lg-4">
            <div class="card quiz h-100 p-30">
                <h2>Quiz</h2>
                <ol class="answers">
                    @foreach($exam->exam->content->questions as $question)
                        <?php
                        $count_correct_answers = 0;
                        $count_correct_user_answers = 0;
                        $count_all_user_answers = 0;
                         foreach($question->answers as $answer){
                             if($answer->check_correct == 1){
                                 $count_correct_answers++;
                             }
                         }


                        foreach($exam->user_answers as $user_answer){
                            if($user_answer->question_id == $question->id ){
                                $count_all_user_answers++;
                                if( $user_answer->check_correct == 1){
                                    $count_correct_user_answers++;
                                }
                            }

                        }

                        ?>
                        <li>
                            <a href="#question_{{$loop->iteration}}">
                                <b style="color: #000;">{{$loop->iteration}}</b>
                                @if($count_correct_answers == $count_correct_user_answers && $count_all_user_answers == $count_correct_answers)
                                <div class="icon correct">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20.248" height="15.247" viewBox="0 0 20.248 15.247">
                                        <path id="Path_121" data-name="Path 121" d="M252.452,339.764l-11,11-6.414-6.414" transform="translate(-233.618 -338.35)" fill="none" stroke="#06ae60" stroke-miterlimit="10" stroke-width="4"/>
                                    </svg>
                                </div>
                                @elseif( ($count_correct_answers > $count_correct_user_answers && $count_correct_user_answers !=0) )
                                    <div class="icon empty"></div>
                                @else
                                    <div class="icon incorrect">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="14.472" height="14.472" viewBox="0 0 14.472 14.472">
                                            <g id="Group_127" data-name="Group 127" transform="translate(-235.537 -259.17)">
                                              <line id="Line_9" data-name="Line 9" x2="12.738" y2="12.738" transform="translate(236.404 260.037)" fill="none" stroke="#ff0000" stroke-miterlimit="10" stroke-width="2.453"/>
                                              <line id="Line_10" data-name="Line 10" x1="12.738" y2="12.738" transform="translate(236.404 260.037)" fill="none" stroke="#ff0000" stroke-miterlimit="10" stroke-width="2.453"/>
                                            </g>
                                          </svg>

                                    </div>
                                @endif
                            </a>
                        </li>
                    @endforeach

                </ol>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.getElementById("demo").innerHTML = "Next";
    document.querySelector(".next").addEventListener("click", function(event){
        window.location.href = '{{$next_url??null}}'
    });

</script>

@endsection
