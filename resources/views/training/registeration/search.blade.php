<form method="get" id="cart-search"  action="{{route('training.carts.registeration')}}">
	<div class="row">
	<div class="col-md-12">
		<div class="card card-default">

		    <div class="card-body px-2">
		      <div class="container-fluid">
		        <div class="row">

					{!! Builder::Hidden('page', request()->page??1) !!}
                    {!! Builder::Hidden('post_type', $post_type) !!}
                            {!! Builder::Hidden('trash') !!}
		        	{!!Builder::Hidden('post_type', request()->post_type??'departments')!!}
		        	@include('training.carts.components.course_combo')


                    {!! Builder::Select('training_option_id', 'training_option_id', $delivery_methods, request()->training_option_id, [
                        'col'=>'col-md-6',
                    ]) !!}

					{!! Builder::Select('with_vat', 'with_vat', $vats, request()->with_vat, [
                        'col'=>'col-md-6',
                    ]) !!}


					<div class="col-md-6 session_from">
					{!! Builder::input('session_from', 'session_from', request()->session_from, []) !!}
					</div>
					{!! Builder::input('session_to', 'session_to', request()->session_to, ['col'=>'col-md-6']) !!}

					{!!Builder::Select('type_id', 'Training Type', $types, request()->type_id, ['col'=>'col-md-6'])!!}
                    <div class="col-12">
                        {!!Builder::Submit('search', 'search', 'btn-primary', 'search')!!}
                        {!!Builder::Reset('clear', 'clear', 'btn-default', 'clear')!!}
                    </div>

		        </div>
		      </div>
		    </div>

		</div>

        @yield('seo')

	</div>

</div>
</form>

@include('training.carts.components.vueCode')

{{-- <script src="{{CustomAsset(FRONT.'-dist/monthly registeration/bootstrap-datepicker.min.js')}}"></script> --}}
{{-- <script src="{{CustomAsset(FRONT.'-dist/monthly registeration/bootstrap-datepicker.js')}}"></script> --}}
{{-- <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/monthly registeration/datepicker.min.css')}}"> --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

<style>
.datepicker.datepicker-dropdown.dropdown-menu.datepicker-orient-left.datepicker-orient-bottom ,
.datepicker.datepicker-dropdown.dropdown-menu.datepicker-orient-left.datepicker-orient-top{
	width: 20% !important;
}
</style>

@section('js')
<script type="text/javascript">

$("input[name='session_from'],input[name='session_to']").datepicker( {
    format: "mm-yyyy",
    startView: "months",
    minViewMode: "months",

});

</script>
@endsection
