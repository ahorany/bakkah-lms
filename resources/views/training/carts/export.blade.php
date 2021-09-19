<?php
use App\Helpers\Lang;
$i=1;
?>
{{-- @dd($withFeatures) --}}
<table>
    <tr>
        <th>#</th>
        <th>UserId</th>
        <th style="width:25px;">Name</th>
        <th style="width:20px;">Email</th>
        <th style="width:15px;">Mobile</th>
        <th style="width:15px;">Gender</th>
        <th style="width:20px;">Job Title</th>
        <th style="width:20px;">Company</th>
        <th style="width:15px;">Invoice Number</th>
        <th style="width:15px;">Currency</th>
        <th style="width:15px;">Invoice SubTotal</th>
        <th style="width:15px;">Invoice VAT</th>
        <th style="width:15px;">Invoice Total</th>
        <th style="width:15px;">Paid Amount</th>
        <th style="width:15px;">Paid Status</th>
        <th style="width:15px;">Paid Date</th>
        <th style="width:15px;">Reg. Date</th>
        <th style="width:15px;">Xero Prepayment</th>
        <th style="width:15px;">Xero Prepayment Date</th>
        <th style="width:15px;">Xero Invoice</th>
        <th style="width:15px;">Xero Invoice Date</th>
        <th style="width:15px;">Xero Item Code</th>
        <th style="width:15px;">Course Title</th>
        <th style="width:15px;">Delivery Option</th>
        <th style="width:15px;">Session Date</th>
        <th style="width:15px;">Session ID</th>
        <th style="width:15px;">Category</th>
        <th style="width:15px;">Partner</th>
        <th style="width:15px;">Price</th>
        <th style="width:15px;">Discount</th>
        <th style="width:15px;">Price After Discount</th>
        <th style="width:15px;">Exam Price</th>
        <th style="width:15px;">Practitioner Exam Price</th>
        <th style="width:15px;">Take2</th>
        <th style="width:15px;">Exam Simulation</th>
        <th style="width:15px;">Book</th>
        <th style="width:15px;">Item SubTotal</th>
        <th style="width:15px;">Item VAT</th>
        <th style="width:15px;">Item Total</th>
        <th style="width:15px;">Item Invoice Number</th>
        <th style="width:15px;">Item Status</th>
        <th style="width:15px;">Item Attend Type</th>
        <th style="width:15px;">Item Date</th>
    </tr>
    @foreach($cartMasters as $cartMaster)
    <?php
        // use App\Helpers\Lang;
        // $master_id__field = 'master_id';
        // if($cartMaster->type_id==372){
        //     request()->post_type = 'group_invoices';
        //     $master_id__field = 'group_invoice_id';
        // }
        // $carts = App\Models\Training\Cart::where("$master_id__field", $cartMaster->id)
        $carts = App\Models\Training\Cart::where("master_id", $cartMaster->id)
        ->whereNull('trashed_status')
        ->whereNull('deleted_at')
        ->with(['session'=>function($query){ $query->withTrashed(); }])
        ->get();
    ?>


        @foreach($carts as $cart)
            <tr>
                <td>{{$i}}</td>
                <?php $i++; ?>
                <td>{{$cartMaster->user_id}}</td>
                <td>{!!Lang::TransTitle($cartMaster->name??null)!!}</td>
                <td>{{$cartMaster->email??null}}</td>
                <td>"{{$cartMaster->mobile??null}}"</td>
                <td>{!!Lang::TransTitle($cartMaster->gender??null)!!}</td>
                <td>{{$cartMaster->job_title??null}}</td>
                <td>{{$cartMaster->company??null}}</td>
                <td>{{$cartMaster->invoice_number}}</td>
                <td>{!!Lang::TransTitle($cartMaster->coin_name??null)!!}</td>
                <td>{{$cartMaster->total}}</td>
                <td>{{$cartMaster->vat_value}}</td>
                <td>{{$cartMaster->total_after_vat}}</td>
                <td>{{$cartMaster->paid_in??0}}</td>
                <td>
                    @if(isset($cartMaster->paid_in) && $cartMaster->paid_in!=0)
                        {!!Lang::TransTitle($cartMaster->payment_status_name??null)!!}
                    @endif
                </td>
                <td>{{$cartMaster->payment_paid_at??null}}</td>
                <td>{{$cartMaster->registered_at??$cartMaster->created_at}}</td>
                <td>{{$cartMaster->xero_prepayment}}</td>
                <td>{{$cartMaster->xero_prepayment_created_at??null}}</td>

                <td>{{$cart->xero_invoice}}</td>
                <td>{{$cart->xero_invoice_created_at??null}}</td>

                <?php
                    $array = [
                        11=>'-SS',
                        13=>'-OT',
                        353=>'',
                    ];
                // return $cart->course->xero_code.$array[$cart->trainingOption->constant_id];
                ?>
                <td>{{$cart->course->xero_code.$array[$cart->trainingOption->constant_id??null]??null}}</td>
                <td>{{$cart->course->trans_title??null}}</td>
                <td>{{$cart->trainingOption->constant->trans_name??null}}</td>
                @if ($cart->trainingOption->constant->id == 13 || $cart->trainingOption->constant->id == 383)
                    <td>{{$cart->session->published_from??null}} - {{$cart->session->published_to??null}}</td>
                @else
                    <td></td>
                @endif
                <td>{{$cart->session_id}}</td>
                <td>{{$cart->course->postMorph->constant->trans_name??null}}</td>
                <td>{{$cart->course->partner->trans_name??null}}</td>
                <td>{{$cart->price}}</td>
                <td>{{$cart->discount_value}}</td>
                <td>{{$cart->price-$cart->discount_value}}</td>
                <td>{{$cart->exam_price}}</td>
                <td>{{$cart->pract_exam_price}}</td>
                <td>{{$cart->take2_price}}</td>
                <td>{{$cart->exam_simulation_price}}</td>
                <td>{{$cart->book_price}}</td>
                <td>{{$cart->total}}</td>
                <td>{{$cart->vat_value}}</td>
                <td>{{$cart->total_after_vat}}</td>
                <td>{{$cart->invoice_number??null}}</td>
                <td>{{$cart->paymentStatus->trans_name??null}}</td>
                <td>{{$cart->attendType->trans_name??null}}</td>
                <td>{{$cart->registered_at??$cart->created_at}}</td>

                {{-- <td>{{$cartMaster->created_at->isoFormat('D MMM Y')}}</td> --}}
                {{-- <td>{{$cartMaster->post_year}}</td> --}}
            </tr>
            @if ($withFeatures)
                @foreach($cart->cartFeatures as $cartFeature)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                        @if($cartFeature->trainingOptionFeature->feature->id > 0 && !empty($cartFeature->trainingOptionFeature->xero_feature_code))

                            <td>{{$cartFeature->trainingOptionFeature->xero_feature_code??null}}</td>
                            <td>
                                {{$cartFeature->trainingOptionFeature->feature->en_title??null}}
                                @if ($cartFeature->trainingOptionFeature->feature->id == 5)
                                     ({{json_decode($cartFeature->trainingOptionFeature->excerpt??'')->en??null}})
                                @endif
                            </td>

                            {{-- Exam Voucher --}}
                            {{-- <td>{{$cart->course->xero_exam_code??null}}</td> --}}
                            {{-- <td>Exam Voucher</td> --}}
                        {{-- @elseif($cartFeature->trainingOptionFeature->feature->id == 2 && !empty($cartFeature->cart->trainingOption->ExamSimulation->course->xero_code)) --}}
                            {{-- Exam Simulation --}}
                            {{-- <td>{{$cartFeature->cart->trainingOption->ExamSimulation->course->xero_code??null}}</td>
                            <td>Exam Simulation</td> --}}

                        {{-- @elseif($cartFeature->trainingOptionFeature->feature->id == 3) --}}
                            {{-- Take2 Exam --}}
                            {{-- <td>Take2 Exam</td>
                            <td>Take2 Exam</td> --}}

                        @else
                            <td></td>
                            <td></td>
                        @endif

                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{$cartFeature->price}}</td>
                        <td></td>
                        <td></td>
                        <td>Feature Details</td>
                        <td>Feature Details</td>
                        <td>Feature Details</td>
                        <td>Feature Details</td>
                        <td>Feature Details</td>
                        <td>{{$cartFeature->price}}</td>
                        <td>{{$cartFeature->vat_value}}</td>
                        <td>{{$cartFeature->total_after_vat}}</td>
                        <td></td>
                        <td>{{$cart->paymentStatus->trans_name??null}}</td>
                        <td></td>
                        <td>{{$cart->registered_at??$cart->created_at}}</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    @endforeach
</table>
