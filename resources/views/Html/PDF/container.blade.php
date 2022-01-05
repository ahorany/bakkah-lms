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
    #the-container{
        position: relative;
    }
    /* canvas{
        height: 1000px;
    } */
    #update_file_source {
        background-color: #f4f4f4;
        border-bottom: 2px solid #eaeaea;
        margin-top: 2px;
        padding: 10px 0;
    }
    #prev, #next {
        /* border-radius: 3px; */
        /* padding: 4px 10px; */
        /* margin: 5px 1px 5px 1px; */
        /* display: inline-block; */
        /* font-weight: 500; */
        /* text-align: center; */
        /* width: 100px; */
        border: 1px solid #f0f0f0;
        background-color: transparent;
        cursor: pointer;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        position: absolute;
    }
    button#prev {
        left: 15px;
    }
    button#next {
        right: 15px;
    }
    #prev:hover, #next:hover {
        border-color: transparent;
        background: #fff;
        box-shadow: 0 0 10px 1px #f0f0f0;

    }


    .anim2 {
        width: 50px;
        height: 50px;
        background-color: #fff;
        position: absolute;
        left: 50%;
        top: 50%;

        border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        -ms-border-radius: 50%;
        -o-border-radius: 50%;
        border: 5px solid darkblue;
        border-left-color: transparent;

        animation-name: spin;
        animation-duration: 3s;
        animation-iteration-count: infinite;
        animation-timing-function: linear;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
        }
    }

    /* by esraa eid 31-12-2021 */
</style>
<div id="the-container">
    <div id="update_file_source" class="text-center">

        <button id="prev">
            <svg id="Group_92" data-name="Group 92" xmlns="http://www.w3.org/2000/svg" width="10px" height="auto" viewBox="0 0 14.836 24.835">
                <path id="Path_99" data-name="Path 99" d="M161.171,218.961a1.511,1.511,0,0,1-1.02-.4l-11.823-10.909a1.508,1.508,0,0,1,0-2.215l11.823-10.912a1.508,1.508,0,0,1,2.045,2.215l-10.625,9.8,10.625,9.8a1.508,1.508,0,0,1-1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="#8a8a8a"/>
            </svg>
        </button>

        ( <span>Page: <span id="page_num"></span> / <span id="page_count"></span></span> )

        <button id="next">
            <svg id="Group_92" data-name="Group 92" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="auto" viewBox="0 0 14.836 24.835">
                <defs>
                <linearGradient id="linear-gradient" x1="-1623.535" y1="17.172" x2="-1624.535" y2="17.172" gradientUnits="objectBoundingBox">
                    <stop offset="0" stop-color="#8a8a8a"/>
                    <stop offset="0.564" stop-color="#f7ba50"/>
                    <stop offset="1" stop-color="#f7b243"/>
                </linearGradient>
                </defs>
                <path id="Path_99" data-name="Path 99" d="M149.351,218.961a1.511,1.511,0,0,0,1.02-.4l11.823-10.909a1.508,1.508,0,0,0,0-2.215l-11.823-10.912a1.508,1.508,0,0,0-2.045,2.215l10.625,9.8-10.625,9.8a1.508,1.508,0,0,0,1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="url(#linear-gradient)"/>
            </svg>
        </button>
    </div>
    <div class="anim2"></div>
    <canvas id="the-canvas"></canvas>
</div>
<script>

var url = "{{CustomAsset('upload/files/presentations/'.$file)}}"
// console.log(url);
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

        // const scale = 1.5;
        var viewport = page.getViewport({scale: 1});
        var scale = container.clientWidth / viewport.width;
        viewport = page.getViewport({ scale });
        const outputScale = window.devicePixelRatio || 1;
        canvas.width = Math.floor(viewport.width * outputScale);
        canvas.height = Math.floor(viewport.height * outputScale);
        canvas.style.width = Math.floor(viewport.width) + "px";
        canvas.style.height = Math.floor(viewport.height) + "px";
        const transform = outputScale !== 1
        ? [outputScale, 0, 0, outputScale, 0, 0]
        : null;
        console.log(transform);
        // var viewport = page.getViewport({scale: 1});
        // var scale = container.clientWidth / viewport.width;
        // viewport = page.getViewport({scale: scale});

        // var viewport = page.getViewport({scale: scale});
        // canvas.height = viewport.height;
        // canvas.width = viewport.width;

        // Render PDF page into canvas context
        var renderContext = {
            canvasContext: context,
            viewport: viewport,
            transform: transform,
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
    document.querySelector('.anim2').remove();
});

document.addEventListener('keydown', logKey);

function logKey(e) {
//   log.textContent += ` ${e.code}`;
    if(e.code=='ArrowRight'){
        onNextPage();
    }
    else if(e.code=='ArrowLeft'){
        onPrevPage();
    }
}
</script>
