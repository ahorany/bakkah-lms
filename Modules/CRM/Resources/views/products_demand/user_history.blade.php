<style>

tr.active {
    background-color: #cfffc2 !important;
}
</style>
<div class="card mt-5">
    <div class="card-header">
        <h6 class="mb-0 float-left"><i class="fas fa-history" aria-hidden="true"></i> Customer Order's History</h6>
        <h6 class="mb-0 float-right" style="text-transform: lowercase">{{$cart->userId->email}}</h6>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped table-hover table-total-info">
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 35%">Title</th>
                <th>Date</th>
                <th>Status</th>
                <th>Order Date</th>
            </tr>

            @foreach ($carts as $cart)
            <tr {{ (strpos(request()->path(), '/'. $cart->id . '/') !== false) ? 'class=active' : '' }}>
                <td><a href="{{route('crm::products-demand.show', $cart->id)}}">{{$cart->id}}</a></td>
                <td>{{$cart->trainingOption->training_name??null}}</td>
                <td>{{$cart->session->published_from??null}} - {{$cart->session->published_to??null}}</td>
                <td>
                    @if(isset($cart->payment->paid_in))
                        <span class="badge {{($cart->payment->payment_status==68)?'badge-success':'badge-danger'}}">
                            {{$cart->payment->paymentStatus->trans_name??null}}
                        </span><br>
                    @endif
                </td>
                <td>{{$cart->registered_at}}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
