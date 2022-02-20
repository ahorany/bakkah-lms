@section('useHead')
    <title>{{__('education.Course Users')}} | {{ __('home.DC_title') }}</title>
@endsection
@if($type == 'all')
    <h4>All SCORMS</h4>
@else
    <h4>SCORMS in <h3> {{$course->trans_title }}</h3> </h4>
@endif
<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center scorm">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.name')}}</th>
            <th class="">{{__('admin.course_name')}}</th>
            <th class="">{{__('admin.attempts')}}</th>
            <th class="">{{__('admin.passed')}}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($scorms as $post)
            <tr data-id="{{$post->id}}">
                <td>
                <span class="td-title px-1">{{$loop->iteration}}</span>
                </td>
                <td class="px-1">
                     <a style="display: block;" href="{{route('training.scorm_users',['id'=>$post->id])}}" class="info-button btn-sm">{{$post->title }}</a>
                </td>
                <td class="px-1">
                    <span style="display: block;"> <a style="display: block;" href="{{route('training.scormsReportScorms',['course_id'=>$post->course->id])}}" class="info-button btn-sm">{{$post->course->trans_title}}</a> </span>

                </td>
                <?php
                        $attempts = DB::table('scormvars_master')->where('content_id',$post->id)->count();
                        $passed =   DB::table('scormvars_master')->where('content_id',$post->id)->where('lesson_status','completed')->count();
                        // dump($post->id.'--'.$passed);
                ?>
                <td class="px-1">
                    <span style="display: block;"> {{$attempts}}</span>
                </td>
                <td class="px-1">
                    <span style="display: block;"> {{$passed}} </span>
                </td>
            </tr>
        @endforeach
      </tbody>
    </table>
  </div>


  <script>
    $(function(){
            $(".scorms").addClass("active");
    });
</script>
