@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.Settings')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('table')
    <div  class="course_info mb-3 card p-3">
        <div class="row">
            <div class="col-md-9 col-9 text-left">
                <span>Bakkah Settings</span>
            </div>

            <div class="col-md-3 col-3 text-right">
                <div class="back">
                    <a href="{{route('user.home')}}" class="cyan mb-1"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                </div>
            </div>
        </div>
    </div>

    <div>
        <form action="{{route('training.settings.update')}}" method="POST">
           @csrf
            <div class="row">
                <div class="col-md-9">
                    <div class="card p-5">
                        <ul list-style="none">
                            @foreach($criteria as $c)
                                <li>
                                    <div class="form-group">
                                         <input type="checkbox" @foreach($branche->criteria as $bc) @if($bc->id == $c->id) checked="checked" @endif @endforeach  name="criteria[]" value="{{$c->id}}" id="content_{{$c->id}}">
                                         <label for="content_{{$c->id}}">{{$c->title}}</label>
                                         <input type="number" @foreach($branche->criteria as $bc) @if($bc->id == $c->id) value="{{$bc->points}}"  @endif @endforeach  name="points[{{$c->id}}][]" value="{{$c->id}}" id="content_{{$c->id}}">

                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-4 mb-2">
                        <button type="submit" class="main-color" style="width: max-content;"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
