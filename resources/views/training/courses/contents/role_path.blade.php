@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.Course Units')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('table')
    <div  class="course_info mb-3 card p-3">
        <div class="row">
            <div class="col-md-9 col-9">
                <span style="font-size: 0.8rem;" class="mr-1 p-1 badge badge-dark">Course Name : {{$course->trans_title}}</span>

                <button type="button" @click="OpenModal()" style="padding: 2px 8px !important;" class="group_buttons mb-1 btn-sm">
                    <i class="fa fa-plus"></i> {{__('admin.add_unit')}}
                </button>
                <a href="{{route('training.contents',['course_id'=>$course->id])}}"  class="group_buttons mb-1 btn-sm mr-1">
                    {{__('admin.contents')}}
                </a>
                <a href="{{route('training.units',['course_id'=>$course->id])}}" class="group_buttons mb-1 btn-sm">Units</a>
                <a href="{{route('training.course_users',['course_id'=>$course->id])}}" class="group_buttons mb-1 btn-sm">Users</a>
            </div>

            <div class="col-md-3 col-3 text-right">
                <div class="back">
                    <a href="{{route('training.courses.edit',[$course->id])}}" class="cyan mb-1"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                </div>
            </div>
        </div>
    </div>

<div class="row">
    <div class="col-md-9"></div>
    <div class="col-md-3">
        <form action="{{route('training.send_role_path',['course_id'=>$course->id])}}" method="GET">
            <div class="card p-4 mb-2">
                <input type="hidden" name="course_id" value="{{$course_id}}">
                <button type="submit" class="main-color" style="width: max-content;"><i class="fa fa-save"></i> Update</button>
            </div>
            <div class="card p-4">
                <ul list-style="none">
                    @foreach ($course->contents as $content)
                        @if ($content->parent_id == null)
                            <li>
                                <div class="form-group">
                                    <input type="checkbox" @if ($content->role_and_path == 1) checked="checked" @endif name="contents[]" value="{{$content->id}}" id="content_{{$content->id}}">
                                    <label for="content_{{$content->id}}">{{$content->title}}</label>
                                </div>
                                @if (count($content->contents) > 0)
                                    <ul list-style="none" class="pl-4">
                                        @foreach ($content->contents as $sub_content)
                                            <li>
                                                <div class="form-group">
                                                    <input type="checkbox" @if ($sub_content->role_and_path == 1) checked="checked" @endif name="contents[]" value="{{$sub_content->id}}" id="content_{{$sub_content->id}}">
                                                    <label for="content_{{$sub_content->id}}">{{$sub_content->title}}</label>
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
        </form>
    </div>
</div>
@endsection
