@if($eloquent)
    <title>{{$eloquent->seo->trans_author??__('home.DC_title')}}</title>
    <meta name="description" content="{{$eloquent->seo->trans_description??null}}">
    @if(isset($eloquent->postkeywords))
        <?php $seokeywords = []; ?>
        @foreach($eloquent->postkeywords()->where('locale', app()->getLocale())->get() as $keyword)
            <?php array_push($seokeywords, $keyword->seokeyword->title); ?>
        @endforeach
        <meta name="keywords" content="{{implode(', ', $seokeywords)}}">
    @endif
@endif
<meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
<link rel="canonical" href="{{__('home.canonical')}}" />
<meta name="google" content="notranslate">

@if(isset($type))
<meta property="og:locale" content="{{app()->getLocale()}}">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ url()->full() }}">
<meta property="og:site_name" content="{{__('education.app_title')}}">
<meta property="article:publisher" content="https://www.facebook.com/BakkahInc/">
@if ($type == 'post')
    <meta property="article:section" content="{{$eloquent->postMorph->constant->trans_name}}">
    <meta property="og:title" content="{{$eloquent->title}}">
    <meta property="og:description" content="{{$eloquent->excerpt}}">
    <meta name="twitter:description" content="{{$eloquent->excerpt}}l">
    <meta name="twitter:title" content="{{$eloquent->title}}">
@else
    <meta property="og:title" content="{{$eloquent->trans_title}}">
    <meta property="og:description" content="{{$eloquent->trans_excerpt}}">
    <meta name="twitter:description" content="{{$eloquent->trans_excerpt}}l">
    <meta name="twitter:title" content="{{$eloquent->trans_title}}">
@endif
<meta property="article:published_time" content="{{$post->published_date}}">
<meta property="article:modified_time" content="{{$post->updated_at}}">
<meta property="og:updated_time" content="{{$post->published_date}}">
<meta property="og:image" content="{{CustomAsset('upload/full/'.$eloquent->upload->file)}}">
<meta property="og:image:secure_url" content="{{CustomAsset('upload/full/'.$eloquent->upload->file)}}">
<meta property="og:image:width" content="1000">
<meta property="og:image:height" content="300">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@BakkahInc">
<meta name="twitter:image" content="{{CustomAsset('upload/full/'.$eloquent->upload->file)}}">
<meta name="twitter:creator" content="@BakkahInc">
@endif
