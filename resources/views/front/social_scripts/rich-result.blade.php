{{--@if(env('HAS_SOCIAL_SCRIPTS')==true)--}}
<?php
use App\Helpers\Lang;
// $SessionHelper->SetCourse($course);
// if(auth()->check()){
//     if(auth()->user()->id==1){
//         foreach($CardsSingles as $CardsSingle){
//             // dump($CardsSingle->session_price);
//             // dd(Lang::TransTitle($CardsSingle->trans_title));
//             dd(url()->current());
//         }
//         // dd($CardsSingles);
//     }
// }
$coin_id = GetCoinId();
$currency = 'SAR';
if($coin_id==335){
    $currency = 'USD';
}

// if(auth()->check())
{
    // if(auth()->user()->id==1)
    {
        foreach($CardsSingles as $CardsSingle){
        ?>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org/",
          "@type": "Event",
          "name": "{{Lang::TransTitle($CardsSingle->trans_title??'')}}",
          "location": {
            "@type": "Place",
            "address": {
              "@type": "PostalAddress"
            },
            "name": "{{env('APP_NAME')}}"
          },
          "startDate": "{{$CardsSingle->session_date_from??''}}",
          "endDate": "{{$CardsSingle->session_date_to??''}}",
          "description": "{!! str_replace( '"', "'", Lang::TransTitle($CardsSingle->options_details??'')) !!}",
          "eventStatus": "https://schema.org/EventRescheduled",
          "eventAttendanceMode": "https://schema.org/OnlineEventAttendanceMode",
          "image": "{{CustomAsset('upload/thumb300/'.$CardsSingle->file)}}",
          "offers": {
            "@type": "Offer",
            "url": "{{url()->current()}}",
            "priceCurrency": "{{$currency}}",
            "price": "{{$CardsSingle->session_price??0}}",
            "availability": "https://schema.org/InStock"
          },
          "inLanguage": "{{app()->getLocale()??'en'}}"
        }
        </script>

        <script type="application/ld+json">
        {
          "@context": "https://schema.org/",
          "@type": "Product",
          "name": "{{Lang::TransTitle($CardsSingle->trans_title??'')}}",
          "image": "{{CustomAsset('upload/thumb300/'.$CardsSingle->file)}}",
          "offers": {
            "@type": "Offer",
            "priceCurrency": "{{$currency}}",
            "price": "{{$CardsSingle->session_price??0}}",
            "availability": "https://schema.org/InStock",
            "url": "{{url()->current()}}",
            "priceValidUntil": "{{$CardsSingle->session_date_to??''}}"
          },
          "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "{{$CardsSingle->rating??0}}",
            "ratingCount": "{{$CardsSingle->rating??500}}",
            "reviewCount": "{{$CardsSingle->reviews??500}}",
            "bestRating": "5",
            "worstRating": "0"
          },
          "description": "{!! str_replace( '"', "'", Lang::TransTitle($CardsSingle->options_details??'')) !!}",
          "brand": ""
        }
        </script>
        <?php
        }
    }
}
//   "description": `{!!Lang::TransTitle($CardsSingle->options_details??'')!!}`,
?>
