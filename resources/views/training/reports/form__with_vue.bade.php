@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

{{Builder::SetNameSpace('training.')}}
{{Builder::SetPublishName('publish')}}

@section('col9')
    {!!Builder::Input('en_title', 'en_title', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Input('ar_title', 'ar_title', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Excerpt('en_excerpt', 'en_excerpt')!!}
    {!!Builder::Excerpt('ar_excerpt', 'ar_excerpt')!!}

    {{Builder::SetPrefix('training.')}}
    {!!Builder::Excerpt('en_details', 'en_details')!!}
    {!!Builder::Excerpt('ar_details', 'ar_details')!!}
    @endsection

    @section('col3_block')
    <!-- knowledge_center -->
    @include(ADMIN.'.Html.checkbox_const', ['const_type'=>'course'])

@endsection

@section('seo')
    @include(ADMIN.'.SEO.form', ['post'=>$eloquent??null])
@endsection

@section('image')
    <?php $image_title = __('admin.image'); ?>
    @include(ADMIN.'.Html.image')
@endsection
