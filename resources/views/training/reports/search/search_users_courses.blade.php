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
    {{-- @dd($user[0]->id) --}}
    <form id="post-search" class="mb-4" method="get" action="{{route('training.usersReportCourse',['id'=>$user[0]->id??null,'course_id'=>$course[0]->id??null])}}">
        <div class="card card-default">
            <div class="card-header">
                <b>{{__('admin.search form')}}</b>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        {{-- {!! Builder::Hidden('page', request()->page??1) !!} --}}
                        {!! Builder::Hidden('id', $user[0]->id??null) !!}
                        {!! Builder::Hidden('course_id', $course[0]->id??null) !!}
                        {!! Builder::Hidden('show_all', $show_all??null) !!}

                        {!!Builder::Input('course_search', 'course_search',request()->course_search??null,['col'=>'col-md-6'])!!}
                        {!!Builder::Select('category_id', 'category_id', $categories, request()->category_id??null, ['col'=>'col-md-6', 'model_title'=>'trans_title',])!!}
                        {!!Builder::Select('training_option_id', 'training_option_id', $delivery_methods, request()->training_option_id??null, ['col'=>'col-md-6', 'model_title'=>'trans_name',])!!}
                        <div class="col-md-12">
                            <div style="margin-left:0px;margin-top: 5px;">
                                {!! Builder::Submit('search', 'search', 'main-color', 'search') !!}
                                <button type="reset" class="cyan" >{{__('admin.clear')}}</button>
                                <input type="submit" value="export" name="export" class="export">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </form>
