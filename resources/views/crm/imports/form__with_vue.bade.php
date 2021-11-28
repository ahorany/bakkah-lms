@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

{{Builder::SetNameSpace('training.')}}
{{Builder::SetPublishName('publish')}}

@section('col9')
{!!Builder::Input('en_title', 'en_title', null, ['col'=>'col-md-6'])!!}
{!!Builder::Input('ar_title', 'ar_title', null, ['col'=>'col-md-6'])!!}
{!!Builder::Excerpt('en_excerpt', 'en_excerpt')!!}
{!!Builder::Excerpt('ar_excerpt', 'ar_excerpt')!!}

{{Builder::SetPrefix('training.')}}
{!!Builder::Excerpt('en_accredited_notes', 'en_accredited_notes')!!}
{!!Builder::Excerpt('ar_accredited_notes', 'ar_accredited_notes')!!}
@endsection

@section('col3_block')
<!-- knowledge_center -->
@include(ADMIN.'.Html.checkbox_const', ['const_type'=>'course'])

@if(isset($eloquent->id))
<div id="course_details">
    <div class="card card-default">
        <div class="card-header">Accordion</div>
        <div class="card-body">
            <?php $courseDetails = \App\Models\Training\CourseDetail::with('constant')
                ->where('course_id', $eloquent->id)
                ->get();
            ?>
            <ul class="list-unstyled">
                @foreach($courseDetails as $courseDetail)
                <li>
                    {{$courseDetail->constant->trans_name}}
                    <button v-on:click="Accordion()" type="button" class="btn btn-outline-primary btn-xs">
                        {{__('admin.edit')}}
                    </button>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @include('Html.modal')
</div>
@endif

@endsection

@section('seo')
@include(ADMIN.'.SEO.form', ['post'=>$eloquent??null])
@endsection

@section('image')
<?php $image_title = __('admin.image'); ?>
@include(ADMIN.'.Html.image')
@endsection

@push('vue')
<script>
    vm_modal = new Vue({
        el:'#course_details',
        data:{
            accordions:null,
            accordion_en_title:null,
            accordion_ar_title:null,
            accordion_en_details:null,
            accordion_ar_details:null,
        },
        methods:{
            Accordion:function (){
                axios.get('{{route("admin.accordions.index")}}')
                    .then(function(resp){
                        // console.log(resp.data);
                        vm_modal.accordions = resp.data;

                    }.bind(this))
                    .catch(function(err){
                        console.log(err);
                    });
                $('.admin-modal').modal('show');
            },
            ClearAccordion: function (){
                this.accordion_en_title = null;
                this.accordion_ar_title = null;
                this.accordion_en_details = null;
                this.accordion_ar_details = null;
            },
            SaveAccordion: function (){

                axios.post('{{route("admin.accordions.store")}}',{
                    params: {
                        accordion:accordion.id
                    }
                })
                    .then(function(resp){
                        // vm_modal.accordion = resp.data;
                        vm_modal.accordion_en_title = JSON.parse(resp.data.title).en;
                        vm_modal.accordion_ar_title = JSON.parse(resp.data.title).ar;
                        vm_modal.accordion_en_details = JSON.parse(resp.data.details).en;
                        vm_modal.accordion_ar_details = JSON.parse(resp.data.details).ar;
                    })
                    .catch(function(err){
                        console.log(err);
                    });
            },
            EditAccordion: function (accordion){

                axios.get('{{route("admin.accordions.edit")}}',{
                    params: {
                        accordion:accordion.id
                    }
                })
                    .then(function(resp){
                        // vm_modal.accordion = resp.data;
                        vm_modal.accordion_en_title = JSON.parse(resp.data.title).en;
                        vm_modal.accordion_ar_title = JSON.parse(resp.data.title).ar;
                        vm_modal.accordion_en_details = JSON.parse(resp.data.details).en;
                        vm_modal.accordion_ar_details = JSON.parse(resp.data.details).ar;
                    })
                    .catch(function(err){
                        console.log(err);
                    });
            }
        },
    });
</script>
@endpush
