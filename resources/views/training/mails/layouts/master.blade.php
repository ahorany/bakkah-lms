<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body style="width: 100%; font-family: sans-serif; text-align:center;">
    <table border="0" width="600" style="background:#fafafa; border: 2px solid #d6d6d6; margin: 20px auto; text-align:center; border-radius: 5px; padding: 20px 40px 0;">
        <tr>
            <td>
                <header style="background: #f0f0f0;">
                    <img src="{{CustomAsset('images/email/img_header.png')}}" width="80" height="auto" style="margin: 0 auto">
                </header>

                @yield('content')

                <footer style="width: 90%; margin: 15px auto;">
                    <table border="0" cellspacing="0" width="100%">
                        <tr>
                            <td width="350" style="text-align:left;">
                                <table border="0">
                                    <tr>
                                        <td>
                                            <a href="#" width="20" style="text-decoration: none; margin-right: 5px;">
                                                <img src="{{CustomAsset('images/email/facebook.png')}}" width="15" height="auto" alt="">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" width="20" style="text-decoration: none; margin-right: 5px;">
                                                <img src="{{CustomAsset('images/email/twitter.png')}}" width="15" height="auto" alt="">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" width="20" style="text-decoration: none; margin-right: 5px;">
                                                <img src="{{CustomAsset('images/email/insta.png')}}" width="15" height="auto" alt="">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" width="20" style="text-decoration: none; margin-right: 5px;">
                                                <img src="{{CustomAsset('images/email/linked-in.png')}}" width="15" height="auto" alt="">
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                                <div><small>Phone: +966 920003928</small></div>
                                <div><small>Privacy Policy | Contact Support</small></div>
                                <div><small>© 2021 <span style="color: #fb4400;">Bakkah Inc.</span></small></div>
                            </td>
                            <td width="350" style="text-align:right; vertical-align: bottom;">
                                <img src="{{CustomAsset('images/email/logo.png')}}" width="40" height="auto" alt="">
                            </td>
                        </tr>
                    </table>
                </footer>
            </td>
        <tr>
    </table>
</body>
</html>
