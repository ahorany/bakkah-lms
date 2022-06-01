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
            <th class="text-left">{{__('admin.name')}}</th>
            <th class="text-left">{{__('admin.email')}}</th>
            <th class="text-left">{{__('admin.mobile')}}</th>
            <th class="">{{__('admin.role')}}</th>
            <th class="">{{__('admin.company')}}</th>
            <th class="">{{__('admin.last_login')}}</th>
            <th class="">{{__('admin.assigned_courses')}} as learner</th>
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
              1 => 'badge-green',
              2 => 'badge-pink',
              3 => 'badge-blue',
              4 => 'badge-red',
          ];
          ?>
          @foreach($users as $post)
            <tr data-id="{{$post->id}}">
                <td>
                  <span class="td-title px-1">{{$loop->iteration}}</span>
                </td>

                <td  class="px-1 text-left">
                    <span style="display: block;">{{$post->name}}</span>
                </td>

                <td class="px-1 text-left">
                    <span class="td-title">{{$post->user->email??null}}</span>
                </td>

                <td class="px-1 text-left">
                    <span class="td-title">{{$post->user->mobile??null}}</span>
                </td>

                <td class="px-1">
                    @foreach($post->user->roles as $role)
                        <span class="td-title {{$roles_class[$role->id]??'badge-pink'}} ">{{$role->name}}</span>
                    @endforeach
                </td>
                <td class="px-1">
                    <span class="td-title">{{$post->user->company??null}}</span>
                </td>
                <td class="px-1">
                    <span class="td-title">{{$post->user->last_login??'Not logged in'}}</span>
                </td>
                <td class="px-1">
                    <?php
                        $assigned_courses =  CourseRegistration::getCoursesNo(null,512)
                                    ->where('courses_registration.user_id',$post->user_id)->count();
                    ?>
                    <span style="display: block;" class="td-title">  {{ $assigned_courses }}</span>
                </td>
                <td class="px-1">
                    {!!Builder::BtnGroupRows($post->name, $post->user_id, $btn_roles, [
                        'post'=>$post->user_id,
                    ])!!}
                    @if($assigned_courses > 0)
                        @can('user.report')
                            @if(!(\request()->has('trash') && \request()->trash =='trash'))
                                <a href="{{route('training.usersReportOverview',['id'=>$post->user_id])}}" target="blank" class="cyan my-1" >
                                    Report</a>
                            @endif
                        @endcan
                    @endif
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
