<section class="page-header consulting-page-header py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <ol class="list-unstyled">
                    <li><a href="{{CustomRoute('consulting.index')}}">{{__('app.Home')}}</a></li>
                    <li><a href="{{CustomRoute('consulting.static.knowledge-center.single', ['slug'=>$post->slug])}}">{{$post->postMorph->constant->trans_name}}</a></li>
                    <li>{{$post->title}}</li>
                </ol>
                <h1 class="second-color my-4">{{$post->title}}</h1>
                {{-- <p>{{$post->excerpt}}</p> --}}
{{--                <a href="#">Consultation Services Download <i class="fas fa-download"></i></a>--}}
            </div>
            {{--<div class="col-md-4">
                <div class="p-4 box-shadow mb-4 request_box">
                    <div class="d-flex align-items-start">
                        <img class="mr-3" src="{{CustomAsset('images/consulting_icon.png')}}" alt="">
                        <div>
                            <p>{{__('consulting.If you’re interested')}}</p>
                        </div>
                    </div>
                    <a href="#" class="btn btn-block btn-table mt-3"> <i class="fas fa-arrow-right"></i> {{__('consulting.Request Proposal')}}</a>
                </div>

            </div>--}}
        </div>
    </div>
</section> <!-- /.page-header -->
