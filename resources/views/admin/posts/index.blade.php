@extends(ADMIN.'.general.index')

@section('table')

@include(ADMIN.'.'.$folder.'.search')

{{Builder::SetPostType($post_type)}}
{{Builder::SetTrash($trash)}}
{{Builder::SetFolder($folder)}}
{{Builder::SetObject('post')}}

@include(ADMIN.'.'.$folder.'.table')

@endsection
