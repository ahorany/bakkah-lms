<style>
    .href{
        color:black;
    }
    </style>
@section('useHead')
    <title>{{__('education.Course Users')}} | {{ __('home.DC_title') }}</title>
@endsection

{{-- <h4>SCORMS IN :  {{\App\Helpers\Lang::TransTitle($course[0]->title) }} </h4> --}}

<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center scorm">
      <thead>
        <tr>
            <th class="">#</th>
            {{-- <th class="">{{__('admin.course_name')}}</th> --}}
            <th class="text-left">Section</th>
            <th class="text-left">{{__('admin.scorm')}}</th>
            <th class="">{{__('admin.no_attempts')}}</th>
            <th class="">Completed Count{{--{{__('admin.no_passed')}}--}}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($scorms as $post)
            <tr data-id="{{$post->id}}">
                <td>
                <span class="td-title px-1">{{$loop->iteration}}</span>
                <td>
                    {{ \App\Helpers\Lang::TransTitle($post->sestion) }}
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
