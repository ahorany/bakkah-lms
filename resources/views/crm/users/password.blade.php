@extends('layouts.crm.form')

{!!Builder::SetPostType(null)!!}
{{Builder::SetFolder($folder)}}
{{Builder::SetPublishName('save')}}

@section('col9')

	{!!Builder::Hidden('user_id', $eloquent->id??null)!!}
	{!!Builder::Input('en_name', 'en_name', null, ['col'=>'col-md-6'])!!}
	{!!Builder::Input('ar_name', 'ar_name', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Input('email', 'email', null, ['col'=>'col-md-6'])!!}

    {!!Builder::Input('en_trainer_courses_for_certifications', 'en_trainer_courses_for_certifications', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Input('ar_trainer_courses_for_certifications', 'ar_trainer_courses_for_certifications', null, ['col'=>'col-md-6'])!!}

	@if(!isset($eloquent))
		{!!Builder::Password('password', 'password', null, ['type'=>'password','col'=>'col-md-6'])!!}
		{!!Builder::Password('password_confirmation', 'password_confirmation', null, ['type'=>'password','col'=>'col-md-6'])!!}
	@endif
@endsection
