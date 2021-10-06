@extends(FRONT.'.education.layouts.master')
@section('useHead')
    <title>{{__('education.Password Reset')}}</title>
@endsection
@section('content')

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <form action="{{route('user.resetSubmit')}}" method="POST">
                @csrf
                <h2 class="mb-3">{{__('education.Password Reset')}}</h2>
                <input type="email" name="email" required placeholder="{{ __('education.Email Address') }}" class="form-control mb-3">
                <button class="btn btn-primary btn-block mb-3">{{__('education.Send Password Reset Link')}}</button>
            </form>
        </div>
    </div>
</div>

@endsection
