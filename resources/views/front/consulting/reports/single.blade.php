@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>$post??null])
@endsection

@section('content')
<?php
//dd($post->courses);
?>
    @include(FRONT.'.consulting.Html.page-header', ['title'=>$post->trans_title])

    <section class="book-details py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="img-wrapper">
                        <?php $img = $post->upload()->where('post_type', 'image')->first(); ?>
                        <img class="" src="{{CustomAsset('upload/thumb450/'.$img->file)}}" title="{{$post->trans_name}}" alt="{{$post->upload_title}}">
                    </div> <!-- /.img-wrapper -->
                </div>
                <div class="col-md-6 mt-5 mt-md-0">
                    <div>{!!$post->trans_details!!}</div>
                    @if(isset($post->uploads()->where('post_type', 'pdf')->where('locale', app()->getLocale())->first()->file))
                        <a class="btn btn-dark text-white px-4"" href="{{CustomAsset('upload/pdf/'.$post->uploads->where('post_type', 'pdf')->where('locale', app()->getLocale())->first()->file)}}" download="">{{__('consulting.Download')}}</a>
                    @endif

                    </div>
                </div>
                <hr>
                <div class="share-box">
                    @include(FRONT.'.Html.share')
                </div>
        </div> <!-- /.container -->
    </section>

@endsection
