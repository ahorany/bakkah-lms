@extends(FRONT.'.education.layouts.master')
@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(85)??null])
    @include(FRONT.'.social_scripts.learning.thanks')
@endsection

@section('content')

{{-- @dump(session()->get('zeroPaid'))
@dump(session()->get('code'))
@dump(session()->get('payment_status')) --}}

    @if(session()->has('zeroPaid'))
        <div class="alert alert-success m-5 p-5">
            <p style="text-align: center;"><strong>{{__('education.Your registration was completed successfully')}}</strong></p>
            <p style="text-align: center;">{{__('education.Your transaction has been completed')}}</p>
        </div>
    @else
        @if(session()->has('code'))
            @if(session()->get('payment_status')==68)
                {{-- @include(FRONT.'.social_scripts.learning.thanks') --}}
                <div class="alert alert-success m-5 p-5">
                    <h3 style="text-align: center;"><strong>{{__('education.Payment Done Successfully')}}</strong></h3>
                    <p style="text-align: center;"><strong>{{__('education.Your registration was completed successfully')}}</strong></p>
                    <p style="text-align: center;">{{__('education.Your transaction has been completed')}}</p>
                </div>
            @else
                <div class="alert alert-danger m-5 p-5 text-center">
                    <h3 style="text-align: center;"><strong>{{__('education.Payment Fail')}}</strong></h3>
                    <p style="text-align: center;"><strong>{{__('education.Your registration was not completed')}}</strong></p>
                    {{-- <p style="text-align: center;">{{session()->get('code')}}</p> --}}
                    <p style="text-align: center;">{{session()->get('description')}}</p>
                    <div class="error-img-wrapper">
                        <a class="btn btn-lg btn-primary px-5 py-3 mt-5" href="{{ route('education.index') }}">{{ __('education.Back To Home') }}</a>
                    </div>
                </div>
            @endif
        @endif
    @endif
@endsection
