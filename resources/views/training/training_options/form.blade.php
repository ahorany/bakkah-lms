@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}
{{Builder::SetPrefix('training.')}}
{{Builder::SetPublishName('publish')}}

<style>
ul{
    list-style: none;
}
</style>
@if(Session::has('message'))
    <div  class="alert alert-danger">{{Session::get('message')}}</div>
@endif
@section('col9')

    <?php
        use App\Models\Training\Feature;
        use App\Models\Training\TrainingOptionFeature;
        use Illuminate\Support\Facades\DB;
        $constants = \App\Constant::where('parent_id', 10)->get();
        $courses = \App\Models\Training\Course::orderBy('title->en')->get();
        $exams = \App\Models\Training\TrainingOption::where('constant_id', 353)->get();
    ?>
    {!! Builder::Select('constant_id', 'option', $constants, null, ['col'=>'col-md-6']) !!}
    {!!Builder::Select('course_id', 'course_name', $courses, null, [
        'col'=>'col-md-6', 'model_title'=>'trans_title',
    ])!!}

    {!!Builder::Select('exam_simulation_id', 'exam_simulation', $exams, null, [
        'col'=>'col-md-6', 'model_title'=>'course_name',
    ])!!}

    {{-- ======================== Price Tab ======================= --}}
    <div class="price-tabs my-4">
        <div class="nav nav-pills flex-column flex-sm-row" id="pills-tab" role="tablist">
            <a class="flex-sm-fill nav-link btn btn-light active" data-toggle="pill" href="#price-sar">Price (SAR)</a>
            <a class="flex-sm-fill nav-link btn btn-light" data-toggle="pill" href="#price-usd">Price (USD)</a>
        </div>
        <div class="tab-content border rounded p-3" id="pills-tabContent">
            {!!Builder::CheckBox('exam_is_included', 'exam_is_included', null, ['col'=>'col-md-12'])!!}
            {{-- ======================== SAR ======================= --}}
            <div class="tab-pane fade show active" id="price-sar" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="row">
                    {!!Builder::Input('price', 'price', null, ['col'=>'col-md-4'])!!}
                    {!!Builder::Input('exam_price', 'exam_price', null, ['col'=>'col-md-4'])!!}
                    {!!Builder::Input('take2_price', 'take2_price', null, ['col'=>'col-md-4'])!!}
                    {{-- {!!Builder::Input('exam_simulation_price_sar', 'exam_simulation_price_sar', null, ['col'=>'col-md-4'])!!} --}}
                </div>
            </div>
            {{-- ======================== USD ======================= --}}
            <div class="tab-pane fade" id="price-usd" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="row">
                    {!!Builder::Input('price_usd', 'price_usd', null, ['col'=>'col-md-4'])!!}
                    {!!Builder::Input('exam_price_usd', 'exam_price_usd', null, ['col'=>'col-md-4'])!!}
                    {!!Builder::Input('take2_price_usd', 'take2_price_usd', null, ['col'=>'col-md-'])!!}
                    {{-- {!!Builder::Input('exam_simulation_price_usd', 'exam_simulation_price_usd', null, ['col'=>'col-md-4'])!!} --}}
                </div>
            </div>
        </div>
    </div>

    {!!Builder::Tinymce('en_details', 'en_training_options')!!}
    {!!Builder::Tinymce('ar_details', 'ar_training_options')!!}

    <div class="card card-info col-md-12 p-0" id="features">
        <div class="card-header font-weight-bold">{{__('Trainings Features')}}</div>
        <div class="card-body">
            <ul class="p-0">
                <li class="mb-3" v-for="feature in features" :key="feature.id">
                    <feature-input :id="feature.id" :title="JSON.parse(feature.title??'[]').en"
                                   :feature_id="feature.training_option_feature ? feature.training_option_feature.id :''"
                                   :price="feature.training_option_feature ? feature.training_option_feature.price :''"
                                   :price_usd="feature.training_option_feature ? feature.training_option_feature.price_usd:''"
                                   :xero_feature_code="feature.training_option_feature ? feature.training_option_feature.xero_feature_code :''"
                                   :is_include="feature.training_option_feature ? feature.training_option_feature.is_include : 0"
                                   :en_excerpt="feature.training_option_feature ? JSON.parse(feature.training_option_feature.excerpt??'[]').en :''"
                                   :ar_excerpt="feature.training_option_feature ? JSON.parse(feature.training_option_feature.excerpt??'[]').ar :''">
                    </feature-input>
                </li>
            </ul>
            @{{ price_usd }}
        </div>
    </div>
@endsection

