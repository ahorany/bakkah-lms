@extends(ADMIN.'.general.index')

@section('table')

    {{-- @include('training.'.$folder.'.search') --}}

	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
	{{Builder::SetPrefix('training.')}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('prepayments')}}

	<div class="cart-table">
	    @include('training.'.$folder.'.table')
    </div>
	{{-- @include('training.carts.table') --}}

@endsection
