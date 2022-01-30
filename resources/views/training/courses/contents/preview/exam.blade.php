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
        {{-- <ol class="breadcrumb">
            <li><a href="{{CustomRoute('user.home')}}">Dashboard</a></li>
            <li><a href="{{CustomRoute('user.home')}}">My Courses</a></li>
            <li>{{$exam->title}}</li>
        </ol> --}}
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
            <a class="yellow d-flex items-align-center" style="height: max-content;" title="Preview" href="{{route('training.add_questions',$exam->exam->content->id)}}" target="{{$exam->content_id}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" style="margin-right: 5px;" viewBox="0 0 64.078 64.156">
                    <g id="Group_330" data-name="Group 330" transform="translate(-2056.667 -217.856)">
                      <path id="Path_175" data-name="Path 175" d="M2120.574,288.088c1.922-1.895,3.751-3.687,5.64-5.55.554.522,20.382,20.275,29.69,29.6a12.523,12.523,0,0,1,1.8,2.068,3.905,3.905,0,0,1-5.261,5.475,7.374,7.374,0,0,1-1.562-1.231C2141.026,308.607,2120.869,288.446,2120.574,288.088Z" transform="translate(-50.066 -50.673)" fill="#000"/>
                      <path id="Path_176" data-name="Path 176" d="M2078.891,222.692l-17.31,17.272a38.428,38.428,0,0,1-3.752-4.377c-1.909-2.987-1.417-6.62,1.116-9.417,1.672-1.846,3.468-3.582,5.259-5.316,3.962-3.836,8.59-4.058,12.583-.276C2077.565,221.315,2078.891,222.692,2078.891,222.692Z" fill="#000"/>
                      <path id="Path_177" data-name="Path 177" d="M2094.159,323.39s1.817-1.984,2.7-2.953c.787.771,1.341,1.355,1.92,1.934q13.968,13.958,27.927,27.927c.528.528,1.491,1.338,1.667,1.98a2.108,2.108,0,0,1-.487,2.1,2.362,2.362,0,0,1-1.932.493c-.489-.079-1.2-.862-1.615-1.272Q2109.664,338.93,2095,324.249C2094.774,324.018,2094.159,323.39,2094.159,323.39Z" transform="translate(-29.372 -80.363)" fill="#000"/>
                      <path id="Path_178" data-name="Path 178" d="M2158.628,258.921s1.817-1.984,2.7-2.953c.787.771,1.341,1.355,1.92,1.934q13.969,13.958,27.927,27.927c.528.529,1.491,1.338,1.668,1.98a2.109,2.109,0,0,1-.488,2.1,2.36,2.36,0,0,1-1.931.493c-.489-.079-1.2-.862-1.615-1.272q-14.673-14.668-29.333-29.349C2159.242,259.549,2158.628,258.921,2158.628,258.921Z" transform="translate(-79.878 -29.858)" fill="#000"/>
                      <path id="Path_179" data-name="Path 179" d="M2264.78,440.176c.667-2.663,1.9-3.7,4.473-4,3.322-.39,5.208-2.858,5.971-6.963a3.66,3.66,0,0,1,2.076-2.659l1.687-.771c1.591,6.342,3.138,12.509,4.8,19.121Z" transform="translate(-163.038 -162.892)" fill="#000"/>
                    </g>
                  </svg>
                Edit
            </a>
        </div>
    </div>

    <div class="row mx-0">
        <div class="col-xl-9 col-lg-8 mb-4 mb-lg-0">
            @foreach($exam->exam->content->questions as $question)
                <div id="question_{{$loop->iteration}}" class="card p-30 q-card {{($loop->last) ? ' ' : 'mb-3'}}">
                    <div class="q-number">
                        Q{{$loop->iteration}}/{{count($exam->exam->content->questions)}}
                        <small>({{$question->mark}} Marks)</small>
                    </div>
                    <h3 style="padding-right: 15%;">{!! $question->title!!}</h3>
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
