@extends('layouts.app')

@section('useHead')
    <title>{{__('education.Messages')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('style')
    <style>
        .reply{
            background: #f7f7f7;
            padding: 15px;
            border-radius: 5px;
        }
        .head{
            display: flex;
            justify-content: space-between;
        }
        .username{
            margin: 0;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card px-5 py-3 mb-2" style="direction: rtl;">
            <a href="{{route('user.messages')}}" class="main-color back_button" style="width: 85px;">
                Back
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="back" style="vertical-align: middle;" width="35%" x="0px" y="0px" viewBox="0 0 60 60" xml:space="preserve">
                    <path d="M8.66,30.08c0.27-1.02,1-1.72,1.72-2.43c4.76-4.75,9.51-9.51,14.27-14.26c1.15-1.15,2.78-1.32,4.01-0.44  c1.42,1.03,1.67,3.1,0.54,4.45c-0.11,0.13-0.22,0.25-0.34,0.37c-2.77,2.77-5.53,5.56-8.34,8.31c-0.61,0.6-1.37,1.04-2.06,1.54  c0.1,0,0.26,0,0.42,0c9.65,0,19.3,0,28.95,0c1.02,0,1.94,0.24,2.65,1.04c1.53,1.75,0.67,4.45-1.61,4.98  c-0.37,0.09-0.77,0.1-1.15,0.1c-9.64,0.01-19.27,0-28.91,0c-0.16,0-0.33,0-0.53,0c0.05,0.06,0.07,0.1,0.1,0.11  c1.08,0.43,1.93,1.17,2.73,1.99c2.55,2.57,5.1,5.13,7.66,7.69c0.7,0.7,1.14,1.49,1.12,2.5c-0.03,1.21-0.56,2.1-1.66,2.61  c-1.08,0.5-2.13,0.38-3.1-0.31c-0.24-0.17-0.44-0.38-0.65-0.58c-4.63-4.63-9.25-9.25-13.88-13.88c-0.78-0.78-1.62-1.51-1.94-2.62  C8.66,30.85,8.66,30.47,8.66,30.08z"></path>
                </svg>
            </a>
        </div>
        <div class="card p-5">
            <form action="{{route('user.add_reply')}}" method="GET">
                <div class="row">
                    {{-- <div class="col-md-3">
                        <h2>{{$user->trans_name}}</h2>
                    </div> --}}
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <span for=""><strong>Subject: </strong></span><label>{{$message->title??null}}</label>
                                    <br><br>
                                    <span for=""><strong>Description: </strong></span><label>{{$message->description??null}}</label>
                                    <br><br><br>
                                    @foreach ($message->replies as $reply)
                                    <div class="reply">
                                        <div class="head">
                                            <h4 class="username">{{$reply->user->trans_name??null}}</h4>
                                            <small>{{$reply->created_at}}</small>
                                        </div>
                                        <p>{{$reply->title}}</p>
                                    </div>
                                    <br>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <input type="hidden" name="message_id" value="{{$message->id}}">
                                    <label for="reply"><strong>Reply: </strong></label>
                                    <textarea type="text" placeholder="Reply" name="reply" id="reply" class="form-control" style="height: 150px;"></textarea>
                                    @error('reply')
                                        <span style="color: red;">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <input type="submit" class="main-color" value="Submit">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
