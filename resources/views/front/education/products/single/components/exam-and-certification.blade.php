<div class="py-5 bg-light" id="{{$detail->constant->post_type}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                @if($course->slug!='pmp_exam_simulators')
                    <h2 class="mb-5">{{$detail->constant->trans_name}}</h2>
                @else
                    <h2 class="mb-5">{{__('education.Exam')}}</h2>
                @endif
                <div id="Sample_Papers" class="row">
                    <div class="col-sm-12">

                        {!! $detail->trans_details !!}

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
