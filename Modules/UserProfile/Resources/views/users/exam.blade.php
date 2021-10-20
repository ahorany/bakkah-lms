@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')

<style>
.userarea-wrapper{
    background: #fafafa;
}
.number_question {
    top: 0;
    right: 0;
    text-align: center;
    background: #fb4400;
    border-bottom-left-radius: 15px;
    color: #fff;
    font-size: 30px;
    font-weight: 700;
    width: 100px;
    height: 100px;
    padding: 30px 0;
}
.question{
    font-size: 20px;
    font-weight: 700;
}
.card> label {
    font-size: 16px;
    font-weight: 700;
}
.answer label {
    font-size: 15px;
}
.arrow i {
    border: 1px solid;
    border-radius: 50%;
    padding: 5px 10px;
    text-align: center;
    margin: 0 5px;
    font-size: 20px;
    cursor:pointer;
}
.arrow i:hover {
    color: #fb4400
}
input[type="submit"] {
    background: transparent;
    border: 1px solid #fb4400;
    padding: 5px 25px;
    font-size: 16px;
    border-radius: 5px;
}
input[type="submit"]:hover{
    background:#fb4400;
    color:#fff;
}
label.navigation {
    border: 2px solid #9a9a9a;
    border-radius: 7px;
    width: 90%;
    height: 40px;
    text-align: center;
    padding: 10px 0;
    background:transparent;
}
.done_question{
    background: #efefef !important;
}
table.table {
    text-align: center;
}
</style>
    <div class="userarea-wrapper exam">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-5 user-info">
                        <h4 class="mb-4"><i class="fas fa-graduation-cap"></i> {{ __('education.Exam') }}</h4>
                        <div class="row">
                            <?php $users_exams_count = count($exam->exam->users_exams) ?>

                                <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Exam title : {{$exam->title}}</h5>
                                            <p class="card-title">Start date : {{$exam->exam->start_date}}</p>
                                            <p class="card-title">End date : {!!$exam->exam->end_date??'<span style="font-size:19px">∞</span>'!!}</p>
                                            <p class="card-title">Duration : {!! $exam->exam->duration == 0 ? '<span style="font-size:19px">∞</span>' : $exam->exam->duration . ' minutes' !!} </p>
                                            <p class="card-title">Exam attempt count : {!! $exam->exam->attempt_count == 0 ? '<span style="font-size:19px">∞</span>' : $exam->exam->attempt_count!!}</p>
                                            <p class="card-title">Your attempts  : {{$users_exams_count}}</p>
                                            <p class="card-title">Mark  : {{$exam->exam->exam_mark}} </p>


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

                            <div class="col-12 col-sm-12 col-md-8 col-lg-8">

                                <div class="card pt-3 pl-3 h-100" >
                                    <h5 class="card-title">Exam Description</h5>

                                    <div class="card-body">
                                        <p class="card-text">{!!  $exam->details->excerpt  !!}</p>
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
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        function confirmNewAttempt(){
           if( confirm('Are u sure ?') == false)
               event.preventDefault()
        }

    </script>
@endsection
