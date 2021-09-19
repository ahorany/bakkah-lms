<section class="page-header bg-light py-4">
    <div class="container">
        <div class="row">
            <div class="col">
                <ol class="list-unstyled">
                    <li><a href="{{route('education.index')}}">{{__('education.home')}}</a></li>
                    <li>{{$title}}</li>
                </ol>
                <h1>{{$title}}</h1>
                @isset($subtitle)
                    <p class="mt-3 mb-0">{!! $subtitle !!}</p>
                @endisset
            </div>
        </div>
    </div>
</section>
