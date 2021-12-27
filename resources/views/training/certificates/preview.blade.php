<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dragg Components</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
  <?php
    // function get_browser_name($user_agent)
    // {
    //     $t = strtolower($user_agent);
    //     $t = " " . $t;
    //     if     (strpos($t, 'opera'     ) || strpos($t, 'opr/')     ) return 'Opera'            ;
    //     elseif (strpos($t, 'edge'      )                           ) return 'Edge'             ;
    //     elseif (strpos($t, 'chrome'    )                           ) return 'Chrome'           ;
    //     elseif (strpos($t, 'safari'    )                           ) return 'Safari'           ;
    //     elseif (strpos($t, 'firefox'   )                           ) return 'Firefox'          ;
    //     elseif (strpos($t, 'msie'      ) || strpos($t, 'trident/7')) return 'Internet Explorer';
    //     return 'Unkown';
    // }
    // $browser_name =  get_browser_name($_SERVER['HTTP_USER_AGENT']);


    if(isset($certificate->upload->file))
    {
      $src = CustomAsset('upload/cert_bg/'.$certificate->upload->file);
      $src = $certificate->upload->file;


    //   $width = '3508px';
    //   $height = '2480px';
      $widht_after_zoom = '701px';
      $height_after_zoom = '496px';
      if($certificate->direction == 490)//P
      {
        // $width = '2480px';
        // $height = '3508px';
        $widht_after_zoom = '496px';
        $height_after_zoom = '701px';
      }

    }
    ?>
<style>
    body {
    margin: 0;
    }
    </style>
</head>
<body>
    <div id="wrapper">
  @if(isset($src))
  <div class="zoom "  style="width:{{$widht_after_zoom}};height: {{$height_after_zoom}};background-color:green;">
    @if(env('NODE_ENV')=='production')
        <img  src="{{env('APP_URL')}}/public/certificates/img/{{$src}}"  style="width:{{$widht_after_zoom}};height: {{$height_after_zoom}}" >
    @else
        <img  src="https://bakkah.com/public/certificates/img/{{$src}}"  style="width:{{$widht_after_zoom}};height: {{$height_after_zoom}}" >
    @endif

    <?php
        $src = CustomAsset('upload/cert_bg/'.$certificate->upload->file);
    ?>
    <form action="{{ route('training.certificates.preview') }}" type="GET">

    <input type="hidden" name="id" value="{{$parent_id}}">

       @foreach($childs as $child)

            <?php

                if( $child->x_axis == null)
                    $child->x_axis = 200;
                if( $child->y_axis == null)
                    $child->y_axis = 100;
                if( $child->xpdf_axis == null)
                    $child->xpdf_axis = 100;
                if( $child->xpdf_axis == null)
                    $child->ypdf_axis = 100;

                $text_align = '';$justify = '';
                if($child->align == 495 && $certificate->direction == 490)//center portrait
                    $text_align = 'text-align:center;width:496px;margin:0 auto;';
                else if($child->align == 495 && $certificate->direction == 491)//center Landscape
                    $text_align = 'text-align:center;width:701px;margin:0 auto;';

                elseif($child->align == 494 && $certificate->direction == 490)//left portrait
                    $text_align = 'left:93px;text-align:left;';
                elseif($child->align == 494 && $certificate->direction == 491)//left Landscape
                    $text_align = 'left:120px;text-align:left;';

                elseif($child->align == 493 && $certificate->direction == 490)//right portrait
                    $text_align = 'right:790px;text-align:right;';
                elseif($child->align == 493 && $certificate->direction == 491)//right Landscape
                    $text_align = 'right:650px;text-align:right;';

                if($child->align == 489 && $certificate->direction == 490 )
                    $justify = 'width:520px;';
                elseif($child->align == 489 && $certificate->direction == 491 )
                    $justify = 'width:800px;';

            ?>

            {{-- @dd($child->y_axis) --}}
            @if($text_align == '' && $justify == '') {{-- choose value --}}
                <div class="dragabble choose" id="{{$child->id}}" style="position: fixed;top:{{$child->y_axis}}px; left:{{$child->x_axis}}px;" >{!! $child->content !!}</div>
            @elseif($text_align == '') {{-- justify --}}
                <div class="dragabble justify"  id="{{$child->id}}" style="{{$justify}}position: fixed;top:{{$child->y_axis}}px;left:{{$child->x_axis}}px;">{!! $child->content !!}</div>
            @else {{--c l r--}}
                <div class="dragabble aligning"  id="{{$child->id}}" style="{{$text_align}}position: fixed;top:{{$child->y_axis}}px;">{!! $child->content !!}</div>
            @endif

            {{-- @dump($child->x_axis) --}}
          <input type="hidden" name="x_{{$child->id}}" value="{{$child->x_axis}}">
          <input type="hidden" name="y_{{$child->id}}" value="{{$child->y_axis}}">

          <input type="hidden" name="xpdf_{{$child->id}}" value="{{$child->xpdf_axis}}">
          <input type="hidden" name="ypdf_{{$child->id}}" value="{{$child->ypdf_axis}}">

       @endforeach
    </div>
      {{-- <button type="submit" class="btn btn-sm btn-primary save_position2"  class="btn btn-sm btn-success">
            <i class="fa fa-plus"></i>
      </button> --}}
      <button class="save_position"  type="submit" role="button"> Save Position<i class="fa fa-plus"></i></button>

    </form>
    <a class="preview_pdf" href="{{route('training.certificates.preview_pdf', ['id'=> $parent_id ] )}}"

        target="blank" class="btn btn-success btn-xs mb-1">
                       Certificate
        </a>
      @endif
    </div>

