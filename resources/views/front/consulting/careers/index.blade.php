@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(81)??null])
@endsection

@section('content')

    @include(FRONT.'.consulting.Html.page-header', ['title'=>__('consulting.Careers')])

    <section class="all-alliances py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="careers-intro">
                        <h2>{{__('consulting.Join our team')}}</h2>
                        {!! __('consulting.Join our team details') !!}
                    </div>
                </div>
            </div>

            <div class="row my-5">
                <div class="col-md-4">
                    <div class="career-box">
                        <div class="d-flex justify-content-between">
                            <div><small><i class="fas fa-briefcase"></i></small> Full time</div>
                            <div><small><i class="fas fa-map-marker-alt"></i></small> Riyadh, Saudi Arabia</div>
                        </div>
                        <h2>Contract Specialist</h2>
                        <h5>Position code: <span>OS011</span></h5>
                        <p class="text-justify">Who are we? We are young and agile national consulting and training company with a team of employees who are…</p>
                        <a class="btn btn-block btn-dark" href="{{CustomRoute('consulting.static.contactusIndex')}}">Apply Now</a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="career-box">
                        <div class="d-flex justify-content-between">
                            <div><small><i class="fas fa-briefcase"></i></small> Full time</div>
                            <div><small><i class="fas fa-map-marker-alt"></i></small> Riyadh, Saudi Arabia</div>
                        </div>
                        <h2>Contract Specialist</h2>
                        <h5>Position code: <span>OS011</span></h5>
                        <p class="text-justify">Who are we? We are young and agile national consulting and training company with a team of employees who are…</p>
                        <a class="btn btn-block btn-dark" href="{{CustomRoute('consulting.static.contactusIndex')}}">Apply Now</a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="career-box">
                        <div class="d-flex justify-content-between">
                            <div><small><i class="fas fa-briefcase"></i></small> Full time</div>
                            <div><small><i class="fas fa-map-marker-alt"></i></small> Riyadh, Saudi Arabia</div>
                        </div>
                        <h2>Contract Specialist</h2>
                        <h5>Position code: <span>OS011</span></h5>
                        <p class="text-justify">Who are we? We are young and agile national consulting and training company with a team of employees who are…</p>
                        <a class="btn btn-block btn-dark" href="{{CustomRoute('consulting.static.contactusIndex')}}">Apply Now</a>
                    </div>
                </div>

            </div>

        </div>
    </section>

@endsection
