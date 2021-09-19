<div class="card">
    <div class="card-header">
        {!!Builder::BtnGroupTable()!!}
        {!!Builder::TableAllPosts($count, $discounts->count())!!}
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed light">
            <thead>
            <tr>
                <th class="">{{__('admin.index')}}</th>
                <th width="" class="">{{__('admin.name')}}</th>
                <th width="" class="">{{__('admin.currency')}}</th>
                <th class="">{{__('admin.code')}}</th>
                <th class="">{{__('admin.type')}}</th>
                <th class="d-none" width="45%" class="">{{__('admin.details')}}</th>
                <th class="">{{__('admin.country_id')}}</th>
                <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
            </tr>
            </thead>
            <tbody>
                {{-- @php
                    $count_duplicated = 1;
                @endphp --}}
            @foreach($discounts as $discount)

                <tr data-id="{{$discount->id}}">
                    <td>
                        <span class="td-title">{{$loop->iteration}}</span>
                    </td>
                    <td>

                        <span style="display: block;">{{$discount->trans_excerpt}}</span>

                        {!!Builder::BtnGroupRows($discount->trans_excerpt, $discount->id, ['Edit', 'Destroy', 'Trans','Replicate'], [
                           'post'=>$discount->id,
                        ])!!}

                    </td>
                    <td>
                        <span class="light" style="display: block;">{{$discount->coin->trans_name??null}}</span>
                    </td>
                    <td>
                        <span class="light" style="display: block;">{{$discount->code??$code}}</span>
                    </td>
                    <td>
                        {!! $discount->is_private==1?'<span class="badge badge-pill badge-danger">Private</span>':'<span class="badge badge-pill badge-success">Public</span>'!!}
                    </td>
                    <td class="d-none">
                        <table class="table table-condensed" style="width:100%;">
                            <thead>
                                <tr>
                                    <th width="50%">{{__('admin.course_id')}}</th>
                                    <th>{{__('admin.session_id')}}</th>
                                    <th>{{__('admin.percentage')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $DateTimeNow = DateTimeNow();
                                ?>
                                @foreach($discount->DiscountDetails as $DiscountDetail)
                                <?php
                                    $expired='';
                                    if($DiscountDetail->date_to < $DateTimeNow){
                                        $expired='expired';
                                    }
                                ?>
                                <tr data-tr="{{$DiscountDetail->id}}" class="{{$expired}}">
                                    <td style="white-space: normal;">{{$DiscountDetail->trainingOption->training_name??null}}</td>
                                    <td>{{$DiscountDetail->discount_interval??null}}</td>
                                    <td class="digit maxlength9">
                                        <mark>{{$DiscountDetail->value}}%</mark>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                    <td>
                        <ul>
                            @foreach($discount->countries as $country)
                                @if(isset($country->discounts))
                                    <?php
                                        $d = $country->discounts()->where('discount_id', $discount->id)->count();
                                    ?>
                                        @if($d!=0)
                                            <li>
                                                {{$country->trans_name??null}}
                                            </li>
                                        @endif
                                @endif
                            @endforeach
                        </ul>
                    </td>
                    <td class="d-none d-sm-table-cell">
                      <span class="author">
                        {!!$discount->published_at!!}
                      </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>
<script>
    function replicate(event){
        var frm = confirm('Sure To Replicate');
        if(!frm){
            event.preventDefault();
        }
    }
</script>
<!-- /.card-body -->
{{ $discounts->appends(['post_type' => $post_type??null, 'trash' => request()->trash??null])->render() }}
