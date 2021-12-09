@extends('layouts.app')

@section('useHead')
    <title>{{__('education.Messages')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')

    <div class="container">
        <div class="card px-5 py-3 mb-2" style="direction: rtl;">
            <a href="{{route('user.messages')}}" class="cyan" style="width: max-content;">Back</a>
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
                                    <option value="{{$course->id}}">{{$course->course->trans_title}}</option>
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
