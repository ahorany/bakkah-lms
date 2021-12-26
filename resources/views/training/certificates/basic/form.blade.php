@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}
{{Builder::SetNameSpace('training.')}}
{{Builder::SetPrefix('training.')}}
{{Builder::SetPublishName('publish')}}

@if(Session::has('message'))
    <div  class="alert alert-danger">{{Session::get('message')}}</div>
@endif

@section('col9')

    {!!Builder::Input('title', 'project_name', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Input('time_line', 'time_line', null, ['col'=>'col-md-6 digit'])!!}
    {!!Builder::Input('budget', 'budget', null, ['col'=>'col-md-6 digit'])!!}
    {!!Builder::Select('currency', 'currency', $coins, request()->has('currency')?request()->coin:null, [
        'col'=>'col-md-6',
        'model_title'=>'trans_name',
    ])!!}
   
    {!! Builder::Select('developer_id', 'developer_id', $developers, null, [
                        'col'=>'col-md-6', 'model_title'=>'trans_name']) !!}
        
@endsection

@section('image')
	<?php $image_title = __('admin.work_order'); ?>
	{!!Builder::File('work_order', 'work_order')!!}

    <div class="card card-default">
        <div class="card-header">{!! Builder::CheckBox('show_to_developers') !!}</div>
    </div>

    <div class="card card-default list_developer">
        <div class="card-header">{{__('admin.list_developers_courses')}}</div>
        <div class="card-body p-0 py-2">
            <div class="col-md-12">
                <ul class="list-group">
                    @if(isset($wish_developers))
                    @foreach($wish_developers as $developer)
                        <li class="list-group-item">
                            <label>{{$developer->trans_name}}</label>
                            <button style="float:right;" type="button" class="btn btn-outline-info btn-sm wish"  id={{$developer->id}}>Approve</button>
                        </li>
                    @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>

@endsection

<script>
$(function(){

    if($('input[name="show_to_developers"]').is(":checked"))
        $('.list_developer').show();
    else
        $('.list_developer').hide();
    
    var selected = $('select[name="developer_id"]').val();
    if(selected > 0)
        $('.wish[id='+selected+']').hide();

    $('.wish').click(function(){
        $('.wish').show();
        $(this).hide();
        $('select[name="developer_id"]').val($(this).attr('id'));
    });

});
</script>