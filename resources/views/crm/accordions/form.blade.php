<div class="col-md-12">
    @include(ADMIN.'.Html.alert')
    @include(ADMIN.'.Html.errors')
</div>

<div class="row">
    <div class="col-md-9">
        @csrf
        <div class="card card-default">

            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        {!!Builder::Hidden('master_id', request()->master_id??null)!!}
                        {!!Builder::Input('en_title', 'en_title', null, ['col'=>'col-md-6'])!!}
                        {!!Builder::Input('ar_title', 'ar_title', null, ['col'=>'col-md-6'])!!}
                        {!!Builder::Tinymce('en_details', 'en_details')!!}
                        {!!Builder::Tinymce('ar_details', 'ar_details')!!}
                        {{-- {!!Builder::Textarea('en_details', 'en_details', null, [
                            'row'=>5,
                            'tinymce'=>'tinymce-small',
                        ])!!} --}}
                        {{-- {!!Builder::Textarea('ar_details', 'ar_details', null, [
                            'row'=>5,
                            'tinymce'=>'tinymce-small',
                        ])!!} --}}
                    </div>
                </div>
            </div>

        </div>
        <!-- /.card -->
        <div class="card card-default">
            <div class="card-body">
                <div class="BtnGroupForm">
                {!! '<a href="'.route('admin.accordions.index', ['master_id'=>$eloquent->master_id??request()->master_id]).'" class="btn btn-sm btn-default" ><i class="fa fa-arrow-left"></i> '.__('admin.back').'</a>' !!}
                    {!! Builder::Submit('submit', 'update', 'btn-primary', 'save'); !!}
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-3">

        <div class="card card-default">
            <div class="card-body">
                <div class="BtnGroupForm">
                    {!! '<a href="'.route('admin.accordions.index', ['master_id'=>$eloquent->master_id??request()->master_id]).'" class="btn btn-sm btn-default" ><i class="fa fa-arrow-left"></i> '.__('admin.back').'</a>' !!}
                    {!! Builder::Submit('submit', 'update', 'btn-primary', 'save'); !!}
                </div>
            </div>
        </div>

        <div class="card card-default">
            <div class="card-header">{{__('admin.options')}}</div>
            <div class="card-body">
                {!!Builder::Input('order', 'order', null)!!}

            </div>
        </div>

    </div>
</div>
