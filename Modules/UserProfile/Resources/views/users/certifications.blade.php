@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{ __('education.Certifications') }} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')

    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-5 user-info">
                        <div class="d-flex upload-certification">
                            <h4><i class="fas fa-certificate"></i> {{ __('education.Certifications') }}</h4>
                            {{-- <button class="ml-auto"><i class="fas fa-cloud-upload-alt"></i> Upload Proffisional Certification</button> --}}
                        </div>
                        <div class="row mt-3 mx-0">

                            <?php
                                // $show_pdf3 = env('APP_URL') . 'certificates/certificate/'.$file_name_pdf.'.pdf';
                                // $show_pdf = CustomAsset('certificates/certificate/'.$file_name_pdf.'.pdf');
                                // $physical_pdf = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';
                                // if(file_exists($physical_pdf)){
                                //     unlink($physical_pdf);
                                // }
                            ?>
                            @foreach ($carts as $cart)
                            {{-- @if ($certificate->certificate_file != null && $certificate->course->certificate_type_id == 324) --}}
                                <div class="col-md-2 col-lg-2 px-2 mb-4 card-certifications">
                                    <div class="certifications course-profile px-3 py-4 text-center">
                                        <div class="preview">
                                            <img src="{{CustomAsset('upload/thumb200/'.$cart->course->upload->file)}}" class="card-img-top w-100" alt="{{$cart->course->trans_title}}">
                                        </div>
                                        <h5>{{$cart->course->trans_short_title}}</h5>
                                        <a class="download d-block btn btn-primary rounded-pill btn-sm py-0 px-2 mb-2" href="{{route('user.downloadCertifications',['id' => $cart->id ])}}"><em><small>{{ __('education.Download') }}</small></em></a>
                                        <a target="_blank" class="preview d-block btn btn-primary rounded-pill btn-sm py-0 px-2" href="{{route('user.certifications.index',['id' =>$cart->id])}}"><em><small>{{ __('education.Preview') }}</small></em></a>
                                    </div>
                                    {{-- user.previewCertifications --}}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
