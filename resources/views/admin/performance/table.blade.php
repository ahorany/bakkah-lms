<div class="card">
    <div class="card-header">
        @php
            $total_sar = 0;
            $total_usd = 0;
            $total_trainee_net = 0;
        @endphp
        <span class="ml-2 paid-value">
            @foreach ($products as $course)
                @php
                    $getSales = $course->getSales($course->id);
                @endphp
                @foreach ($getSales as $getSale)
                    @php
                        $total_trainee_net += $getSale['trainees_no'];
                        if($getSale['coin_id']==334){
                            $total_sar += $getSale['total_after_vat'];
                        }else
                            $total_usd += $getSale['total_after_vat'];
                    @endphp
                @endforeach
            @endforeach

            @if($total_sar || $total_usd || $total_trainee_net)
                <strong>Totals: </strong>
                @if($total_sar)
                    <span class="badge badge-success ml-2" style="font-size: 100%;">{{NumberFormatWithComma($total_sar)}} <small>SAR</small></span>
                @endif
                @if($total_usd)
                    <span class="badge badge-warning ml-2" style="font-size: 100%;">{{NumberFormatWithComma($total_usd)}} <small>USD</small> </span>
                @endif
                @if($total_trainee_net)
                    <span class="badge badge-dark text-white ml-2" style="font-size: 100%;">{{NumberFormatWithComma($total_trainee_net)}} <small>Trainees</small> </span>
                @endif
            @endif
        </span>
        {!!Builder::TableAllPosts($count, $products->count())!!}
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed text-center">
            <thead>
            <tr>
                <th class="">{{__('admin.index')}}</th>
                <th class="">{{__('admin.partner_name')}}</th>
                <th class="">{{__('admin.product_name')}}</th>
                <th class="">{{__('admin.sales')}}</th>
                <th class="">{{__('admin.status')}}</th>
                <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $course)
                <tr data-id="{{$course->id}}">
                    <td>
                        <span class="td-title">{{$loop->iteration}}</span>
                    </td>
                    <td>
                        <?php
                            $href_new = env('APP_URL');
                            $href_new .= '/admin/agreement/';
                            $href_new .= $course->partner->agreement->id;
                            $href_new .= '/edit?post_type=agreement';
                            $agreement_link = '<a target="_blank" href="'.$href_new.'" class="btn btn-sm text-primary"><span class="d-block">'.$course->partner->trans_name??null.'</span></a>';
                        ?>
                        <span style="display: block;">{!! $agreement_link !!}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$course->trans_title}}</span>
                    </td>
                    <td>
                        <?php
                            $total_trainees = 0;
                            $getSales = $course->getSales($course->id);
                        ?>
                        @foreach($getSales as $getSale)
                            <span class="d-block">
                                <span class="ml-2 paid-value badge {{($getSale['coin_id']==334)?'badge-success':'badge-warning'}}">
                                    {{($getSale['total_after_vat']!=0)?NumberFormatWithComma($getSale['total_after_vat']):0}} <small>{{($getSale['coin_id']==334) ? ' SAR' : ' USD'}}</small>
                                </span>
                                <span class="ml-2 paid-value badge badge-dark text-white">{{$getSale['trainees_no']}} Trainees</span>
                                <?php $total_trainees += $getSale['trainees_no']; ?>
                            </span>
                        @endforeach
                    </td>
                    <td>
                        @php
                            $t = '<i style="color: #0fc10f; font-size: 20px;" class="fas fa-check"></i>';
                            $f = '<i style="color: #f31f1f; font-size: 20px;" class="fas fa-times"></i>';
                        @endphp
                        <span style="display: block;">{!! ($total_trainees > 0) ? $t : $f !!}</span>
                    </td>
                    <td class="d-none d-sm-table-cell">
                      <span class="author">
                        {!!$course->partner->published_at!!}
                      </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
{{ $products->render() }}
