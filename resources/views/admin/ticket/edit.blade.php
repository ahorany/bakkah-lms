@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('ticket')}}
    @include('admin.'.$folder.'.form',['action' => 'edit'])


@endsection
