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
<a href="{{route('training.testUsers',['exam_id'=>$exam[0]->id,'content_id'=>$content[0]->id,'export'=>1])}}" class="export btn-sm">
    <span class="icon">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
            <path d="M50.07,82.5c-9.62,0-19.24,0-28.86,0c-6.13,0-10.74-4.59-10.74-10.71c0-8.3,0-16.6,0-24.9c0-2.13,1.11-3.59,2.95-3.94
                c2.15-0.4,4.16,1.16,4.25,3.36c0.04,1.01,0.01,2.03,0.01,3.04c0,7.45,0,14.91,0,22.36c0,2.23,1.34,3.56,3.57,3.56
                c19.21,0,38.43,0,57.64,0c2.29,0,3.61-1.31,3.61-3.6c0-8.33,0-16.65,0-24.98c0-2.56,2.07-4.27,4.46-3.71
                c1.64,0.38,2.73,1.82,2.74,3.69c0.02,2.14,0.01,4.28,0.01,6.41c0,6.22,0,12.43,0,18.65c0,5.52-3.66,9.8-9.11,10.65
                c-0.55,0.09-1.12,0.1-1.68,0.1C69.31,82.5,59.69,82.5,50.07,82.5z"/>
            <path d="M46.5,30.02c-1.79,1.77-3.41,3.41-5.06,5.01c-1.69,1.64-4.29,1.4-5.58-0.48c-1.01-1.48-0.82-3.29,0.54-4.69
                c3.44-3.52,6.9-7.03,10.36-10.52c0.41-0.41,0.88-0.78,1.37-1.09c1.41-0.9,3.17-0.8,4.36,0.37c3.85,3.79,7.67,7.6,11.46,11.46
                c1.42,1.45,1.28,3.71-0.17,5.08c-1.41,1.34-3.58,1.31-5.02-0.09c-1.47-1.44-2.9-2.91-4.35-4.36c-0.19-0.19-0.4-0.37-0.74-0.69
                c0,0.48,0,0.77,0,1.07c0,9.73,0,19.47-0.01,29.2c0,0.58-0.02,1.2-0.19,1.75c-0.52,1.7-2.15,2.66-3.95,2.42
                c-1.64-0.22-2.92-1.65-3.01-3.36c-0.02-0.45-0.01-0.9-0.01-1.35c0-9.54,0-19.07,0-28.61C46.5,30.82,46.5,30.52,46.5,30.02z"/>
        </svg>
    </span>
    <span>{{__('admin.export')}}</span>
</a>
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
