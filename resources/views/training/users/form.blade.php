@extends('layouts.crm.form')

<style>
.upload_title, .upload_excerpt, .upload_caption, [for="exclude_img"]{
    display: none;
}
</style>
{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

{{Builder::SetNameSpace('training.')}}
{{Builder::SetPublishName('publish')}}


@section('col9')
    {!! Builder::Input('en_name', 'en_name', null, ['col' => 'col-md-6']) !!}
    {!! Builder::Input('ar_name', 'ar_name', null, ['col' => 'col-md-6']) !!}
    {!! Builder::Input('email', 'email', null, ['col' => 'col-md-6']) !!}
    {!! Builder::Input('mobile', 'mobile', null, ['col' => 'col-md-6']) !!}
    {!! Builder::Select('gender_id', 'gender_id', $genders->where('parent_id', 42), null, [
        'col' => 'col-md-6',
        'model_title' => 'trans_name',
    ]) !!}


    {!! Builder::Select('role', 'role', $roles, $role_id??null, [
       'col' => 'col-md-6',
       'model_title' => 'name',
       ]) !!}



    {!! Builder::Password('password', 'password', null, ['type' => 'password', 'col' => 'col-md-6']) !!}
    {!! Builder::Password('password_confirmation', 'password_confirmation', null, ['type' => 'password', 'col' => 'col-md-6']) !!}

@endsection


@section('image')
    <?php $image_title = __('admin.image'); ?>
    <div class="image">
        @include('Html.image')
    </div>
@endsection
