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
<form id="post-search" class="courses form-inline mb-4" method="get" action="{{route('training.courses.index')}}">
        <div class="col-md-12">

            <div class="card card-default">
                <div class="card-header">
                    <b>{{__('admin.search form')}}</b>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            {!! Builder::Hidden('page', request()->page??1) !!}
                            {!! Builder::Hidden('post_type', $post_type) !!}
                            {!! Builder::Hidden('trash') !!}

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text"  name="course_search"  class="form-control input_search" placeholder="Course Title" value="{{request()->course_search??null}}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                {!!Builder::Select('category_id', 'category_id', $categories, null, ['col'=>'col-md-6', 'model_title'=>'trans_title',])!!}
                            </div>
                            <div style="margin-top: 5px;"> {{-- class="col-md-6"  --}}
                                {!! Builder::Submit('search', 'search', 'main-color', 'search') !!}
                                <button type="reset" class="cyan" >{{__('admin.clear')}}</button>
                                {{-- {!! Builder::Submit('clear', 'clear', 'btn-default', 'eraser') !!}
                                --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
</form>

