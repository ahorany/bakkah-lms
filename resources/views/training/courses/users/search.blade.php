<style>
.form-inline .form-group {
    margin-bottom: 5px;
}
.form-inline .form-group label {
    font-weight: normal !important;
}
.form-inline .custom-control-label{
    display: inline-block;
}
.form-inline .form-group .form-control {
    width: 60%;
    height: calc(2rem + 2px);
}
.form-inline > div {
    padding-left: 0 !important;
    padding-right: 0 !important;
}
</style>
<form id="post-search" class="courses form-inline mb-4" method="get" action="{{route('training.course_users')}}">
        <div class="col-md-12">

            <div class="card card-default">
                <div class="card-header">
                    <b>{{__('admin.search form')}}</b>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="course_id" value="{{$course_id}}">
                                    <input type="text"  name="user_search"  class="form-control input_search" placeholder="Name or Email" value="{{request()->user_search??null}}" >
                                </div>
                            </div>

{{--                            <div class="col-md-12">--}}
{{--                                <div class="form-group">--}}
{{--                                        {!! Builder::SelectForCheckBox('trainee', request()->trainee) !!}--}}

{{--                                        {!! Builder::SelectForCheckBox('instructor', request()->instructor) !!}--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div style="margin-top: 5px;">
                                {!! Builder::Submit('search', 'search', 'main-color', 'search') !!}
                                <button type="reset" class="cyan" >{{__('admin.clear')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
</form>