@section('col3_block')

    <div class="card card-default">
        <div class="card-header">{{__('admin.details')}}</div>
        <div class="card-body">
            {!!Builder::Number('PDUs', 'PDUs', null, ['col'=>'col-md-12'])!!}
        </div>
    </div>

    <div class="card card-default">
        <div class="card-header">{{__('admin.lms_options')}}</div>
        <div class="card-body">
            <?php $lms = \App\Constant::where('parent_id', 344)->get(); ?>
            {!! Builder::Select('lms_id', 'lms_id', $lms, null, ['col'=>'col-md-12']) !!}
            {!!Builder::Input('lms_course_id', 'lms_course_id', null, ['col'=>'col-md-12'])!!}

        </div>
    </div>

    {{-- <div class="card card-default" id="features">
        <div class="card-header">{{__('Trainings Features')}}</div>
        <div class="card-body">
            <ul class="p-0">
                <li class="mb-3" v-for="feature in features" :key="feature.id">
                    <feature-input :id="feature.id"  :title="JSON.parse(feature.title).en"
                                   :feature_id="feature.training_option_feature ? feature.training_option_feature.id :''"
                                   :price="feature.training_option_feature ? feature.training_option_feature.price :''"
                                   :price_usd="feature.training_option_feature ? feature.training_option_feature.price_usd:''"
                                   :is_include="feature.training_option_feature ? feature.training_option_feature.is_include : 0">
                    </feature-input>
                </li>
            </ul>
            @{{ price_usd }}
        </div>
    </div> --}}

    {{-- <div class="card card-default">
        <div class="card-header">{{__('admin.evaluation_options')}}</div>
        <div class="card-body">
            {!!Builder::Input('evaluation_api_code', 'evaluation_api_code', null, ['col'=>'col-md-12'])!!}
        </div>
    </div> --}}

        @if(auth()->user()->role_id==12)
            <style>
                .btn-primary {display: none;}
            </style>
        @endif

    <?php //$title = __('admin.details'); ?>

        @include(ADMIN.'.details.call', ['eloquent'=>$eloquent??null, 'parent_id'=>312])

        @include(ADMIN.'.accordions.call', ['eloquent'=>$eloquent??null])

        <div class="card card-default">
            <div class="card-header">{{__('admin.teaser_video')}}</div>
            <div class="card-body">
                {!!Builder::File('teaser_video', 'teaser_video')!!}
                {!!Builder::VideoFile('teaser_video')!!}
            </div>
        </div>
@endsection

@push('vue')
    <script>
        Vue.component('feature-input',
            {
                // d-flex align-items-center
                template: `
                        <div>
                <label>
                        <input type="checkbox" name="feature_id[]" v-if="feature_id > 0" :checked="feature_id" :value="id">
                        <input type="checkbox" name="feature_id[]" v-else="feature_id = 0" v-model="featureItems" :value="id">
                        <span class="text-danger" v-html="title"></span>
                </label>
                <div class="row">
                    <div class="col-md-2">
                        <label class="m-0">SAR</label>
                        <input type="text" :name="'price-'+id" class="form-control" v-model="price" v-if="price > 0 ">
                        <input type="text" :name="'price-'+id" class="form-control" v-model="price"  v-else="price == 0" :disabled="featureItems.length==0">
                    </div>
                    <div class="col-md-2">
                        <label class="m-0 pl-2">USD</label>
                        <input type="text" :name="'price_usd-'+id" class="form-control"   v-model="price_usd" v-if="price_usd > 0" >
                        <input type="text" :name="'price_usd-'+id" class="form-control" v-else="price_usd == 0" :disabled="featureItems.length==0">
                    </div>
                    <div class="col-md-6">
                        <label class="m-0 pl-2" style="width: 150px;">Xero Code</label>
                        <input type="text" :name="'xero_feature_code_'+id" class="form-control" v-model="xero_feature_code" v-if="feature_id > 0">
                        <input type="text" :name="'xero_feature_code_'+id" class="form-control" v-model="xero_feature_code" v-else="feature_id = 0" :disabled="featureItems.length==0">
                    </div>
                    <div class="col-md-2 pt-4">
                        <input type="checkbox" :checked="is_include" :name="'is_include_'+id" value="1" v-if="feature_id > 0">
                        <input type="checkbox" :checked="is_include" :name="'is_include_'+id" value="1" v-else="feature_id = 0" :disabled="featureItems.length==0">
                        <label class="align-middle">Build In</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="m-0" style="width: 150px;">English Details</label>
                        <input type="text" :name="'en_excerpt_'+id" class="form-control" v-model="en_excerpt" v-if="feature_id > 0">
                        <input type="text" :name="'en_excerpt_'+id" class="form-control" v-model="en_excerpt" v-else="feature_id = 0" :disabled="featureItems.length==0">
                    </div>
                    <div class="col-md-6">
                        <label class="m-0" style="width: 150px;">Arabic Details</label>
                        <input type="text" :name="'ar_excerpt_'+id" class="form-control" v-model="ar_excerpt" v-if="feature_id > 0">
                        <input type="text" :name="'ar_excerpt_'+id" class="form-control" v-model="ar_excerpt" v-else="feature_id = 0" :disabled="featureItems.length==0">
                    </div>
                </div>
                        </div>`,
                data() {
                    return {
                        feature:{
                            id:'',
                            title:''
                        },
                        price: '',
                        price_usd: '',
                        is_active:true,
                        featureItems:[],
                        features: features,
                        uri:'http://localhost:8000/training/feature'
                    }

                },
                methods: {

                },
                props: ['id', 'title','price','price_usd','feature_id','is_include','xero_feature_code','en_excerpt','ar_excerpt']
            }),
            window.features = {!!json_encode($features??[])!!}
            console.log(window.features);
            new Vue({
                el:'#features',
                data:{
                    price:'',
                    price_usd:'',
                    features: features,
                    featureItems:[],
                    uri:'http://localhost:8000/training/feature',
                    // disabled:true,
                },
                methods: {
                    testtest: function($event){
                        console.log($event);
                        // this.disabled = false;
                    }
                }

            });
    </script>
@endpush
