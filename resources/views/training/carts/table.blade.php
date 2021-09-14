<style>
/* price-card */
.title {
    width: 250px;
}
.value {
    font-weight: bold;
    font-size: 12px;
    color: #666;
}
.table-total-info {
    font-size: 14px;
}
.table-total-info td {
    padding-left: 1rem !important;
}
.paid-value {
    font-size: 13px; font-weight: normal;
}
.vtop{
    vertical-align: top !important;
}
</style>
{{Builder::SetTrash($trash)}}
{{Builder::SetPostType('cart')}}
{{Builder::SetFolder('carts')}}
{{Builder::SetObject('cart')}}
{{Builder::SetNameSpace('training.')}}

<?php use App\Helpers\Lang; ?>

<div class="card">
    <div class="card-header">

        {!!Builder::BtnGroupTable(false)!!}

        <div class="float-right d-inline-flex align-items-center justify-content-between">

            {{-- {!!Builder::TableAllPosts($count, $cartMasters->count())!!} --}}
            <a class="btn btn-success float-right mx-3 export-btn" id="exportYes-btn" target="_blank" href="{{route('training.carts.exportYes')}}">Export Results</a>

            <a class="btn btn-success float-right mx-3 pl-1 export-btn" id="exportNo-btn" target="_blank" href="{{route('training.carts.exportNo')}}">Export Results without features</a>

            {!!Builder::TableAllPosts($totalCount, $count)!!}
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-condensed" style="font-size: 14px;">
            <thead>
            <tr>
                <th style="width:200px">Inv.ID</th>
                <th >{{__('admin.user')}}</th>
                <th class="">{{__('admin.financial')}}</th>
                {{-- <th class="">{{__('admin.table')}}</th> --}}
            </tr>
            </thead>
            <tbody>
        {{-- @if(is_array($cartMasters) && $count >1 ) --}}
            @forelse($cartMasters as $cartMaster)
                
                <tr class="master_id" data-id="{{$cartMaster->id}}" style="border-top: 2px solid #a1a1a1;">
                    <td class="text-center">
                        <span class=""><a target="_blank" href="{{route('crm::products-demand.show', $cartMaster->id)}}">{{$cartMaster->id}}</a></span>
                        @if(isset($cartMaster->type_id))
                            <?php
                            $class = [
                                370 => 'dark', // B2B
                                372 => 'info', // Group
                                373 => 'warning', // RFQ
                                374 => 'success', // B2C
                                // 373 => 'danger',
                            ];
                            ?>
                            <span class="d-block badge badge-{{$class[$cartMaster->type_id]??null}}">
                                {!!Lang::TransTitle($cartMaster->type_name??null)!!}
                            </span>
                        @endif

                        <?php
                            $btns = [];
                                array_push($btns, 'Destroy');
                        ?>
                        @if ($cartMaster->type_id == 374 && $cartMaster->payment_status == 63)
                            {!!Builder::BtnGroupRows($cartMaster->id??null, $cartMaster->id, $btns, [
                                'post'=>$cartMaster->id,
                            ])!!}
                        @endif

                    </td>
                    <td style="width: 50%;">
                        @include('training.carts.table-parts.user', ['cartMaster'=>$cartMaster])
                    </td>
                    <td>
                        @include('training.carts.table-parts.register', ['cartMaster'=>$cartMaster])
                    </td>
                    {{-- <td>
                        @include('training.carts.table-parts.courses', ['cartMaster'=>$cartMaster])
                    </td> --}}
                </tr>
            @empty
                <tr><td colspan="3" class="p-3 mb-2 bg-danger text-white font-weight-bold">No Orders</td></tr>
            @endforelse
        {{-- @endif --}}
            </tbody>
        </table>
    </div>
</div>
<!-- /.card-body -->

{{$paginator->render()}}

<script>
jQuery(function (){

    function AjaxSearch(btnName, page=1){

        var btnHtml = btnName.html();
        btnName.html('Loading...');

        jQuery('[name="page"]').val(page);
        var form = jQuery('#cart-search');
        console.log(form.attr('action'));
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

    jQuery('.page-link').click(function (){
        var btnName = jQuery(this);
        //var page = jQuery(this).data('page');
        var href = getUrlVars( jQuery(this).attr('href') );
        var page = href['page'];
        AjaxSearch(btnName, page);
        return false;
    });

    function getUrlVars(url)
    {
        var vars = [], hash;
        var hashes = url.slice(url.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }

    jQuery('[name="trash"], [name="list"]').click(function (){

        var btnName = jQuery(this);
        var page = jQuery(this).data('page');
        var name = btnName.attr('name');

        if(name=='trash') {
            jQuery('[name="trash"]').val('trash');
        }
        else{
            jQuery('[name="trash"]').val('');
        }

        AjaxSearch(btnName, page);
        return false;
    });

    // jQuery('tr').hover(function(){
    jQuery('.master_id').hover(function(){
        var data_id = $(this).attr('data-id');

        jQuery('.btn-delete[data-id="'+data_id+'"]'+' .btn-table').css('visibility', 'visible');
        // jQuery('.BtnGroupRows[data-id="'+data_id+'"]'+' .btn-table').css('visibility', 'visible');
    }, function(){
        var data_id = $(this).attr('data-id');
        jQuery('.btn-delete[data-id="'+data_id+'"]'+' .btn-table').css('visibility', 'hidden');
        // jQuery('.BtnGroupRows[data-id="'+data_id+'"]'+' .btn-table').css('visibility', 'hidden');
    });

    jQuery('[name="delete"], [name="restore"]').click(function(){
        var frm = confirm('Sure To Delete?');
        if(frm){
            var form = jQuery(this);
            var btnHtml = form.html();
            var id = form.data('id');
            if(form.attr('name')=='delete') {
                jQuery('[name="delete_btn"][data-id="' + id + '"]').html('Removing...');
            }else{
                jQuery('[name="restore_btn"][data-id="' + id + '"]').html('Restoring...');
            }


                jQuery.ajax({
                    type:'post',
                    url:form.attr('action'),
                    data:form.serialize(),
                    success:function(data){
                        // console.log(data);
                        if(data='deleted'){
                            jQuery('tr[data-id="'+id+'"]').remove();
                        }
                    }
                    ,errors:function(er){
                        console.log(er);
                    }
                });
        }
        return false;
    });

    jQuery('.export-btn').on('click', function(e) {
        e.preventDefault();
        var form = jQuery('#cart-search');
        var url = jQuery(this).attr('href');
        var url_data = form.serialize();
        var full_url = url + '?' + url_data;
        window.open(full_url, 'blank');
    })

});
</script>
