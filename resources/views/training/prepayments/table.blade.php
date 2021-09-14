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

                {{-- Abdullah, Hani, Tamer, Tariq --}}
                @if(auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==5074 || auth()->user()->id==7085 )
                    <a class="btn-warning btn-sm float-right mx-3" target="_blank" id="export-btn-xero" href="{{route('xero.authorization', ['post_type'=>'prepayments'])}}"><i class="fa fa-fa-solid fa-file-export"></i> Prepayments Run</a>
                @endif

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
                                    {{json_decode($cartMaster->type->name)->en??null}}
                                </span>
                            @endif

                            {{-- $arge = 'Empty';
                            @if(is_null($cartMaster->paid_in) && $cartMaster->payment_status_from_payment==63)
                                $arge = 'Destroy';
                            @endif
                            {!!Builder::BtnGroupRows(Lang::TransTitle($cartMaster->type_name??null), $cartMaster->id, [$arge], [
                               'post'=>$cartMaster->id,
                            ])!!} --}}

                            {{-- @if(!is_null($cartMaster->wp_migrate))
                                <span class="d-block badge badge-dark mt-2">
                                    OLD DATA
                                </span>
                            @endif --}}

                        </td>
                        <td style="width: 50%;">
                            {{-- @include('training.carts.table-parts.user', ['cartMaster'=>$cartMaster]) --}}
                            @include('training.'.$folder.'.user', ['cartMaster'=>$cartMaster])
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
        jQuery('.page-link').click(function (){
            $(this).html('Loading...');
        });
    });
</script>
