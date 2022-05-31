@extends('layouts.crm.form')
<link rel="stylesheet" href="{{CustomAsset('assets/css/jquery.datetimepicker.css')}}">

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}
{{Builder::SetNameSpace('training.')}}

@section('col9')
    {!!Builder::Hidden('course_id', request()->course_id)!!}
    {!!Builder::Input('title', 'title', isset($eloquent->message->title) ? $eloquent->message->title : null, ['col'=>'col-md-12'])!!}
    {!!Builder::DateTime('start_date', 'start_date', null, ['col'=>'col-md-6'])!!}
    {!!Builder::DateTime('end_date', 'end_date', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Textarea('description', 'description', isset($eloquent->message->description) ? $eloquent->message->description : null, [
        'row'=>8,
        'attr'=>'maxlength="1000"',
    ])!!}
@endsection



@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{CustomAsset('assets/js/jquery.datetimepicker.js')}}"></script>


    <script>
        $(function(){
            $('[data-date="datetime"]').datetimepicker({
                format:'Y-m-d H:i',
                dayOfWeekStart : 6,
            });
        });
    </script>
@endsection
