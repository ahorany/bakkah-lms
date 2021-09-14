@if(auth()->check())
    @if(auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==3178 || auth()->user()->id==5337 || auth()->user()->id==6174 || auth()->user()->id==10559 || auth()->user()->id==7952)
    <a class="btn btn-success mb-3" href="{{route('certificates.certificate.pdf', ['id'=>$cart->id,])}}">Send Certificate</a>
    @endif
@endif

<style>
    .certificate-content {
        border: 1px solid #ccc;
        margin-top: 5px;
        background-color: #fff;
    }

    .embed_pdf{
        height: 680px;
    }

    /* @media (min-width: 551px) and (max-width: 767px) and (max-width: 991px) and (max-width: 550px) and (max-width: 330px) and (max-width: 290px) { */
    @media (max-width: 767px) {
        .embed_pdf {
            /* height: calc(2rem + 25px); !important; */
            height: 250px !important;
        }
    }
</style>

{{-- @include('training.certificates.certificate.content') --}}
<?php
    // $show_pdf3 = env('APP_URL') . 'certificates/certificate/'.$file_name_pdf.'.pdf';
    $show_pdf = CustomAsset('certificates/certificate/'.$file_name_pdf.'.pdf');
    $physical_pdf = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';
    // if(file_exists($physical_pdf)){
    //     unlink($physical_pdf);
    // }
    // height="680px"
?>
<div class="col-12 text-center">
    @if(file_exists($physical_pdf))
        <embed class="embed_pdf" src="{!!$show_pdf!!}#view=Fit&=<?=time();?>" type="application/pdf" width="100%" height="680px" />
    @else
        <div style="direction: ltr;" class="alert alert-danger alert-dismissible" role="alert">
                <strong>Certificate PDF Not Generated!</strong>
            <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">×</span>
            </button>
        </div>
    @endif
</div>
