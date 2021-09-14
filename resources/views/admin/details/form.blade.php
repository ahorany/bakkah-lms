<div class="col-md-12">
    @include(ADMIN.'.Html.alert')
    @include(ADMIN.'.Html.errors')
</div>

<div class="row">
    <div class="col-md-12">
        @csrf
        <div class="card card-default">

            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        {!!Builder::Hidden('master_id', request()->master_id??null)!!}
                        {!!Builder::Hidden('constant_id', request()->constant_id??null)!!}
                        {!!Builder::Tinymce('en_details', 'en_details')!!}
                        {!!Builder::Tinymce('ar_details', 'ar_details')!!}
                    </div>
                </div>
            </div>

        </div>
        <!-- /.card -->

        <div class="card card-default">
            <div class="card-body">
                <div class="BtnGroupForm">
                    {!! Builder::Submit('submit', 'update', 'btn-primary', 'save'); !!}
                </div>
            </div>
        </div>

    </div>

</div>
