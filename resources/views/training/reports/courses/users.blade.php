<?php
use App\Models\Training\CourseRegistration;
?>

@section('useHead')
    <title>{{__('education.Course Users')}} | {{ __('home.DC_title') }}</title>
@endsection

{{-- {!! Builder::Submit('export', 'export_results', 'btn-success', 'file-excel') !!} --}}
<a href="{{route('training.coursesReportUser',['id'=>$course_id,'export'=>1])}}" class="export btn-sm">{{__('admin.export')}}</a>

<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.name')}}</th>
            <th class="">{{__('admin.progress')}}</th>
           {{--  <th class="">{{__('admin.score')}}</th>
            <th class="">{{__('admin.enrolled_on')}}</th>
            <th class="">{{__('admin.completion_date')}}</th>
            <th class="">{{__('admin.pdu')}}</th> --}}

            <th class="">{{__('admin.session')}}</th>

            <th class="">{{__('admin.user_type')}}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $post)
            <tr data-id="{{$post->id}}">

                <td>
                    <span class="td-title px-1">{{$loop->iteration}}</span>
                </td>

                <td class="px-1">
                    <span style="display: block;">{{ \App\Helpers\Lang::TransTitle($post->name) }} </span>
                </td>

                <td class="px-1">
                    @if($post->role_type_id == 512)
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" @if ($post->progress != null) style="width: {{$post->progress}}%;" @else style="width: 0%;" @endif aria-valuenow="{{$post->progress}}" aria-valuemin="0" aria-valuemax="100">@if ($post->progress != null) {{$post->progress}}% @else 0% @endif</div>
                    </div>
                    @endif
                </td>

                <td>
                    {{$post->date_from}} -  {{$post->date_to}}
                </td>



                <td>
                    @if($post->role_type_id == 511)
                        <span class="badge-pink" style="width: max-content;">{{__('admin.instructor')}}</span>
                    @elseif($post->role_type_id == 512)
                        <span class="badge-blue">{{__('admin.learner')}}</span>
                    @endif
                </td>

            </tr>
        @endforeach
      </tbody>
    </table>
  </div>
