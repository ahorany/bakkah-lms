<?php
use App\Models\Training\CourseRegistration;
?>
@include('training.reports.courses.dashboard')

<div class="card courses">
  <div class="card-header">
      <?php $create_role = false; ?>
      @can('course.create')
          <?php $create_role = true; ?>
      @endcan
    {!!Builder::BtnGroupTable($create_role)!!}
    {!!Builder::TableAllPosts($count, $courses->count())!!}
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.name')}}</th>
            <th class="">{{__('admin.delivery_methods')}}</th>
            <th class="">{{__('admin.course_code')}}</th>
            <th class="">{{__('admin.category')}}</th>
            <th class="">{{__('admin.course_pdus')}}</th>
            <th class="">{{__('admin.assigned_learners')}}</th>
            <th class="">{{__('admin.completed_learners')}}</th>
            <th class="">{{__('admin.Registered')}}</th>
            <th class="">{{__('admin.image')}}</th>
            {{-- <th class="d-none d-sm-table-cell user-td col-md-2">{{__('admin.user')}}</th> --}}
            <th class="text-right">{{__('admin.action')}}</th>
        </tr>
      </thead>
      <tbody>

      <?php $btn_roles = null; ?>
      @can('course.edit')
          <?php  $btn_roles[] = 'Edit'; ?>
      @endcan

      @can('course.delete')
          <?php   $btn_roles[] = 'Destroy' ?>
      @endcan

      @foreach($courses as $post)
      <tr data-id="{{$post->id}}">
        <td>
          <span class="td-title">{{$loop->iteration}}</span>
        </td>
        <td>
            <span style="display: block;" class="title">{{$post->trans_title ?? null}}</span>
        </td>

       <td>
          <span class="td-title">{{$post->deliveryMethod->trans_name}}</span>
       </td>

        <td>
            <span class="td-title">{{$post->code ?? null}}</span>
        </td>
        <td>
            <span class="td-title">{{$post->category->trans_title ?? null}}</span>
        </td>
        <td>
            <span class="td-title">{{$post->PDUs ?? null}}</span>
        </td>

        <td>
            <?php
                $trainee_count =   DB::table('courses_registration')
                    ->where('course_id',$post->id)
                    ->groupBy('role_id')
                    ->select(DB::raw('COUNT(*) as counts'),'role_id')->get();
                foreach($trainee_count as $c)
                    if($c->role_id == 2)
                       echo '<span class="badge-pink mb-1 mr-1 d-block" style="width: max-content;">Instructors: '.$c->counts.'</span>';
                    elseif($c->role_id == 3)
                        echo '<span class="badge-blue mr-1">Trainees '.$c->counts.'</span>';
            ?>
        </td>
        <td>
            <?php
            $completed_learners = DB::table('courses_registration')
                                    ->where('course_id',$post->id)
                                    ->where('role_id',3)
                                    ->where('progress',100)->count();
            ?>

             <span class="td-title">{{$completed_learners}}</span>
        </td>
        <td>
            <?php
            $registered = DB::table('courses_registration')
                                    ->where('course_id',$post->id)
                                    ->where('user_id',auth()->user()->id)->count();
            ?>
             <span class="td-title">
                 @if($registered>0)
                    <span class="badge-green d-block">{{__('admin.True')}}</span>
                @else
                    <span class="badge-red d-block">{{__('admin.False')}}</span>
                 @endif
             </span>
        </td>
        <td class="d-sm-table-cell">{!!Builder::UploadRow($post)!!}</td>


          <td class="d-sm-table-cell text-right">
{{--              @if(!checkUserIsTrainee())--}}
                {!!Builder::BtnGroupRows($post->trans_title, $post->id, $btn_roles, [
                    'post'=>$post->id,
                ])!!}
{{--              @endif--}}

              {{-- <a class="cyan" href="{{CustomRoute('user.course_details', $post->id) }}?preview=true">Preview</a> --}}
              @if( (!request()->has('trash') && request()->trash != "trash") || request()->trash == "" )

              <a href="{{route('training.coursesReportOverview',['id'=>$post->id])}}" target="blank" class="cyan mt-1" >
                {{-- <i class="fa fa-pencil"></i> --}}
                Report</a>
                <div class="my-1">
                    @include('training.courses.contents.header',['course_id' => $post->id, 'green' =>true , 'courses_home' =>true ])
                    {{-- <a href="{{route('training.contents',['course_id'=>$post->id])}}" class="green">Contents</a>
                    <a href="{{route('training.units',['course_id'=>$post->id])}}" class="green">Units</a>
                    <a href="{{route('training.course_users',['course_id'=>$post->id])}}" class="green">Users</a>
                    <a href="{{route('training.role_path',['course_id'=>$post->id])}}" class="green">to Role and Path</a> --}}
                </div>
              @endif
          </td>
      </tr>

      @endforeach
      </tbody>
    </table>
  </div>
</div>
<!-- /.card-body -->
{{-- {{ $courses->render() }} --}}
{{ $courses->appends(['post_type' => $post_type??null, 'trash' => request()->trash??null, 'course_search' => request()->course_search??null, 'category_id' => request()->category_id??-1, 'show_in_website' => request()->show_in_website??null])->render() }}
