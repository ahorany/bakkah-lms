<?php
use App\Models\Training\CourseRegistration;
// dd($users);
?>

@include('training.'.$folder.'.dashboard')
<div class="card users">
  {{-- <div class="card-header"></div> --}}

    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed text-center">
        <thead>
            <tr>
                <th class="col-md-1 col-1">{{__('admin.index')}}</th>
                <th class="col-md-7 col-7">{{__('admin.name')}}</th>
                <th class="col-md-2 col-2">{{__('admin.user_type')}}</th>
                <th class="col-md-2 col-2">{{__('admin.assigned_courses')}}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $post)
        <tr data-id="{{$post->id}}">
            <td>
            <span class="td-title">{{$loop->iteration}}</span>
            </td>
            <td>
                <span style="display: block;" class="title">{{$post->trans_name}} </span>
            </td>
            @php
                $role_name = \App\Helpers\Lang::TransTitle($post->role_name);
            @endphp
            {{-- @dd($role_name) --}}
            <td>
                <span class="title {{($role_name == 'Admin') ? 'badge-blue' : (($role_name == 'Instructor') ? 'badge-pink' : (($role_name == 'Trainee') ? 'badge-green' : ''))}}"> {{$role_name}}</span>
            </td>
            <td>
                <?php
                    $assigned_courses = CourseRegistration::where('user_id',$post->id)->count();
                ?>
                <span style="display: block;" class="title">  {{ $assigned_courses }}</span>
            </td>

        </tr>

        @endforeach
        </tbody>
        </table>
    </div>
</div>
<?php
    $array = Builder::get_appends(request()->all());
?>
{{ $users->appends($array)->links() }}
