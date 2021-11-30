@extends('layouts.crm.index')

@section('table')
<div style="background-color:white;">
    <div class="row">
        <div class="col-md-6" >
            <div class="card p-3 mb-3">
                <form action="{{ route('training.importCourses') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        {!!Builder::File('file', 'file', null, [])!!}
                        {!!Builder::Submit('importCourses', 'importCourses', 'save btn btn-success export-btn', null, [
                            'icon'=>'far fa-file-excel',
                        ])!!}
                        <a href="{{CustomAsset('samples/courses.xlsx')}}" download class="btn info" role="button"> Sample </a>
                </form>
            </div>
        </div>

        <div class="col-md-6" >
            <div class="card p-3 mb-3">
                <form action="{{ route('training.importUsers') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        {!!Builder::File('file', 'file', null, [])!!}
                        {!!Builder::Submit('importUsers', 'importUsers', 'save btn btn-success export-btn', null, [
                            'icon'=>'far fa-file-excel',
                        ])!!}
                        <a href="{{CustomAsset('samples/users.xlsx')}}" download class="btn info" role="button"> Sample </a>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6" >
            <div class="card p-3 mb-3">
                <form action="{{ route('training.importUsersCourses') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        {!!Builder::File('file', 'file', null, [])!!}
                        {!!Builder::Submit('importUsersCourses', 'importUsersCourses', 'save btn btn-success export-btn', null, [
                            'icon'=>'far fa-file-excel',
                        ])!!}
                        <a href="{{CustomAsset('samples/users-courses.xlsx')}}" download class="btn info" role="button"> Sample </a>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 mb-3">
                <form action="{{ route('training.importUsersGroups') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {!!Builder::File('file', 'file', null, [])!!}
                    {!!Builder::Submit('importUsersGroups', 'importUsersGroups', 'save btn btn-success export-btn', null, [
                        'icon'=>'far fa-file-excel',
                    ])!!}
                    <a href="{{CustomAsset('samples/users-groups.xlsx')}}" download class="btn info" role="button"> Sample </a>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
