@extends(FRONT.'.education.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>$post??null, 'type' => 'report'])
@endsection

@section('content')
<?php
//dd($post->courses);
?>
    @include(FRONT.'.education.Html.page-header', ['title'=>$post->trans_title])

    <section class="book-details py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-5">
                    <div class="img-wrapper">
                        <?php
                            //$path = 'upload/pdf/'. $post->upload()->where('post_type', 'pdf')->first()->file;
                            $img = $post->upload()->where('post_type', 'image')->first();
                        ?>
                        <!--<iframe src = "/ViewerJS/#../<?php //echo $path ?>" width='100%' height='500' allowfullscreen webkitallowfullscreen></iframe>-->

                        <img src="{{CustomAsset('upload/full/'.$img->file)}}" alt="">

                    </div> <!-- /.img-wrapper -->
                </div>
                <div class="col-md-7 mt-5 mt-md-0">
                    <div class="mb-4">{!!$post->trans_details!!}</div>
                    @if(isset($post->uploads()->where('post_type', 'pdf')->where('locale', app()->getLocale())->first()->file))
                        <a class="btn btn-primary px-5" href="{{CustomAsset('upload/pdf/'.$post->uploads->where('post_type', 'pdf')->where('locale', app()->getLocale())->first()->file)}}" download>{{__('education.Download')}}</a>
                    @endif

                    </div>
                </div>

                @include(FRONT.'.education.education-parts.related-articles')
                @include(FRONT.'.education.education-parts.related-courses', ['items' => 4])

                <hr>
                <div class="share-box">
                    @include(FRONT.'.Html.share')
                </div>
        </div> <!-- /.container -->
    </section>

@endsection
