@extends(ADMIN.'.general.index')

@section('table')
<div style="background-color:white;">
    <div class="row">
        <div class="col-md-6" >
            <form action="{{ route('training.importCourses') }}" method="POST" enctype="multipart/form-data" class="col-md-5">
                @csrf
                    {!!Builder::File('file', 'file', null, ['col'=>'col-md-8'])!!}
                    {!!Builder::Submit('importCourses', 'importCourses', 'btn-success mx-1 export-btn py-1 px-2', null, [
                        'icon'=>'far fa-file-excel',
                    ])!!}
                    <a href="{{CustomAsset('samples/courses.xlsx')}}" download class="btn btn-warning btn-md" role="button"> Sample </a>
            </form>
        </div>
        <div class="col-md-6" >
            <form action="{{ route('training.importUsers') }}" method="POST" enctype="multipart/form-data" class="col-md-5">
                @csrf
                    {!!Builder::File('file', 'file', null, ['col'=>'col-md-8'])!!}
                    {!!Builder::Submit('importUsers', 'importUsers', 'btn-success mx-1 export-btn py-1 px-2', null, [
                        'icon'=>'far fa-file-excel',
                    ])!!}
                    <a href="{{CustomAsset('samples/users.xlsx')}}" download class="btn btn-warning btn-md" role="button"> Sample </a>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6" >
            <form action="{{ route('training.importUsersCourses') }}" method="POST" enctype="multipart/form-data" class="col-md-5">
                @csrf
                    {!!Builder::File('file', 'file', null, ['col'=>'col-md-8'])!!}
                    {!!Builder::Submit('importUsersCourses', 'importUsersCourses', 'btn-success mx-1 export-btn py-1 px-2', null, [
                        'icon'=>'far fa-file-excel',
                    ])!!}
                    <a href="{{CustomAsset('samples/users-courses.xlsx')}}" download class="btn btn-warning btn-md" role="button"> Sample </a>
            </form>
        </div>
        <div class="col-md-6" >
            <form action="{{ route('training.importUsersGroups') }}" method="POST" enctype="multipart/form-data" class="col-md-5">
                @csrf
                {!!Builder::File('file', 'file', null, ['col'=>'col-md-8'])!!}
                {!!Builder::Submit('importUsersGroups', 'importUsersGroups', 'btn-success mx-1 export-btn py-1 px-2', null, [
                    'icon'=>'far fa-file-excel',
                ])!!}
                <a href="{{CustomAsset('samples/users-groups.xlsx')}}" download class="btn btn-warning btn-md" role="button"> Sample </a>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6" >
            <form action="{{ route('training.importQuestions') }}" method="POST" enctype="multipart/form-data" class="col-md-5">
                @csrf
                    {!!Builder::File('file', 'file', null, ['col'=>'col-md-8'])!!}
                    {!!Builder::Submit('importQuestions', 'import_questions', 'btn-success mx-1 export-btn py-1 px-2', null, [
                        'icon'=>'far fa-file-excel',
                    ])!!}
                    <a href="{{CustomAsset('samples/examQuestionsAnswers.xlsx')}}" download class="btn btn-warning btn-md" role="button"> Sample </a>
            </form>
        </div>
    </div>
</div>
@endsection
