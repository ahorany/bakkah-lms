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
@include('training.reports.dashboard')
<div class="card courses">
  <div class="card-header">
    {!!Builder::BtnGroupTable()!!}
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
            {{-- <th class="">{{__('admin.job_title')}}</th> --}}
            <th class="">{{__('admin.role')}}</th>
            <th class="">{{__('admin.company')}}</th>
            <th class="">{{__('admin.last_login')}}</th>
            {{-- <th class="">{{__('admin.gender_id')}}</th> --}}
            {{-- <th class="img-table d-none d-sm-table-cell">{{__('admin.image')}}</th> --}}
            <th class="">{{__('admin.assigned_courses')}}</th>
            <th class="" style="width: 12%;">{{__('admin.action')}}</th>
        </tr>
      </thead>
      <tbody>
      @foreach($users as $post)
      <tr data-id="{{$post->id}}">
        <td>
          <span class="td-title px-1">{{$loop->iteration}}</span>
        </td>
        <td class="px-1">
            <span style="display: block;">{{$post->trans_name}}</span>

        </td>
        <td class="px-1">
            <span class="td-title">{{$post->email??null}}</span>
        </td>
        <td class="px-1">
            <span class="td-title">{{$post->mobile??null}}</span>
        </td>
        {{-- <td class="px-1"> <span class="td-title">{{$post->job_title??null}}</span> </td> --}}
        <td class="px-1">
            @if(isset($post->roles[0]))
                <span class="td-title {{($post->roles[0]->id == 1) ? 'badge-blue' : (($post->roles[0]->id == 2) ? 'badge-pink' : (($post->roles[0]->id == 3) ? 'badge-green' : ''))}} ">{{$post->roles[0]->trans_name??null}}</span>
            @endif
        </td>
        <td class="px-1">
            <span class="td-title">{{$post->company??null}}</span>
        </td>
        <td class="px-1">
            <span class="td-title">{{$post->last_login??'Not logged in'}}</span>
        </td>
        {{-- <td class="px-1"> <span class="td-title">{{$post->gender->en_name??null}}</span> </td> --}}
        {{-- <td class="d-none d-sm-table-cell px-1">{!!Builder::UploadRow($post)!!}</td> --}}
        <td class="px-1">
            <?php
                $assigned_courses = CourseRegistration::where('user_id',$post->id)->count();
            ?>
            <span style="display: block;" class="td-title">  {{ $assigned_courses }}</span>
        </td>
        <td class="px-1">
            {!!Builder::BtnGroupRows($post->trans_name, $post->id, [], [
                'post'=>$post->id,
            ])!!}
            <a href="{{route('training.usersReport',['id'=>$post->id])}}" target="blank" class="my-2 green" ><i class="fa fa-pencil"></i> Report</a>
         </td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
<!-- /.card-body -->
{{-- {{ $users->render() }} --}}
{{ $users->appends([
    'user_search' => request()->user_search??null,
    'post_type' => $post_type
    ])->render() }}
