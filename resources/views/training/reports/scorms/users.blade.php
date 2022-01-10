@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.scorm_Reports')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('table')
<h4>{{__('admin.users')}}</h4>
<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>

            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.user')}}</th>
            <th class="">{{__('admin.date')}}</th>
            <th class="">{{__('admin.result')}}</th>
            <th class="">{{__('admin.score')}}</th>

        </tr>
      </thead>
      <tbody>
      @foreach($users as $post)
      <tr data-id="{{$post->id}}">
        <td>
          <span class="td-title px-1">{{$loop->iteration}}</span>
        </td>
        <td class="px-1">
            <span style="display: block;">  {{ \App\Helpers\Lang::TransTitle($post->name) }}    </span>
        </td>
       <td>
             <span style="display: block;">{{$post->date}} </span>
       </td>
       <td>
            @if($post->is_complete == 1)
                <span class="d-block badge badge-success mb-1 ">
                    PASSED
                </span>
             @else
                <span class="d-block badge badge-secondary mb-1 ">
                    IN PROGRESS
                </span>
             @endif
        </td>
        <td>
            <span style="display: block;"> {{$post->score}}</span>
        </td>

      </tr>
      @endforeach
      </tbody>
    </table>
  </div>

@endsection
