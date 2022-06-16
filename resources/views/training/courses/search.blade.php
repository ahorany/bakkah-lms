<style>
.form-inline .form-group {
    margin-bottom: 10px;
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
                        {!!Builder::Input('course_search', 'course_search',request()->course_search??null,['col'=>'col-md-6'])!!}
                        {!!Builder::Select('category_id', 'category_id', $categories, request()->category_id??null, ['col'=>'col-md-6', 'model_title'=>'trans_title',])!!}
                        {!!Builder::Select('training_option_id', 'training_option_id', $delivery_methods, request()->training_option_id??null, ['col'=>'col-md-6', 'model_title'=>'trans_name',])!!}
                        <div class="col-md-12">
                            <div style="margin-top: 5px;">
                                {!! Builder::Submit('search', 'search', 'main-color', 'search') !!}
                                {!! Builder::clearSearch('clear', 'clear', 'cyan', 'clear') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

