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

@section('table')
    {{-- <a class="d-none" href="{{route('training.carts.export')}}">Export</a> --}}

    @include('training.'.$folder.'.search')

    <div class="cart-table">
	    @include('training.'.$folder.'.table')
    </div>
@endsection
