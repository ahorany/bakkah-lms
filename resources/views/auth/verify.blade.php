@extends('layouts.auth')

@section('content')
  <div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">{{ __('education.Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('education.A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('education.Before proceeding, please check your email for a verification link.') }}
                    {{ __('education.If you did not receive the email') }},
                    <form style="display: inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary mt-3 align-baseline">{{ __('education.Click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
