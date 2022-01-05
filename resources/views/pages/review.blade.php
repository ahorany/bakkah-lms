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
    <div class="dash-header course_info">
        {{-- <ol class="breadcrumb">
            <li><a href="{{CustomRoute('user.home')}}">Dashboard</a></li>
            <li><a href="{{CustomRoute('user.home')}}">My Courses</a></li>
            <li>{{$exam->exam->content->title}}</li>
        </ol> --}}
        <h2>{{$exam->exam->content->title}}</h2>
    </div>


    <div class="row">
        <div class="col-xl-9 col-lg-8 mb-4 mb-lg-0">
            @foreach($exam->exam->content->questions as $question)
             <div id="question_{{$loop->iteration}}" class="card p-30 q-card {{($loop->last) ? ' ' : 'mb-3'}}">
                <div class="q-number">
                    Q{{$loop->iteration}}/{{count($exam->exam->content->questions)}}
                    <small>({{$question->mark}} Marks)</small>
                </div>
                <h3 style="padding-right: 25%;">{!! $question->title!!}</h3>
                 @foreach($question->answers as $answer)
                     <label class="custom-radio"> {{$answer->title}}
                         <input type="checkbox" disabled="true" @foreach($exam->user_answers as $user_answer) @if($user_answer->id == $answer->id ) @if($answer->check_correct == 0) checked class="incorrect-radio" @else checked @endif   @endif @endforeach>
                         <span class="radio-mark"></span>
                     </label>
                 @endforeach

             <?php $lock = false; ?>
             @foreach($exam->user_questions as $q)
                    @if($q->id == $question->id)
                        <?php $lock = true; ?>
                         <div>
                            {{ $q->pivot->mark . '/' . $q->mark }} Marks
                        </div>
                     @endif
                 @endforeach

                 @if(count($exam->user_questions) > 0 && !$lock)
                     {{ '0' . '/' . $question->mark }} Marks
                 @endif

                  <div>
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
                     <div>
                         <h4 class="mb-0">Feedback : </h4>
                         <p class="mb-0">{{  $question->feedback }}</p>
                     </div>
                 @endif

             </div>
            @endforeach



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
