<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body style="width: 80%; margin: 0 auto; font-family: sans-serif;">
    <header style="background: url({{CustomAsset('images/email/Rectangle\ 1.png')}}); position: relative; height: 130px;">
        <img src="{{CustomAsset('images/email/top-img.png')}}" alt="" style="width: 100px; position: absolute; bottom: -50px; left: 50%; transform: translateX(-50%);">
    </header>
    <main style="text-align: center; width: 80%; margin: 80px auto 0;">
        <div style="margin-bottom: 50px;">
            <h5 style="font-size: 14px; margin: 15px 0;">Dear Jihad Hamdan,</h5>
            <p style="font-size: 30px; font-weight: bold; margin: 15px 0; text-transform: uppercase; color: #fb4400;">Welcome onboard!</p>
            <p style="font-size: 15px; margin: 15px 0;">You have registered to attend Self-Study Lean Six Sigma – Yellow Belt Course. We would like to welcome you and wish you a great learning experience!</p>
            <hr style="width: 40%; margin: 30px auto;">
            <p style="font-size: 17px;">This email is intended to provide you with the needed information before the beginning of the course to help you log in our LMS system and deal with the course properly.</p>
        </div>
        <div style="background: #f9f9f9; padding: 15px 0;">
            <h3 style="font-size: 20px;">Details you need to note</h3>
            <div style="display: flex; justify-content: center; align-items: center;">
                <div style="display: flex; margin: 0 10px; align-items: center;">
                    <img src="{{CustomAsset('images/email/self.png')}}" width="auto" height="45px" alt="" style="margin: 0 15px;">
                    <p style="font-size: 20px;">Self-Study</p>
                </div>
                <div style="display: flex; margin: 0 10px; align-items: center;">
                    <img src="{{CustomAsset('images/email/english.png')}}" width="auto" height="25px" alt="" style="margin: 0 15px;">
                    <p style="font-size: 20px;">English</p>
                </div>
            </div>
        </div>
        <div style="margin: 40px 0;">
            <h3 style="font-size: 20px;">The Learning Management System (LMS)</h3>
            <p style="font-size: 17px;">The access below is for our LMS system where you’ll find the course and all supporting materials!</p>
        </div>
        <hr style="margin: 0 auto;">
        <div style="padding: 30px 0; width: 80%; margin: 0 auto;">
            <h3 style="color: #fb4400; font-size: 20px; margin-top: 0;">Here’s your details:</h3>
            <div style="display: flex; justify-content: center; align-items: center;">
                <div style="display: flex; justify-content: center; align-items: center; padding: 0 15px; align-items: center;">
                    <img src="{{CustomAsset('images/email/link_icon.png')}}" width="auto" height="20px" alt="" style="margin: 0 10px;">
                    <span style="font-size: 15px;"><a href="{{env('APP_URL')}}">{{env('APP_URL')}}</a></span>
                </div>
                <div style="display: flex; justify-content: center; align-items: center; padding: 0 15px; align-items: center;">
                    <img src="{{CustomAsset('images/email/user_icon.png')}}" width="auto" height="20px" alt="" style="margin: 0 10px;">
                    <span style="font-size: 15px;">jhamdan</span>
                </div>
                <div style="display: flex; justify-content: center; align-items: center; padding: 0 15px; align-items: center;">
                    <img src="{{CustomAsset('images/email/email_icon.png')}}" width="auto" height="20px" alt="" style="margin: 0 10px;">
                    <span style="font-size: 15px;">jhamdan@Fp_</span>
                </div>
            </div>
        </div>
        <hr style="margin: 0 auto;">
        <p style="font-size: 17px; margin: 30px 0;">If you face any technical issues and need support, please feel free to contact our Training Department and we’ll get in touch with you in due time! </p>
        <p style="font-size: 17px; color: #fff; background: #fb4400; text-align: center; border-radius: 6px; padding: 15px;">We hope you enjoy this learning experience and wish you best of luck! </p>
    </main>
    <footer style="width: 80%; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; margin: 50px auto;">
            <div>
                <div style="display: flex;">
                    <a href="#" style="text-decoration: none; margin-right: 5px;">
                        <img src="{{CustomAsset('images/email/facebook.png')}}" width="auto" height="25px" alt="">
                    </a>
                    <a href="#" style="text-decoration: none; margin-right: 5px;">
                        <img src="{{CustomAsset('images/email/twitter.png')}}" width="auto" height="25px" alt="">
                    </a>
                    <a href="#" style="text-decoration: none; margin-right: 5px;">
                        <img src="{{CustomAsset('images/email/insta.png')}}" width="auto" height="25px" alt="">
                    </a>
                    <a href="#" style="text-decoration: none; margin-right: 5px;">
                        <img src="{{CustomAsset('images/email/linked-in.png')}}" width="auto" height="25px" alt="">
                    </a>
                </div>
                <div><small>Phone: +966 920003928</small></div>
                <div><small>Privacy Policy | Contact Support</small></div>
                <div><small>© 2021 <span style="color: #fb4400;">Bakkah Inc.</span></small></div>
            </div>
            <div>
                <img src="{{CustomAsset('images/email/logo.png')}}" width="auto" height="60px" alt="">
            </div>
        </div>
    </footer>
    <img src="{{CustomAsset('images/email/Rectangle small.png')}}" width="100%" alt="">
</body>
</html>
