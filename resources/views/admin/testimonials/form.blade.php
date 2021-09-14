@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

@section('col9')
    {!!Builder::Select('user_id', 'user_id', $users, null, [
        'col'=>'col-md-6', 'model_title'=>'trans_name',
    ])!!}

        {!!Builder::Select('course_id', 'course_name', $courses, null, [
        'col'=>'col-md-6', 'model_title'=>'trans_title',
    ])!!}

    {!!Builder::Textarea('en_excerpt', 'en_excerpt', null, ['row'=>10])!!}
    {!!Builder::Textarea('ar_excerpt', 'ar_excerpt', null, ['row'=>10])!!}

    {!!Builder::Select('status', 'status', $status, null, [
        'col'=>'col-md-12', 'model_title'=>'trans_name',
    ])!!}
    {!!Builder::Input('order', 'order')!!}
@endsection

@section('col3')
    <?php $title = __('admin.table'); ?>
    {!!Builder::Date('post_date', 'post_date')!!}
    {!!Builder::CheckBox('show_in_home')!!}
@endsection
