@extends(FRONT.'.education.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(72)??null])
@endsection

@section('content')

    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-4 user-info">
                        <h4><i class="far fa-money-bill-alt"></i> {{ __('education.Payment Info') }}</h4>
                        <table class="table table-hover my-4 text-center">
                            <thead>
                              <tr>
                                <th scope="col">{{ __('education.id') }}</th>
                                <th scope="col">{{ __('education.paid_in') }}</th>
                                <th scope="col">{{ __('education.coin_id') }}</th>
                                <th scope="col">{{ __('education.payment_status') }}</th>
                                {{-- <th scope="col">{{ __('education.post_year') }}</th> --}}
                                <th scope="col">{{ __('education.paid_at') }}</th>
                                {{-- <th scope="col">{{ __('education.Description') }}</th> --}}
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                @php
                                    if($payment->payment_status == 68){
                                        $status = 'badge badge-success';
                                    }elseif ($payment->payment_status == 63) {
                                        $status = 'badge badge-danger';
                                    }
                                    // $date = $payment->paid_at;
                                    // $date = $date->format('m/d/Y');
                                @endphp
                                <tr data-id="{{$payment->id}}">
                                    <td scope="row">{{$loop->iteration}}</td>
                                    <td>{{$payment->paid_in}}</td>
                                    {{-- <td>{{$payment->coin->trans_name??null}}</td> --}}
                                    <td>{{$payment->cartMaster->coin->trans_name??null}}</td>
                                    <td><span class="">{{$payment->paymentStatus->trans_name}}</span></td>
                                    {{-- <td>{{$payment->post_year}}</td> --}}
                                    <td dir="ltr">{{$payment->paid_at}}</td>
                                    {{-- <td>{{$payment->description}}</td> --}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
