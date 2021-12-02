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
                        <div class="clearfix">
                            <h4 class="mb-4 float-left"><i class="fas fa-graduation-cap"></i> {{$exam_title}}</h4>
                            <a href="{{CustomRoute('user.exam',$exam_id)}}" class="mb-4 main-color float-right"><i class="fa fa-arrow-left"></i> back</a>
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
