@extends(ADMIN.'.general.edit')

{{Builder::SetEloquent($eloquent)}}
{{Builder::SetObject('interesting')}}

@section('edit')

	@include(ADMIN.'.'.$folder.'.form')

@endsection
