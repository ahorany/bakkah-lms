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
    input.form-control {
        width: 630px !important;
    }
    .custom-control.custom-checkbox {
        padding-left: 100px;
    }
    .form-inline > div {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    </style>
    <form id="post-search" class="form-inline" method="get" action="{{route('admin.partners.index')}}">
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
                                    <div class="form-group"> --}}
                                        {{-- <label>{{__('admin.course_name')}}</label> --}}
                                        <input type="text" name="title_search"  class="form-control" placeholder="Input title to search" value="{{request()->title_search}}" class="form-control">
                                    {{-- </div>
                                </div> --}}

                                {{-- <input class="custom-control-input" type="checkbox" name="show_in_home" id="show_in_home" value="show_in_home" checked="{{request()->show_in_home==1?'checked':''}}">
                                <label for="show_in_home" class="custom-control-label">Show In Home</label> --}}

                                {!! Builder::SelectForCheckBox('in_education', request()->in_education) !!}
                                {!! Builder::SelectForCheckBox('in_consulting', request()->in_consulting) !!}
                                {!! Builder::SelectForCheckBox('show_in_home', request()->show_in_home) !!}

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
