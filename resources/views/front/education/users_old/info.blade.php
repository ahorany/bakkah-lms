@extends(FRONT.'.education.layouts.master-user')

@section('title', __('education.Info'))

@section('content')

    <div class="userarea-wrapper">
        <div class="row no-gutters">

            @include('front.education.users.sidebar')

            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-4 user-info">
                        <form action="">
                            <div class="d-md-flex">
                                <div class="img">
                                    <img src="https://placehold.it/200x200" alt="">
                                </div>
                                <div class="text mx-4">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="fname">{{__('education.First Name')}}</label>
                                                <input type="text" id="fname" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="lname">{{__('education.Last Name')}}</label>
                                                <input type="text" id="lname" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="lname">{{__('education.Bio')}}</label>
                                                <textarea class="form-control" rows="5"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="lname">{{__('education.Headline')}}</label>
                                                <input type="text" id="lname" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="lname">{{__('education.Language')}}</label>
                                                <select class="form-control">
                                                    <option>English</option>
                                                    <option>Arabic</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <hr>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="email">{{__('education.Email')}}</label>
                                                <input type="email" id="email" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="password">{{__('education.Password')}}</label>
                                                <input type="password" id="password" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="new-password">{{__('education.New Password')}}</label>
                                                <input type="password" id="new-password" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="retype-password">{{__('education.Re-type New Password')}}</label>
                                                <input type="password" id="retype-password" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <hr>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="retype-password">{{__('education.Twitter')}}</label>
                                                <div class="input-group twitter">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="retype-password">{{__('education.Twitter')}}</label>
                                                <div class="input-group twitter">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="retype-password">{{__('education.Twitter')}}</label>
                                                <div class="input-group twitter">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="retype-password">{{__('education.Twitter')}}</label>
                                                <div class="input-group twitter">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 text-center mt-4">
                                            <button class="btn btn-primary">{{ __('education.Save Changes') }}</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
