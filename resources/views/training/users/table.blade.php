<?php
use App\Models\Training\CourseRegistration;
?>
@section('style')
<style>
    .card-header span{
        color: #fff !important;
    }
    .img-thumbnail{
        width: 60%;;
    }
</style>
@endsection

@include('training.reports.users.dashboard')
<div class="card courses">
  <div class="card-header">
      <?php $create_role = false; ?>
      @can('users.create')
          <?php $create_role = true; ?>
      @endcan

    {!!Builder::BtnGroupTable($create_role)!!}
    {!!Builder::TableAllPosts($count, $users->count())!!}
  </div>

  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.name')}}</th>
            <th class="">{{__('admin.email')}}</th>
            <th class="">{{__('admin.mobile')}}</th>
            <th class="">{{__('admin.role')}}</th>
            <th class="">{{__('admin.company')}}</th>
            <th class="">{{__('admin.last_login')}}</th>
            <th class="">{{__('admin.assigned_courses')}}</th>
            <th class="" style="width: 12%;">{{__('admin.action')}}</th>
        </tr>
      </thead>
      <tbody>
      <?php $btn_roles = null; ?>
          @can('users.edit')
              <?php $btn_roles[] = 'Edit'; ?>
          @endcan

          @can('users.delete')
              <?php $btn_roles[] = 'Destroy' ?>
          @endcan

          <?php
          $roles_class = [
              1 => 'badge-blue',
              2 => 'badge-pink',
              3 => 'badge-green',
              4 => 'badge-blue',
          ];
          ?>
          @foreach($users as $post)
            <tr data-id="{{$post->id}}">
                <td>
                  <span class="td-title px-1">{{$loop->iteration}}</span>
                </td>

                <td class="px-1">
                    <span style="display: block;">{{$post->branches[0]->pivot->name}}</span>
                </td>

                <td class="px-1">
                    <span class="td-title">{{$post->email??null}}</span>
                </td>

                <td class="px-1">
                    <span class="td-title">{{$post->mobile??null}}</span>
                </td>

                <td class="px-1">
                    @foreach($post->roles as $role)
                        <span class="td-title {{$roles_class[$role->id]??'badge-pink'}} ">{{$role->name}}</span>
                    @endforeach
                </td>
                <td class="px-1">
                    <span class="td-title">{{$post->company??null}}</span>
                </td>
                <td class="px-1">
                    <span class="td-title">{{$post->last_login??'Not logged in'}}</span>
                </td>
                <td class="px-1">
                    <?php
                        $branch_id = getCurrentUserBranchData()->branch_id;
                        $assigned_courses = CourseRegistration::join('courses', function ($join) use($branch_id) {
                                                $join->on('courses_registration.course_id', '=', 'courses.id')
                                                    ->where('courses.branch_id',$branch_id);
                                            })
                        ->where('user_id',$post->id)->count();
                    ?>
                    <span style="display: block;" class="td-title">  {{ $assigned_courses }}</span>
                </td>
                <td class="px-1">
                    {!!Builder::BtnGroupRows($post->trans_name, $post->id, $btn_roles, [
                        'post'=>$post->id,
                    ])!!}
                    @can('user.report')
                        @if(!(\request()->has('trash') && \request()->trash =='trash'))
                            <a href="{{route('training.usersReportOverview',['id'=>$post->id])}}" target="blank" class="cyan my-1" >
                                 Report</a>
                         @endif
                    @endcan
                 </td>
          </tr>
          @endforeach
      </tbody>
    </table>
  </div>
</div>
<!-- /.card-body -->



{{ $users->appends([
    'user_search' => request()->user_search??null,
    'post_type' => $post_type
    ])->render() }}
