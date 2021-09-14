@foreach($aboutus as $about)
    @if($loop->index==$index)
        <section class="about-block section-overlap py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-6">
                        <img src="{{CustomAsset('upload/thumb450/'.$about->upload->file)}}" class="w-100 about-img-flip" alt="">
                    </div>
                    <div class="col">
                        <div class="about-text-block move-left">
                            <span class="section-number">{{$number}}.</span>
                            <h2>{{$about->title}}</h2>
                            {!! $about->details !!}
                        </div>
                    </div>
                </div>
            </div>
        </section> <!-- /.about-block section-overlap -->
    @endif
@endforeach
