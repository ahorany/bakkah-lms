<style>
    .form-inline .form-group {
        margin-bottom: 5px;
    }
    .form-inline .form-group label {
        font-weight: normal !important;
        width: 190px;
    }
    .form-inline .form-group .form-control {
        width: 60%;
        height: calc(2rem + 2px);
    }
    .bg-secondary {
        background-color: #303d47!important;
        border-color: #303d47!important;
    }
    .card-header a {
        color: #fff;
    }
    .form-inline > div {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    </style>
    <?php
        // echo $coin_id;
    // dd($delivery_methods);
    ?>
    {{-- form-group d-flex align-items-center --}}
<form id="training-schedule-search" method="get" action="{{route('training.carts.training-schedule')}}">
    <div class="row">
        <div class="col-md-12">

            <div class="card card-default">
                <div class="card-header">
                    <b>{{__('admin.search form')}}</b>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            {{-- {!! Builder::Hidden('page', request()->page??1) !!} --}}
                            {{-- {!! Builder::Hidden('trash') !!} --}}

                            {!! Builder::Select('course_id', 'All Courses', $all_courses, request()->course_id??-1, [
                                'col'=>'col-md-6',
                                'model_title'=>'trans_title',
                            ]) !!}

                            {!! Builder::SelectForSearch('training_option_id', $delivery_methods, [
                                'col'=>'col-md-2',
                            ]) !!}

                            {!! Builder::Date('date_from', 'date_from', request()->date_from??null, ['col'=>'col-md-2',]) !!}

                            {!! Builder::Date('date_to', 'date_to', request()->date_to??null, ['col'=>'col-md-2',]) !!}


                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        {!! Builder::Submit('search', 'search', 'btn-primary', 'search') !!}
                                        {!! Builder::Submit('clear', 'clear', 'btn-default', 'eraser') !!}
                                    </div>
                                    <input type="hidden" name="coin_id" value="{{$coin_id}}">
                                    @if($coin_id==334)
                                        <button class="btn btn-success float-right" name="convertToUSD">Change Prices to {{__('education.USD')}}</button>
                                    @else
                                        <button class="btn btn-success float-right" name="convertToSAR">Change Prices to {{__('education.SAR_currency')}}</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</form>


<script>
    jQuery(function (){
        jQuery('[name="convertToUSD"]').click(function (){
            jQuery('[name="coin_id"]').val(335);
            location.reload();
        });
        jQuery('[name="convertToSAR"]').click(function (){
            jQuery('[name="coin_id"]').val(334);
            location.reload();
        });

        jQuery('[name="clear"]').click(function (){
            jQuery('[name="course_id"], [name="training_option_id"]').val(-1);
            jQuery('[name="date_from"], [name="date_to"]').val('');
            return false;
        });
    });
</script>
