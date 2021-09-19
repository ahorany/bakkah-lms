@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
{{--    {{$constant}}--}}
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(99)??null])
@endsection

@section('content')

    @include(FRONT.'.consulting.Html.page-header', ['title'=>__('consulting.webinars')])

    <section class="all-exams py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="btn-group event-cat" role="group">
                        <a href="{{route('consulting.static.webinars')}}" class="btn @if(is_null(request()->event)) active @endif"> <i class="fas fa-globe-asia"></i> <span>{{__('consulting.all_events')}}</span></a>
                        <a href="{{route('consulting.static.webinars')}}?event=happening" class="btn @if(!is_null(request()->event) && request()->event == 'happening') active @endif"> <i class="fas fa-bolt"></i> <span>{{__('consulting.Happening')}}</span></a>
                        <a href="{{route('consulting.static.webinars')}}?event=upcoming" class="btn @if(!is_null(request()->event) && request()->event == 'upcoming') active @endif"> <i class="fas fa-star"></i> <span>{{__('consulting.Upcoming')}}</span></a>
                    </div>
                </div>

            </div>

            <div id="exam-main-content" class="row justify-content-center mt-5">
                @foreach($posts as $post)
                <div class="col-md-4">
                    <div class="event-box box-shadow">
                        <div class="img-wrapper">
                            @php
                                $session_start_time = \Carbon\Carbon::parse($post->session_start_time)->format('Y-m-d H:i:s');
                                $today_now = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
                                // echo $session_start_time.' - '.$today_now;
                                // {{-- 2020-12-14 06:41:20 --}}
                                $session_time_from = \Carbon\Carbon::parse($post->session_start_time)->format('H:iA');
                                $session_time_to = \Carbon\Carbon::parse($post->session_end_time)->format('H:iA');
                            @endphp
                            @if ($session_start_time < $today_now)
                                <small class="closed">{{__('consulting.Closed')}}</small>
                            @else
                                <small class="opened">{{__('consulting.Open')}}</small>
                            @endif

                            <a href="{{route('consulting.static.webinars.single', ['slug'=>$post->slug])}}">
                                @if(isset($post->upload->file))
                                    <a href="{{route('consulting.static.webinars.single', ['slug'=>$post->slug])}}">
                                        <img width="1000" height="300" src="{{CustomAsset('upload/full/'.$post->upload->file)}}" class="w-100" alt="{{$post->upload->excerpt}}" title="{{$post->upload->title}}">
                                    </a>
                                @else
                                    <div class="no-image-avilable">{{$post->trans_title}}</div>
                                @endif
                            </a>

                            {{-- <span>17 <small>Jun</small></span> --}}
                            <span>{{ \Carbon\Carbon::parse($post->session_start_time)->format('j') }}<small>{{ $post->session_from_month }}</small><small>{{ \Carbon\Carbon::parse($post->session_start_time)->format('Y') }}</small></span>
                        </div>
                        <div class="p-3 text-center">
                            <h2 class="boldfont"><a href="{{route('consulting.static.webinars.single', ['slug'=>$post->slug])}}"> {{$post->trans_title}} </a></h2>
                            <p class="time"><i class="far fa-clock"></i> {{ $session_time_from }} - {{ $session_time_to }}</p>
                            <hr>
                            <p class="event-description text-center">{{$post->trans_excerpt}}</p>
                            <a href="{{route('consulting.static.webinars.single', ['slug'=>$post->slug])}}" class="btn btn-block btn-dark text-white">{{__('consulting.Read More')}}</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <nav aria-label="Page navigation example">
            {{ $posts->render() }}
        </nav>
    </section>
@endsection
