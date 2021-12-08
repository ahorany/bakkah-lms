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
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card px-5 py-3 mb-2" style="direction: rtl;">
                    <a href="{{route('user.add_message')}}" class="main-color" style="width: max-content;">Add Message</a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card p-5">
                    <table>
                        <thead>
                            <tr>
                                <th>#</td>
                                <th>Course</td>
                                <th>Recipients</td>
                                <th>Subject</td>
                                <th>Date</td>
                                <th>Replay</td>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @dd($messages) --}}
                            @foreach ($messages as $message)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$message->courses->course->trans_title??null}}</td>
                                    <td>{{(($message->role_id == 1) ? 'Admin' : (($message->role_id == 2) ? 'Instructor' : null))}}</td>
                                    <td>{{$message->title??null}}</td>
                                    <td>{{$message->created_at??null}}</td>
                                    <td>
                                        @if ($message->replay == null)
                                            <a href="{{route('user.replay_message',$message->id)}}" class="main-color">Replay</a>
                                        @else
                                        <a href="{{route('user.replay_message',$message->id)}}" class="main-color">Show Replay</a>
                                        @endif
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
