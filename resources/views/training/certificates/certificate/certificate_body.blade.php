@if(auth()->check() && isset($cart->id))
    @if(auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==3178 || auth()->user()->id==5337 || auth()->user()->id==6174 || auth()->user()->id==10559 || auth()->user()->id==13094 || auth()->user()->id==10220)
    <a class="btn btn-success mb-3" href="{{route('certificates.certificate.pdf', ['id'=>$cart->id])}}">Send Certificate</a>
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
{{-- @dd($course) --}}
@if(isset($course))
@include('pages.templates.breadcrumb', [
    'course_id'=>$course->id,
    'course_title'=>$course->trans_title,
    // 'section_title' => $content->section->title,
    // 'content_title'=>$content->title,
])
@endif
{{-- @include('training.certificates.certificate.content') --}}
<?php
    // $show_pdf3 = env('APP_URL') . 'certificates/certificate/'.$file_name_pdf.'.pdf';
    // $show_pdf = CustomAsset('certificates/certificate/'.$file_name_pdf.'.pdf');
    // $physical_pdf = public_path() . '/certificates/certificate/'.$file_name_pdf.'.pdf';
    if(!isset($folder))
        $folder = 'certificate';

    $show_pdf = CustomAsset('certificates/'.$folder.'/'.$file_name_pdf.'.pdf');
    $physical_pdf = public_path() . '/certificates/'.$folder.'/'.$file_name_pdf.'.pdf';
?>
<div class="col-12 text-center">
    @if(file_exists($physical_pdf))
        <?php /* ?>
        <embed class="embed_pdf" src="{!!$show_pdf!!}#view=Fit&=<?=time();?>" type="application/pdf" width="100%" height="680px" />
        <?php */  ?>
        <style>
        #adobe-dc-view {
            height: 600px;
        }
        @media (max-width: 767.98px) {

            #adobe-dc-view {
                height: 554px;
            }
        }
        </style>
        <div id="adobe-dc-view"></div>
        <script src="https://documentcloud.adobe.com/view-sdk/main.js"></script>
        <script type="text/javascript">
            document.addEventListener("adobe_dc_view_sdk.ready", function(){
                var adobeDCView = new AdobeDC.View({clientId: "288b6dcfd4b5432c9603f104dfb93981", divId: "adobe-dc-view"});
                adobeDCView.previewFile({
                    content:{location: {url: "{{$show_pdf}}"}},
                    metaData:{fileName: "{{$course_title??'preview_pdf'}}"}
                }, {showAnnotationTools: false, dockPageControls: false, showDownloadPDF: true,
                    showPrintPDF: true, showLeftHandPanel: false, showPageControls: false});
            });
        </script>
    @else
        <div style="direction: ltr;" class="alert alert-danger alert-dismissible" role="alert">
                <strong>Certificate PDF Not Generated!</strong>
            <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">×</span>
            </button>
        </div>
    @endif
</div>
