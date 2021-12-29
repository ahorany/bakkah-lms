@extends('layouts.app')

@section('useHead')
    <title>{{__('education.home')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')

    <div class="container certificate-page">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="#">
                    <div class="text-center course-image certificate">
                        <a href="#" class="download">
                            <img src="{{CustomAsset('icons/download.svg')}}" width="50px" alt="">
                        </a>
                        @php
                            // $url = '';
                            // if($course->upload != null) {
                            //         $url = $course->upload->file;
                                    $url = CustomAsset('icons/capm.png');
                            // }else {
                                $url = 'https://ui-avatars.com/api/?background=6a6a6a&color=fff&name=' . 'test';
                            // }
                        @endphp
                        @isset($course->upload->file)
                            <div class="image" style="height: 150px; display:flex; align-items: center; justify-content: center;">
                                <img src="{{$url}}" height="auto" width="100%">
                            </div>
                        @else
                            <div class="no-img" style="height: 120px; display:flex; align-items: center; justify-content: center;">
                                <img src="{{$url}}" height="auto" width="100px">
                            </div>
                        @endisset
                        <h2>CAMP Course</h2>
                        <hr>
                        <div class="completed">
                            <img src="{{CustomAsset('icons/true.svg')}}" width="25px" alt="">
                            <span>Complete</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="#">
                    <div class="text-center course-image certificate">
                        <a href="#" class="download">
                            <img src="{{CustomAsset('icons/download.svg')}}" width="50px" alt="">
                        </a>
                        @php
                            // $url = '';
                            // if($course->upload != null) {
                            //         $url = $course->upload->file;
                                    $url = CustomAsset('icons/capm.png');
                            // }else {
                                $url = 'https://ui-avatars.com/api/?background=6a6a6a&color=fff&name=' . 'test';
                            // }
                        @endphp
                        @isset($course->upload->file)
                            <div class="image" style="height: 150px; display:flex; align-items: center; justify-content: center;">
                                <img src="{{$url}}" height="auto" width="100%">
                            </div>
                        @else
                            <div class="no-img" style="height: 120px; display:flex; align-items: center; justify-content: center;">
                                <img src="{{$url}}" height="auto" width="100px">
                            </div>
                        @endisset
                        <h2>CAMP Course</h2>
                        <hr>
                        <div class="completed">
                            <img src="{{CustomAsset('icons/true.svg')}}" width="25px" alt="">
                            <span>Complete</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="#">
                    <div class="text-center course-image certificate">
                        <a href="#" class="download">
                            <img src="{{CustomAsset('icons/download.svg')}}" width="50px" alt="">
                        </a>
                        @php
                            // $url = '';
                            // if($course->upload != null) {
                            //         $url = $course->upload->file;
                                    $url = CustomAsset('icons/capm.png');
                            // }else {
                                $url = 'https://ui-avatars.com/api/?background=6a6a6a&color=fff&name=' . 'test';
                            // }
                        @endphp
                        @isset($course->upload->file)
                            <div class="image" style="height: 150px; display:flex; align-items: center; justify-content: center;">
                                <img src="{{$url}}" height="auto" width="100%">
                            </div>
                        @else
                            <div class="no-img" style="height: 120px; display:flex; align-items: center; justify-content: center;">
                                <img src="{{$url}}" height="auto" width="100px">
                            </div>
                        @endisset
                        <h2>CAMP Course</h2>
                        <hr>
                        <div class="completed">
                            <img src="{{CustomAsset('icons/true.svg')}}" width="25px" alt="">
                            <span>Complete</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="#">
                    <div class="text-center course-image certificate">
                        <a href="#" class="download">
                            <img src="{{CustomAsset('icons/download.svg')}}" width="50px" alt="">
                        </a>
                        @php
                            // $url = '';
                            // if($course->upload != null) {
                            //         $url = $course->upload->file;
                                    $url = CustomAsset('icons/capm.png');
                            // }else {
                                $url = 'https://ui-avatars.com/api/?background=6a6a6a&color=fff&name=' . 'test';
                            // }
                        @endphp
                        @isset($course->upload->file)
                            <div class="image" style="height: 150px; display:flex; align-items: center; justify-content: center;">
                                <img src="{{$url}}" height="auto" width="100%">
                            </div>
                        @else
                            <div class="no-img" style="height: 120px; display:flex; align-items: center; justify-content: center;">
                                <img src="{{$url}}" height="auto" width="100px">
                            </div>
                        @endisset
                        <h2>CAMP Course</h2>
                        <hr>
                        <div class="completed">
                            <img src="{{CustomAsset('icons/true.svg')}}" width="25px" alt="">
                            <span>Complete</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

