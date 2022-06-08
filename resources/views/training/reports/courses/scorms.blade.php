<style>
    .href{
        color:black;
    }
</style>
@section('useHead')
    <title>{{__('education.Course Users')}} | {{ __('home.DC_title') }}</title>
@endsection

{{-- <h4>SCORMS IN :  {{\App\Helpers\Lang::TransTitle($course[0]->title) }} </h4> --}}




@if(!is_null($user) && $user != '')
    <?php
        $active_all = '';
        $active_s  = '';

        if(isset($user[0]->id))
        {
            $active_s = 'active';
        }
        else
        {
            $active_all = 'active';
        }
    ?>
    <a href="{{route('training.coursesReportScorm',['id'=>$course[0]->id,'user_id'=>$user[0]->id])}}" class="group_buttons btn-sm {{$active_all}}" >All Users </a>
    <a href="{{route('training.coursesReportScorm',['id'=>$course[0]->id,'user_id'=>$user[0]->id,'show_all'=>0])}}" class="group_buttons btn-sm {{$active_s}}">{{ \App\Helpers\Lang::TransTitle($user[0]->name) }} | {{$user[0]->email}} </a>
@endif
@include('training.reports.search.search_courses_scorms')

<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center scorm">
      <thead>
        <tr>
            <th class="">#</th>
            {{-- <th class="">{{__('admin.course_name')}}</th> --}}
            <th class="text-left">Section</th>
            <th class="text-left">{{__('admin.scorm')}}</th>
            <th class="">{{__('admin.no_attempts')}}</th>
            <th class="">Completed{{--{{__('admin.no_passed')}}--}}</th>
            <th class=""></th>
        </tr>
      </thead>
      <tbody>
        @foreach($scorms as $post)
            <tr data-id="{{$post->content_id}}">
                <td>
                <span class="td-title px-1">{{$loop->iteration}}</span>
                <td class="px-1 text-left">
                    {{ \App\Helpers\Lang::TransTitle($post->section) }}
                </td>
                </td>
                {{-- <td class="px-1">
                    <span style="display: block;"> <a style="display: block;" href="{{route('training.scormsReportScorms',['course_id'=>$course[0]->id])}}" >{{\App\Helpers\Lang::TransTitle($course[0]->title) }}</a> </span>

                </td> --}}
                <td class="px-1 text-left">
                    {{ \App\Helpers\Lang::TransTitle($post->title) }}
                    {{-- <a href="{{CustomRoute('user.course_preview', $post->content_id)}}" target="_blank" class="href" >
                        {{ \App\Helpers\Lang::TransTitle($post->title) }}</a> --}}
                </td>

                <td class="px-1">
                    <span style="display: block;"> {{$post->attempts??0}}</span>
                </td>
                <td class="px-1">
                    <span style="display: block;"> {{$post->passess??0}} </span>
                </td>

                <td>
                    <a class="nav-link cyan" target="_blank" href="{{route('training.scormUsers',['content_id'=>$post->content_id])}} " title="Users" style=" display: inline-block">
                        @include('training.reports.svg_report.users')
                         </a>
                </td>
            </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  {{$paginator->render()}}

  <script>
    $(function(){
            $(".scorms").addClass("active");
    });
</script>
