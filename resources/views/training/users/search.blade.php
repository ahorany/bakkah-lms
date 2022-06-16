<style>
    .form-inline .form-group {
        margin-bottom: 5px;
    }
    .form-inline .form-group label {
        font-weight: normal !important;
        width: 150px;
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
    <form id="post-search" class="mb-4" method="get" action="{{route('training.users.index')}}">
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
                        {!! Builder::Input('user_search', 'user_search',request()->user_search??null,['col'=>'col-md-6'])!!}
                        {!! Builder::Input('mobile', 'mobile',request()->mobile??null,['col'=>'col-md-6'])!!}
                        <div class="col-md-12">
                            <div style="margin-left:0px;margin-top: 5px;">
                                {!! Builder::Submit('search', 'search', 'main-color', 'search') !!}
                                {!! Builder::clearSearch('clear', 'clear', 'cyan', 'clear') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </form>
