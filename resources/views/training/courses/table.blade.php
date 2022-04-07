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
            <th class="">{{__('admin.assigned_to_course')}}</th>
            <th class="">{{__('admin.completed_learners')}}</th>
            <th class="">{{__('admin.Registered')}}</th>
            <th class="">{{__('admin.image')}}</th>
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
            <span class="td-title">
                @php
                    $type = [
                        '11' => 'self-paced',
                        '13' => 'live-online',
                        '353' => 'exam-simulators',
                        '383' => 'instructor-led',
                    ];
                @endphp
              <span class="badge {{ $type[$post->deliveryMethod->id] }}">{{$post->deliveryMethod->trans_name}}</span>
            </span>
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
                $assigned_learners1 = CourseRegistration::getAssigned(512);
                $assigned_learners =  $assigned_learners1->where('course_id',$post->id)->count();

                $assigned_instructors = CourseRegistration::getAssigned(511);
                $assigned_instructors =  $assigned_instructors->where('course_id',$post->id)->count();

                $completed_learners =  $assigned_learners1->where('progress',100)->where('course_id',$post->id)->count();

                echo '<span class="badge-pink mb-1 mr-1 d-block" style="width: max-content;">Instructors: '.$assigned_instructors.'</span>';
                echo '<span class="badge-blue mr-1">Trainees '.$assigned_learners.'</span>';
            ?>
        </td>
        <td>
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
                {!!Builder::BtnGroupRows($post->trans_title, $post->id, $btn_roles, [
                    'post'=>$post->id,
                ])!!}

              @if( (!request()->has('trash') && request()->trash != "trash") || request()->trash == "" )

              <a href="{{route('training.coursesReportOverview',['id'=>$post->id])}}" target="blank" class="cyan mt-1" >
                Report</a>
                <div class="my-1">
                    @include('training.courses.contents.header',['course_id' => $post->id, 'green' =>true , 'courses_home' =>true ])
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

{{ $courses->appends(['post_type' => $post_type??null, 'trash' => request()->trash??null, 'course_search' => request()->course_search??null, 'category_id' => request()->category_id??-1, 'show_in_website' => request()->show_in_website??null])->render() }}
