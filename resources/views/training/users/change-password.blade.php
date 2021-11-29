@extends('layouts.crm.master')

@section('content')

<form method="post" action="{{route('training.users.savePassword', auth()->user()->id)}}">

    @method('PATCH')
    <div class="col-md-12">
        @include('Html.alert')
        @include('Html.errors')

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{session('error')}}
        </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{session('success')}}
        </div>
        @endif

    </div>

    <div class="row">
        <div class="col-md-9">
            @csrf
            <div class="card card-default">

                <div class="card-body">
                  <div class="container-fluid">
                    <div class="row">

                        {!!Builder::Hidden('user_id', $eloquent->id??null)!!}
                        {!!Builder::Password('old_password', 'old_password', null, ['type'=>'password','col'=>'col-md-4'])!!}
                        {!!Builder::Password('password', 'password', null, ['type'=>'password','col'=>'col-md-4'])!!}
                        {!!Builder::Password('password_confirmation', 'password_confirmation', null, ['type'=>'password','col'=>'col-md-4'])!!}

                    </div>
                  </div>
                </div>

            </div>
            <!-- /.card -->
        </div>

        <div class="col-md-3">
            {!!Builder::BtnGroupForm()!!}
        </div>
    </div>

</form>

@stop
