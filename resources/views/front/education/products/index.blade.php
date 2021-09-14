@extends(FRONT.'.education.layouts.master')

{{-- @include('SEO.infa', ['infa_id'=>77]) --}}
{{-- @include('SEO.head', ['eloquent'=>\App\Constant::where('slug', $category)->first()??null]) --}}
@if(is_null($category))
    @include('SEO.infa', ['infa_id'=>77])
@else
    @include('SEO.head', ['eloquent'=>$category??null])
@endif

@section('content')

    @include(FRONT.'.education.Html.page-header', ['title'=>$title])

    <section class="pb-5 sessions-category">

        <div class="container">

            @if(!is_null($constants))
                <div class="row justify-content-center mt-4 course-categories-tabs">
                    @foreach($constants as $constant)
                        <div class="col p-1 nav-item" role="presentation">
                            <?php
                            $cls = '';
                            if(!is_null($category) && $category->slug==$constant->slug){
                                $cls = 'background-color:#fb4400;';
                            }
                            ?>
                            <a href="{{route($route_name, $constant->slug)}}-courses" data-target="category_{{$constant->id}}" class="btn btn-secondary d-block p-3 btn-category" style="{{$cls}}">
                                <i class="{{$constant->icon}} fa-2x d-block mb-2"></i>
                                <span>{{$constant->trans_name}}</span>
                            </a>
                        </div>
                    @endforeach
                </div>

                    @if (!is_null($category) && $category->trans_excerpt)
                        <hr>
                        <p class="lead my-5">{{ $category->trans_excerpt }}</p>
                    @endif

                <hr>

                <div class="row align-items-center justify-content-between">
                    <div class="col-12 col-md">
                        <h3 id="category-title" class="uppertext boldfont m-0">{{$title}}</h3>
                    </div>
                    <div class="filter-course">
                        <button type="button" data-target="all" class="btn btn-light active">{{__('education.All')}}</button>
                        <button type="button" data-target="course_type_13" class="btn btn-light">{{__('education.Online Training')}}</button>
                        <button type="button" data-target="course_type_11" class="btn btn-light">{{__('education.Self Study')}}</button>
                        <button type="button" data-target="course_type_353" class="btn btn-light">{{__('education.Exam Simulation')}}</button>
                    </div>
                </div>
            @endif

            @include(FRONT.'.education.products.card', ['courses'=>$courses])

        </div>
    </section>
@endsection
