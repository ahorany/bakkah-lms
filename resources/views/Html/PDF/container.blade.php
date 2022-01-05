{{-- <embed width="100%" height="600px" id="update_file_source" src='' > --}}
{{-- <iframe width="100%" height="600px" id="update_file_source" src='' style="border: 1px solid #eaeaea;" ></iframe> --}}
<style>
    #the-canvas {
        /* border: 1px solid #eaeaea; */
        direction: ltr;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js" integrity="sha512-Z8CqofpIcnJN80feS2uccz+pXWgZzeKxDsDNMD/dJ6997/LSRY+W4NmEt9acwR+Gt9OHN0kkI1CTianCwoqcjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//mozilla.github.io/pdf.js/build/pdf.js"></script>
<style>
    #update_file_source {
        background-color: #f4f4f4;
        border-bottom: 2px solid #eaeaea;
        margin-top: 2px;
    }
    #prev, #next {
        border: 1px solid #eaeaea;
        background-color: #fafafa;
        border-radius: 3px;
        padding: 4px 10px;
        margin: 5px 1px 5px 1px;
        display: inline-block;
        font-weight: 500;
        cursor: pointer;
        width: 100px;
    }
    #prev:hover, #next:hover {
        border-color: #999999;
        background-color: #eaeaea;
    }
</style>
<div id="the-container">
    <div id="update_file_source" class="text-center">
        <button id="prev">Previous</button>

        ( <span>Page: <span id="page_num"></span> / <span id="page_count"></span></span> )

        <button id="next">Next</button>
    </div>
    <canvas id="the-canvas"></canvas>
</div>
<script>

var url = "{{CustomAsset('upload/files/presentations/'.$file)}}"
{{--var url = "{{CustomAsset('upload/files/presentations/2021-12-30-10-19-28_document_1_.pdf')}}"--}}

// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];

pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';
var container = document.getElementById('the-canvas');

// var scale = screen.width/1000;
var pdfDoc = null,
    pageNum = 1,
    pageRendering = false,
    pageNumPending = null;
    // scale = scale,
    // scale = 1.3,
    // canvas = document.getElementById('the-canvas'),
    // ctx = canvas.getContext('2d');

/**
* Get page info from document, resize canvas accordingly, and render page.
* @param num Page number.
*/
function renderPage(num) {
    pageRendering = true;
    // Using promise to fetch the page
    pdfDoc.getPage(num).then(function(page) {

        var PRINT_RESOLUTION = 150;
        var PRINT_UNITS = PRINT_RESOLUTION / 72.0;

        var container = document.getElementById('the-container');
        var canvas = document.getElementById('the-canvas');
        var context = canvas.getContext('2d');

        var viewport = page.getViewport({scale: 1});
        var scale = container.clientWidth / viewport.width;
        viewport = page.getViewport({scale: scale});

        // var viewport = page.getViewport({scale: scale});
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        // Render PDF page into canvas context
        var renderContext = {
            canvasContext: context,
            viewport: viewport,
            // transform: [PRINT_UNITS, 0, 0, PRINT_UNITS, 0, 0],
        };
        var renderTask = page.render(renderContext);

        // Wait for rendering to finish
        renderTask.promise.then(function() {
        pageRendering = false;
        if (pageNumPending !== null) {
            // New page rendering is pending
            renderPage(pageNumPending);
            pageNumPending = null;
        }
        });
    });

    // Update page counters
    document.getElementById('page_num').textContent = num;
}
/*
function renderPage(activeService, pdfDocument, pageNumber, size) {
  var scratchCanvas = activeService.scratchCanvas;
  var PRINT_RESOLUTION = 300;
  var PRINT_UNITS = PRINT_RESOLUTION / 72.0;
  scratchCanvas.width = Math.floor(size.width * PRINT_UNITS);
  scratchCanvas.height = Math.floor(size.height * PRINT_UNITS);
  var width = Math.floor(size.width * _ui_utils.CSS_UNITS) + 'px';
  var height = Math.floor(size.height * _ui_utils.CSS_UNITS) + 'px';
  var ctx = scratchCanvas.getContext('2d');
  ctx.save();
  ctx.fillStyle = 'rgb(255, 255, 255)';
  ctx.fillRect(0, 0, scratchCanvas.width, scratchCanvas.height);
  ctx.restore();
  return pdfDocument.getPage(pageNumber).then(function (pdfPage) {
    var renderContext = {
      canvasContext: ctx,
      transform: [PRINT_UNITS, 0, 0, PRINT_UNITS, 0, 0],
      viewport: pdfPage.getViewport(1, size.rotation),
      intent: 'print'
    };
    return pdfPage.render(renderContext).promise;
  }).then(function () {
    return {
      width: width,
      height: height
    };
  });
}
*/

/**
* If another page rendering in progress, waits until the rendering is
* finised. Otherwise, executes rendering immediately.
*/
function queueRenderPage(num) {
    if (pageRendering) {
        pageNumPending = num;
    } else {
        renderPage(num);
    }
}

/**
* Displays previous page.
*/
function onPrevPage() {
    if (pageNum <= 1) {
        return;
    }
    pageNum--;
    queueRenderPage(pageNum);
}
document.getElementById('prev').addEventListener('click', onPrevPage);

/**
* Displays next page.
*/
function onNextPage() {
    if (pageNum >= pdfDoc.numPages) {
        return;
    }
    pageNum++;
    queueRenderPage(pageNum);
}
document.getElementById('next').addEventListener('click', onNextPage);

/**
* Asynchronously downloads PDF.
*/
pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
pdfDoc = pdfDoc_;
document.getElementById('page_count').textContent = pdfDoc.numPages;

// Initial/first page rendering
renderPage(pageNum);
});
</script>
