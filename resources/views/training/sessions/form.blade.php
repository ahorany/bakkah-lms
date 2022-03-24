@extends('layouts.crm.form')
<link rel="stylesheet" href="{{CustomAsset(ADMIN.'-dist/css/jquery.datetimepicker.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

{{Builder::SetNameSpace('training.')}}
{{Builder::SetPublishName('publish')}}

@section('col9')
    <div class="col-12">
        <div class="row mx-0 inputs">
            {!!Builder::Select2('course_id', 'course', $courses,null,['model_title' => 'trans_title'])!!}
            {!!Builder::DateTime('date_from', 'date_from', null, ['col'=>'col-md-6'])!!}
            {!!Builder::DateTime('date_to', 'date_from', null, ['col'=>'col-md-6'])!!}
            {!!Builder::Input('ref_id', 'ref_id')!!}
        </div>
    </div>
@endsection




<script src="{{CustomAsset(ADMIN.'-dist/js/jquery.datetimepicker.js')}}"></script>

<script>
    $(function(){
        $('[data-date="datetime"]').datetimepicker({
            format:'Y-m-d H:i',
            dayOfWeekStart : 6,
        });
    });
</script>


<script>

    $(function(){

        $('.select2').select2();

    });

</script>
