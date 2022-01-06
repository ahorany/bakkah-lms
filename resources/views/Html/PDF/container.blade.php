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

    .anim2{
        /* position: relative; */
        min-height: 300px;
    }

    .anim2 svg{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* .anim2 {
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
    } */

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
    <div class="anim2">
        <svg id="Group_95" data-name="Group 95" xmlns="http://www.w3.org/2000/svg" width="125"  viewBox="0 0 245.935 343.644">
            <path id="Path_100" data-name="Path 100" d="M1904.381,772.014a8.654,8.654,0,0,0,8.654,8.654h228.627a8.654,8.654,0,0,0,8.654-8.654v-213.7H1904.381Z" transform="translate(-1904.381 -437.024)" fill="#f5f5f5"/>
            <path id="Path_101" data-name="Path 101" d="M2095.177,152.9H1913.035a8.654,8.654,0,0,0-8.654,8.654V274.187h245.935V208.036Z" transform="translate(-1904.381 -152.897)" fill="#fb4400"/>
            <path id="Path_102" data-name="Path 102" d="M2542.123,199.382a8.654,8.654,0,0,0,8.654,8.654h46.485L2542.123,152.9Z" transform="translate(-2351.327 -152.897)" fill="#ff6561"/>
            <g id="Group_93" data-name="Group 93" transform="translate(22.087 144.59)">
            <rect id="Rectangle_51" data-name="Rectangle 51" width="196.278" height="6.227" fill="#ededed"/>
            <rect id="Rectangle_52" data-name="Rectangle 52" width="196.278" height="6.227" transform="translate(0 20.043)" fill="#ededed"/>
            <rect id="Rectangle_53" data-name="Rectangle 53" width="196.278" height="6.227" transform="translate(0 40.087)" fill="#ededed"/>
            <rect id="Rectangle_54" data-name="Rectangle 54" width="196.278" height="6.227" transform="translate(0 60.13)" fill="#ededed"/>
            <rect id="Rectangle_55" data-name="Rectangle 55" width="196.278" height="6.227" transform="translate(0 80.173)" fill="#ededed"/>
            <rect id="Rectangle_56" data-name="Rectangle 56" width="196.278" height="6.227" transform="translate(0 100.217)" fill="#ededed"/>
            <rect id="Rectangle_57" data-name="Rectangle 57" width="196.278" height="6.227" transform="translate(0 120.26)" fill="#ededed"/>
            <rect id="Rectangle_58" data-name="Rectangle 58" width="196.278" height="6.227" transform="translate(0 140.303)" fill="#ededed"/>
            <rect id="Rectangle_59" data-name="Rectangle 59" width="196.278" height="6.227" transform="translate(0 160.347)" fill="#ededed"/>
            </g>
            <g id="Group_94" data-name="Group 94" transform="translate(23.138 33.444)">
            <path id="Path_103" data-name="Path 103" d="M1981.722,264.684h19.255c9.858,0,16.636,5.468,16.636,15.019,0,9.7-6.7,15.4-17.175,15.4h-14.479V318.6h-4.236Zm4.236,26.571h14.865c8.7,0,12.323-4.621,12.323-11.4,0-7.24-4.775-11.322-12.015-11.322h-15.173Z" transform="translate(-1981.722 -264.684)" fill="#fff"/>
            <path id="Path_104" data-name="Path 104" d="M2138.246,264.684h17.868c14.788,0,25.031,10.32,25.031,26.494,0,16.1-10.012,27.418-25.108,27.418h-17.791Zm4.159,50.062h13.016c14.249,0,21.257-10.012,21.257-23.336,0-11.861-6.315-22.874-21.257-22.874H2142.4Z" transform="translate(-2091.418 -264.684)" fill="#fff"/>
            <path id="Path_105" data-name="Path 105" d="M2321.286,264.684h32.656v3.851h-28.5v20.333h26.725v3.851h-26.725V318.6h-4.159Z" transform="translate(-2219.697 -264.684)" fill="#fff"/>
            </g>
        </svg>
    </div>
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
    pageNumPending = null

/**
* Get page info from document, resize canvas accordingly, and render page.
* @param num Page number.
*/
function renderPage(num) {
    pageRendering = true;
    // Using promise to fetch the page
    pdfDoc.getPage(num).then(function(page) {

        var PRINT_RESOLUTION = 300;
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
