@extends(ADMIN.'.general.edit')

@section('edit')

    <div class="col-md-12">
        @include(ADMIN.'.Html.alert')
        @include(ADMIN.'.Html.errors')
    </div>

    <?php $folder='SEO'; ?>
    {{Builder::SetObject('infastructure')}}
    {{Builder::SetEloquent($eloquent)}}

    @csrf

    @include(ADMIN.'.SEO.form', ['post'=>$eloquent??null])

    {!!Builder::BtnGroupForm()!!}

@endsection
