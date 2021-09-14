@extends(ADMIN.'.general.form')


{{Builder::SetNameSpace('training.')}}
{{Builder::SetFolder($folder)}}

@section('col9')
    {!!Builder::Select('user_id', 'user_id', $users, null, [
        'col'=>'col-md-6', 'model_title'=>'trans_name',
    ])!!}

    {!!Builder::Select('session_id', 'session_id', $sessions, null, [
        'col'=>'col-md-6', 'model_title'=>'session_details',
    ])!!}

{{--    {!!Builder::Input('course', 'course',null,array( 'col'=>'col-md-6','attr'=>'disabled'))!!}--}}

    {!!Builder::Select('status', 'status', $status, null, [
        'col'=>'col-md-6', 'model_title'=>'trans_name',
    ])!!}

{{--    {!!Builder::Input('price', 'price',null,array( 'col'=>'col-md-6'))!!}--}}
{{--    {!!Builder::Input('discount_percentage', 'discount_percentage',null,array( 'col'=>'col-md-6'))!!}--}}
{{--    {!!Builder::Input('discount_value', 'discount_value',null,array( 'col'=>'col-md-6'))!!}--}}
{{--    {!!Builder::Input('exam_price', 'exam_price',null,array( 'col'=>'col-md-6'))!!}--}}
{{--    {!!Builder::Input('vat', 'vat',null,array( 'col'=>'col-md-6'))!!}--}}
{{--    {!!Builder::Input('total', 'total',null,array( 'col'=>'col-md-6'))!!}--}}

@endsection

<script>
    $(function () {
        // $('select[name="session_id"]').change(function () {
        //     alert();
        // });
    });
</script>
