@extends('layouts.app')

@section('useHead')
    <title>{{$exam_title}} {{ __('education.Exam Details') }} | {{ __('home.DC_title') }}</title>
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

    @media screen and (max-width: 767px) {
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
        <div class="d-flex" style="justify-content: space-between;">
            <h4 class="m-0"><i class="fas fa-graduation-cap"></i> {{$exam_title}}</h4>
            <a style="width: 8%;" href="{{CustomRoute('user.exam',$exam_id)}}" class="main-color">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="back" style="vertical-align: middle;" width="35%" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
                    <path d="M8.66,30.08c0.27-1.02,1-1.72,1.72-2.43c4.76-4.75,9.51-9.51,14.27-14.26c1.15-1.15,2.78-1.32,4.01-0.44  c1.42,1.03,1.67,3.1,0.54,4.45c-0.11,0.13-0.22,0.25-0.34,0.37c-2.77,2.77-5.53,5.56-8.34,8.31c-0.61,0.6-1.37,1.04-2.06,1.54  c0.1,0,0.26,0,0.42,0c9.65,0,19.3,0,28.95,0c1.02,0,1.94,0.24,2.65,1.04c1.53,1.75,0.67,4.45-1.61,4.98  c-0.37,0.09-0.77,0.1-1.15,0.1c-9.64,0.01-19.27,0-28.91,0c-0.16,0-0.33,0-0.53,0c0.05,0.06,0.07,0.1,0.1,0.11  c1.08,0.43,1.93,1.17,2.73,1.99c2.55,2.57,5.1,5.13,7.66,7.69c0.7,0.7,1.14,1.49,1.12,2.5c-0.03,1.21-0.56,2.1-1.66,2.61  c-1.08,0.5-2.13,0.38-3.1-0.31c-0.24-0.17-0.44-0.38-0.65-0.58c-4.63-4.63-9.25-9.25-13.88-13.88c-0.78-0.78-1.62-1.51-1.94-2.62  C8.66,30.85,8.66,30.47,8.66,30.08z"/>
                </svg> back</a>
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
                                <?php  $progress = ($data->unit_marks / $data->total_marks) * 100; $progress = round($progress,2)  ?>
                                <div class="progress">
                                    <div class="mx-auto progress-bar @if($progress < 50) bg-danger @endif"   role="progressbar" style="width: {{$progress}}%;" aria-valuenow="{{$progress}}" aria-valuemin="0" aria-valuemax="100">{{$progress}}%</div>
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
