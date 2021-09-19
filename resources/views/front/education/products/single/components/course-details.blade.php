<div class="{{empty($CardsSingles) ? 'py-5' : '' }}" id="{{$detail->constant->post_type}}">
    <div class="container">

        <div class="row">
            <div class="col-lg-8">

                @if($option_slug == 'auto-date')
                {{-- @if($CardsSingles->option__post_type != 'exam-simulator') --}}
                {{-- @if($course->id!=32 && $course->id!=35 && $course->id!=36 && $course->id!=37) --}}
                    <h2 class="mb-5">{{$detail->constant->trans_name}}</h2>
                @else
                    <h2 class="mb-5 mt-5">{{__('education.Exam Details')}}</h2>
                @endif

                {!! $detail->trans_details !!}
            </div>
        </div>

    </div>
</div> <!-- #course-details -->
