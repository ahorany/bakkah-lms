@extends('layouts.app')

@section('useHead')
    <title>{{__('education.Messages')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('style')
    <style>
        table {
            border :1px solid #ccc;
            border-collapse: collapse;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        table tr {
            background: #f8f8f8;
            border: 1px solid #ccc;
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

        @media screen and (max-width: 600px) {
            table {
                border: 0;
            }
            table caption {
                font-size: 1.3em;
            }
            table thead {
                display: none;
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
            table td:before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }
            table td:last-child {
                border-bottom: 0;
            }
        }
        .main-color:hover svg#svg_icon {
            fill: #fff;
        }
        svg#svg_icon {
            fill: #fb4400;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-12 mb-2">
                <div class="card px-5 py-4">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{route('user.messages')}}" method="GET">
                                <div class="form-group">
                                    <label for="search">Search:</label>
                                    <input type="text" name="search" placeholder="Search Subject" id="search" class="form-control mb-2">
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="submit" value="Search" class="form-control main-color back_button" style="width: max-content;">
                                        <svg width="15px" version="1.1" id="svg_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                             viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
                                        <style type="text/css">
                                            .st0{fill:#FFFFFF;}
                                        </style>
                                            <path d="M3.35,23.71c0.14-0.88,0.24-1.77,0.42-2.65c0.91-4.51,3.08-8.33,6.43-11.48c2.93-2.74,6.37-4.55,10.31-5.35
                                            c4.59-0.93,9.05-0.45,13.31,1.53c4.28,1.98,7.57,5.05,9.86,9.17c1.49,2.67,2.35,5.55,2.59,8.61c0.36,4.57-0.64,8.82-2.97,12.76
                                            c-0.21,0.36-0.11,0.53,0.14,0.78c3.87,3.86,7.74,7.74,11.61,11.6c0.99,0.99,1.6,2.14,1.49,3.56c-0.13,1.74-1.02,3.01-2.61,3.69
                                            c-1.53,0.65-3.01,0.45-4.34-0.58c-0.29-0.22-0.55-0.47-0.81-0.73c-3.76-3.76-7.52-7.51-11.27-11.28c-0.3-0.31-0.5-0.34-0.86-0.09
                                            c-2.54,1.72-5.35,2.82-8.37,3.28c-4.29,0.66-8.44,0.15-12.4-1.69c-4.3-2-7.59-5.08-9.9-9.21c-1.43-2.56-2.29-5.32-2.54-8.25
                                            c-0.01-0.15-0.06-0.3-0.1-0.45C3.35,25.86,3.35,24.78,3.35,23.71z M39.36,25.28c-0.07-8.07-6.55-14.51-14.55-14.48
                                            c-7.97,0.03-14.5,6.42-14.48,14.55c0.02,8.09,6.6,14.53,14.62,14.47C32.91,39.77,39.43,33.19,39.36,25.28z"/>
                                        </svg>
                                        Search
                                    </button>
                                    <input type="reset" name="reset" value="Clear" class="form-control cyan" style="width: max-content;">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-2">
                <div class="card p-5">
                    <table>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Course</th>
                            <th>Sender</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Reply</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0; $lang = app()->getLocale(); ?>
                        @foreach ($messages as $message)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{json_decode($message->course_title)->$lang}}</td>
                                <td>{{json_decode($message->username_send_msg)->$lang}}</td>
                                <td>{{$message->msg_title}}</td>
                                <td>{{$message->msg_date}}</td>
                                <td>
{{--                                    <a href="{{route('user.reply_message',$message->msg_id)}}" class="green">Reply</a>--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
