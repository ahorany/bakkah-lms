@extends('layouts.app')

@section('useHead')
    <title>{{$exam->exam->content->title}} {{ __('education.Review Exam') }} | {{ __('home.DC_title') }}</title>
@endsection

@section('style')
    <style>
       .incorrect-radio + .radio-mark:after {
            border: solid #f00 ;
           border-width: 0 3px 3px 0;
        }
    </style>
@endsection

@section('content')
{{--    <div class="dash-header course_info">--}}
{{--        --}}{{-- <ol class="breadcrumb">--}}
{{--            <li><a href="{{CustomRoute('user.home')}}">Dashboard</a></li>--}}
{{--            <li><a href="{{CustomRoute('user.home')}}">My Courses</a></li>--}}
{{--            <li>{{$exam->exam->content->title}}</li>--}}
{{--        </ol> --}}
{{--        <h2>{{$exam->exam->content->title}}</h2>--}}
{{--    </div>--}}

<?php
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
?>

{{-- @dd($exam) --}}
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
</div>
    <div class="row mx-0">
        @if(session()->has('status'))
            <div style="background: #fb4400;color: #fff; padding: 20px;font-size: 1rem">{{session()->get('msg')}}</div>
        @endif

        <div class="col-xl-9 col-lg-8 mb-4 mb-lg-0">
            @foreach($exam->exam->content->questions as $question)
             <div id="question_{{$loop->iteration}}" class="card p-30 q-card {{($loop->last) ? ' ' : 'mb-3'}}">
                <div class="q-number">
                    Q{{$loop->iteration}}/{{count($exam->exam->content->questions)}}
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
                <h3 style="padding-right: 15%;">{!! $question->title!!}</h3>
                 @foreach($question->answers as $answer)
                     <label class="custom-radio"> {{$answer->title}}
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

                  <div class="correct_feedback">
                     <h4 class="mb-0">Correct Answer: </h4>
                     @foreach($question->answers as $answer)
                         @if($answer->check_correct == 1)
                             <div style="color: #2a9055" >
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

                <a href="{{CustomRoute('user.exam',$exam->exam->content->id)}}" class="form-control main-color">Exit Review</a>

        </div>
        <div class="col-xl-3 col-lg-4">
            <div class="card h-100 p-30">
                <h4>Quiz</h4>
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
                                        <path id="Path_121" data-name="Path 121" d="M252.452,339.764l-11,11-6.414-6.414" transform="translate(-233.618 -338.35)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="4"/>
                                    </svg>
                                </div>
                                @elseif( ($count_correct_answers > $count_correct_user_answers && $count_correct_user_answers !=0) )
                                    <div class="icon empty"></div>
                                @else
                                    <div class="icon incorrect">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14.472" height="14.472" viewBox="0 0 14.472 14.472">
                                            <g id="Group_127" data-name="Group 127" transform="translate(-235.537 -259.17)">
                                                <line id="Line_9" data-name="Line 9" x2="12.738" y2="12.738" transform="translate(236.404 260.037)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="2.453"/>
                                                <line id="Line_10" data-name="Line 10" x1="12.738" y2="12.738" transform="translate(236.404 260.037)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="2.453"/>
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
