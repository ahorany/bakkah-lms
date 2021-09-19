<div id="{{$postMorph->constant->post_type}}">
    <div class="container">
        <h2 class="mb-5">{{$postMorph->constant->trans_name}}</h2>
        <div class="row">
            <div class="col-lg-8">
                @include(FRONT.'.education.Html.accordion')
            </div>
        </div>
    </div>
</div> <!-- #course-outline -->
