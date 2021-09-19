@extends(ADMIN.'.general.index')

{{-- <script>
    function AjaxSearch(btnName, page=1){

        var btnHtml = btnName.html();
        btnName.html('Loading...');

        jQuery('[name="page"]').val(page);
        var form = jQuery('#cart-search');
        jQuery.ajax({
            type:'get',
            url:form.attr('action'),
            data:form.serialize(),
            success:function(data){
                jQuery('.cart-table').html(data);
                btnName.html(btnHtml);
            }
            ,errors:function(er){
                console.log(er);
            }
        });
    }
</script> --}}
{{-- @if(Session::has('msg'))
	<div style="direction: ltr;" class="alert alert-{{Session::get('class')}} alert-dismissible" role="alert"><!-- fade show-->
	  <div>
		  <strong>{{Session::get('title')}} </strong> {{session('msg')}}
	  </div>
	  <button type="button" class="close" data-dismiss="alert">
		<span aria-hidden="true">&times;</span>
	  </button>
	</div>
@endif --}}
{{-- @include(ADMIN.'.Html.alert'); --}}
@section('table')
    {{-- <a class="d-none" href="{{route('training.carts.export')}}">Export</a> --}}
    @include('training.'.$folder.'.search')

    <div class="cart-table">
	    @include('training.'.$folder.'.table')
    </div>
@endsection
