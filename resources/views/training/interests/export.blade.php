<table>
    <tr>
        <th>UserId</th>
        <th style="width:15px;">Invoice Number</th>
        <th style="width:40px;">Course Title</th>
        <th style="width:25px;">Name</th>
        <th style="width:20px;">Email</th>
        <th style="width:15px;">Mobile</th>
        <th style="width:15px;">Price</th>
        <th style="width:15px;">Discount</th>
        <th style="width:15px;">Exam Price</th>
        <th style="width:15px;">Pract. Exam Price</th>
        <th style="width:15px;">Take2 Price</th>
        <th style="width:15px;">Exam Simulation Price</th>
        <th style="width:15px;">Book Price</th>
        <th style="width:15px;">SubTotal</th>
        <th style="width:15px;">VAT</th>
        <th style="width:15px;">Total</th>
        <th style="width:15px;">Paid</th>
        <th style="width:15px;">Paid Status</th>
        <th style="width:15px;">Date</th>
    </tr>
    @foreach($carts as $post)
    <tr>
        <td>{{$post->user_id}}</td>
        <td>{{$post->invoice_number}}</td>
        <td>{{$post->trainingOption->training_name}}</td>
        <td>{{$post->userId->trans_name}}</td>
        <td>{{$post->userId->email}}</td>
        <td>"{{$post->userId->mobile}}"</td>
        <td>{{$post->price}}</td>
        <td>{{$post->discount_value}}</td>
        <td>{{$post->exam_price}}</td>
        <td>{{$post->pract_exam_price}}</td>
        <td>{{$post->take2_price}}</td>
        <td>{{$post->exam_simulation_price}}</td>
        <td>{{$post->book_price}}</td>
        <td>{{$post->total}}</td>
        <td>{{$post->vat_value}}</td>
        <td>{{$post->total_after_vat}}</td>
        <td>{{$post->payment->paid_in??0}}</td>
        <td>
            @if(isset($post->payment->paid_in) && $post->payment->paid_in!=0)
                {{$post->payment->paymentStatus->trans_name??null}}
            @endif
        </td>
        <td>{{$post->created_at->isoFormat('D MMM Y')}}</td>
    </tr>
    @endforeach
</table>
