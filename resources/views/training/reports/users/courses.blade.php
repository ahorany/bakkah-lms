<?php
use App\Models\Training\CourseRegistration;
?>

@section('useHead')
<title>{{__('education.User Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

<a href="{{route('training.usersReportCourse',['id'=>$user->id,'export'=>1])}}" class="export btn-sm">{{__('admin.export')}}</a>


<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.name')}}</th>
            <th class="">{{__('admin.progress')}}</th>
            <th class="">{{__('admin.score')}}</th>
            <th class="">{{__('admin.enrolled_on')}}</th>
            {{-- <th class="">{{__('admin.completion_date')}}</th> --}}
            <th class="">{{__('admin.pdu')}}</th>
            <th class="">{{__('admin.certificate')}}</th>
        </tr>
      </thead>
      <tbody>
      @foreach($courses as $post)
      <tr data-id="{{$post->id}}">
        <td>
          <span class="td-title px-1">{{$loop->iteration}}</span>
        </td>
        <td class="px-1">
            <span style="display: block;">{{ \App\Helpers\Lang::TransTitle($post->title) }} </span>

        </td>
        <td class="px-1">
            <?php
                // $reg = CourseRegistration::where('user_id',$user->id)->where('course_id',$post->id)->first();
                // dd($post->id);
            ?>
            <div class="progress">
                <div class="progress-bar bg-main" role="progressbar"@if ($post->progress !=null) style="width: {{$post->progress}}%;" @else style="width: 0%;" @endif  aria-valuenow="{{$post->progress}}" aria-valuemin="0" aria-valuemax="100">@if ($post->progress !=null) {{$post->progress}}% @else 0% @endif </div>
              </div>
              <div style="float:right;"><a href="{{route('training.progressDetails',['user_id'=>$user->id,'course_id'=>$post->id])}}" class="btn-sm outline">{{__('admin.details')}}</a></div>
        </td>
        <td class="px-1">
            <span class="badge badge-info">{{$post->score}}</span>
        </td>
        <td class="px-1">
            <span class="td-title">{{$post->created_at}}</span>
        </td>
        {{-- <td class="px-1">
            <span class="td-title"></span>
        </td> --}}
        <td class="px-1">
            <span class="td-title">{{$post->PDUs}}</span>
        </td>
        <td>
            @if(isset($post->progress) && ($post->progress >= $post->complete_progress ))
                    <a href="{{route('training.certificates.certificate_dynamic', ['course_registration_id'=> $post->c_reg_id ] )}}"
                        target="_blank">
                        Certificate
                    </a>
            @endif
        </td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
