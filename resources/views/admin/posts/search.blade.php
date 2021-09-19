<style>
.form-inline .form-group {
    margin-bottom: 5px;
}
.form-inline .form-group label {
    font-weight: normal !important;
    width: 150px;
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
<form id="post-search" class="form-inline" method="get" action="{{route('admin.posts.index')}}">
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

                            {{-- <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" name="post_search"  class="form-control" placeholder="Article Title, Details or Link" value="{{request()->post_search}}" class="form-control">
                                </div>
                            </div> --}}

                            {!! Builder::Input('post_search', 'post_search', request()->post_search, ['col'=>'col-md-4']) !!}

                            {!! Builder::Select('category_id', 'category_id', $categories, request()->category_id??-1, [
                                'col'=>'col-md-4',
                            ]) !!}


                            <div class="col-md-2 pt-2">
                                <div class="form-group">
                                    <label for="show_in_website">{{__('admin.show_in_website')}}</label>
                                    <input id="show_in_website" type="checkbox" name="show_in_website" style="width: 20px; height: 20px;" {{request()->has('show_in_website') ? 'checked' : '' }} >
                                </div>
                            </div>

                            <div style="margin-left:25px;margin-top: 5px;"> {{-- class="col-md-6"  --}}
                                {!! Builder::Submit('search', 'search', 'btn-primary', 'search') !!}
                                {{-- {!! Builder::Submit('clear', 'clear', 'btn-default', 'eraser') !!} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
</form>
