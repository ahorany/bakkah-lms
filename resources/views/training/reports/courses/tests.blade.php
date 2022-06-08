<?php
use App\Models\Training\CourseRegistration;
use Illuminate\Support\Facades\DB;
?>

@section('useHead')
    <title>{{__('education.Course Tests')}} | {{ __('home.DC_title') }}</title>
@endsection


@if(!is_null($user) && $user != '')
    <?php
        $active_all = '';
        $active_s  = '';

        if(isset($user[0]->id) && $show_all == 0)
        {
            $active_s = 'active';
        }
        else
        {
            $active_all = 'active';
        }
    ?>
    <a href="{{route('training.coursesReportTest',['id'=>$course[0]->id,'user_id'=>$user[0]->id])}}" class="group_buttons btn-sm {{$active_all}}" >All Users </a>
    <a href="{{route('training.coursesReportTest',['id'=>$course[0]->id,'user_id'=>$user[0]->id,'show_all'=>0])}}" class="group_buttons btn-sm {{$active_s}}">{{ \App\Helpers\Lang::TransTitle($user[0]->name) }} | {{$user[0]->email}} </a>
@endif
@include('training.reports.search.search_courses_tests')


<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
        <thead>
        <tr>
            <th class="">#</th>
            <th class="text-left">Section</th>
            <th class="text-left">{{__('admin.test')}}</th>
            <th class="">{{__('admin.completedNo')}}</th>
            <th class="">{{__('admin.passedNo')}}</th>
            <th class=""></th>
        </tr>
        </thead>
        <tbody>
            @foreach($tests as $post)
                <tr data-id="{{$post->exam_id}}">
                    <td>
                        <span class="td-title px-1">{{$loop->iteration}}</span>
                    </td>
                    <td class="px-1 text-left">
                        <span style="display: block;">{{ $post->section }} </span>

                    </td>
                    <td class="px-1 text-left">
                        <span style="display: block;">{{ $post->content_title }} </span>

                        {{-- <a v-if="entry.post_type != 'exam'" class="cyan" title="Preview" :href="'{{url('/')}}/{{app()->getLocale()}}/user/preview-content/' + entry.id + '?preview=true'" :target="entry.id">
                            <i class="fa fa-folder-open-o" aria-hidden="true"></i>
                        </a> --}}

                    </td>
                    <td>
                        <span style="display: block;">{{ $post->completed }} </span>
                    </td>
                    <td>
                        <span style="display: block;">{{  $post->passess }} </span>
                    </td>
                    <td>

                        <a class="nav-link cyan" target="_blank" href="{{route('training.exam_preview',['content_id'=>$post->content_id])}} " title="Preview" style=" display: inline-block">
                            @include('training.reports.svg_report.preview')
                        </a>

                        <a class="nav-link cyan" target="_blank" href="{{route('training.testUsers',['exam_id'=>$post->exam_id,'content_id'=>$post->content_id])}} "  title="Users" style=" display: inline-block">
                            @include('training.reports.svg_report.users')
                        </a>

                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{$paginator->render()}}
