<?php

use App\Helpers\Date;
use App\Models\Training\Webinar;

if(isset($certificate->upload->file))
{
    $src = $certificate->upload->file;
?>

{{-- <img src="https://bakkah.com/public/certificates/img/{{$src}}" style="width:{{$width}};height: {{$height}}"> --}}
    @foreach($childs as $child)
        <?php
            if(isset($course) && $course != '')
            {

                // $training_option = $course->trainingOption->constant->id??353;
                $training_option = $course->training_option_id??353;
                $gender = 'he';

                if($user[0]->gender_id != 43)
                    $gender = 'she';

                if (strpos($child->content,  '${finished}') !== false)
                {
                    if( $gender == 'he')
                        $child->content=  str_replace('${finished}','أنهي',$child->content);
                    else
                        $child->content=  str_replace('${finished}','أنهت',$child->content);
                }
                if (strpos($child->content,  '${literal_date}') !== false)
                {
                    $child->content=  str_replace('${literal_date}','<h6 style="background-color: var(--mainColor);color: #fff;margin: 0;margin-top: 20px;padding: 5px 10px;width: 150px;text-align: center;">'.date_format(now(), 'l, F d, Y').'</h6>',$child->content);
                }
                // if (strpos($child->content,  '${from_date_ar}') !== false)
                // {
                //     $date = Date::replace_month_ar($course_registration->created_at??null);
                //     $child->content=  str_replace('${from_date_ar}',$date,$child->content);
                // }
                if (strpos($child->content,  '${from_date_en}') !== false)
                {

                    $s = $course_registration->date_from;
                    $date = strtotime($s);
                    $date_from = date('d F,Y', $date);
                    $child->content=  str_replace('${from_date_en}',($date_from??null),$child->content);
                }
                // if (strpos($child->content,  '${to_date_ar}') !== false)
                // {
                //     $date = Date::replace_month_ar($course_registration->expire_date??null);
                //     $child->content=  str_replace('${to_date_ar}',$date,$child->content);
                // }
                if (strpos($child->content,  '${to_date_en}') !== false)
                {
                    // dd($course_registration->date_to->format('d F,Y'));
                    $s = $course_registration->date_to;
                    $date = strtotime($s);
                    $expire_date = date('d F,Y', $date);
                    $child->content=  str_replace('${to_date_en}',$expire_date??null,$child->content);
                }

                if (strpos($child->content,  '${gender}') !== false)
                {
                    $child->content=  str_replace('${gender}',$gender,$child->content);
                }
                if (strpos($child->content,  '${name_en}') !== false)
                {
                    $child->content=  str_replace('${name_en}',$user[0]->name,$child->content);
                }
                if (strpos($child->content,  '${name_ar}') !== false)
                {
                    $child->content=  str_replace('${name_ar}',$user[0]->name,$child->content);
                }
                if (strpos($child->content,  '${certificate}') !== false)
                {
                    // if(isset($is_webinar ))
                    //     $child->content=  str_replace('${certificate}',$cart->webinar->en_title??null,$child->content);
                    //  else
                    //  {
                        // if($cart->trainingOption->type_id==370)
                        //     $child->content=  str_replace('${certificate}',$cart->course->ar_disclaimer??$cart->course->en_title.' Live Online Training ',$child->content);
                        // else
                            $child->content=  str_replace('${certificate}',$course->en_title??null,$child->content);
                    //  }

                }
                if (strpos($child->content,  '${certificate_en}') !== false)
                {
                    $child->content=  str_replace('${certificate_en}',$course->en_title,$child->content);
                }
                if (strpos($child->content,  '${certificate_ar}') !== false)
                {
                    $child->content=  str_replace('${certificate_ar}',$course->ar_title,$child->content);
                }


                if (strpos($child->content,  '${certificate_no}') !== false) {
                    $child->content=  str_replace('${certificate_no}',$course->certificate_no,$child->content);
                }
                // if (strpos($child->content,  '${course_days_no}') !== false) {
                //     $child->content=  str_replace('${course_days_no}',$cart->duration,$child->content);
                // }

                if (strpos($child->content,  '${hours_no}') !== false)
                {
                    $child->content = str_replace('${hours_no}', $course->PDUs, $child->content);
                }
                if (strpos($child->content,  '${date_from}') !== false)
                {
                    // if(isset($is_webinar ))
                    //     $child->content=  str_replace('${date_from}',$cart->webinar->certificate_from??null,$child->content);
                    // else
                        $child->content=  str_replace('${date_from}',($course_registration->created_at??null),$child->content);
                }
                if (strpos($child->content,  '${date_to}') !== false)
                {
                    $child->content=  str_replace('${date_to}',$course_registration->expire_date??null,$child->content);
                }
                if (strpos($child->content,  '${city}') !== false)
                {
                    // $city_id = $cart->b2b->city_id??'Riyadh';
                    $city_id = 'Riyadh';
                    // if($cart->trainingOption->type_id==370)
                    //     $child->content=  str_replace('${city}',$city_id.'-'.'Saudi Arabia'??null,$child->content);
                    // else
                        $child->content=  str_replace('${city}',' Riyadh - Saudi Arabia'??null,$child->content);
                }
                if (strpos($child->content,  '${barcode}') !== false)
                {
                    $child->content=  str_replace('${barcode}','<barcode code="'.$data_for_qr.'" type="QR" class="barcode" size="0.9" error="L" disableborder="I" />',$child->content);
                }
                if (strpos($child->content,  '${cource_img}') !== false)
                {
                    // dd($course->upload->file);
                    // if($cart->course->type_id != 370)
                    // {
                    //      if(env('NODE_ENV')=='production')
                    //         $path = env('APP_URL');
                    //     else
                    //         $path = "https://bakkah.com/";
                        // dd($course->upload->file);
                        if(isset($course->upload->file))
                        {
                            $child->content=  str_replace('${cource_img}','<img src="https://stage.bakkah.com/public/upload/thumb300/'.$course->upload->file.'" style="width: 200px;display: block;margin: 0 auto;margin-top: 5px;">',$child->content);
                        }


                    // }
                }
                if (strpos($child->content,  '${signature}') !== false)
                {
                    $child->content=  str_replace('${signature}',' <div style="display: inline-block;width: 100%;margin-bottom: 5px;margin-top: 80px;">
                    <div><h3 style="font-style: italic;">Nawar Saleh Nur</h3>
                    <h4 style="color: #808080;">Education Services Director</h4>
                    <img src="https://bakkah.com/public/certificates/img/Nawar-Signature.png" style="width: 200px;display: block;margin: 0 auto;margin-top: 5px;"></div></div>',$child->content);
                }
                // if (strpos($child->content,  '${trainer_name}') !== false)
                // {
                //     $dd = $cart->session;
                //     $child->content=  str_replace('${trainer_name}',$cart->session->usersSessions()->where('post_type','trainer')->first()->user->trans_name??null,$child->content);
                // }
                if (strpos($child->content,  '${hourOrhours}') !== false)
                {
                    // dd(500);
                    $hours = $course->PDUs??null;

                    $word_hour = 'ساعة';
                    if($hours>2 && $hours <11)
                        $word_hour = 'ساعات';

                    $child->content=  str_replace('${hourOrhours}',$word_hour,$child->content);
                }

            }

            $text_align = ''; $justify = '';
            if($child->align == 495)
                $text_align = 'text-align:center;';
            elseif($child->align == 494 )
                $text_align = 'text-align:left;';
            elseif($child->align == 493 )
                $text_align = 'text-align:right;';
            elseif($child->align == 489 )
            {
                $justify = 'width:75%;';
                // dump($child->content);
            }


            $y_axis = deviation_improve_y($child->ypdf_axis);
            $x_axis = deviation_improve_x($child->xpdf_axis);
            // dump($y_axis.'---'.$child->id);
            // $y_axis = $child->ypdf_axis;
        ?>

        @if($text_align == '' && $justify == ''){{--choose value--}}
            <div style="position: fixed;top:{{$y_axis}}px;left:{{$x_axis}}px;"> {!! $child->content !!}</div>
        @elseif($text_align == '') {{--justify--}}
            <div style="{{$justify}}position: fixed;top:{{$y_axis}}px;left:{{$x_axis}}px;">{!! $child->content !!}</div>
        @else
            <div style="{{$text_align}}position: fixed;top:{{$y_axis}}px;"> {!! $child->content !!}</div>
        @endif
    @endforeach

<?php
}
?>
<style>

@page
{
    @if(env('NODE_ENV')=='production')
        background: url("{{env('APP_URL')}}/public/certificates/img/<?= $src?>");
    @else
        background: url("https://bakkah.com/public/certificates/img/<?= $src?>");
    @endif

    background-image-resize:6;
    background-image-resolution: from-image;
}

div
{
    margin: auto;
    /* padding:0; */
    /* margin-left : <?=$certificate->margin ?>%;
    margin-right : <?=$certificate->margin ?>%; */
    width: 100%;
    /* font-family: 'lato', sans-serif, serif; */
}

</style>

