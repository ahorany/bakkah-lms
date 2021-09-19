<section class="partnership-with py-5 wow fadeInUp partnership">
    <div class="container ">
        <div class="section-title text-center">
            <h2>{!! __('education.partnership') !!}</h2>
        </div>
        <div class="container">
            <div class="row text-center">
                @foreach($partners as $partner)
                    @if(isset($partner->file))
                        <div class="col-sm px-2">
                            <a href="{{route('education.static.partners.single', $partner->slug)}}"><img style="width: 100px" src="{{CustomAsset('upload/thumb100/'.$partner->file)}}" title="{{$partner->trans_name}}" alt="{{$partner->upload_title}}"></a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</section> <!-- /.partnership-with -->
