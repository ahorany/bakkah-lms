@extends(ADMIN.'.general.index')

@section('table')
	@include('training.'.$folder.'.search')

    <div class="cart-table">
	    @include('training.'.$folder.'.table')
    </div>
@endsection
