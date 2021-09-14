<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,300,700" rel="stylesheet" type="text/css">
<style>
    body {
        background-color: #f6f6f6;
    }
</style>
<div style="background-color: #f6f6f6;">
    <table width="600" align="center" style="padding-left: 3px;" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td style="font-size: 12px; vertical-align: middle; font-family:Tahoma,sans-serif">
                <a href="{{CustomRoute('education.index')}}" target="_blank" title="{{__('education.DC_title')}}">
                    <img src="{{CustomAsset('images/mail/logo-bakkah.png')}}" width="90" border="0" alt="{{__('education.DC_title')}}">
                    <br><br>
                </a>
            </td>
        </tr>
        </tbody>
    </table>

    @yield('content')

    <table id="templateFooter" align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:100%;min-width:100%;background-color:#F5F5F5;" width="100%" class="mcnTextContentContainer">
        <tbody>
        <tr>
            <td class="mcnImageContent" valign="middle" style="text-align:left;padding-top:9px;">
            </td>
        </tr>
        <tr>
            <td style="background-color: #F6F6F6;">
                <table id="templateFooter2" align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;min-width:600px;background-color:#F5F5F5;" width="600" class="mcnTextContentContainer">
                    <tbody>
                    <tr>
                        <td width="240"></td>
                        <td width="30" class="mcnImageContent" valign="middle" style="text-align:center;padding-top:9px;padding-right:4px;">
                            <a href="{{FACEBOOK}}" target="_blank"><img width="22" style="padding: 4px;" src="{{CustomAsset('images/mail/facebook.png')}}" class="CToWUd" alt="facebook"></a>
                        </td>
                        <td width="30" class="mcnImageContent" valign="middle" style="text-align:center;padding-top:9px;padding-right:4px;">
                            <a href="{{TWITTER}}" target="_blank"><img width="22" style="padding: 4px;" src="{{CustomAsset('images/mail/twitter.png')}}" class="CToWUd" alt="twitter"></a>
                        </td>
                        <td width="30" class="mcnImageContent" valign="middle" style="text-align:center;padding-top:9px;padding-right:4px;">
                            <a href="{{INSTAGRAM}}" target="_blank"><img width="22" style="padding: 4px;" src="{{CustomAsset('images/mail/instagram.png')}}" class="CToWUd" alt="instagram"></a>
                        </td>
                        <td width="30" class="mcnImageContent" valign="middle" style="text-align:center;padding-top:9px;padding-right:4px;">
                            <a href="{{LINKEDIN}}" target="_blank"><img width="22" style="padding: 4px;" src="{{CustomAsset('images/mail/linkedin.png')}}" class="CToWUd" alt="linkedin"></a>
                        </td>
                        <td width="240"></td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <!--[if mso]>
        <table>
        <tr>
            <td class="mcnImageContent" valign="middle" height="1"></td>
        </tr>
        </table>
        <![endif]-->
        <tr>
            <td class="mcnImageContent" valign="middle" style="text-align:center;">
                <p style="font-family:Helvetica;font-size:14px;font-weight:normal;text-align:center;margin: 3px !important;color:#999999;"><a href="{{route('education.static.static-page', ["post_type"=>"privacy-policy"])}}" style="color:#999999;text-decoration:none">Privacy Policy</a> | <a href="{{route('education.static.contactusIndex')}}" style="color:#999999;text-decoration:none">Contact Support</a></p>
            </td>
        </tr>
        <!--[if mso]>
        <table>
        <tr>
            <td class="mcnImageContent" valign="middle" style="padding: 1px !important;"></td>
        </tr>
        </table>
        <![endif]-->
        <tr>
            <td class="mcnImageContent" valign="middle" style="text-align:center;background-color: #F6F6F6;">
                <p style="font-family:Helvetica;font-size:14px;font-weight:normal;text-align:center;margin: 3px !important;color:#999999;">© {{YEAR}} <a href="{{CustomRoute('education.index')}}" target="_blank" style="color:#fb4400;text-decoration:none;">{{__('education.app_title')}}</a></p>
            </td>
        </tr>
        </tbody>
    </table>
</div>
