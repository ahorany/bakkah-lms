@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')

    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-5 user-info">
                        <h4 class="mb-4"><i class="fas fa-graduation-cap"></i> {{ __('education.Exam') }}</h4>
                        <div class="row">
                            <?php $users_exams_count = count($exam->exam->users_exams) ?>

                                <div class="col-12 col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Exam title : {{$exam->title}}</h5>
                                            <p class="card-title">Start date : {{$exam->exam->start_date}}</p>
                                            <p class="card-title">End date : {{$exam->exam->end_date}}</p>
                                            <p class="card-title">Duration : {{$exam->exam->duration}} minutes</p>
                                            <p class="card-title">Exam attempt count : {{$exam->exam->attempt_count}}</p>
                                            <p class="card-title">Your attempts  : {{$users_exams_count}}</p>
                                            <p class="card-title">Mark  : {{$exam->exam->exam_mark}} </p>

                                            @if($users_exams_count == 0)
                                                <p class="text-warning">No Attempts</p>
                                                <a href="{{CustomRoute('user.preview.exam',$exam->id)}}" class="btn btn-primary">Start Attempt</a>
                                            @elseif($exam->exam->users_exams[$users_exams_count-1]->status == 0)
                                                <a href="{{CustomRoute('user.preview.exam',$exam->id)}}" class="btn btn-primary">Return to Exam</a>

                                            @elseif($users_exams_count < $exam->exam->attempt_count && $exam->exam->users_exams[$users_exams_count-1]->status == 1)
                                                <a onclick="confirmNewAttempt()" href="{{CustomRoute('user.preview.exam',$exam->id)}}" class="btn btn-primary">Start New Attempt</a>
                                            @else
                                                <p class="text-danger">All your attempts are over</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            <div class="col-12 col-md-8">

                                <div class="card pt-3 pl-3" >
                                    <h5 class="card-title">Exam Description</h5>

                                    <div class="card-body">
                                        <p class="card-text">{{$exam->details->excerpt}}</p>
                                    </div>
                                </div>

                            </div>


                            <div class="col-12 mt-5">
                                <table class="table">
                                    <caption>List of Attempts</caption>
                                    <thead>
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Your Start Time</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Mark</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($exam->exam->users_exams as $attempt)
                                        <tr>
                                            <td>Attempt # {{$loop->iteration}}</td>
                                            <td>{{$attempt->time}}</td>
                                            <td class="text-bold {{$attempt->status == 1 ? 'text-success' : 'text-danger' }}">{{$attempt->status == 1 ? 'Complete' : 'Not Complete'}}</td>
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
