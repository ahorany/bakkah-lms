@extends('layouts.crm.index')

<style>
.href{
    color:black;
}
</style>

@section('useHead')
<title>{{__('education.User Tests')}} | {{ __('home.DC_title') }}</title>
@endsection


@section('table')
 <h3 style="text-align:right;float:right;">{{\App\Helpers\Lang::TransTitle($course[0]->title)}}</h3>
<h3 style="text-align:left;float:left;">{{$content[0]->title}}</h3>
<hr style="clear:both;"/>

<a href="{{route('training.scormUsers',['content_id'=>$content[0]->id,'export'=>1])}}" class="export btn-sm">{{__('admin.export')}}</a>
{{-- <h3 style="text-align:left;float:left;">Pass Mark {{$pass_mark_percentage}}%</h3> --}}
<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>

            <th class="">#</th>
            <th class="text-left">{{__('admin.user')}}</th>
            <th class="text-left">{{__('admin.email')}}</th>
            <th class="text-left">{{__('admin.date')}}</th>
            <th class="">{{__('admin.score')}}</th>
            <th class="text-left">{{__('admin.progress')}}</th>
        </tr>
      </thead>
      <tbody>
      @foreach($users as $user)
      <tr data-id="{{$user->user_id}}">
        <td>
            <span class="td-title px-1">{{$loop->iteration}}</span>
        </td>
        <td class="px-1 text-left" >

            <span class="td-title">{{\App\Helpers\Lang::TransTitle($user->user_name)}}</span>

        </td>
        <td class="px-1 text-left" >

            <span class="td-title">{{\App\Helpers\Lang::TransTitle($user->email)}}</span>

        </td>
        <td class="px-1 text-left">



            <span class="td-title">{{$user->date}}</span>

        </td>

        <td class="px-1">
            <span class="td-title">{{$user->score}}</span>

        </td>
        <td class="px-1">
            <?php

            $lesson_status = ucfirst($user->lesson_status);
            if($user->lesson_status == 'completed')
                $badge = 'info';
            elseif($user->lesson_status == 'incomplete')
                $badge = 'danger';
            elseif($user->lesson_status == 'not attempted')
                $badge = 'warning';
            elseif($user->lesson_status == 'passed')
                $badge = 'success';
            ?>

            <span class="d-block badge badge-{{$badge}} mb-1 ">
                {{$lesson_status}}
            </span>
        </td>


      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  {{$paginator->render()}}
@endsection
