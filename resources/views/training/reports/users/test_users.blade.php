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

<?php
    $pass_mark = $exam[0]->pass_mark/100*$exam[0]->exam_mark;
    $pass_mark_percentage = $exam[0]->pass_mark;
?>
<h5 style="text-align:left;float:left;">Pass Mark {{$pass_mark_percentage}}%</h5>
<hr style="clear:both;"/>
<a href="{{route('training.testUsers',['exam_id'=>$exam[0]->id,'content_id'=>$content[0]->id,'export'=>1])}}" class="export btn-sm">{{__('admin.export')}}</a>
<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>

            <th class="">#</th>
            <th class="text-left">{{__('admin.user')}}</th>
            <th class="text-left">{{__('admin.email')}}</th>
            <th class="text-left">{{__('admin.date')}}</th>
            <th class="">{{__('admin.result')}}</th>
            <th class="">{{__('admin.score')}}</th>
            <th class="text-left">{{__('admin.test_time')}}</th>

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

            <span class="td-title">{{$user->time}}</span>

        </td>

        <td class="px-1">
            @if($user->mark >= $pass_mark)
                <span class="badge badge-success">{{__('admin.pass')}}</span>
            @else
                <span class="badge badge-danger">{{__('admin.fail')}}</span>
            @endif
        </td>
        <td class="px-1">
            <span class="td-title">{{number_format($user->score,2)}}%</span>
        </td>
        <td class="px-1 text-left">
            <?php
            $datetime1 = new DateTime($user->end_attempt);//start time
            $datetime2 = new DateTime($user->time);//end time
            $interval = $datetime1->diff($datetime2);
            ?>
            <span class="td-title">{{$interval->format(' %H h: %i m: %s s')}}</span>
        </td>

      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  {{$paginator->render()}}
@endsection
