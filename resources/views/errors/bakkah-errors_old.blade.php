@extends(FRONT.'.consulting.layouts.master')
@section('content')
    <section class="page-header bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="entry-title">{{__('home.Oops')}}</h1>
                    <h3 class="entry-subtitle">{{__('home.This page was not found')}}</h3>
                </div>
            </div>
        </div>
    </section>
    <div class="main-content py-5 text-center">
        <div class="container container-padding">
            <div class="row" id="error404">
                {{--
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <i class="fas fa-search icon-search fa-3x mb-4" aria-hidden="true"></i>
                    <h2>{{__('home.Search Our Website')}}</h2>
                    <div class="search404">
                        <form id="searchform" method="get" action="https://bakkah.net.sa/">
                            <input type="text" class="form-control search-field" name="s" placeholder="Search here" value="">
                            <input type="hidden" name="lang" value="en">
                            <button type="submit" value="Search"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
                --}}
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <i class="fas fa-envelope fa-3x mb-4 icon-envelope" aria-hidden="true"></i>
                    <h2>{{__('home.Report a Problem')}}</h2>
                    <p>{!! __('home.Please write some descriptive information about your problem, and email our', ['mailto'=>'mailto:aalhorany@bakkah.net.sa']) !!}</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <i class="fas fa-home fa-3x mb-4 icon-home" aria-hidden="true"></i>
                    <h2>{{__('home.Back to the Homepage')}}</h2>
                    <p>{!! __('home.You can also', ['prev_path'=>url()->previous()]) !!}</p>
                </div>
            </div>
        </div>
        <div class="extra_space"></div>
    </div>
@endsection
