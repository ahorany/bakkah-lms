<style>
    .href{
        color: black;
    }
</style>
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
            <th class="">#</th>
            <th class="text-left">{{__('admin.user')}}</th>
            <th class="text-left">{{__('admin.email')}}</th>
            <th class="text-left">{{__('admin.progress')}}</th>
            {{--  <th class="">{{__('admin.score')}}</th>--}}
            <th class="">{{__('admin.enrolled_date')}}</th>
            <th class="">{{__('admin.Last_login_date')}}</th>
           {{-- <th class="">{{__('admin.completion_date')}}</th>
            <th class="">{{__('admin.pdu')}}</th> --}}
            @if($course[0]->training_option_id == 13)
                <th class="text-left">{{__('admin.session')}}</th>
            @endif
            <th class="">{{__('admin.user_type')}}</th>
            <th class=""></th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $post)
            <tr data-id="{{$post->id}}">

                <td>
                    <span class="td-title px-1">{{$loop->iteration}}</span>
                </td>

                <td class="px-1 text-left">
                    <a href="{{route('training.usersReportOverview',['id'=>$post->id,'course_id'=>$course_id])}}" target="_blank" class="btn-sm outline"><span style="display: block;text-decoration: underline;" class="href">{{ \App\Helpers\Lang::TransTitle($post->name) }} </span></a>
                </td>
                <td class="px-1 text-left">
                    <span style="display: block;">{{$post->email }} </span>
                </td>
                <td class="px-1 text-left">
                    @if($post->role_type_id == 512)

                    <div class="progress">
                        <div class="progress-bar" role="progressbar" @if ($post->progress != null) style="width: {{$post->progress}}%;" @else style="width: 0%;" @endif aria-valuenow="{{$post->progress}}" aria-valuemin="0" aria-valuemax="100">@if ($post->progress != null) {{$post->progress}}% @else 0% @endif</div>
                    </div>

                    @endif
                </td>
                <td class="px-1 text-left">
                    <span style="display: block;">{{$post->enrolled_date }} </span>
                </td>
                <td class="px-1 text-left">
                    <span style="display: block;">{{$post->last_login }} </span>
                </td>
                @if($course[0]->training_option_id == 13)
                    <td class="text-left">
                        <span class="badge-green" > {{$post->date_from?$post->date_from.' - ':''}}   {{$post->date_to}}</span>
                    </td>
                @endif
                <td>

                    @if($post->role_type_id == 511)
                        <span class="badge-pink" >
                    @elseif($post->role_type_id == 512)
                        <span class="badge-blue">
                    @endif
                    {{\App\Helpers\Lang::TransTitle($post->c_name)}}</span>
                </td>

                <td>
                    <a href="{{route('training.progressDetails',['user_id'=>$post->id,'course_id'=>$course_id,'preview'=>'true'])}}" class="primary-outline" target="_blank"><span class="href">{{__('admin.details')}}</span></a>
                    @if(isset($post->progress) && ($post->progress >= $post->complete_progress ))
                        <a href="{{route('training.certificates.certificate_dynamic', ['course_registration_id'=> $post->c_reg_id ] )}}"
                            target="_blank" class="primary-outline">
                            Certificate
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  {{$paginator->render()}}
