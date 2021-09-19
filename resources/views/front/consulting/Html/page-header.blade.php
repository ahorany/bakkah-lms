<section class="page-header consulting-page-header {{$css??''}} py-5">
    <div class="container">
        <div class="row">
            <div class="col">
                <ol class="list-unstyled">
                    <li><a href="{{CustomRoute('consulting.index')}}">{{__('education.home')}}</a></li>
                    <li>{{$title}}</li>
                </ol>
                <h1 class="second-color my-4">{{$title}}</h1>
                <p>{{$excerpt??null}}</p>
            </div>
        </div>
    </div>
</section>
