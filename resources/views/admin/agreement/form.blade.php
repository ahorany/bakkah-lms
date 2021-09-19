@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

@section('col9')

    {!!Builder::Select('partner_id', 'partner_id', $partners, null, [
        'col'=>'col-md-12', 'model_title'=>'trans_name',
    ])!!}
    {!!Builder::Date('signing_date', 'signing_date',null,array('col'=>'col-md-6'))!!}
    {!!Builder::Date('expired_date', 'expired_date',null,array('col'=>'col-md-6'))!!}
    {{-- {!!Builder::CheckBox('is_active', 'is_active',null,array('col'=>'col-md-12'))!!} --}}

@endsection

@section('col3_block')
    @if ($eloquent)
        @include(ADMIN.'.agreement.notes', ['eloquent' => $eloquent])
    @endif
@endsection

