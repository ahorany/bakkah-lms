<section class="newsletter-section py-md-5">
    <div class="container">
        <div class="row align-items-center m-md-4">
            <div class="col-md-6">
                <div class="newsletter-text  d-flex align-items-center">
                    {{-- <img class="mr-5" src="https://bakkah.net.sa/wp-content/themes/bakkah-new/images/email-icon.png" alt=""> --}}
                    <div>
                        <h3><span class="second-color">{!! __('education.STAY TUNED WITH US') !!}</h3>
                        <p>{{__('education.Subscribe_msg')}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5 offset-md-1">
                <form method="post" id="capture_emails_form_kc" action="{{route('education.static.knowledge-center.newsletter')}}" target="hidenFrame">
                    @csrf
                    <label>{{__('education.Your Email Address')}}</label>
                    <div class="row no-gutters">
                        <div class="col-8">
                            <input type="email" name="email" class="form-control mb-2 mr-sm-2" placeholder="{{__('education.Email Address')}}">
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-block btn-dark mb-2">{{__('education.Subscribe')}}</button>
                        </div>
                    </div>
                </form>
                <p class="msg" style="color: green;font-size: 16px;text-align: center;margin-top: 10px;"></p>
            </div>
        </div>
    </div>
</section> <!-- /.newsletter-section -->
<script>
$(function(){
    $('#capture_emails_form_kc button').click(function(){

        if($('[name="email"]').val()==''){
            msg = 'You must type your email address <br>';
            var l = $("html").attr('lang');
            if(l=='ar'){
                msg = 'يجب ادخال البريد الالكتروني <br>';
            }
            jQuery(".msg").html(msg);
            return false;
        }

        $.ajax({
            url:$('#capture_emails_form_kc').attr('action'),
            type:'post',
            data:{
                email:$('[name="email"]').val(),
                _token:$('[name="_token"]').val(),
            },
            success:function(data){
                // alert(data);
                var msg_en = 'Your email successfully added into the Newsletter!';
                var msg_ar = 'تم إضافة بريدك الإلكتروني بنجاح لقائمة النشرة البريدية!';

                if(!data){
                    msg_en = 'Error! Maybe your email added before, Invalid email address or send message in contact us page.';
                    msg_ar = 'خطأ! ربما بريدك تمت اضافته مسبقاً أو بريدك الالكتروني خاطئ أو أرسل رسالة في صفحة اتصل بنا ';
                }

                var msg = msg_en;
                var l = $("html").attr('lang');
                if(l=='ar'){
                    msg = msg_ar;
                }
                jQuery(".msg").html(msg);
                // console.log(data);
                // PERMANENTLY DELETE
            },
            error:function(e){
                // console.log(e);
                if(e){
                    msg = 'Error! Send message in contact us page with this error please <br>' + e;
                    var l = $("html").attr('lang');
                    if(l=='ar'){
                        msg = 'خطأ! أرسل رسالة في صفحة اتصل بنا بهذا الخطأ من فضلك <br>' + e;
                    }
                    jQuery(".msg").html(msg + '<br>' + e.status + ':' + e.statusText,e.responseText);
                    // jQuery(".msg").html(e);
                }
            }
        });
        return false;
    });
});
</script>
