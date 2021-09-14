<div class="row search-form mb-5">
    <div class="col">
        <div class="bg-light p-4 rounded">
            <h2>{{__('education.Search Course')}}</h2>
            <form role="search" action="{{route('education.training-schedule')}}" method="get">
                <div class="row">

                    {{Builder::SetPrefix('education.')}}

                    {!! Builder::Select('course_id', 'All Courses', $all_courses, request()->course_id??-1, [
                        'col'=>'col-md-6',
                        'model_title'=>'trans_title',
                    ]) !!}

                    {!! Builder::Date('date_from', 'Date From', request()->date_from??null, ['col'=>'col-md-3',]) !!}
                    {!! Builder::Date('date_to', 'Date To', request()->date_to??null, ['col'=>'col-md-3',]) !!}

                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{__('education.Search')}} <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div> <!-- /.col-md-2 -->
                </div>
            </form>
        </div>
    </div>
</div> <!-- /.search-form -->
