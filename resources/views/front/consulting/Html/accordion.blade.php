<div class="accordion">
    @foreach($postMorph->accordions as $accordion)
        <div class="accordion-item">
            <div class="card-header" id="heading-{{$accordion->id}}">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#faq-{{$accordion->id}}" aria-expanded="true" aria-controls="faq-{{$accordion->id}}">
                        {{$accordion->trans_title}}
                        <i class="fas fa-chevron-down fa-chevron-up"></i>
                    </button>
                </h2>
            </div>

            <div id="faq-{{$accordion->id}}" class="collapse {{$loop->index==0?'show':''}}" aria-labelledby="heading-{{$accordion->id}}">
                <div class="card-body">
                    {!! $accordion->trans_details !!}
                </div>
            </div>
        </div>
    @endforeach
</div>
