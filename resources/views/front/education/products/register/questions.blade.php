<div class="row">
    <div class="col mt-3">
        <div class="form-wrapper">
            <div class="row">
                {{--<a target="_blank" href="{{route('education.courses.question.exportCipdToDoc', 1)}}" class="btn btn-sm btn-success"><span>Download CIPD Filled Form</span></a>--}}
                <div class="col-md-12">
                    <div class="row">
                        <input type="hidden" name="exam_id" value="{{$exam_id??null}}">

                        @forelse ($questions as $question)
                            <?php
                                $type = $question->type??'';
                                $col = ($type == 'Textarea' || $type == 'CheckBox1') ? 'col-md-12' : 'col-md-6 align-self-end';
                                $col .= ($question->id == 16) ? ' mt-3' : '';

                                $row = ($type == 'Textarea') ? 5 : null;
                            ?>
                            {!!Builder::$type("q_".$question->id, $question->trans_question, null, ['col'=> $col, 'row'=>$row, 'db_trans'=>true, 'validation' => $question->validation])!!}
                        @empty
                            There is no questions
                        @endforelse

                    </div>
                    {{--<div class="row submit_loading_div" style="padding-top: 15px;border-top: 1px solid #e5e5e5;margin-top: 10px;">
                        <div class="col-lg-6 col-md-6">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">{!! __('education.submit') !!}</button>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
    </div>
</div>
