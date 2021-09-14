@if(isset($navbar_campaign))
<div class="top_bar advertisement_bar bg-primary">
    <div class="close-banner">
        <i class="fa fa-times"></i>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="headerstrip-text" style="">
                    {!! $navbar_campaign->details !!}
                    <a href="{{$navbar_campaign->url}}" class="btn btn-info btn-sm mx-3">{{__('education.Read More')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
