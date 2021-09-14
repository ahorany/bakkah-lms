@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>$post??null])
@endsection

@section('content')

    @include(FRONT.'.consulting.consulting-service.page-header')

    <!-- |||||||||||||||||||||||||| MAIN CONTENT OF PM SERVICES |||||||||||||||||||||||||||||| -->
    <div class="main-content py-5">
        <div class="container mb-5 wow fadeInUp">
            <div class="section-title text-center">
                <h2>{!! __('consulting.do_you_know') !!}</h2>
            </div>

            <div class="row mb-5 big-progress">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="d-flex consulting-progress  shadow p-4">
                        <div class="progress" data-value="36">
                            <span class="progress-left">
                                <span class="progress-bar border-info"></span>
                            </span>
                            <span class="progress-right">
                                <span class="progress-bar border-info" style="transform: rotate(129.6deg);"></span>
                            </span>
                            <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                              <div class="h4 font-weight-bold">36%</div>
                            </div>
                          </div>
                          <div class="mx-3">
                              <h1 class="boldfont">of Projects</h1>
                              <p class="lead m-0">fail because of bad governance and practices</p>
                          </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="d-flex consulting-progress  shadow p-4">
                        <div class="progress" data-value="21">
                            <span class="progress-left">
                                <span class="progress-bar border-info"></span>
                            </span>
                            <span class="progress-right">
                                <span class="progress-bar border-info" style="transform: rotate(129.6deg);"></span>
                            </span>
                            <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                              <div class="h4 font-weight-bold">21%</div>
                            </div>
                          </div>
                          <div class="mx-3">
                              <h1 class="boldfont">of Projects</h1>
                              <p class="lead m-0">do not meet their goals because of PMO absence
                            </p>
                          </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5 small-progress">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="d-flex consulting-progress  shadow p-4">
                        <div class="progress" data-value="50">
                            <span class="progress-left">
                                <span class="progress-bar border-dark"></span>
                            </span>
                            <span class="progress-right">
                                <span class="progress-bar border-dark" style="transform: rotate(129.6deg);"></span>
                            </span>
                            <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                              <div class="h5 font-weight-bold">50%</div>
                            </div>
                          </div>
                          <div class="mx-3">
                              <h3 class="boldfont">of Projects</h3>
                              <p class="lead m-0">experience scope changes</p>
                          </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="d-flex consulting-progress  shadow p-4">
                        <div class="progress" data-value="40">
                            <span class="progress-left">
                                <span class="progress-bar border-dark"></span>
                            </span>
                            <span class="progress-right">
                                <span class="progress-bar border-dark" style="transform: rotate(129.6deg);"></span>
                            </span>
                            <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                              <div class="h5 font-weight-bold">40%</div>
                            </div>
                          </div>
                          <div class="mx-3">
                              <h3 class="boldfont">of Projects</h3>
                              <p class="lead m-0">do not gain stakeholder satisfaction </p>
                          </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="d-flex consulting-progress  shadow p-4">
                        <div class="progress" data-value="25">
                            <span class="progress-left">
                                <span class="progress-bar border-dark"></span>
                            </span>
                            <span class="progress-right">
                                <span class="progress-bar border-dark" style="transform: rotate(129.6deg);"></span>
                            </span>
                            <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                              <div class="h5 font-weight-bold">25%</div>
                            </div>
                          </div>
                          <div class="mx-3">
                              <h3 class="boldfont">of Projects</h3>
                              <p class="lead m-0">spend more than its budget</p>
                          </div>
                    </div>
                </div>

            </div>

            <div class="row mb-5 thin-progress">
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="d-flex consulting-progress  shadow p-4">
                        <div class="progress" data-value="50">
                            <span class="progress-left">
                                <span class="progress-bar border-dark"></span>
                            </span>
                            <span class="progress-right">
                                <span class="progress-bar border-dark" style="transform: rotate(129.6deg);"></span>
                            </span>
                            <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                              <div class="h5 font-weight-bold">50%</div>
                            </div>
                          </div>
                          <div class="mx-3">
                              <h5 class="boldfont">of Projects</h5>
                              <p class="m-0">face priority changes </p>
                          </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="d-flex consulting-progress  shadow p-4">
                        <div class="progress" data-value="40">
                            <span class="progress-left">
                                <span class="progress-bar border-dark"></span>
                            </span>
                            <span class="progress-right">
                                <span class="progress-bar border-dark" style="transform: rotate(129.6deg);"></span>
                            </span>
                            <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                              <div class="h5 font-weight-bold">40%</div>
                            </div>
                          </div>
                          <div class="mx-3">
                              <h5 class="boldfont">of Projects</h5>
                              <p class="m-0">do not apply risk management practices</p>
                          </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="d-flex consulting-progress  shadow p-4">
                        <div class="progress" data-value="40">
                            <span class="progress-left">
                                <span class="progress-bar border-dark"></span>
                            </span>
                            <span class="progress-right">
                                <span class="progress-bar border-dark" style="transform: rotate(129.6deg);"></span>
                            </span>
                            <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                              <div class="h5 font-weight-bold">40%</div>
                            </div>
                          </div>
                          <div class="mx-3">
                              <h5 class="boldfont">of Projects</h5>
                              <p class="m-0">fail to finish on time</p>
                          </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="d-flex consulting-progress  shadow p-4">
                        <div class="progress" data-value="20">
                            <span class="progress-left">
                                <span class="progress-bar border-dark"></span>
                            </span>
                            <span class="progress-right">
                                <span class="progress-bar border-dark" style="transform: rotate(129.6deg);"></span>
                            </span>
                            <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                              <div class="h5 font-weight-bold">20%</div>
                            </div>
                          </div>
                          <div class="mx-3">
                              <h5 class="boldfont">of Projects</h5>
                              <p class="m-0">do not handover successfully to operation </p>
                          </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="devider pt-3">
            <div class="container">
                <hr>
            </div>
        </div>

        <div class="container consulting-plans pt-5 wow fadeInUp">
            <div class="section-title text-center mb-5">
                <h2>Bakkah Consulting helps you to achieve <span class="third-color">Results</span></h2>
                <h3>by optimizing your state of Project Management & PMO</h3>
            </div>
            <div class="row">

                <div class="col-md-4 mb-4">
                    <div class="bg-light main-text check-list p-4 mb-3">
                        <h3 class="text-center boldfont mb-4">Maturity Assessment</h3>
                        <ul>
                            <li>Observations</li>
                            <li>Benchmarking</li>
                            <li>P3M3 Maturity Level</li>
                            <li>Recommendations</li>
                            <li>Quick-wins</li>
                        </ul>
                    </div>
                    <div class="second-text">
                        <ul class="p-0 list-unstyled">
                            <li>Comprehensive observations report that identifies your organization’s current capabilities and weaknesses</li>
                            <li>Comprehensive recommendations report that eliminates root causes and reduce costs</li>
                            <li>Transformation plans and initiatives for improvements</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="bg-light main-text check-list p-4 mb-3">
                        <h3 class="text-center boldfont mb-4">PMO Advisory</h3>
                        <ul class="p-0">
                            <li>Governance & Frameworks</li>
                            <li>Methodologies & Policies</li>
                            <li>Processes, Templates & KPIs</li>
                            <li>PPM Competency Dictionary</li>
                            <li>Enablement & Implementation Plan</li>
                        </ul>
                    </div>
                    <div class="second-text">
                        <ul class="p-0 list-unstyled">
                            <li>Provide you with frameworks for better alignment with the organization strategy and realization of outcomes and benefits</li>
                            <li>Provide you with governance that clarify roles and responsibilities and increase transparency in your organization</li>
                            <li>Provide you with methodologies to support the successful delivery of portfolio, programs and projects and ensure the achievement of expected business results</li>
                            <li>Provide guidelines to improve change management and project management capabilities in the organizationس</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="bg-light main-text check-list p-4 mb-3">
                        <h3 class="text-center boldfont mb-4">Tools & Dashboards</h3>
                        <ul class="p-0">
                            <li>PPM Tools</li>
                            <li>PPM Reports</li>
                            <li>Customized Dashboards</li>
                        </ul>
                    </div>
                    <div class="second-text">
                        <ul class="p-0 list-unstyled">
                            <li>Provide you with interactive tools and dashboards that improve monitoring and controlling of your business</li>
                            <li>Provide you with customized reports that satisfies different stakeholders requirements</li>
                            <li>Support decision making in your organization</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="container container-padding">
            <div class="row">
                <div class="col-sm-12">

                    <div class="consulting-content">
                        <h2 class="fancy-title">{{$post->title}}</h2>
                        {!! $post->details !!}

                        @include(FRONT.'.Html.share')

                    </div>

                </div>
                <div class="col-sm-4 sidebar related_courses">

                    @include(FRONT.'.consulting.consulting-service.sections')

                </div>
            </div>
        </div> --}}
        <!-- <div class="extra_space"></div> -->
        <?php $path = FRONT.'.consulting.consulting-parts'; ?>
        @include($path.'.clients')

        <section class="py-5 mb-5 wow fadeInUp">
            <div class="container">
                <div class="section-title text-center mb-5">
                    <h2>Bakkah Management <span class="third-color">Consulting Methodology</span></h2>
                    <h3>it contains 4 steps and 10 perspectives</h3>
                </div>
                <div class="row align-items-center">
                    <div class="col">
                        <div class="method-box bg-light p-4">
                            <h3 class="text-center mb-3">Building Capabilities</h3>
                            <ul>
                                <li>Professional Programs</li>
                                <li>Executive Programs</li>
                                <li>Customized Programs</li>
                                <li>Professionals Outsourcing</li>
                                <li>Executive Headhunting</li>
                                <li>Board Services</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <img class="w-100" src="{{ CustomAsset('images/cunsulting.png') }}" alt="">
                    </div>
                    <div class="col">
                        <div class="method-box bg-light p-4">
                            <h3 class="text-center mb-3">Sustainable  Value Creation</h3>
                            <ul>
                                <li>Strategy Development</li>
                                <li>Corporate Governance</li>
                                <li>Frameworks & Methedologies</li>
                                <li>Organizational Design</li>
                                <li>SMO, PMO & HR Setup</li>
                                <li>Dashboards & Reports</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include($path.'.USP')

        @include($path.'.latest-Insights')

    </div>
    <!-- ||||| MAIN CONTENT OF PM SERVICES ||||| -->
@endsection
