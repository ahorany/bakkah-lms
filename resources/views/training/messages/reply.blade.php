@extends('layouts.app')

@section('useHead')
    <title>{{__('education.Messages')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('style')
    <style>
        .message-panel {
            background-color: #f2f2f2;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #eaeaea;
            border-left: 2px solid #d8d8d8;
            border-bottom: 1px solid #d8d8d8;
        }
        .reply {
            background: #f7f7f7;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #f2f2f2;
            border-left: 2px solid #eaeaea;
            border-bottom: 1px solid #eaeaea;
            margin-top: 10px;
            /* margin-left: 5px !important; */
        }
        .head {
            display: flex;
            justify-content: space-between;
            font-style: italic;
        }
        .username {
            margin: 0;
            color: #666666;
            font-weight: normal;
        }
    </style>
@endsection

@section('content')
    <div id="message-app" class="container">
        <div class="card px-5 py-3 mb-2" style="direction: rtl;">
            <a href="{{route('user.messages.inbox')}}" class="cyan form-control" style="width: 85px; text-align:center;">
                {{__('education.Back')}}
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="back" style="vertical-align: middle;" width="35%" x="0px" y="0px" viewBox="0 0 60 60" xml:space="preserve">
                    <path d="M8.66,30.08c0.27-1.02,1-1.72,1.72-2.43c4.76-4.75,9.51-9.51,14.27-14.26c1.15-1.15,2.78-1.32,4.01-0.44  c1.42,1.03,1.67,3.1,0.54,4.45c-0.11,0.13-0.22,0.25-0.34,0.37c-2.77,2.77-5.53,5.56-8.34,8.31c-0.61,0.6-1.37,1.04-2.06,1.54  c0.1,0,0.26,0,0.42,0c9.65,0,19.3,0,28.95,0c1.02,0,1.94,0.24,2.65,1.04c1.53,1.75,0.67,4.45-1.61,4.98  c-0.37,0.09-0.77,0.1-1.15,0.1c-9.64,0.01-19.27,0-28.91,0c-0.16,0-0.33,0-0.53,0c0.05,0.06,0.07,0.1,0.1,0.11  c1.08,0.43,1.93,1.17,2.73,1.99c2.55,2.57,5.1,5.13,7.66,7.69c0.7,0.7,1.14,1.49,1.12,2.5c-0.03,1.21-0.56,2.1-1.66,2.61  c-1.08,0.5-2.13,0.38-3.1-0.31c-0.24-0.17-0.44-0.38-0.65-0.58c-4.63-4.63-9.25-9.25-13.88-13.88c-0.78-0.78-1.62-1.51-1.94-2.62  C8.66,30.85,8.66,30.47,8.66,30.08z"></path>
                </svg>
            </a>
        </div>
        <div class="card p-5">
            <form action="{{route('user.add_reply')}}" method="GET">
                <div class="row">
                    <div class="col-md-12">
                        <div class="message-panel mb-2" style="background-color: #ffffff;">
                            <strong>{{__('education.Course')}}: </strong>
                            {{ \App\Helpers\Lang::TransTitle($message->course->course->title??null )}}
                        </div>
                        <div class="form-group mb-3">
                            <div class="message-panel">
                                <div class="head mb-2">
                                    <h4 class="username">
                                        {{$message->user->trans_name??null}}
                                    </h4>
                                    <small style="line-height: 1.5rem; color:#999999; display: flex;">
                                        <span>
                                            <svg version="1.1" id="Layer_1" width="20" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                    viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                                                <g>
                                                    <path fill="#9c9c9c" d="M28.92,10.88c0.46,0,0.92,0,1.38,0c1.72,0.72,2.36,2.05,2.19,3.86c-0.06,0.63-0.01,1.27-0.01,1.94c11.71,0,23.33,0,35.04,0
                                                        c0-0.78-0.02-1.51,0.01-2.25c0.02-0.53,0.03-1.08,0.17-1.59c0.27-1.03,1.13-1.52,2-1.96c0.46,0,0.92,0,1.38,0
                                                        c1.73,0.65,2.41,1.93,2.28,3.73c-0.05,0.68-0.01,1.37-0.01,2.11c1.45,0,2.81-0.09,4.16,0.03c1.24,0.1,2.52,0.25,3.67,0.68
                                                        c4.26,1.58,6.75,5.4,6.75,10.19c0.01,17.05,0,34.1,0,51.16c0,0.41-0.02,0.82-0.05,1.23c-0.39,5.37-4.85,9.64-10.23,9.65
                                                        c-18.44,0.03-36.87,0.01-55.31,0.01c-1.53,0-3.01-0.34-4.37-1.05c-3.97-2.07-5.91-5.38-5.91-9.84c0-16.92,0-33.85,0-50.77
                                                        c0-0.49,0-0.98,0.04-1.46c0.32-4.74,3.53-8.6,8.18-9.54c1.73-0.35,3.57-0.2,5.36-0.28c0.32-0.01,0.64,0,1,0
                                                        c0-0.78,0.04-1.45-0.01-2.11C26.51,12.81,27.18,11.53,28.92,10.88z M26.64,22.56c-1.3,0-2.47,0-3.65,0c-3.23,0-5.1,1.86-5.1,5.11
                                                        c0,17,0,34,0,51.01c0,3.29,1.86,5.14,5.15,5.14c13.05,0,26.11,0,39.16,0c5.08,0,10.16,0.01,15.23,0c2.16-0.01,3.96-1.37,4.48-3.41
                                                        c0.14-0.56,0.18-1.17,0.18-1.75c0.01-16.98,0.01-33.95,0-50.93c0-0.41-0.01-0.82-0.08-1.23c-0.31-1.93-1.41-3.35-3.3-3.72
                                                        c-1.73-0.34-3.54-0.21-5.37-0.29c0,1.03,0.01,1.87,0,2.72c-0.02,1.86-1.25,3.19-2.93,3.19c-1.68,0-2.89-1.33-2.9-3.2
                                                        c0-0.86,0-1.72,0-2.57c-11.74,0-23.36,0-35.04,0c0,0.9,0,1.75,0,2.59c-0.01,1.86-1.24,3.19-2.92,3.18
                                                        c-1.68-0.01-2.9-1.34-2.91-3.21C26.64,24.35,26.64,23.5,26.64,22.56z"/>
                                                    <path fill="#9c9c9c" d="M33.24,66.32c2.01,0.01,3.68,1.71,3.64,3.71c-0.04,1.99-1.7,3.61-3.67,3.6c-2.01-0.01-3.68-1.72-3.64-3.71
                                                        C29.61,67.93,31.26,66.3,33.24,66.32z"/>
                                                    <path fill="#9c9c9c" d="M33.22,59.03c-2.01,0-3.68-1.69-3.65-3.69c0.03-1.99,1.66-3.6,3.66-3.6c2,0,3.62,1.61,3.65,3.6
                                                        C36.9,57.34,35.23,59.03,33.22,59.03z"/>
                                                    <path fill="#9c9c9c" d="M61.68,40.8c0-2.04,1.61-3.65,3.66-3.64c2.01,0.01,3.6,1.6,3.6,3.62c0,2.04-1.62,3.66-3.66,3.65
                                                        C63.29,44.42,61.69,42.8,61.68,40.8z"/>
                                                    <path fill="#9c9c9c" d="M52.92,69.95c0.01,2.01-1.66,3.69-3.67,3.67c-2-0.02-3.6-1.64-3.61-3.64c-0.01-2.03,1.64-3.69,3.66-3.67
                                                        C51.28,66.33,52.91,67.97,52.92,69.95z"/>
                                                    <path fill="#9c9c9c" d="M68.95,55.35c0.01,2.04-1.61,3.68-3.64,3.68c-1.99-0.01-3.61-1.63-3.62-3.62c-0.01-2.04,1.62-3.68,3.64-3.67
                                                        C67.34,51.75,68.94,53.35,68.95,55.35z"/>
                                                    <path fill="#9c9c9c" d="M33.2,37.16c2.04-0.01,3.69,1.63,3.67,3.65c-0.02,1.99-1.65,3.61-3.64,3.62c-2.02,0.01-3.69-1.65-3.67-3.66
                                                        C29.58,38.78,31.2,37.17,33.2,37.16z"/>
                                                    <path fill="#9c9c9c" d="M68.95,69.98c0,2-1.61,3.62-3.6,3.64c-2.01,0.02-3.67-1.64-3.66-3.66c0-2,1.61-3.62,3.61-3.64
                                                        C67.31,66.3,68.95,67.94,68.95,69.98z"/>
                                                    <path fill="#9c9c9c" d="M45.64,55.41c-0.02-2.04,1.6-3.68,3.64-3.67c2,0,3.62,1.62,3.64,3.61c0.01,2.02-1.64,3.68-3.65,3.68
                                                        C47.27,59.03,45.66,57.42,45.64,55.41z"/>
                                                    <path fill="#9c9c9c" d="M49.25,37.16c2.04-0.01,3.68,1.61,3.67,3.64c-0.01,1.99-1.63,3.61-3.62,3.63c-2.04,0.01-3.66-1.61-3.66-3.65
                                                        C45.64,38.76,47.23,37.17,49.25,37.16z"/>
                                                </g>
                                            </svg>
                                        </span>
                                        <span>{{$message->created_at}}</span>
                                    </small>
                                </div>
                                <hr>
                                <div class="pb-2">
                                    <strong>{{$message->title??null}}</strong>
                                </div>
                                <div class="pt-2 pb-2">
                                    <label>{{$message->description??null}}</label>
                                </div>
                                @include('training.messages.like_reply_btn', [
                                    'table_name'=>'messages',
                                    'eloquent'=>$message,
                                ])
                            </div>
                            <br>

                            @include('training.messages.replies', compact('message'))
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <input type="hidden" name="message_id" value="{{$message->id}}">
                            <label for="reply"><strong>{{__('education.Reply')}}: </strong></label>
                            <textarea type="text" placeholder="Reply" name="reply" id="reply" class="form-control" style="height: 150px;"></textarea>
                            @error('reply')
                                <span style="color: red;">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <input type="submit" class="main-color form-control" value="Submit">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
<script>
var vrm = new Vue({
	el:'#message-app',
    data:{
        liked_it:'',
        loved_it:'',
    },
    methods: {
        Like: function(e, operation, table_name, likeable_id){
            e.preventDefault();
            var self = this;

            axios.get("{{route('user.like')}}", {
                params: {
                    operation: operation,
                    table_name: table_name,
                    likeable_id: likeable_id,
                }
            })
            .then(response => {
            })
            .catch(e => {
                console.log(e)
            });
        }
    },
});
</script>
@endsection
