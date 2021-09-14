<section id="testimonials" class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 text-center mb-5">
                <h2>{{__('education.What our Clients have to say about bakkah')}}</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach($testimonials as $testimonial)
                <div class="col-md-4">
                    <div class="tetsimonial-box mb-5">
                        <p>{{$testimonial->trans_excerpt}}</p>
                        <div class="person d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                @if(isset($testimonial->userId->upload->file))
                                    <img src="{{CustomAsset('upload/thumb100/'.$testimonial->userId->upload->file)}}" class="d-block w-100" alt="{{$testimonial->userId->upload->title}}">
                                @endif
                                <span class="name ml-3">{{$testimonial->userId->trans_name}}<br>
                                {{--<small class="main-color">{{$testimonial->course->trans_short_title}}</small>--}}
                                </span>
                            </div>
                            {{--<span class="date ml-auto">{{$testimonial->published_date}}</span>--}}
                        </div>
                    </div> <!-- /.tetsimonial-box -->
                </div>
            @endforeach
        </div>
    </div>
    {{--<div class="text-center">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#testimonialModal"> {{__('education.Submit Your Testimonial')}} </button>
    </div>--}}
{{--
    <!-- Modal -->
    <div class="modal fade" id="testimonialModal" tabindex="-1" role="dialog" aria-labelledby="testimonialModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="margin-top: 5%">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testimonialModalLabel">Submit Your Testimonial</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="registration_form" method="post">
                        <input type="hidden" name="do" value="add_new_testimonial">
                        <div class="form-group">
                            <label>Full Name</label><br>
                            <input type="text" name="name" value="" class="form-control parsley-validated" data-trigger="change" data-required="true" data-error-message="Please type your full name">
                        </div>

                        <div class="form-group">
                            <label>Email</label><br>
                            <input style="display: inline-block;" type="text" name="email" id="email" value="" class="form-control parsley-validated" data-trigger="change" data-required="true" data-type="email" data-error-message="Please type your email">
                        </div>

                        <div class="form-group">
                            <label>Message</label><br>
                            <textarea name="message" id="message" value="" class="form-control parsley-validated" rows="4" data-trigger="change" data-required="true" data-error-message="Please type your message"></textarea>
                        </div>

                        <div class="form-group">
                            <div style="padding-bottom: 30px;" class="g-recaptcha" data-sitekey="6LcSMyETAAAAAPXBLKXvICAyv_2_xvnp0OyboOTp">
                                <div style="width: 304px; height: 78px;">
                                    <div><iframe src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6LcSMyETAAAAAPXBLKXvICAyv_2_xvnp0OyboOTp&amp;co=aHR0cHM6Ly9iYWtrYWgubmV0LnNhOjQ0Mw..&amp;hl=en&amp;v=aUMtGvKgJZfNs4PdY842Qp03&amp;size=normal&amp;cb=4crfxmdbb9p" width="304" height="78" role="presentation" name="a-4ua592k002yq" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox"></iframe></div><textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea>
                                </div>
                            </div>
                        </div>

                        <button name="registerBtn" type="submit" class="btn btn-primary px-4">Submit</button>
                        <div class="alert_container"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    --}}
</section>
