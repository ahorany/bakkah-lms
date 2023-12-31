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

@section('sidebar-content')
    @include("layouts.sidebar-content",['content' => $exam])
@endsection

@section('content')
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


                        @if(count($exam->questions) == 0 || (\Carbon\Carbon::create($exam->exam->start_date)  > \Carbon\Carbon::now() && !is_null($exam->exam->start_date)))
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

                        @endif
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
                                        <a href="{{CustomRoute('user.review.exam',$attempt->id)}}" class="badge-blue p-2">Review</a>
                                    </span>
                                @else
                                    <span>----</span>
                                @endif
                            </td>
                            <td data-label="Details">
                                @if($attempt->status == 1 && $exam->exam->end_date <= \Carbon\Carbon::now())
                                    <span>
                                        <a href="{{CustomRoute('user.attempt_details.exam',$attempt->id)}}" class="badge-red p-2">View Result Details</a>
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
