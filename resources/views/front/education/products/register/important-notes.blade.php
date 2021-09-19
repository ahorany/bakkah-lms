@if($course->take2_price > 0)
    <div class="row">
        <div class="container container-padding py-3">
            <div class="important-notes border p-4">
                <h3>{{__('education.Important Notes')}}</h3>
                <ul class="list-unstyled check-list" style="margin: 0px 0px 0px 18px;">
                    <li><b>{{__('education.Exam take2 is available')}}</b></li>
                </ul>
            </div>
        </div>
    </div>
@endif
