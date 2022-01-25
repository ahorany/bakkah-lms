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
                            <form action="{{route('user.messages.inbox')}}" method="GET">
                                <div class="form-group">
                                    <label for="search">Search:</label>
                                    <input type="text" name="search" placeholder="Search Subject" id="search" class="form-control mb-2">
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="submit" value="Search" class="form-control main-color back_button" style="width: max-content;">
                                        <svg width="15px" version="1.1" id="svg_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 60 60" style="vertical-align: middle;" xml:space="preserve">
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
                    <div class="add_message mb-2">
                        <a href="{{route('user.add_message')}}" class="main-color form-control" style="width: max-content;">
                            <svg width="12px" version="1.1" id="svg_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve">
                            <style type="text/css">
                                .st0{fill:#FFFFFF;}
                            </style>
                            <path d="M57.28,30.77c-0.25,0.89-0.57,1.75-1.22,2.44c-0.94,1-2.09,1.53-3.47,1.53c-5.55,0-11.1,0-16.65,0c-0.21,0-0.41,0-0.69,0
                                c0,0.23,0,0.43,0,0.64c0,5.57,0,11.13,0,16.7c0,2.37-1.73,4.34-4.08,4.67c-2.26,0.32-4.47-1.12-5.12-3.36
                                c-0.13-0.46-0.18-0.96-0.19-1.44c-0.01-5.53-0.01-11.06-0.01-16.6c0-0.19,0-0.37,0-0.61c-0.25,0-0.46,0-0.66,0
                                c-5.57,0-11.14,0-16.7,0c-2.16,0-4.08-1.52-4.55-3.6c-0.49-2.15,0.5-4.36,2.48-5.28c0.66-0.3,1.43-0.49,2.15-0.5
                                c5.55-0.04,11.1-0.02,16.65-0.02c0.19,0,0.38,0,0.64,0c0-0.23,0-0.44,0-0.64c0-5.5,0-10.99,0-16.49c0-2.48,1.36-4.21,3.78-4.81
                                c0.07-0.02,0.13-0.06,0.19-0.09c0.49,0,0.97,0,1.46,0c0.05,0.03,0.09,0.06,0.14,0.07c2.49,0.62,3.83,2.33,3.83,4.88
                                c0,5.48,0,10.96,0,16.44c0,0.2,0,0.4,0,0.64c0.28,0,0.48,0,0.69,0c5.55,0,11.1,0,16.65,0c1.38,0,2.53,0.53,3.47,1.53
                                c0.65,0.69,0.97,1.55,1.22,2.44C57.28,29.79,57.28,30.28,57.28,30.77z"/>
                            </svg>
                            Send Message</a>
                    </div>

                    <table class="result_review">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Course</th>
                            @if($is_inbox)
                            <th>Sender</th>
                            @else
                            <th>Recipients</th>
                            @endif
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Reply count</th>
                            <th>Reply</th>
                        </tr>
{{--                            <tr>--}}
{{--                                <td>#</td>--}}
{{--                                <td>Course</td>--}}
{{--                                <td>Recipients</td>--}}
{{--                                <td>Subject</td>--}}
{{--                                <td>Date</td>--}}
{{--                                <td>Count of Replies</td>--}}
{{--                                <td>Reply</td>--}}
{{--                            </tr>--}}
                        </thead>
                        <tbody>
                        <?php  $lang = app()->getLocale(); ?>
                        @foreach ($messages as $message)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{json_decode($message->course_title)->$lang}}</td>
                                @if($is_inbox)
                                <td>{{json_decode($message->username_send_msg)->$lang}}</td>
                                @else
                                <td>{{$message->recipient == 2 ? "Instructor" : "Admin" }}</td>
                                @endif
                                <td>{{$message->msg_title}}</td>
                                <td>{{$message->msg_date}}</td>
                                <td>{{$message->replies_count}}</td>
                                <td>
                                      <a href="{{route('user.reply_message',$message->msg_id)}}" class="green form-control">Reply</a>
                                </td>
                            </tr>
{{--                                <tr>--}}
{{--                                    <td>{{$loop->iteration}}</td>--}}
{{--                                    <td>{{json_decode($message->course_title)->$lang}}</td>--}}
{{--                                    <td>{{(($message->role_id == 1) ? 'Admin' : (($message->role_id == 2) ? 'Instructor' : null))}}</td>--}}
{{--                                    <td>{{$message->title??null}}</td>--}}
{{--                                    <td>{{$message->created_at??null}}</td>--}}
{{--                                    <td>{{count($message->replies)??null}}</td>--}}
{{--                                    <td>--}}
{{--                                        <a href="{{route('user.reply_message',$message->id)}}" class="green">Reply</a>--}}
{{--                                        --}}{{-- @if (($user->roles[0]->pivot->role_id == 1) || ($user->roles[0]->pivot->role_id == 2))--}}
{{--                                            @if ($message->reply == null)--}}
{{--                                                <a href="{{route('user.reply_message',$message->id)}}" class="green">Reply</a>--}}
{{--                                            @else--}}
{{--                                                <a href="{{route('user.reply_message',$message->id)}}" class="green">Show Reply</a>--}}
{{--                                            @endif--}}
{{--                                        @else--}}
{{--                                            @if ($message->reply == null)--}}
{{--                                                <span>No reply</span>--}}
{{--                                            @else--}}
{{--                                                <a href="{{route('user.reply_message',$message->id)}}" class="green">Show Reply</a>--}}
{{--                                            @endif--}}
{{--                                        @endif --}}
{{--                                    </td>--}}
{{--                                </tr>--}}
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
