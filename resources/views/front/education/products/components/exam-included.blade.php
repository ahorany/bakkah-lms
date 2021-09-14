@if($exam_is_included==1 && $exam_price!=0)
    <p class="exam-include">
        <small class="fas fa-plus"></small>
        {{__('education.Exam Included')}}
    </p>
@endif
