<div class="glance">
    <h2 class="mx-md-5">{!! __('education.bakkah_at_glance') !!}</span></h2>

    <div class="row no-gutters">
        @foreach($options as $option)
        <div class="col-6 col-sm-6">
            <div class="glance-numbers">
                <h3 class="main-color">{{$option->trans_title}}</h3>
                <p><span class="counter">{{$option->trans_excerpt}}</span>+</p>
            </div> <!-- /.glance-numbers -->
        </div>
        @endforeach
    </div> <!-- /.inner row -->
    {{--
    <div class="row no-gutters">
        <!-- <div class="col-6 col-md-3"> -->
        <div class="col-6 col-sm-6">
            <div class="glance-numbers">
                <h3 class="main-color">{{__('education.Capabilities Built')}}</h3>
                <p><span class="counter">20,000</span>+</p>
            </div> <!-- /.glance-numbers -->
        </div>

        <!-- <div class="col-6 col-md-3"> -->
        <div class="col-6 col-sm-6">
            <div class="glance-numbers">
                <h3 class="main-color">{{__('education.Learning Sessions')}}</h3>
                <p><span class="counter">1,000</span>+</p>
            </div> <!-- /.glance-numbers -->
        </div>

        <!-- <div class="col-6 col-md-3"> -->
        <div class="col-6 col-sm-6">
            <div class="glance-numbers">
                <h3 class="main-color">{{__('education.Client Engagements')}}</h3>
                <p><span class="counter">600</span>+</p>
            </div> <!-- /.glance-numbers -->
        </div>

        <!-- <div class="col-6 col-md-3"> -->
        <div class="col-6 col-sm-6">
            <div class="glance-numbers">
                <h3 class="main-color">{{__('education.Partners of Success')}}</h3>
                <p><span class="counter">300</span>+</p>
            </div> <!-- /.glance-numbers -->
        </div>
    </div> <!-- /.inner row -->
    --}}
</div> <!-- /.glance -->
