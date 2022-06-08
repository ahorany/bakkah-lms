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
    <form id="post-search" class="mb-4" method="get" action="{{route('training.coursesAssessments',['id'=>$course[0]->id??null,'user_id'=>$user[0]->id??null])}}">
        <div class="card card-default">
            <div class="card-header">
                <b>{{__('admin.search form')}}</b>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        {{-- {!! Builder::Hidden('page', request()->page??1) !!} --}}
                        {!! Builder::Hidden('id', $course[0]->id??null) !!}
                        {!! Builder::Hidden('user_id', $user[0]->id??null) !!}
                        {!! Builder::Hidden('show_all', $show_all??null) !!}

                        {!! Builder::Input('user_search', 'user_search',request()->user_search??null,['col'=>'col-md-6'])!!}
                        @if($course[0]->training_option_id == 13)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="session_id">{{__('admin.session_id')}}</label>
                                <select name="session_id" class="form-control" style="width:250px" data-show-flag="true" >
                                <option value="">Choose Value</option>
                                @foreach ($sessions as $key => $value)

                                <option value="{{ $value->id }}" {{ $session_id ==  $value->id ? "selected" :""}} >
                                    SID : {{$value->id }} | {{$value->date_from}} | {{$value->date_to}}
                                </option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
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
