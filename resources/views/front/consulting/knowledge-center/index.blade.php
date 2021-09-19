@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(79)??null])
@endsection

@section('content')

    @include(FRONT.'.consulting.Html.page-header', ['title'=>__('consulting.Insights')])

    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 sidebar order-2 order-md-1">

                    @include(FRONT.'.consulting.knowledge-center.search')

                    @include(FRONT.'.consulting.knowledge-center.sections')

                    @include(FRONT.'.consulting.knowledge-center.recent-articles')

                    <!-- display none -->
                    <div class="widget card box-shadow mb-4 d-none"><!-- by ahorany-->
                        <div class="card-header bg-dark text-white">
                            <h4 class="mb-0">Tags</h4>
                        </div>
                        <div class="card-body">
{{--                            <a class="tag-item" href="#" rel="tag">analysis</a><a class="tag-item" href="#" rel="tag">Axelos</a><a class="tag-item" href="#" rel="tag">Bakkah Academy</a><a class="tag-item" href="https://bakkah.net.sa/tag/bakkah-training/" rel="tag">Bakkah Training</a><a class="tag-item" href="https://bakkah.net.sa/tag/bakkah/" rel="tag">Bakkah</a><a class="tag-item" href="https://bakkah.net.sa/tag/benchmark/" rel="tag">Benchmark</a><a class="tag-item" href="https://bakkah.net.sa/tag/bom/" rel="tag">BOM</a><a class="tag-item" href="https://bakkah.net.sa/tag/business-courses/" rel="tag">Business Courses</a><a class="tag-item" href="https://bakkah.net.sa/tag/business-management/" rel="tag">Business Management</a><a class="tag-item" href="https://bakkah.net.sa/tag/business-managers/" rel="tag">Business Managers</a><a class="tag-item" href="https://bakkah.net.sa/tag/business-operating-model/" rel="tag">Business Operating Model</a><a class="tag-item" href="https://bakkah.net.sa/tag/business-operating/" rel="tag">Business Operating</a><a class="tag-item" href="https://bakkah.net.sa/tag/business-training-courses/" rel="tag">Business Training Courses</a><a class="tag-item" href="https://bakkah.net.sa/tag/business/" rel="tag">business</a><a class="tag-item" href="https://bakkah.net.sa/tag/capm/" rel="tag">CAPM</a><a class="tag-item" href="https://bakkah.net.sa/tag/center/" rel="tag">Center</a><a class="tag-item" href="https://bakkah.net.sa/tag/certification-pmp/" rel="tag">certification pmp</a><a class="tag-item" href="https://bakkah.net.sa/tag/certification/" rel="tag">certification</a><a class="tag-item" href="https://bakkah.net.sa/tag/challenges/" rel="tag">Challenges</a><a class="tag-item" href="https://bakkah.net.sa/tag/champs2-training-course/" rel="tag">CHAMPS2 Training Course</a><a class="tag-item" href="https://bakkah.net.sa/tag/champs2-training/" rel="tag">CHAMPS2 Training</a><a class="tag-item" href="https://bakkah.net.sa/tag/champs2/" rel="tag">CHAMPS2</a><a class="tag-item" href="https://bakkah.net.sa/tag/change-management/" rel="tag">Change Management</a><a class="tag-item" href="https://bakkah.net.sa/tag/change-manager/" rel="tag">Change Manager</a><a class="tag-item" href="https://bakkah.net.sa/tag/change-process/" rel="tag">Change Process</a><a class="tag-item" href="https://bakkah.net.sa/tag/change/" rel="tag">Change</a><a class="tag-item" href="https://bakkah.net.sa/tag/cipd/" rel="tag">CIPD</a><a class="tag-item" href="https://bakkah.net.sa/tag/coe/" rel="tag">CoE</a><a class="tag-item" href="https://bakkah.net.sa/tag/concept/" rel="tag">concept</a><a class="tag-item" href="https://bakkah.net.sa/tag/consulting/" rel="tag">consulting</a><a class="tag-item" href="https://bakkah.net.sa/tag/corporate/" rel="tag">Corporate</a><a class="tag-item" href="https://bakkah.net.sa/tag/e-learning/" rel="tag">e-learning</a><a class="tag-item" href="https://bakkah.net.sa/tag/exam/" rel="tag">exam</a><a class="tag-item" href="https://bakkah.net.sa/tag/function/" rel="tag">Function</a><a class="tag-item" href="https://bakkah.net.sa/tag/governance/" rel="tag">Governance</a><a class="tag-item" href="https://bakkah.net.sa/tag/government/" rel="tag">government</a><a class="tag-item" href="https://bakkah.net.sa/tag/hr/" rel="tag">HR</a><a class="tag-item" href="https://bakkah.net.sa/tag/human-resources-management/" rel="tag">human resources management</a><a class="tag-item" href="https://bakkah.net.sa/tag/human-resources/" rel="tag">human resources</a><a class="tag-item" href="https://bakkah.net.sa/tag/improve-business/" rel="tag">Improve Business</a><a class="tag-item" href="https://bakkah.net.sa/tag/infographic/" rel="tag">Infographic</a><a class="tag-item" href="https://bakkah.net.sa/tag/itil/" rel="tag">ITIL</a><a class="tag-item" href="https://bakkah.net.sa/tag/kpa/" rel="tag">KPA</a><a class="tag-item" href="https://bakkah.net.sa/tag/ladder/" rel="tag">Ladder</a><a class="tag-item" href="https://bakkah.net.sa/tag/learning/" rel="tag">learning</a><a class="tag-item" href="https://bakkah.net.sa/tag/management-maturity-models/" rel="tag">Management Maturity Models</a><a class="tag-item" href="#" rel="tag">management</a><a class="tag-item" href="#" rel="tag">managers</a><a class="tag-item" href="#" rel="tag">methodology</a><a class="tag-item" href="#" rel="tag">MoP</a><a class="tag-item" href="#" rel="tag">MoV</a>--}}
                        </div> <!-- /.card-body -->
                    </div> <!-- /.widget -->

                    <div class="widget card box-shadow mb-4 text-center d-none"><!-- by ahorany-->
                        <div class="card-body">
                            <h2>READY TO SUBMIT PAPERS?</h2>
                            <h3>Write with us now</h3>
                            <p>All full paper submissions will be peer reviewed and evaluated based on originality, technical and/or research</p>
                            <a href="mailto:contactus@bakkah.net.sa?subject=Bakkah Inc. Happy to publish my article&amp;body=Kindly, find the attached article file." target="_blank" class="btn btn-light boldfont px-4 main-color">Submit Now</a>
                        </div>
                    </div> <!-- /.widget -->
                        <!-- end display none -->

                </div> <!-- /.col-md-4 sidebar -->
                <div class="col-md-8 main-articles order-1 order-md-2">

                    @foreach($posts as $post)
                        <article class="article-post insight mb-5">
                            <header>
                                <div class="row align-items-center">
                                    <div class="col-3 col-md-2">
                                        <div class="published">
                                            {{$post->published_date}}
                                        </div>
                                    </div>
                                    <div class="col-9 col-md-10">
                                        <div class="category">
                                            <a href="{{CustomRoute('consulting.static.knowledge-center', ['post_type'=>$post->postMorph->constant->slug])}}">
                                                {{$post->postMorph->constant->trans_name}}
                                            </a>
                                        </div>
                                        <h2>
                                            <a href="{{CustomRoute('consulting.static.knowledge-center.single', ['slug'=>$post->slug])}}">
                                                {{$post->title}}
                                            </a>
                                        </h2>
                                        <div class="author d-none">
                                            <i class="fas fa-user"></i> {!! __('consulting.Posted By Bakkah Inc') !!}.
                                        </div>
                                    </div>
                                </div>
                            </header>
                            <main>
                                <div class="article-img my-4">
                                    <a href="{{CustomRoute('consulting.static.knowledge-center.single', ['slug'=>$post->slug])}}">
                                        <img width="1000" height="300" src="{{CustomAsset('upload/full/'.$post->upload->file)}}" class="w-100 h-auto" alt="{{$post->upload->excerpt}}">
                                    </a>
                                </div>
                                <div class="article-text">
                                    <p>
                                    <?php
                                    $content = strip_tags($post->details);
                                    echo substr ($content, 0, 300).'...';
                                    ?>
                                    </p>
                                </div>

                                <div class="d-md-flex my-5 justify-content-between align-items-center">
                                    <a href="{{CustomRoute('consulting.static.knowledge-center.single', ['slug'=>$post->slug])}}" class="btn btn-outline-dark px-4 mb-3 mb-md-0">{{__('consulting.Read More')}}</a>
                                    {{-- @include(FRONT.'.Html.share') --}}
                                </div>
                                <hr>
                            </main>
                        </article> <!-- /.article-post -->
                    @endforeach

                    <nav aria-label="Page navigation example">
                        {{ $posts->render() }}
                    </nav>

                </div> <!-- /.col-md-8 -->
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </section>
@endsection
