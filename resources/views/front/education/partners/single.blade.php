@extends(FRONT.'.education.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>$partner??null])
@endsection

@section('content')
<?php
//dd($partner->courses);
?>
    @include(FRONT.'.education.Html.page-header', ['title'=>$partner->trans_name])

    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card p-5">
                        <div class="row py-4">
                            <div class="col-md-3">
                                <img class="w-100" src="{{CustomAsset('upload/thumb450/'.$partner->upload->file)}}" title="{{$partner->trans_name}}" alt="{{$partner->upload_title}}">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <h4 class="boldfont m-0">{{$partner->trans_name}}</h4>
                                    </div>
                                    {{-- <div class="col-md-6 mb-4">
                                        <i class="fas fa-globe"></i> {{$partner->website}}
                                    </div> --}}
                                    <div class="col-md-12">
                                        {!!$partner->trans_details!!}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </section> <!-- /.all-consulting -->

    @if($partner->courses->count() > 0)
    <section class="pb-5 sessions-category">
        <div class="section-title text-center">
            <h3>{{__('education.Accredited Courses By This Partner')}} {{$partner->trans_name}}</h3>
        </div>
        <div class="container">

            @include(FRONT.'.education.products.card', ['courses'=>$courses])

        </div>
    </section>
    @endif

@endsection
