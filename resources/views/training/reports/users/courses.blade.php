<style>
    .href{
        color: black;
    }
</style>
<?php
    use App\Models\Training\CourseRegistration;
?>

@section('useHead')
<title>{{__('education.User Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

<a href="{{route('training.usersReportCourse',['id'=>$user[0]->id,'export'=>1])}}" class="export btn-sm">{{__('admin.export')}}</a>


<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
            <th class="">#</th>
            <th class="">{{__('admin.course')}}</th>
            <th class="">{{__('admin.delivery_methods')}}</th>
            <th class="">{{__('admin.category')}}</th>
            <th class="">{{__('admin.progress')}}</th>
            {{-- <th class="">{{__('admin.score')}}</th> --}}
            <th class="text-center">{{__('admin.enrolled_on')}}</th>
            {{-- <th class="">{{__('admin.completion_date')}}</th> --}}
            <th class="text-center">{{__('admin.pdu')}}</th>
            <th class=""></th>
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

        <td>

            <span class="td-title">
                @php
                    $type = [
                        '11' => 'self-paced',
                        '13' => 'live-online',
                        '353' => 'exam-simulators',
                        '383' => 'instructor-led',
                    ];
                @endphp
              <span class="badge {{ $type[$post->deleviry_method_id] }}">{{\App\Helpers\Lang::TransTitle($post->deleviry_method_name)}}</span>
            </span>
       </td>

        <td>
            <span class="td-title">{{ \App\Helpers\Lang::TransTitle($post->categ_title) }} </span>
        </td>

        <td class="px-1">
            <?php
                // $reg = CourseRegistration::where('user_id',$user->id)->where('course_id',$post->id)->first();
                // dd($post->id);
            ?>
            <div class="progress progress-new">
                <div class="progress-bar bg-main" role="progressbar"@if ($post->progress !=null) style="width: {{$post->progress}}%;" @else style="width: 0%;" @endif  aria-valuenow="{{$post->progress}}" aria-valuemin="0" aria-valuemax="100"> </div>
                <span>@if ($post->progress !=null) {{$post->progress}}% @else 0% @endif</span>
            </div>
        </td>
        {{-- <td class="px-1">
            <span class="badge badge-info">{{$post->score}}</span>
        </td> --}}
        <td class="px-1 text-center">
            <span class="td-title">{{$post->created_at}}</span>
        </td>
        {{-- <td class="px-1">
            <span class="td-title"></span>
        </td> --}}
        <td class="px-1 text-center">
            <span class="td-title">{{$post->PDUs}}</span>
        </td>
        <td>
            {{-- <a class="primary-outline" target="_blank" href="{{route('training.exam_preview',['exam_id'=>$post->id])}} "><i class="fa fa-plus" aria-hidden="true"></i> Preview </a> --}}
            <a  target="_blank" href="{{route('training.progressDetails',['user_id'=>$user[0]->id,'course_id'=>$post->id])}}" class="btn-sm outline" ><span class="href primary-outline">{{__('admin.details')}}</span></a>
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
