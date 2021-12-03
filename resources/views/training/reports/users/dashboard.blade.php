
<div class="row mb-5">

    <div class="col-lg-12">
        <div class="card h-100 justify-content-center p-30">

            <div class="d-flex flex-column flex-sm-row flex-wrap">

                <div class="course-cards card-report bg-third justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="71.3" height="62.387" viewBox="0 0 71.3 62.387">
                        <path id="Icon_open-task" data-name="Icon open-task" d="M0,0V62.387H62.387v-32L53.475,39.3V53.475H8.912V8.912h32L49.821,0ZM62.387,0,35.65,26.737l-8.912-8.912-8.912,8.912L35.65,44.562,71.3,8.912Z" fill="#fff"></path>
                      </svg>
                    <div>
                        <span>{{__('admin.courses_not_started')}}</span>
                        <b>{{$courses_not_started}}</b>
                    </div>
                </div>

                <div class="course-cards card-report bg-third justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="71.3" height="62.387" viewBox="0 0 71.3 62.387">
                        <path id="Icon_open-task" data-name="Icon open-task" d="M0,0V62.387H62.387v-32L53.475,39.3V53.475H8.912V8.912h32L49.821,0ZM62.387,0,35.65,26.737l-8.912-8.912-8.912,8.912L35.65,44.562,71.3,8.912Z" fill="#fff"></path>
                      </svg>
                    <div>
                        <span>{{__('admin.courses_in_progress')}}</span>
                        <b>{{$courses_in_progress}}</b>
                    </div>
                </div>

                <div class="course-cards card-report bg-main justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="71.3" height="62.387" viewBox="0 0 71.3 62.387">
                        <path id="Icon_open-task" data-name="Icon open-task" d="M0,0V62.387H62.387v-32L53.475,39.3V53.475H8.912V8.912h32L49.821,0ZM62.387,0,35.65,26.737l-8.912-8.912-8.912,8.912L35.65,44.562,71.3,8.912Z" fill="#fff"></path>
                      </svg>
                    <div>
                        <span>{{__('admin.learners')}}</span>
                        <b>{{$learners_no}}</b>
                    </div>

                </div>

                <div class="course-cards card-report bg-two justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="71.3" height="62.387" viewBox="0 0 71.3 62.387">
                        <path id="Icon_open-task" data-name="Icon open-task" d="M0,0V62.387H62.387v-32L53.475,39.3V53.475H8.912V8.912h32L49.821,0ZM62.387,0,35.65,26.737l-8.912-8.912-8.912,8.912L35.65,44.562,71.3,8.912Z" fill="#fff"></path>
                      </svg>

                    <div>
                        <span>{{__('admin.completed_courses')}}</span>
                        <b>{{$complete_courses_no}}</b>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
