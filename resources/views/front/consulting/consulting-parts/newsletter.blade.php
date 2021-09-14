<section class="newsletter-section  py-4 py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="newsletter-text  d-flex align-items-center">
                    {{-- <img class="mr-5" src="https://bakkah.net.sa/wp-content/themes/bakkah-new/images/email-icon.png" alt=""> --}}
                    <div>
                        <h3><span class="third-color">{!! __('consulting.STAY TUNED WITH US') !!}</h3>
                        <p>{{__('consulting.Subscribe_msg')}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5 offset-md-1">
                <form method="post" id="capture_emails_form_kc" action="#" target="hidenFrame">

                    <input type="hidden" name="do" value="capture_emails_form_kc">
                    <input type="hidden" id="l" name="l" value="en" />

                    <label>{{__('consulting.Your Email Address')}}</label>
                    <div class="row no-gutters">
                        <div class="col-8">
                            <input type="email" name="email" class="form-control mb-2 mr-sm-2" placeholder="{{__('consulting.Email Address')}}">
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-block btn-dark mb-2">{{__('consulting.Subscribe')}}</button>
                        </div>
                    </div>

                </form>
                <p class="msg" style="color: green;font-size: 16px;text-align: center;margin-top: 10px;"></p>
            </div>
        </div>
    </div>
</section> <!-- /.newsletter-section -->
