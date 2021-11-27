@extends('layouts.app')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')
    <div class="card p-5 user-info">
                        <div class="clearfix">
                            <h4 class="mb-4 float-left"><i class="fas fa-graduation-cap"></i> {{$exam_title}}</h4>
                            <a href="{{CustomRoute('user.exam',$exam_id)}}" class="mb-4 btn btn-primary float-right"><i class="fa fa-arrow-left"></i> back</a>
                        </div>

                        <div class="row">
                            <div class="col-12 mt-5">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Unit</th>
                                        <th scope="col">Mark</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($unit_marks as $data)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$data->unit_title?? 'Other'}}</td>
                                            <td>
                                                {{ ($data->unit_marks??0) .' / ' . $data->total_marks}}
                                                <?php  $progress = ($data->unit_marks / $data->total_marks) * 100; $progress = round($progress,2)   ?>
                                                <div class="progress mt-2 w-50">
                                                    <div class="progress-bar @if($progress < 50) bg-danger @endif"   role="progressbar" style="width: {{$progress}}%;" aria-valuenow="{{$progress}}" aria-valuemin="0" aria-valuemax="100">{{$progress}}%</div>
                                                </div>
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
