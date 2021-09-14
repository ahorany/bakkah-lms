<div class="py-5 bg-light faq {{$type ?? ''}}" id="{{$postMorph->constant->post_type}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="mb-5">{{$postMorph->constant->trans_name}}</h2>
                @include(FRONT.'.education.Html.accordion')
            </div>
        </div>
    </div>
</div> <!-- #faqs -->
