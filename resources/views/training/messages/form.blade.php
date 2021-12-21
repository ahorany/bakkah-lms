@extends('layouts.app')

@section('useHead')
    <title>{{__('education.Messages')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')

    <div class="container">
        <div class="card px-5 py-3 mb-2" style="direction: rtl;">
            <a href="{{route('user.messages.inbox')}}" class="main-color back_button" style="width: 85px; text-align:center;">
                Back
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="back" style="vertical-align: middle;" width="35%" x="0px" y="0px" viewBox="0 0 60 60" xml:space="preserve">
                    <path d="M8.66,30.08c0.27-1.02,1-1.72,1.72-2.43c4.76-4.75,9.51-9.51,14.27-14.26c1.15-1.15,2.78-1.32,4.01-0.44  c1.42,1.03,1.67,3.1,0.54,4.45c-0.11,0.13-0.22,0.25-0.34,0.37c-2.77,2.77-5.53,5.56-8.34,8.31c-0.61,0.6-1.37,1.04-2.06,1.54  c0.1,0,0.26,0,0.42,0c9.65,0,19.3,0,28.95,0c1.02,0,1.94,0.24,2.65,1.04c1.53,1.75,0.67,4.45-1.61,4.98  c-0.37,0.09-0.77,0.1-1.15,0.1c-9.64,0.01-19.27,0-28.91,0c-0.16,0-0.33,0-0.53,0c0.05,0.06,0.07,0.1,0.1,0.11  c1.08,0.43,1.93,1.17,2.73,1.99c2.55,2.57,5.1,5.13,7.66,7.69c0.7,0.7,1.14,1.49,1.12,2.5c-0.03,1.21-0.56,2.1-1.66,2.61  c-1.08,0.5-2.13,0.38-3.1-0.31c-0.24-0.17-0.44-0.38-0.65-0.58c-4.63-4.63-9.25-9.25-13.88-13.88c-0.78-0.78-1.62-1.51-1.94-2.62  C8.66,30.85,8.66,30.47,8.66,30.08z"></path>
                </svg>
            </a>
            {{-- <a href="{{route('user.messages')}}" class="cyan" style="width: max-content;">Back</a> --}}
        </div>
        <div class="card p-5">
            <form action="{{route('user.send_message')}}" method="GET">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="course">Choose Course:</label>
                            <select name="course_id" id="course" class="form-control">
                                <option value="-1" disabled selected>Course...</option>
                                @foreach ($courses as $course)
                                    <option value="{{$course->course->id??$course->id}}">{{$course->course->trans_title??$course->trans_title}}</option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <span style="color: red;">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="recipients">Recipients:</label>
                            <select name="recipient_id" id="recipients" class="form-control">
                                <option value="-1" disabled selected>Recipients...</option>
                                @foreach ($roles as $role)
                                    {{-- @foreach ($course->course->users as $user) --}}
                                        {{-- <option value="{{$user->id}}">{{$user->trans_name}}</option> --}}
                                        <option value="{{$role->id}}">{{$role->trans_name}}</option>
                                    {{-- @endforeach --}}
                                @endforeach
                            </select>
                            @error('recipient_id')
                                <span style="color: red;">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="subject">Subject:</label>
                            <input type="text" placeholder="Subject" name="subject" id="subject" class="form-control">
                            @error('subject')
                                <span style="color: red;">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="description">Description:</label>
                            <textarea type="text" placeholder="Description" name="description" id="description" class="form-control" style="height: 150px;"></textarea>
                            @error('description')
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
        </form>
    </div>
@endsection
