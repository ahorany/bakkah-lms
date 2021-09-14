<style>
    .price-card .title {
        width: 150px;
    }
    span.value {
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
<div class="card">
    <div class="card-header">
        {!!Builder::BtnGroupTable(false)!!}

        <div class="float-right d-inline-flex align-items-center justify-content-between">
            {!!Builder::TableAllPosts($count, $cartMasters->count())!!}
            <a class="btn btn-success float-right mx-3 d-none" id="export-btn" href="{{route('training.carts.export')}}">Export Results</a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-condensed" style="font-size: 14px;">
            <thead>
            <tr>
                <th class="">Inv.ID</th>
                <th class="" style="width:400px;">{{__('admin.user')}}</th>
                <th class="" style="width:400px;">{{__('admin.title')}}</th>
                {{-- <th class="">{{__('admin.table')}}</th> --}}
            </tr>
            </thead>
            <tbody>
            @foreach($cartMasters as $cartMaster)
                <tr data-id="{{$cartMaster->id}}" style="border-top: 2px solid #a1a1a1;">
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
                                {{$cartMaster->type->trans_name??null}}
                            </span>
                        @endif

                        <?php $arge = 'Empty'; ?>
                        @if(isset($cartMaster->payment->paid_in) && $cartMaster->payment->payment_status!=68)
                            <?php $arge = 'Destroy'; ?>
                        @endif
                        {!!Builder::BtnGroupRows($cartMaster->type->trans_name??null, $cartMaster->id, [$arge], [
                           'post'=>$cartMaster->id,
                        ])!!}

                    </td>
                    <td class="">
                        @include('training.carts.table-parts.user', ['cartMaster'=>$cartMaster])
                    </td>
                    <td>
                        @include('training.carts.table-parts.courses', ['cartMaster'=>$cartMaster])
                    </td>
                    {{-- <td class="price-card vtop">
                        @include('training.carts.table-parts.register', ['post'=>$cartMaster])
                    </td> --}}
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- /.card-body -->
{{ $cartMasters->render() }}
{{-- {{ $cartMasters->appends(['post_type' => $post_type??null])->render() }} --}}

<script>
jQuery(function (){
    jQuery('.page-link').click(function (){

        var btnName = jQuery(this);
        var page = jQuery(this).data('page');
        AjaxSearch(btnName, page);
        return false;
    });

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

    jQuery('tr').hover(function(){
        var data_id = $(this).attr('data-id');
        jQuery('.BtnGroupRows[data-id="'+data_id+'"]'+' .btn-table').css('visibility', 'visible');
    }, function(){
        var data_id = $(this).attr('data-id');
        jQuery('.BtnGroupRows[data-id="'+data_id+'"]'+' .btn-table').css('visibility', 'hidden');
    });

    jQuery('[name="delete"], [name="restore"]').click(function(){

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
                jQuery('tr[data-id="'+id+'"]').remove();
            }
            ,errors:function(er){
                console.log(er);
            }
        });
        return false;
    });

    jQuery('#export-btn').on('click', function(e) {
        e.preventDefault();
        var form = jQuery('#cart-search');
        var url = jQuery(this).attr('href');
        var url_data = form.serialize();
        var full_url = url + '?' + url_data;
        window.open(full_url, 'blank');
    })
});
</script>
