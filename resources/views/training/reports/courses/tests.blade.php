<?php
use App\Models\Training\CourseRegistration;
use Illuminate\Support\Facades\DB;
?>

@section('useHead')
    <title>{{__('education.Course Tests')}} | {{ __('home.DC_title') }}</title>
@endsection

<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
        <thead>
        <tr>
            <th class="">#</th>
            <th class="text-left">{{__('admin.test')}}</th>
            <th class="">{{__('admin.completedNo')}}</th>
            <th class="">{{__('admin.passedNo')}}</th>
        </tr>
        </thead>
        <tbody>
            @foreach($tests as $post)
                <tr data-id="{{$post->id}}">
                    <td>
                        <span class="td-title px-1">{{$loop->iteration}}</span>
                    </td>
                    <td class="px-1 text-left">
                        <span style="display: block;">{{ $post->content_title }} </span>
                    </td>
                    <td>
                        <?php
                            $completed = DB::table('user_exams')
                                            ->where('exam_id',$post ->id)
                                            ->where('status',1)
                                            ->count(DB::raw('DISTINCT status,user_id'));
                        ?>
                        <span style="display: block;">{{ $completed }} </span>
                    </td>
                    <td>
                        <?php
                            $sql = " select count(x.uu) count
                                    from
                                    ( select max(user_exams.mark) ,user_exams.user_id uu
                                        from user_exams join exams on exams.id = user_exams.exam_id
                                        where user_exams.mark >= (exams.pass_mark/100*exams.exam_mark)
                                        and user_exams.exam_id = ".$post ->id."
                                        group by user_exams.user_id ) as x";
                            $passess = DB::select($sql);
                            // dump($passess);
                        ?>
                        @foreach($passess as $pass)
                            <span style="display: block;">{{ $pass->count??0 }} </span>
                        @endforeach
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{$paginator->render()}}
