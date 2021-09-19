@if(isset($navbar_campaign))
    <div class="top_bar advertisement_bar bg-primary">
        <div class="close-banner">
            <i class="fa fa-times"></i>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="headerstrip-text" style="">
                        <div class="d-lg-flex align-items-center">
                          <p class="boldfont" id="timecountdown"></p>
                          <p id="space" class="d-none d-lg-block mx-2"></p>
                          {!! $navbar_campaign->details !!}
                        </div>
                        <a href="{{$navbar_campaign->url}}" class="btn btn-info btn-sm mx-3">{{__('education.Enroll Now')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
