@foreach($aboutus as $about)
    @if($loop->index==1)
    <section class="about-block section-overlap py-5 mb-5">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="about-text-block move-right">
                        <span class="section-number">04.</span>
                        <h2>{{$about->title}}</h2>
                        {!! $about->details !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <img src="{{CustomAsset('upload/thumb450/'.$about->upload->file)}}" class="w-100 about-img-flip" alt="">
                </div>
            </div>
        </div>
    </section> <!-- /.about-block section-overlap -->
    @endif
@endforeach
