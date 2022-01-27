@extends('layouts.app')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
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
    <div class="dash-header">
        <ol class="breadcrumb">
            <li><a href="{{CustomRoute('user.home')}}">Dashboard</a></li>
            <li><a href="{{CustomRoute('user.home')}}">My Courses</a></li>
            <li>{{$exam->title}}</li>
        </ol>
        <div class="d-flex justify-content-between">
            <h1>{{$exam->title}}</h1>
            <?php
            $NextPrevNavigation = \App\Helpers\CourseContentHelper::NextPrevNavigation($next, $previous);
            $next_url = $NextPrevNavigation['next_url'];
            $previous_url = $NextPrevNavigation['previous_url'];
            ?>
            @include('Html.next-prev-navigation', [
                'next'=>$next,
                'previous'=>$previous,
                'previous_url'=>$previous_url,
            ])
            <script>
                function NextBtn(){
                    document.querySelector(".next").addEventListener("click", function(event){
                        window.location.href = '{{$next_url??null}}'
                    });
                }
                NextBtn();
            </script>
            <a class="yellow group_buttons mb-1 btn-sm pull-right" title="Preview" href="{{route('training.add_questions',$exam->exam->content->id)}}" target="{{$exam->content_id}}" style="border: 1px solid #ffc107;"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-9 col-lg-8 mb-4 mb-lg-0">
            @foreach($exam->exam->content->questions as $question)
                <div id="question_{{$loop->iteration}}" class="card p-30 q-card {{($loop->last) ? ' ' : 'mb-3'}}">
                    <div class="q-number">
                        Q{{$loop->iteration}}/{{count($exam->exam->content->questions)}}
                        <small>({{$question->mark}} Marks)</small>
                    </div>
                    <h3 style="padding-right: 7%;">{!! $question->title!!}</h3>
                    @foreach($question->answers as $answer)
                        <label class="custom-radio"> {{$answer->title}}
                            <input type="checkbox" disabled="true" @if($answer->check_correct == 1)  checked @endif >
                            <span class="radio-mark"></span>
                        </label>
                    @endforeach
                        <div>
                            {{ $question->mark??0 . '/' . $q->mark }} Marks
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
                <h4>Quiz Navigation</h4>
                <ol class="answers">
                    @foreach($exam->exam->content->questions as $question)
                        <li>
                            <a href="#question_{{$loop->iteration}}">
                                <b style="color: #000;">{{$loop->iteration}}</b>
                                <div class="icon correct">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20.248" height="15.247" viewBox="0 0 20.248 15.247">
                                        <path id="Path_121" data-name="Path 121" d="M252.452,339.764l-11,11-6.414-6.414" transform="translate(-233.618 -338.35)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="4"/>
                                    </svg>
                                </div>
                            </a>
                        </li>
                    @endforeach

                </ol>
            </div>
        </div>
    </div>
@endsection
