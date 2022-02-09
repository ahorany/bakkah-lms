@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.Rule & Path')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('table')
    <div  class="course_info mb-3 card p-3">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12 text-right">
                @include('training.courses.contents.header',['course_id' => $course->id, 'role_path' =>true])
            </div>
        </div>
    </div>
    <div>
        <form action="{{route('training.send_role_path',['course_id'=>$course->id])}}" method="GET">
            <div class="row">
                <div class="col-md-9">
                    <div class="card p-5">
                        <ul list-style="none">
                            @foreach ($course->contents as $content)
                                @if ($content->parent_id == null)
                                    <li class=" mb-4">
                                        <div class="form-group m-0">
                                            {{-- <input type="checkbox" @if ($content->role_and_path == 1) checked="checked" @endif name="contents[]" value="{{$content->id}}" id="content_{{$content->id}}"> --}}
                                            <label for="content_{{$content->id}}">{{$content->title}}</label>
                                        </div>
                                        @if (count($content->contents) > 0)
                                            <ul list-style="none" class="pl-4">
                                                @foreach ($content->contents as $sub_content)
                                                    <li>
                                                        <div class="form-group m-0 mb-1">
                                                            <label class="container-check form-check-label" for="content_{{$sub_content->id}}" style="padding: 10px 30px 0; font-size: 15px;">
                                                                {{$sub_content->title}}
                                                                <input class="form-check-input child" style="display: inline-block;" type="checkbox" @if ($sub_content->role_and_path == 1) checked="checked" @endif name="contents[]" value="{{$sub_content->id}}" id="content_{{$sub_content->id}}">
                                                                <span class="checkmark" style="top: 12px;"></span>
                                                            </label>
                                                            {{-- <input type="checkbox" @if ($sub_content->role_and_path == 1) checked="checked" @endif name="contents[]" value="{{$sub_content->id}}" id="content_{{$sub_content->id}}">
                                                            <label for="content_{{$sub_content->id}}">{{$sub_content->title}}</label> --}}
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif

                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-4 mb-2">
                        <input type="hidden" name="course_id" value="{{$course_id}}">
                        <button type="submit" class="main-color" style="width: max-content;"><i class="fa fa-save"></i> Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
