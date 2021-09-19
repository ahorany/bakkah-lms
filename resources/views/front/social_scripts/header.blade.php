@if(env('HAS_SOCIAL_SCRIPTS')==true)

    <meta name="google-site-verification" content="ZrPYmZ2B_JDMyl3Mn1nps-NZLBsVOZybwD4jyosPbbs" />
    {{-- <meta name="google-site-verification" content="6bm6YNBXsiqA7DISXVSYtXMjJbd5DpJxwYruVwQaF9M" /> --}}
    <!-- start bakkah.com -->
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T8JKCLQ');</script>
    <!-- End Google Tag Manager -->
    <!---------------------->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-188331463-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-188331463-1');
    </script>
    <!-- end bakkah.com -->
    <!---------------------->
    <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window,document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '396665178069143');
    fbq('track', 'PageView');
    </script>
    <noscript>
    <img height="1" width="1"
    src="https://www.facebook.com/tr?id=396665178069143&ev=PageView
    &noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->

    <?php
    $Infastructure = \App\Infastructure::find(70)??null;
    ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "EducationalOrganization",
      "name": "{{env('APP_NAME')}}",
      "url": "{{env('APP_URL')}}",
      "logo": "{{CustomAsset('images/logo.png')}}",
      "image": "{{CustomAsset('images/logo.png')}}",
      "email": "contactus@bakkah.net.sa",
      "description": "{{$Infastructure->excerpt}}",
      "address": {
        "@type": "PostalAddress",
        "postalCode": "13327",
        "streetAddress": "7344 Othman Bin Affan – Al Narjis Dist. Unit No 76 Al Narjis, Riyadh, 13327 Saudi Arabia"
      },
      "sameAs": [
        "https://www.linkedin.com/company/bakkahinc/mycompany/",
        "https://www.instagram.com/bakkahinc/",
        "https://twitter.com/BakkahInc",
        "https://www.facebook.com/BakkahInc/"
      ],
      "slogan": "Build your capability and advance in your career"
    }
    </script>
@endif

<?php $net = false; ?>
@if($net==true)
<!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PZM4G76"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!---------------------->
    <script type="text/javascript">
        var google_tag_params = {
            edu_pid: 'REPLACE_WITH_VALUE',
            edu_plocid: 'REPLACE_WITH_VALUE',
            edu_pagetype: 'REPLACE_WITH_VALUE',
            edu_totalvalue: 1,
            local_id: 'REPLACE_WITH_VALUE',
            local_pagetype: 'REPLACE_WITH_VALUE',
            local_totalvalue: 1,
        };
    </script>
    <!---------------------->
    <script type="text/javascript">
        /* <![CDATA[ */
        var google_conversion_id = 971233488;
        var google_custom_params = window.google_tag_params;
        var google_remarketing_only = true;
        /* ]]> */
    </script>
    <!---------------------->
    <noscript>
        <div style="display:inline;">
            <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/971233488/?guid=ON&amp;script=0"/>
        </div>
    </noscript>
    <!---------------------->
    <!-- Start of HubSpot Embed Code -->
    <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/6337445.js"></script>
    <!-- End of HubSpot Embed Code -->

    <!-- Global site tag (gtag.js) - Google Ads: 971233488 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-971233488"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'AW-971233488');
    </script>
@endif
