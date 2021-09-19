@extends(FRONT.'.education.layouts.master')

<style>
    /* label{
        font-weight: bold !important;
    } */
    textarea[name="q_12"] {
        display:none;
    }
</style>

@section('content')

<?php use App\Helpers\Recaptcha; ?>
{!! Recaptcha::script() !!}

@include(FRONT.'.education.Html.page-header', ['title'=>__('education.courses'). ' | '. $questions[0]->exams->trans_title??null])

<div class="main-content py-5" id="app-register-form">
    <div class="container container-padding">

        @include('front.education.Html.alert')
        {{-- @include('front.education.Html.errors') --}}

        <div class="row">

            <div class="col mt-3">
                <div class="form-wrapper">
                    <div class="row">
                        <a target="_blank" href="{{route('education.courses.question.exportCipdToDoc', 1)}}" class="btn btn-sm btn-success"><span>Download CIPD Filled Form</span></a>

                        {{-- <form class="row" action="{{route('education.courses.question.submit', ['slug'=>$course->slug, 'session_id'=>request()->session_id??null])}}" method="post"> --}}
                        <form class="row col-md-12" action="{{route('education.courses.question.submit')}}" method="post" enctype="multipart/form-data">
                            <div class="col-md-12">
                                @csrf
                                {!! Recaptcha::execute() !!}

                                <div class="row">
                                    <input type="hidden" name="exam_id" value="{{$exam_id??null}}">
                                    <input type="hidden" name="cart_id" value="{{$cart_id??null}}">

                                    @forelse ($questions as $question)
                                        <?php
                                            $type = $question->type??'';
                                            $col = ($type == 'Textarea' || $type == 'CheckBox1') ? 'col-md-12' : 'col-md-6';
                                            $col .= ($question->id == 16) ? ' mt-3' : '';

                                            $row = ($type == 'Textarea') ? 7 : null;
                                        ?>
                                        {!!Builder::$type("q_".$question->id, $question->trans_question, null, ['col'=> $col, 'row'=>$row, 'db_trans'=>true])!!}
                                    @empty
                                        __('education.There is no questions.')
                                    @endforelse


                                </div>

                                <div class="row submit_loading_div" style="padding-top: 15px;border-top: 1px solid #e5e5e5;margin-top: 10px;">
                                    <div class="col-lg-6 col-md-6">
                                        <button type="submit" class="btn btn-primary btn-block btn-lg">{!! __('education.submit') !!}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
//   jQuery('.date_piker').datepicker({
//       format: "dd-mm-yyyy",
//   });
    jQuery('[name="q_12"]').siblings().hide();
    $('[name="q_11"]').change(function(){

        if(jQuery(this).is(':checked')){
            jQuery('[name="q_12"]').show();
            jQuery('[name="q_12"]').siblings().show();
        }
        else {
            jQuery('[name="q_12"]').hide();
            jQuery('[name="q_12"]').siblings().hide();
        }
    });
});
</script>
@endsection
