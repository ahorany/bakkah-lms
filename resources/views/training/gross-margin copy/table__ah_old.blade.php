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
            {!!Builder::TableAllPosts($count, $carts->count())!!}
            <a class="btn btn-success float-right mx-3" id="export-btn" href="{{route('training.carts.export')}}">Export Results</a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-condensed" style="font-size: 14px;">
            <thead>
            <tr>
                <th class="">CID</th>
                <th class="" style="width:400px;">{{__('admin.title')}}</th>
                <th class="" style="width:400px;">{{__('admin.user')}}</th>
                <th class="">{{__('admin.table')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($carts as $post)
                <tr data-id="{{$post->id}}">
                    <td>
                        <span class="td-title"><a href="{{route('crm::products-demand.show', $post->id)}}">{{$post->id}}</a></span>
                    </td>
                    <td>
                        <span style="display: block;">{{$post->trainingOption->training_name??null}}</span>
                        <?php $arge = 'Empty'; ?>
                        @if(isset($post->payment->paid_in) && $post->payment->payment_status!=68)
                            <?php $arge = 'Destroy'; ?>
                        @endif
                        {!!Builder::BtnGroupRows($post->trainingOption->training_name??null, $post->id, [$arge], [
                           'post'=>$post->id,
                        ])!!}
                        <span style="color: #999; font-size: 12px;">
                            {{$post->session->published_from??null}} - {{$post->session->published_to??null}}<br>
                            SID: {{$post->session->id??null}}
                        </span>

                        @if(isset($post->cartMaster->id) && $post->cartMaster->id != null)
                            <br>
                            <span style="color: #999; font-size: 12px;">
                                Master ID {{$post->cartMaster->id??null}}
                            </span>
                        @endif

                        @if(isset($post->cartMaster->type->id) && $post->cartMaster->type->id != 374)
                            <?php
                            $class = [
                                373 => 'warning',
                                372 => 'info',
                                // 373 => 'danger',
                                68 => 'success',
                                // 332 => 'dark'
                            ];
                            ?>
                            <div class="card card-default mt-2 col-md-auto">
                                {{-- @if(auth()->user()->role_id==3){
                                    <div class="card-header p-2"><a  class="mb-0 align-self-center btn btn-sm btn-primary" href="{{env('APP_URL').'crm/group_invoices/'.$post->cartMaster->id.'/edit'}}" download="" target="_blank" ><span>{{__('admin.edit_order')}}</span></a></div>
                                @endif --}}
                                <div class="card-body p-2">
                                    <span class="badge badge-{{$class[$post->cartMaster->type->id]??null}}">
                                        {{$post->cartMaster->type->trans_name??null}}
                                    </span>

                                    <br>
                                    <span style="color: #999; font-size: 12px;">
                                        {{$post->cartMaster->rfpGroup->organization->en_title??null}}
                                        <br>
                                        {{$post->cartMaster->invoice_number??null}}
                                    </span>

                                    <?php
                                        $class = [
                                            357 => 'success', //Paid
                                            358 => 'dark', //PO
                                            356 => 'info', //Invoice
                                            355 => 'warning', //Pending
                                            359 => 'danger', //Cancel
                                        ];
                                    ?>
                                    @if($post->cartMaster->status_id)
                                        <br>
                                        <span class="badge badge-{{$class[$post->cartMaster->status_id]??null}}">
                                            {{$post->cartMaster->status->trans_name??null}}
                                        </span>
                                    @endif
                                </div>

                            </div>

                        @endif
                    </td>
                    <td>
                        @include('training.carts.table-parts.user', ['post'=>$post])
                    </td>
                    <td class="price-card">
                        @include('training.carts.table-parts.register', ['post'=>$post])
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- /.card-body -->
{{ $carts->render() }}
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
