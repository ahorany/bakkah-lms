@extends('layouts.app')

@section('useHead')
    <title>{{__('education.Messages')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('style')
    <style>
        .replay{
            background: #f7f7f7;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card px-5 py-3 mb-2" style="direction: rtl;">
            <a href="{{route('user.messages')}}" class="cyan" style="width: max-content;">Back</a>
        </div>
        <div class="card p-5">
            <form action="{{route('user.add_replay')}}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <h2>{{$user->trans_name}}</h2>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <h3>{{$message->title??null}}</h3>
                                    <p>{{$message->description??null}}</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                @if ($message->replay == null)
                                    <div class="form-group mb-3">
                                        <input type="hidden" name="message_id" value="{{$message->id}}">
                                        <label for="replay">Replay:</label>
                                        <textarea type="text" placeholder="Replay" name="replay" id="replay" class="form-control" style="height: 150px;"></textarea>
                                        @error('replay')
                                            <span style="color: red;">{{$message}}</span>
                                        @enderror
                                    </div>
                                @else
                                    <p class="replay">{{$message->replay}}</p>
                                @endif

                            </div>
                            @if ($message->replay == null)
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <input type="submit" class="main-color" value="Submit">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
