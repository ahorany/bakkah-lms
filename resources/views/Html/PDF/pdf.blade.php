<link rel="stylesheet" href="{{CustomAsset('pdfviewer/pdf_viewer.min.css')}}?v={{time()}}">
<script src="{{CustomAsset('pdfviewer/pdf.min.js')}}?v={{time()}}"></script>
<script src="{{CustomAsset('pdfviewer/pdf_viewer.min.js')}}?v={{time()}}"></script>
<script src="{{CustomAsset('pdfviewer/preview.js')}}?v={{time()}}"></script>
<script src="{{CustomAsset('pdfviewer/canvas-to-blob.min.js')}}?v={{time()}}"></script>

<iframe class="google-viewer" width="100%" src="{{CustomAsset('upload/files/presentations/'.$file)}}" allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen" webkitallowfullscreen="webkitallowfullscreen" height="684.45"></iframe>
