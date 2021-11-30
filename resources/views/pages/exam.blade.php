@extends('layouts.app')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('style')
<style>
    table {
        border: 1px solid #ccc;
        border-collapse: collapse;
        margin: 0;
        padding: 0;
        width: 100%;
        table-layout: fixed;
    }

        table tr {
        background-color: #f8f8f8;
        border: 1px solid #ddd;
        padding: .35em;
    }

        table th,
        table td {
        padding: .625em;
        text-align: center;
    }

        table th {
        font-size: .85em;
        letter-spacing: .1em;
        text-transform: uppercase;
    }

    @media screen and (max-width: 1150px) {
        table {
            border: 0;
        }

        table caption {
            font-size: 1.3em;
        }

        table thead {
            border: none;
            clip: rect(0 0 0 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px;
        }

        table tr {
            border-bottom: 3px solid #ddd;
            display: block;
            margin-bottom: .625em;
        }

        table td {
            border-bottom: 1px solid #ddd;
            display: block;
            font-size: .8em;
            text-align: right;
        }

        table td::before {
            /*
            * aria-label has no advantage, it won't be read inside a table
            content: attr(aria-label);
            */
            content: attr(data-label);
            float: left;
            font-weight: bold;
            text-transform: uppercase;
        }

        table td:last-child {
            border-bottom: 0;
        }
    }

</style>
@endsection

@section('content')
            <div class="card p-5 user-info">
                        <h2 class="mb-4"><i class="fas fa-graduation-cap"></i> {{ __('education.Exam') }}</h2>
                        <div class="row">
                            <?php $users_exams_count = count($exam->exam->users_exams) ?>

                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-3">
                                <div class="card h-100" style="box-shadow: none; border: 1px solid gainsboro;">
                                    <div class="card-body" style="padding: 15px 30px;">
                                        <h4>Exam title : {{$exam->title}}</h4>
                                        <p>Start date : {{$exam->exam->start_date}}</p>
                                        <p>End date : {!!$exam->exam->end_date??'<span style="font-size:19px">∞</span>'!!}</p>
                                        <p>Duration : {!! $exam->exam->duration == 0 ? '<span style="font-size:19px">∞</span>' : $exam->exam->duration . ' minutes' !!} </p>
                                        <p>Exam attempt count : {!! $exam->exam->attempt_count == 0 ? '<span style="font-size:19px">∞</span>' : $exam->exam->attempt_count!!}</p>
                                        <p>Your attempts  : {{$users_exams_count}}</p>
                                        <p>Mark  : {{$exam->exam->exam_mark}} </p>


                                        @if(count($exam->questions) == 0)
                                                <p class="text-danger">Not Ready Now</p>
                                        @else
                                            @if( \Carbon\Carbon::create($exam->exam->end_date)  > \Carbon\Carbon::now() || is_null($exam->exam->end_date) )
                                                @if($users_exams_count == 0)
                                                    <p class="text-warning">No Attempts</p>
                                                    <a href="{{CustomRoute('user.preview.exam',$exam->id)}}" class="btn btn-primary">Start Attempt</a>
                                                @elseif($exam->exam->users_exams[$users_exams_count-1]->status == 0)
                                                    <a href="{{CustomRoute('user.preview.exam',$exam->id)}}" class="btn btn-primary">Return to Exam</a>

                                                @elseif( ( $exam->exam->attempt_count == 0) || ($users_exams_count < $exam->exam->attempt_count && $exam->exam->users_exams[$users_exams_count-1]->status == 1))
                                                    <a onclick="confirmNewAttempt()" href="{{CustomRoute('user.preview.exam',$exam->id)}}" class="btn btn-primary">Start New Attempt</a>
                                                @else
                                                    <p class="text-danger">All your attempts are over</p>
                                                @endif
                                            @else
                                                <p class="text-danger">Expired Time</p>
                                            @endif

                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-12 col-md-8 col-lg-8 mb-3">
                                <div class="card pt-3 pl-3 h-100" style="padding: 15px 30px; box-shadow: none; border: 1px solid gainsboro;">
                                    <h4 class="card-title">Exam Description</h4>
                                    <div class="card-body">
                                        <p class="card-text">{!!  $exam->details->excerpt == 'null' ? 'There is no description for this exam.' : $exam->details->excerpt  !!}</p>
                                    </div>
                                </div>

                            </div>


                              <div class="col-12 mt-5">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col"># Attempt</th>
                                        <th scope="col">Your Start Time</th>
                                        <th scope="col">Your End Time</th>
                                        <th scope="col">Review</th>
                                        <th scope="col">Details</th>
                                        <th scope="col">Time taken</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Mark</th>
                                        <th scope="col">Progress</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($exam->exam->users_exams as $attempt)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$attempt->time}}</td>
                                            <td >{{$attempt->end_attempt??'-----'}}</td>
                                            <?php

                                            $date1 = new DateTime( $attempt->time);
                                            $date2 = new DateTime($attempt->end_attempt);
                                            $interval = $date1->diff($date2);
                                            $diff = '';

                                            $diff =  $interval->h . " hours, " . $interval->i." minutes, ".$interval->s." seconds ";
                                            ?>
                                            <td>@if($attempt->status == 1)<a href="{{CustomRoute('user.review.exam',$attempt->id)}}" class="badge badge-info p-2">Review</a>@else ---- @endif</td>
                                            <td>@if($attempt->status == 1)<a href="{{CustomRoute('user.attempt_details.exam',$attempt->id)}}" class="badge badge-info p-2">View Result Details</a>@else ---- @endif</td>
                                            <td>{{$diff??'0 seconds'}}</td>
                                            <td class="text-bold">
                                                <span class="{{$attempt->status == 1 ? 'badge badge-success' : 'badge badge-danger' }}">{{$attempt->status == 1 ? 'Complete' : 'Not Complete'}}</span>
                                            </td>



                                            <td>{{($attempt->mark??'-') . ' / ' . $exam->exam->exam_mark}}</td>
                                            <td>
                                                <?php  $progress = ($attempt->mark / $exam->exam->exam_mark) * 100; $progress = round($progress,2)   ?>
                                                {{-- <div class="progress">
                                                    <div class=" progress-bar @if($progress < 50) bg-danger @endif"   role="progressbar" style="width: {{$progress}}%;" aria-valuenow="{{$progress}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div> --}}

{{--                                                <div class="progress">--}}
{{--                                                    <div style=" width: {{$progress}}%;" class="mx-auto bar @if($progress < 50) bg-danger @endif" aria-valuenow="{{$progress}}" aria-valuemin="0" aria-valuemax="100">{{$progress}}%</div>--}}
{{--                                                </div>--}}
                                                <small>{{$progress}}% Complete</small>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                </div>
            </div>
@endsection

@section('script')
    <script>
        function confirmNewAttempt(){
           if( confirm('Are u sure ?') == false)
               event.preventDefault()
        }

    </script>
@endsection