</body>

</html>


<script>
// $('.dragabble').css("transform-origin", "");

$('.dragabble').draggable({
  drag: function() {
    //
    // alert($(this).attr('class'));
    var str = $(this).attr('class');
    if (str.indexOf("aligning") >= 0)
        $(this).css("transform-origin", "0 0");

    var id = $(this).attr('id');

    var style = $('#'+id).attr('style');
    var element = document.getElementById(id),
    style = window.getComputedStyle(element),
    top = style.getPropertyValue('top').slice(0,-2);
    left = style.getPropertyValue('left').slice(0,-2);

    height = style.getPropertyValue('height').slice(0,-2);
    width = style.getPropertyValue('width').slice(0,-2);

    var topOff = $(this).offset().top - $(window).scrollTop();
    var leftOff = $(this).offset().left - $(window).scrollLeft();
    // console.log(width+'--'+);
    // var y = parseFloat(topOff)-topOff/7;
    console.log(leftOff);

    $('input[name="x_'+id+'"]').val(left);
    $('input[name="y_'+id+'"]').val(top);

    $('input[name="xpdf_'+id+'"]').val(leftOff);
    $('input[name="ypdf_'+id+'"]').val(topOff);

  }

});

</script>

<style>
#wrapper {
height: auto;
width: 1200px;
margin: 0 auto; /*this will centralize the div, horizontal. so that it is nice looking in the middle on different computer screens. */
background-color: rgb(179, 226, 173);
left:0;
}

.zoom {
    border: 1px solid rgb(10, 10, 10);
    /* float: right; */
}

.dragabble {
     /* zoom:70%; */
     transform: scale(0.7, 0.7);
     /* border:solid 1px rgb(12, 12, 12); */
     padding:0;
     /* transform-origin : 0 0; */
     /* display: inline; */
    /* : 0 0; */
     /*-moz-transform: scale(0.7); */
}
.choose,.justify{
    transform-origin : 0 0;
}
span{
    line-height: 0;
}

.save_position,.preview_pdf{

  appearance: button;
  backface-visibility: hidden;

  border-radius: 6px;
  border-width: 0;
  box-shadow: rgba(50, 50, 93, .1) 0 0 0 1px inset,rgba(50, 50, 93, .1) 0 2px 5px 0,rgba(0, 0, 0, .07) 0 1px 1px 0;
  box-sizing: border-box;
  color: #fff;
  cursor: pointer;
  font-family: -apple-system,system-ui,"Segoe UI",Roboto,"Helvetica Neue",Ubuntu,sans-serif;
  font-size: 100%;
  height: 44px;
  line-height: 1.15;
  margin: 12px 0 0;
  outline: none;
  overflow: hidden;
  padding: 0 25px;
  position: relative;
  text-align: center;
  text-transform: none;
  transform: translateZ(0);
  transition: all .2s,box-shadow .08s ease-in;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  position: absolute;

}


.save_position {
    background-color: #405cf5;
    left: 80;
    top: 80px;
    right: 400px;
}

.preview_pdf {
    background-color: #07a714;
    left: 80;
    top: 80px;
    right: 250px;
}

</style>

