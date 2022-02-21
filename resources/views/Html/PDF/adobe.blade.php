<style>
    #adobe-dc-view {
        /* border: 1px solid #cccccc; */
        /* width: 100%; */
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
            var adobeDCView = new AdobeDC.View({clientId: "{{env('PDF_EMBED_API')}}", divId: "adobe-dc-view"});
            adobeDCView.previewFile({
                content:{location: {url: "{{$file}}"}},
                metaData:{fileName: "{{$title}}"}
            }, {showAnnotationTools: false, dockPageControls: false, showDownloadPDF: false,
                showPrintPDF: false, showLeftHandPanel: true, showPageControls: true});
        });
    </script>
