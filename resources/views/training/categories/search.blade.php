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
    width: 100%;
    height: calc(2rem + 2px);
}
.form-inline > div {
    padding-left: 0 !important;
    padding-right: 0 !important;
}
</style>
<form id="post-search" class="categories form-inline mb-4" method="get" action="{{route('training.categories.index')}}">
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

                            {!! Builder::Input('category_search', 'category_search', request()->category_search??null, ['col'=>'col-md-12'])!!}
                            <div class="col-md-12">
                                <div style="margin-top: 5px;">
                                    {!! Builder::Submit('search', 'search', 'main-color', 'search') !!}
                                    <button type="reset" class="cyan" >{{__('admin.clear')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
</form>

