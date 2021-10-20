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
    <form id="post-search" class="form-inline" method="get" action="{{route('training.courses.index')}}">
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

                                {!! Builder::Input('course_search', 'course_search', request()->course_search, ['col'=>'col-md-5']) !!}

                                {!! Builder::Select('category_id', 'category_id', $categories, request()->category_id??-1, [
                                    'col'=>'col-md-5',
                                ]) !!}

                                {!! Builder::SelectForCheckBox('show_in_website', request()->show_in_website?1:0, ['col'=>'col-md-2']) !!}

                                <div style="margin-left:25px;margin-top: 5px;"> {{-- class="col-md-6"  --}}
                                    {!! Builder::Submit('search', 'search', 'btn-primary', 'search') !!}
                                    {{-- {!! Builder::Submit('clear', 'clear', 'btn-default', 'eraser') !!}
                                    --}}

                                    {{-- <div class="col-md-4">
                                        <div class="form-group"> --}}
                                            {{-- <label>{{__('admin.course_name')}}</label> --}}
                                            {{-- <input type="text" name="course_search"  class="form-control" placeholder="Course Title" value="{{request()->course_search}}" class="form-control">
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
    </form>
