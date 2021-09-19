<div class="accordion">
    @foreach($postMorph->accordions as $accordion)
        <div class="accordion-single">
            <div class="card-header" id="heading-{{$accordion->id}}">
                <h2 class="mb-0">
                    {{-- @dump($accordion->id) --}}
                    <button class="btn btn-link btn-block text-left" type="button" data-target="#faq-{{$accordion->id}}" aria-expanded="true" aria-controls="faq-{{$accordion->id}}">
                        {{$accordion->trans_title}}

                        @if(request()->has('openaccordion'))
                            @if(request()->openaccordion=='riad')
                                <input onclick="myFunction({{$accordion->id}})" type="text" id="faqsss-{{$accordion->id}}" value="{{$accordion->trans_title}}">
                            @endif
                        @endif
                        <i class="fas fa-chevron-down"></i>
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
@section('scripts')
    <script>
        function myFunction(id) {
            var copyText = document.getElementById('faqsss-'+id);
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            // alert("Copied the text: " + copyText.value);
        }

        jQuery(function(){
            jQuery('.modal').modal({
                show: false
            }).on('hidden.bs.modal', function(){
                jQuery(this).find('video')[0].pause();
            });
        });
    </script>
@endsection
