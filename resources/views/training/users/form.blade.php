@extends('layouts.crm.form')

{{--{!! Builder::SetPostType(null) !!}--}}
{{--{{ Builder::SetFolder($folder) }}--}}
{{--{{ Builder::SetPublishName('save') }}--}}



{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

{{Builder::SetNameSpace('training.')}}
{{Builder::SetPublishName('publish')}}
@section('col9')

    {!! Builder::Hidden('user_id', $eloquent->id ?? null) !!}
    {!! Builder::Hidden('post_type', $post_type ?? null) !!}
    {!! Builder::Input('en_name', 'en_name', null, ['col' => 'col-md-6']) !!}
    {!! Builder::Input('ar_name', 'ar_name', null, ['col' => 'col-md-6']) !!}
    {!! Builder::Input('email', 'email', null, ['col' => 'col-md-6']) !!}
    {!! Builder::Input('mobile', 'mobile', null, ['col' => 'col-md-6']) !!}
    {!! Builder::Select('gender_id', 'gender_id', $genders->where('parent_id', 42), null, [
    'col' => 'col-md-6',
    'model_title' => 'trans_name',
]) !!}

{{--    {!! Builder::Select('group_id[]', 'group', $groups , null, [--}}
{{--           'col' => 'col-md-6',--}}
{{--           'model_title' => 'name',--}}
{{--           'multiple'=> 'multiple'--}}
{{--        ]) !!}--}}





    @if ($post_type == 'users')
        {!! Builder::Password('password', 'password', null, ['type' => 'password', 'col' => 'col-md-6']) !!}
        {!! Builder::Password('password_confirmation', 'password_confirmation', null, ['type' => 'password', 'col' => 'col-md-6']) !!}
    @endif



    {!! Builder::Select('role', 'role', $roles, null, [
   'col' => 'col-md-6',
   'model_title' => 'trans_name',
]) !!}

    {{--    <div class="col-md-12">--}}
{{--        <div class="form-group">--}}
{{--            <label>{{__('admin.Group')}}</label>--}}
{{--        </div>--}}
{{--        @foreach($groups as $group)--}}
{{--            <div class="form-group form-check child">--}}
{{--                <input class="form-check-input child" id="{{$group->id}}" type="checkbox" @isset($user_groups) @foreach($user_groups as $user_group) @if($user_group->group_id == $group->id) checked="true" @endif @endforeach  @endisset name="group_id[{{$group->id}}]">--}}
{{--                <label class="form-check-label" for="{{$group->id}}">{{$group->name}}</label>--}}
{{--            </div>--}}
{{--        @endforeach--}}

{{--    </div>--}}


    @if ($post_type != 'users')
        {!! Builder::Select('country_id', 'country_id', $countries, null, [
    'col' => 'col-md-6',
    'model_title' => 'trans_name',
]) !!}
        {!! Builder::Input('iqama_number', 'iqama_number', $eloquent->profile ? $eloquent->profile->iqama_number : null, ['col' => 'col-md-6']) !!}
        {!! Builder::Input('passport_id', 'passport_id', $eloquent->profile ? $eloquent->profile->passport_id : null, ['col' => 'col-md-6']) !!}
        {!! Builder::Date('passport_expiry_date', 'passport_expiry_date', $eloquent->profile ? $eloquent->profile->passport_expiry_date : null, ['col' => 'col-md-6']) !!}
        {!! Builder::Number('experience', 'experience', $eloquent->profile ? $eloquent->profile->experience : null, ['col' => 'col-md-6']) !!}

        {!! Builder::Select('training_field_id', 'training_field_id', $training_field, $eloquent->profile ? $eloquent->profile->training_field_id : null, [
    'col' => 'col-md-6',
    'model_title' => 'trans_name',
]) !!}
        {!! Builder::Select('activity_level_id', 'activity_level_id', $activity_level, $eloquent->profile ? $eloquent->profile->activity_level_id : null, [
    'col' => 'col-md-6',
    'model_title' => 'trans_name',
]) !!}
        {!! Builder::Input('certifications', 'certifications', $eloquent->profile ? $eloquent->profile->certifications : null, ['col' => 'col-md-6']) !!}
        {!! Builder::Select('level_education_id', 'level_education_id', $level_of_education, $eloquent->profile ? $eloquent->profile->level_education_id : null, [
    'col' => 'col-md-6',
    'model_title' => 'trans_name',
]) !!}


        {!! Builder::Date('joining_bakkah', 'joining_bakkah', $eloquent->profile ? $eloquent->profile->joining_bakkah : null, ['col' => 'col-md-6']) !!}


        @if ($post_type == 'on-demand-team')
            {!! Builder::Select('session_handle_id', 'session_handle_id', $session_can_handle, $eloquent->profile ? $eloquent->profile->session_handle_id : null, [
    'col' => 'col-md-6',
    'model_title' => 'trans_name',
]) !!}
        @endif

        @if ($post_type == 'trainers')
            <div class="row col-md-12 pl-3">
                <div class="card card-default col-md-12 mb-4" style="border: 1px solid #fb44005c;">
                    <div class="card-header text-bold pl-2">{{ __('admin.courses') }}</div>
                    <div class="card-body px-0">
                        {!! Builder::Input('courses_b2c', 'courses_b2c', $eloquent->profile ? $eloquent->profile->courses_b2c : null, ['col' => 'col-md-6 position-relative float-left']) !!}
                        {!! Builder::Input('courses_b2b', 'courses_b2b', $eloquent->profile ? $eloquent->profile->courses_b2b : null, ['col' => 'col-md-6 position-relative float-right']) !!}
                    </div>
                </div>
            </div>
        @endif

        @if ($post_type == 'trainers')
            <div class="row col-md-12">
                <div class="col-md-6">
                    <div class="card card-default col-md-12 mb-4" style="border: 1px solid #28a745;">
                        <div class="card-header text-bold pl-2 text-success">{{ __('admin.online_cost') }}</div>
                        <div class="card-body px-0">
                            {!! Builder::Input('morning_rate_online', 'morning_rate', $eloquent->profile ? $eloquent->profile->morning_rate_online : null, ['col' => 'col-md-6 digit  position-relative float-left']) !!}
                            {!! Builder::Input('evening_rate_online', 'evening_rate', $eloquent->profile ? $eloquent->profile->evening_rate_online : null, ['col' => 'col-md-6 digit position-relative float-right']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-default col-md-12 mb-4" style="border: 1px solid #007bff;">
                        <div class="card-header text-bold pl-2 text-primary">{{ __('admin.classroom_cost') }}</div>
                        <div class="card-body px-0">
                            {!! Builder::Input('morning_rate_classroom', 'morning_rate', $eloquent->profile ? $eloquent->profile->morning_rate_classroom : null, ['col' => 'col-md-6 digit  position-relative float-left']) !!}
                            {!! Builder::Input('evening_rate_classroom', 'evening_rate', $eloquent->profile ? $eloquent->profile->evening_rate_classroom : null, ['col' => 'col-md-6 digit position-relative float-right']) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($post_type == 'developers' || $post_type == 'on-demand-team')
            <div class="row col-md-12 pl-3">
                <div class="card card-default col-md-12 mb-4" style="border: 1px solid #17a2b8;">
                    <div class="card-header text-bold pl-2">{{ __('admin.daily_cost') }}</div>
                    <div class="card-body px-0">
                        {!! Builder::Input('daily_rate_online', 'daily_rate_online', $eloquent->profile ? $eloquent->profile->daily_rate_online : null, ['col' => 'col-md-6 digit  position-relative float-left text-success']) !!}
                        {!! Builder::Input('daily_rate_classroom', 'daily_rate_classroom', $eloquent->profile ? $eloquent->profile->daily_rate_classroom : null, ['col' => 'col-md-6 digit  position-relative float-right text-primary']) !!}
                    </div>
                </div>
            </div>
        @endif

        @if ($post_type == 'trainers' || $post_type == 'developers')
            {!! Builder::Input('classification', 'classification', $eloquent->profile ? $eloquent->profile->classification : null, ['col' => 'col-md-6']) !!}
        @endif

        @if ($post_type == 'developers')
        {!! Builder::Number('number_projects', 'number_projects', $eloquent->profile ? $eloquent->profile->number_projects : null, ['col' => 'col-md-6']) !!}
        @endif


        {{-- @if ($post_type == 'trainers')
            {!! Builder::Input('en_trainer_courses_for_certifications', 'en_trainer_courses_for_certifications', null, ['col' => 'col-md-6']) !!}
            {!! Builder::Input('ar_trainer_courses_for_certifications', 'ar_trainer_courses_for_certifications', null, ['col' => 'col-md-6']) !!}
        @endif --}}
        {!! Builder::Textarea('note', 'note', $eloquent->profile ? $eloquent->profile->note : null, ['col' => 'col-md-12', 'row' => 3]) !!}

    @endif


    {{-- @if (!isset($eloquent))
		{!!Builder::Password('password', 'password', null, ['type'=>'password','col'=>'col-md-6'])!!}
		{!!Builder::Password('password_confirmation', 'password_confirmation', null, ['type'=>'password','col'=>'col-md-6'])!!}
	@endif --}}
@endsection

@section('col3_block')
    @if ($post_type == 'employees')
    <div class="card card-default mb-4">
        <div class="card-header">{{ __('admin.role_id') }}</div>
        <div class="card-body">
            <ul class="list-unstyled px-0 mt-2">
            @foreach($roles as $role)
            <li class="mb-2">
                <div class="form-check">
                    <label class="form-check-label"><input class="form-check-input child" name="roles[]" {{ $eloquent->roles->pluck('id')->contains($role->id) ? 'checked' : '' }} type="checkbox" value="{{ $role->id }}"> {{ $role->trans_name }}</label>
                </div>
            </li>
            @endforeach
            </ul>

        </div>
    </div>
    @endif

    <div class="card card-default">
        <div class="card-header">{{ __('admin.attachments') }}</div>
        <div class="card-body">
            <style>
                .financial_info .form-group,
                .cv .form-group,
                .certificates .form-group {
                    margin-bottom: 0;
                }

            </style>

            {!! Builder::Hidden('user_type', $user_type ?? null) !!}

            {!! Builder::File('financial_info', 'financial_info', null, ['col' => 'col-md-12']) !!}
            {!! Builder::ProfileFile('financial_info') !!}

            {!! Builder::File('cv', 'cv', null, ['col' => 'col-md-12 mt-3']) !!}
            {!! Builder::ProfileFile('cv') !!}

            {!! Builder::File('certificates', 'certificates', null, ['col' => 'col-md-12 mt-3']) !!}
            {!! Builder::ProfileFile('certificates') !!}

        </div>
    </div>
    {{-- @include(ADMIN.'.Html.checkbox_const', ['const_type'=>'employee']) --}}
@endsection

@section('image')
    <?php $image_title = __('admin.image'); ?>
    @include('Html.image')
@endsection
