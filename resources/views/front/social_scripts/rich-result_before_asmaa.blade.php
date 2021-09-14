{{--@if(env('HAS_SOCIAL_SCRIPTS')==true)--}}
<?php
$SessionHelper->SetCourse($course);
$subTotal = $SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT();
?>
<script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Course",
      {{-- "@type": "{{__('education.Course')}}", --}}
      "name": "{{$course->trans_title}}",
      "image": "{{CustomAsset('upload/thumb100/'.$course->file)}}",
      "description": "{{$course->seo->trans_description??null}}",
     "provider":"{{__('education.DC_title')}}",
      "offers": {
        "@type": "Offer",
        "url": "{{route('education.courses.single', ['slug'=>$course->slug])}}",
        "priceCurrency": "{{__('education.SAR')}}",
        "price": "{{NumberFormatWithComma($subTotal??0)}}",
        {{-- "price": "{{NumberFormatWithComma($subTotal)}}", --}}
        "availability": "https://schema.org/InStock"
      },
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{$course->rating}}",
        "bestRating": "5",
        "worstRating": "0",
        "ratingCount": "{{$course->reviews}}",
        "reviewCount": "{{$course->reviews}}"
      },
      "review": {
        "@type": "Review",
        "name": "Hani",
        "reviewBody": "{{__('education.seo_review_card')}} {{$course->trans_title}} {{__('education.seo_review_card2')}}",
        "reviewRating": {
          "@type": "Rating",
          "ratingValue": "5",
          "bestRating": "5",
          "worstRating": "0"
        },
        "datePublished": "{{date_format(now(), 'Y-m-d')}}",
        "author": {"@type": "person", "name": "Hani"},
        "publisher": {"@type": "Organization", "name": "{{__('education.DC_title')}}"}
      }
    }
    </script>
{{--@endif--}}
