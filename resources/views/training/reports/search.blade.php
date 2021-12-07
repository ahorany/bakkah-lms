<style>
    .form-inline .form-group {
        margin-bottom: 5px;
    }
    .form-inline .form-group label {
        font-weight: normal !important;
    }
    .form-inline .custom-control-label {
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
    <form id="post-search" class="courses form-inline mb-4" method="get" action="{{route('training.usersReport')}}">
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

                                <div class="form-group">
                                    <input type="text" name="user_search"  class="form-control" placeholder="Name, Email, Mobile, Job Title, Comapny" value="{{request()->user_search??null}}" class="form-control">
                                </div>


                                <div style="margin-top: 5px;"> {{-- class="col-md-6"  --}}
                                    {!! Builder::Submit('search', 'search', 'main-color', 'search') !!}


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
    </form>
