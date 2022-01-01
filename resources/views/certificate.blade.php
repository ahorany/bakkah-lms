@extends('layouts.app')

@section('useHead')
    <title>{{__('education.home')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')

    <div class="container certificate-page">
        <div class="row">
            @foreach ($certificates as $certificate)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <a href="#">
                    <div class="text-center course-image certificate">

                        <a href="{{route('training.certificates.certificate_dynamic', ['course_registration_id'=> $certificate->cr_id ] )}}" class="download" target="_blank">
                            <img src="{{CustomAsset('icons/download.svg')}}" width="50px" alt="">
                        </a>
                        <?php
                            $url = '';
                            // dd($course);
                            if(!is_null($certificate->file)) {
                                // if (file_exists($course->upload->file) == false){
                                //     $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . $course->trans_title;
                                // }else{
                                    $url = $certificate->file;
                                    $url = CustomAsset('upload/thumb200/'. $url);
                                // }
                            }else {
                                $url = 'https://ui-avatars.com/api/?background=6a6a6a&color=fff&name=' . \App\Helpers\Lang::TransTitle($certificate->title);
                            }
                        ?>
                        @isset($certificate->file)
                            <div class="image" style="height: 150px; display:flex; align-items: center; justify-content: center;">
                                <img src="{{$url}}" height="auto" width="100%">
                            </div>
                        @else
                            <div class="no-img" style="height: 120px; display:flex; align-items: center; justify-content: center;">
                                <img src="{{$url}}" height="auto" width="100%">
                            </div>
                        @endisset
                        <h2>{{\App\Helpers\Lang::TransTitle($certificate->title)}}</h2>
                        <hr>
                        <div class="completed">
                            <img src="{{CustomAsset('icons/true.svg')}}" width="25px" alt="">
                            <span>Complete</span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
@endsection

