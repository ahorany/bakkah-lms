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

    <div class="text-danger text-bold my-3 note" style="display: none">Note: This email already has an account. You only have to fill in these fields to be registered with your branch </div>


    <div class="col-md-6 email">
        <div class="form-group">
            <label>Email </label>
            <input maxlength="155" @isset($eloquent) disabled="true" @endisset type="text" name="email" class="form-control" placeholder="Email" value="{{isset($eloquent) ? $eloquent->email : null}}">
        </div>
    </div>


    {!! Builder::Input('name', 'name',isset($user_branch) ? $user_branch->name : null, ['col' => 'col-md-6']) !!}
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

@if(!isset($eloquent))
    @section('script')
       <script>
            $( ".email input" ).blur(function() {
                    $.ajax({
                        url: "{{route('training.getUserData')}}",
                        data: {'email' : $(this).val()}
                    }).done(function(response) {
                        if(response.status){
                            $( ".name input" ).val(response.data.name)
                            $( ".mobile input" ).val(response.data.mobile)
                            $( "select[name='gender_id']" ).val(response.data.gender_id)
                            $( ".password , .password_confirmation" ).css('display','none')
                            // $( ".note" ).css('display','block')
                            $( ".mobile input , select[name='gender_id']" ).attr('disabled',true)
                        }else{
                            $( ".password , .password_confirmation" ).css('display','block')
                            // $( ".note" ).css('display','none')
                            $( ".mobile input , select[name='gender_id']" ).attr('disabled',false)
                            $( ".mobile input" ).val('')
                            $( "select[name='gender_id']" ).val('-1')

                        }
                    });
            });

    </script>
    @endsection
@endif
