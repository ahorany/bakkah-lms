<?php
use App\Models\Training\CourseRegistration;
// dd($users);
?>
<div class="row mb-5">

    <div class="col-lg-12">
        <div class="card h-100 justify-content-center p-30">

            <div class="d-flex flex-column flex-sm-row flex-wrap">
                <div class="course-cards bg-main">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="62.387" viewBox="0 0 35 62.387">
                        <path id="Icon_open-task" data-name="Icon open-task" d="M0,0V62.387H62.387v-32L53.475,39.3V53.475H8.912V8.912h32L49.821,0ZM62.387,0,35.65,26.737l-8.912-8.912-8.912,8.912L35.65,44.562,35,8.912Z" fill="#fff"></path>
                    </svg>
                    <div>
                        <span>{{__('admin.learners')}}</span>
                        <b>{{$learners_no}}</b>
                    </div>


                </div>

                <div class="course-cards bg-third">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="68.387" viewBox="0 0 35 68.387">
                        <path id="Path_163" data-name="Path 163" d="M403.839,783.393H352.548A8.546,8.546,0,0,1,344,774.845V723.554a8.546,8.546,0,0,1,8.548-8.548h35.946l-3.569,3.573-4.958,4.975H352.548v51.291h51.291V747.263l4.9-4.894,3.65-3.65v36.126a8.546,8.546,0,0,1-8.548,8.548Zm-6.711-41.46-10.515,10.515a11.878,11.878,0,0,1-4.646,2.629l-10.258,2.565a1.514,1.514,0,0,1-1.7-.325,1.543,1.543,0,0,1-.321-1.7l2.565-10.258a11.787,11.787,0,0,1,2.637-4.65l10.515-10.515,7.8-7.788,11.746,11.737-7.822,7.792Zm11.583-11.566-11.754-11.737,1.175-1.171A8.305,8.305,0,0,1,409.879,729.2l-1.167,1.171v-.009Z" transform="translate(-344 -715.006)" fill="#fff" fill-rule="evenodd"></path>
                      </svg>

                    <div>
                        <span>{{__('admin.completed_courses')}}</span>
                        <b>{{$complete_courses_no}}</b>
                    </div>
                </div>

                <div class="course-cards bg-main">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="62.387" viewBox="0 0 35 62.387">
                        <path id="Icon_open-task" data-name="Icon open-task" d="M0,0V62.387H62.387v-32L53.475,39.3V53.475H8.912V8.912h32L49.821,0ZM62.387,0,35.65,26.737l-8.912-8.912-8.912,8.912L35.65,44.562,35,8.912Z" fill="#fff"></path>
                      </svg>
                    <div>
                        <span>{{__('admin.courses_in_progress')}}</span>
                        <b>{{$courses_in_progress}}</b>

                    </div>
                </div>


            </div>

        </div>

    </div>



</div>

<div class="card users">
  <div class="card-header">





  </div>

  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
            <th class="col-md-1">{{__('admin.index')}}</th>
            <th class="">{{__('admin.name')}}</th>
            <th class="">{{__('admin.user_type')}}</th>
            <th class="img-table d-none d-sm-table-cell col-md-1">{{__('admin.assigned_courses')}}</th>
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
        <td>
            <span style="display: block;" class="title">  {{ \App\Helpers\Lang::TransTitle($post->role_name) }}</span>
        </td>
        <td class="d-sm-table-cell">
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
