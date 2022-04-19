@extends('layouts.app')

@section('useHead')
    <title>{{$exam->course->trans_title}} {{ __('education.Exam') }} | {{ __('home.DC_title') }}</title>
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

        table tbody tr {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            padding: .35em;
        }

        table thead tr{
            background-color: #f0f0f0;
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

        .progress {
            background: gainsboro;
            border-radius: 5px;
            overflow: hidden;
            color: #fff;
        }
        .progress-bar{
            width: 50%;
            margin: 0 !important;
        }
        .exam_page .card .card-body * {
            margin: 0 0 10px;
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

            table tr td span {
                font-size: 14px;
            }

            table td {
                border-bottom: 1px solid #ddd;
                display: block;
                font-size: .8em;
                text-align: left;
                display: flex;
                align-items: center;
                /* justify-content: space-between; */
            }

            table td::before {
                content: attr(data-label);
                text-align: left;
                font-weight: bold;
                text-transform: uppercase;
                min-width: 120px;
                margin-right: 25px;
            }

            table td:last-child {
                border-bottom: 0;
            }
        }

    </style>
@endsection

{{-- @section('sidebar-content')
    @include("layouts.sidebar-content",['content' => $exam])
@endsection --}}

@section('content')
@if(isset($user))
<h1 style="text-align:left;float:left;">{{$user->trans_name}}</h1>
<h1 style="text-align:right;float:right;">{{$course->trans_title}}</h1>
<hr style="clear:both;"/>
@endif

<div class="d-flex p-3" style="justify-content: space-between; align-items:center; flex-wrap: wrap;">
    <h2 class="m-0"><i class="fas fa-graduation-cap"></i> Exam Details</h2>
    <a style="width: 85px;" href="{{route('training.progressDetails',['user_id'=>$user->id,'course_id'=>$course->id])}}"  class="cyan form-control">
    <span>
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="back" style="vertical-align: middle;" width="35%" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
            <path d="M8.66,30.08c0.27-1.02,1-1.72,1.72-2.43c4.76-4.75,9.51-9.51,14.27-14.26c1.15-1.15,2.78-1.32,4.01-0.44  c1.42,1.03,1.67,3.1,0.54,4.45c-0.11,0.13-0.22,0.25-0.34,0.37c-2.77,2.77-5.53,5.56-8.34,8.31c-0.61,0.6-1.37,1.04-2.06,1.54  c0.1,0,0.26,0,0.42,0c9.65,0,19.3,0,28.95,0c1.02,0,1.94,0.24,2.65,1.04c1.53,1.75,0.67,4.45-1.61,4.98  c-0.37,0.09-0.77,0.1-1.15,0.1c-9.64,0.01-19.27,0-28.91,0c-0.16,0-0.33,0-0.53,0c0.05,0.06,0.07,0.1,0.1,0.11  c1.08,0.43,1.93,1.17,2.73,1.99c2.55,2.57,5.1,5.13,7.66,7.69c0.7,0.7,1.14,1.49,1.12,2.5c-0.03,1.21-0.56,2.1-1.66,2.61  c-1.08,0.5-2.13,0.38-3.1-0.31c-0.24-0.17-0.44-0.38-0.65-0.58c-4.63-4.63-9.25-9.25-13.88-13.88c-0.78-0.78-1.62-1.51-1.94-2.62  C8.66,30.85,8.66,30.47,8.66,30.08z"/>
        </svg>
    </span>
    <span>back</span>
    </a>
</div>

    <div class="card p-5 user-info exam_page">

        <div class="dash-header d-flex justify-content-between align-items-center">
            @include('pages.templates.breadcrumb', [
                'course_id'=>$exam->course->id,
                'course_title'=>$exam->course->trans_title,
                'section_title' => $exam->section->title,
                'content_title'=>$exam->title,
            ])
            <div class="d-flex mobile-show" style="align-items: center;">
                <h3 class="m-0 title_file_old">{{ $exam->section->title }}</h3>
            </div>

        </div>

        <br>

        <div class="row home-section">
            @if(session()->has('status'))
                <div class="col-md-12">
                    <div class="error-notice">
                        <div class="oaerror danger">
                            {{session()->get('msg')}}
                        </div>
                    </div>
                </div>
            @endif

            <?php $users_exams_count = count($exam->exam->users_exams) ?>

            <div class="col-12 col-sm-12 col-md-6 col-lg-5 mb-3">
                <div class="card h-100" style="padding: 15px 30px; box-shadow: none; border: 1px solid gainsboro;">
                    <div class="card-body">
                        <h4 class="card-title">Exam title : {{$exam->title}}</h4>
                        <p>Start date : {{$exam->exam->start_date}}</p>
                        <p>End date : {!!$exam->exam->end_date??'<span style="font-size:19px">∞</span>'!!}</p>
                        <p>Duration : {!! $exam->exam->duration == 0 ? '<span style="font-size:19px">∞</span>' : $exam->exam->duration . ' minutes' !!} </p>
                        <p>Exam attempt count : {!! $exam->exam->attempt_count == 0 ? '<span style="font-size:19px">∞</span>' : $exam->exam->attempt_count!!}</p>
                        <p>Your attempts : {{$users_exams_count}}</p>
                        <p>Marks : {{$exam->exam->exam_mark}} </p>
                        @if($exam->exam->pass_mark != 0)
                            <p>Pass Mark : {{$exam->exam->pass_mark}} % </p>
                        @endif

                        {{-- @if(count($exam->questions) == 0 || (\Carbon\Carbon::create($exam->exam->start_date)  > \Carbon\Carbon::now() && !is_null($exam->exam->start_date)))
                                <p class="text-danger">Not Ready Now</p>
                        @else
                            @if( \Carbon\Carbon::create($exam->exam->end_date)  > \Carbon\Carbon::now() || is_null($exam->exam->end_date) )
                                @if($users_exams_count == 0)
                                    <p style="color: darkred">No Attempts</p>
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

                        @endif --}}

                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-6 col-lg-7 mb-3 @if ($exam->details->excerpt == null) display-none @endif">
                <div class="card pt-3 pl-3 h-100" style="padding: 15px 30px; box-shadow: none; border: 1px solid gainsboro;">
                    <h4 class="card-title">Exam Description</h4>
                    <div class="card-body">
                        <p class="card-text">{!!  $exam->details->excerpt == 'null' ? 'There is no description for this exam.' : $exam->details->excerpt  !!}</p>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col"># Attempt</th>
                        <th scope="col">Your Start Time</th>
                        <th scope="col">Your End Time</th>
                        <th scope="col">Review</th>
                        <th scope="col" style="width: 18%;">Details</th>
                        <th scope="col">Time taken</th>
                        <th scope="col">Pass Mark (%)</th>
                        <th scope="col">Status</th>
                        <th scope="col">Mark</th>
                        <th scope="col">Progress</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($exam->exam->users_exams as $attempt)
                        <?php
                            $date1 = new DateTime( $attempt->time);
                            $date2 = new DateTime($attempt->end_attempt);
                            $interval = $date1->diff($date2);
                            $diff = '';
                            $diff =  $interval->h . " hours, " . $interval->i." minutes, ".$interval->s." seconds ";
                        ?>
                        <tr>
                            <td data-label="# Attempt">
                                <span>{{$loop->iteration}}</span>
                            </td>
                            <td data-label="Your Start Time">
                                <span>{{$attempt->time}}</span>
                            </td>
                            <td data-label="Your End Time">
                                <span>{{$attempt->end_attempt??'-----'}}</span>
                            </td>
                            <td data-label="Review">
                                @if($attempt->status == 1 && $exam->exam->end_date <= \Carbon\Carbon::now())
                                    <span>
                                        <a href="{{CustomRoute('training.exam.review',[$attempt->id,$user->id,$exam->course->id])}}" class="badge-blue p-2">Review</a>
                                    </span>
                                @else
                                    <span>----</span>
                                @endif
                            </td>
                            <td data-label="Details">
                                @if($attempt->status == 1 && $exam->exam->end_date <= \Carbon\Carbon::now())
                                    <span>
                                        <a href="{{CustomRoute('training.exam.exam_result_details',[$attempt->id,$user->id,$exam->course->id])}}" class="badge-red p-2">View Result Details</a>
                                    </span>
                                @else
                                    <span>---- </span>
                                @endif
                            </td>
                            <td data-label="Time taken">
                                <span>{{$diff??'0 seconds'}}</span>
                            </td>
                            <td data-label="Pass Mark (%)" class="text-bold">
                                <span>{{($exam->exam->pass_mark??0).'%'}}</span>
                            </td>
                            <td data-label="Status" class="text-bold">
                                @if( (($exam->exam->exam_mark * $exam->exam->pass_mark) / 100) <= $attempt->mark)
                                    <span class="badge-green">Pass</span>
                                @else
                                    <span class="badge-red">Fail</span>
                                @endif
                            </td>
                            <td data-label="Mark">
                                <span>{{($attempt->mark??'-') . ' / ' . $exam->exam->exam_mark}}</span>
                            </td>
                            <td data-label="Progress">
                                @if($exam->exam->exam_mark && $exam->exam->exam_mark != 0)
                                    <?php  $progress = ($attempt->mark / $exam->exam->exam_mark) * 100; $progress = round($progress,2)   ?>
                                    <span>{{($progress > 0) ? number_format($progress, 0, '.', ',').'%'  : '0%' }}</span>

                                @endif
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
        // if( confirm('Are u sure ?') == false)
        //     event.preventDefault()
        Swal.fire({
        title: 'Start the exam',
        // text: "To start new attempt",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{CustomRoute('user.preview.exam',$exam->id)}}";
            }
            // event.preventDefault()
        })
        event.preventDefault()
    }
</script>
@endsection
