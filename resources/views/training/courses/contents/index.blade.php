@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.Course Content')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('table')
    <style>
        .course_info button {
            padding: .375rem .75rem !important;
        }

        .ql-container.ql-snow{
            height: 200px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/@morioh/v-quill-editor/dist/editor.css" rel="stylesheet">

         <div class="toLoad" id="contents">
            <div class="course_info mb-3 card p-3">
                <div class="row">
                    <div class="col-md-10 col-10">
                        @include('training.courses.contents.header',['course_id' => $course->id, 'contents' =>true])
                    </div>
                    <div class="col-md-2 col-2 text-right">
                        <div class="back">
                            <a href="{{route('training.courses.index')}}" class="cyan mb-1">Course List</a>
                            <a href="{{route('training.courses.edit',[$course->id])}}" class="cyan mb-1"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                        </div>
                    </div>

                    @if(!checkUserIsTrainee())
                        <div class="col-md-12 col-12">
                            <span style="font-size: 0.8rem;" class="mr-1 p-1 badge badge-dark">Course Name : {{$course->trans_title}}</span>

                            <button type="button" @click="OpenModal('section',null)" style="padding: 2px 8px;" class="group_buttons mb-1 btn-sm"><i class="fa fa-plus" aria-hidden="true"></i>{{__('admin.add_section')}}
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <template v-if="contents">
                <div class="sortable">
                    <div class="card mb-2" v-for="(content,index) in contents" :key="content.id" :id="content.id">
                        <div class="card-body" >
                            <div class="clearfix">
                                <div class="row my-3">
                                    <div class="col-md-8 col-lg-8">
                                        <span class="icon-bottom mr-1" style="cursor: pointer; vertical-align: text-bottom;">
                                            <i class="fa fa-chevron-down d-none" aria-hidden="true"></i>
                                            <i class="fa fa-chevron-up " aria-hidden="true"></i>
                                        </span>
                                        <h3 class="BtnGroupRows text-capitalize d-inline-block" style="font-size: 22px;">@{{content.title}}</h3>
                                    </div>
                                    <div class="col-md-4 col-lg-4 text-right">
                                        <div class="BtnGroupRows" data-id="150">
                                            <button @click="OpenSectionEditModal(content.id)" class="yellow"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button>

                                            <button @click="deleteSection(content.id)"  class="red" ><i class="fa fa-trash"></i> Delete</button>
                                        </div>
                                    </div>
                                    <div class="mt-3 col-md-12 col-lg-12">
                                        <div>
                                            <button style="font-size: 90%;" type="button" @click="OpenModal('video',content.id)" class="cyan" id="video" ><i class="fa fa-video-camera" aria-hidden="true"></i> {{__('admin.video')}}</button>
                                            <button style="font-size: 90%;" type="button" @click="OpenModal('audio',content.id)" class="cyan" id="audio" ><i class="fa fa-headphones"></i> {{__('admin.audio')}}</button>
                                            <button style="font-size: 90%;" type="button" @click="OpenModal('presentation',content.id)" class="cyan" id="presentation" ><i class="fa fa-file-powerpoint-o" aria-hidden="true"></i> {{__('admin.presentaion')}}</button>
                                            <button style="font-size: 90%;" type="button" @click="OpenModal('scorm',content.id)" class="cyan" id="scorm" ><i class="fa fa-file-archive-o" aria-hidden="true"></i> {{__('admin.scorm')}}</button>
                                            <button style="font-size: 90%;" type="button" @click="OpenModal('exam',content.id)" class="cyan" id="exam" ><i class="fa fa-file" aria-hidden="true"></i> {{__('admin.exam')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="content.details" class="my-2" v-html="content.details.excerpt">}</div>

                            <table class="table" id="content-items">
                                <thead>
                                <tr>
                                    <th scope="col">Title</th>
                                    <th scope="col">Type</th>
                                    <th scope="col" class="text-right">Action</th>
                                    {{-- <th>import</th> --}}
                                </tr>
                                </thead>
                                <tbody class="sortable" >
                                <tr v-if="content.contents" v-for="(entry, index) in content.contents" :key="entry.id" :id="entry.id" class="text-capitalize">
                                    <td>
                                        <span>@{{entry.title}}</span>
                                    </td>
                                    <td>
                                        <span v-if="entry.post_type == 'scorm'" class="badge badge-secondary">@{{entry.post_type}}</span>
                                        <span v-if="entry.post_type == 'video'" class="badge badge-primary">@{{entry.post_type}}</span>
                                        <span v-if="entry.post_type == 'audio'" class="badge badge-warning">@{{entry.post_type}}</span>
                                        <span v-if="entry.post_type == 'presentation'" class="badge badge-success">@{{entry.post_type}}</span>
                                        <span v-if="entry.post_type == 'exam'" class="badge badge-info">@{{entry.post_type}}</span>
                                        <span v-if="entry.post_type == 'exam'" class="badge badge-secondary">Questions Count : ( @{{entry.questions_count}} )</span>
                                    </td>
                                    <td class="text-right">
                                        <div class="BtnGroupRows buttons" data-id="150">
                                            <a v-if="entry.post_type == 'exam'"  class="primary-outline" :href="base_url  + '/training' + '/add_questions' + '/'+ entry.id "><i class="fa fa-plus" aria-hidden="true"></i> Questions<!-- Add Questions  --> </a>
                                            <a v-if="entry.post_type == 'exam'" class="cyan" title="Preview" :href="'{{url('/')}}/{{app()->getLocale()}}/training/exam/preview-content/' + entry.id" :target="entry.id">
                                                <i class="fa fa-folder-open-o" aria-hidden="true"></i>
                                            </a>
                                            <a v-if="entry.post_type != 'exam'" class="cyan" title="Preview" :href="'{{url('/')}}/{{app()->getLocale()}}/user/preview-content/' + entry.id" :target="entry.id">
                                                <i class="fa fa-folder-open-o" aria-hidden="true"></i>
                                            </a>

                                            <button title="Edit" v-if="entry.post_type == 'exam'" @click="OpenEditModal(content.id, entry.id)"  class="yellow" > <i class="fa fa-pencil" aria-hidden="true"></i> </button>
                                            <button title="Edit" v-else @click="OpenEditModal(content.id, entry.id)"  class="yellow" >
                                                <i class="fa fa-pencil" aria-hidden="true"></i> </button>

                                            <button title="Delete" @click="deleteContent(content.id,entry.id)"  class="red"><i class="fa fa-trash" aria-hidden="true"></i> </button>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <br>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="ContentModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-capitalize" id="exampleModalLabel">@{{ model_type }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div v-if="model_type != 'exam'" class="col-md-12 p-0">
                                    <div class="form-group">
                                        <label>Title </label>
                                        <input type="text" v-model="title" name="title" class="form-control" placeholder="title">
                                        <div v-show="'title' in errors">
                                            <span style="color: red;font-size: 13px">@{{ errors.title }}</span>
                                        </div>
                                    </div>
                                </div>
                                <template v-if="model_type == 'exam'">
                                    <div class="row">
                                        <div class="col-md-6 col-6">
                                            <div class="form-group">
                                                <label class="m-0">Title </label>
                                                <input type="text" v-model="title" name="title" class="form-control" placeholder="title">
                                                <div v-show="'title' in errors">
                                                    <span style="color: red;font-size: 13px">@{{ errors.title }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="modal-diff-content form-group">
                                                <label class="m-0">Duration (minutes)</label>
                                                <input type="number" v-model="duration" name="duration" class="form-control" placeholder="duration">
                                                <div v-show="'duration' in errors">
                                                    <span style="color: red;font-size: 13px">@{{ errors.duration }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="modal-diff-content form-group">
                                                <label class="m-0">Attempt Count</label>
                                                <input type="number" v-model="attempt_count" name="attempt_count" class="form-control" placeholder="attempt_count">
                                                <div v-show="'attempt_count' in errors">
                                                    <span style="color: red;font-size: 13px">@{{ errors.attempt_count }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="modal-diff-content form-group">
                                                <label class="m-0">Pagination Number</label>
                                                <input type="number" v-model="pagination" name="pagination" class="form-control" placeholder="pagination">
                                                <div v-show="'pagination' in errors">
                                                    <span style="color: red;font-size: 13px">@{{ errors.pagination }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="modal-diff-content form-group">
                                                <label class="m-0">Start Date </label>
                                                <input type="datetime-local" v-model="start_date" name="start_date" class="form-control" placeholder="start date">
                                                <div v-show="'start_date' in errors">
                                                    <span style="color: red;font-size: 13px">@{{ errors.start_date }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div  class="modal-diff-content form-group">
                                                <label class="m-0">End Date </label>
                                                <input  type="datetime-local" v-model="end_date" name="end_date" class="form-control" placeholder="end date">
                                                <div v-show="'end_date' in errors">
                                                    <span style="color: red;font-size: 13px">@{{ errors.end_date }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="modal-diff-content form-group">
                                                <label class="m-0">Pass Mark (%)</label>
                                                <input min="0" max="100" type="number" v-model="pass_mark" name="pass_mark" class="form-control" placeholder="pass mark">
                                                <div v-show="'pass_mark' in errors">
                                                    <span style="color: red;font-size: 13px">@{{ errors.pass_mark }}</span>
                                                </div>
                                            </div>
                                        </div>








                                        <div class="col-md-6 col-12">
                                            <div class="modal-diff-content form-group">
                                                <label class="container-check" style="padding: 25px 30px 0; font-size: 15px;">
                                                    Shuffle Answers
                                                    <input class="mx-3" style="display: inline-block;" id="shuffle" type="checkbox" v-model="shuffle_answers" name="shuffle_answers">
                                                    <span class="checkmark" style="top: 26px;"></span>
                                                    <div v-show="'shuffle_answers' in errors">
                                                        <span style="color: red;font-size: 13px">@{{ errors.shuffle_answers }}</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-6 col-12">
                                            <div class="modal-diff-content form-group">
                                                <label for="shuffle" class="m-0">Shuffle Answers</label>
                                                <input id="shuffle" type="checkbox" v-model="shuffle_answers" name="shuffle_answers">
                                                <div v-show="'shuffle_answers' in errors">
                                                    <span style="color: red;font-size: 13px">@{{ errors.shuffle_answers }}</span>
                                                </div>
                                            </div>
                                        </div> --}}

                                    </div>
                                </template>
                                <div v-if="model_type == 'section' || model_type == 'exam' " class="modal-diff-content my-2">
                                    <editor v-model="excerpt" theme="snow" :options="options" :placeholder="'Details'"></editor>
                                    <div v-show="'excerpt' in errors">
                                        <span style="color: red;font-size: 13px">@{{ errors.excerpt }}</span>
                                    </div>
                                </div>

                                <div v-else-if="model_type != 'video'" class="modal-diff-content">
                                    <div class="text-danger">Note: (Max Upload File Size: 200MB)</div>

                                    <input type="file" @change="file = $event.target.files[0]" ref="inputFile" class="form-control">
                                    <div v-show="'file' in errors">
                                        <span style="color: red;font-size: 13px">@{{ errors.file }}</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" :style="{'width' : progress}"  aria-valuemin="0" aria-valuemax="100">@{{ progress }}</div>
                                    </div>
                                </div>

                                <div v-else class="modal-diff-content">
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active cyan" id="pills-file-tab" data-toggle="pill" href="#pills-file" role="tab" aria-controls="pills-file" aria-selected="true">Upload</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link cyan" id="pills-url-tab" data-toggle="pill" href="#pills-url" role="tab" aria-controls="pills-url" aria-selected="false">Url</a>
                                        </li>

                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-file" role="tabpanel" aria-labelledby="pills-file-tab">
                                            <div class="form-group">
                                                <label class="label">
                                                    <i class="far fa-file-code"></i>
                                                    <span class="title">Add File</span>
                                                    <div class="text-danger">Note: (Max Upload File Size: 200MB)</div>
                                                    <input type="file" @change="file = $event.target.files[0]" ref="inputFile" class="form-control">
                                                </label>
                                            </div>
                                            <div v-show="'file' in errors">
                                                <span style="color: red;font-size: 13px">@{{ errors.file }}</span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" :style="{'width' : progress}"  aria-valuemin="0" aria-valuemax="100">@{{ progress }}</div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-url" role="tabpanel" aria-labelledby="pills-url-tab">
                                            <input type="url" v-model="url" class="form-control" placeholder="Enter url">
                                            <div v-show="'url' in errors">
                                                <span style="color: red;font-size: 13px">@{{ errors.url }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="model_type != 'section'" class="form-group form-check child">
                                    <label class="container-check form-check-label" for="enabled" style="padding: 25px 30px 0; font-size: 15px;">
                                        {{__('admin.Enabeld Status')}}
                                        <input class="form-check-input child" style="display: inline-block;" v-model="status" id="enabled" type="checkbox" name="status">
                                        <span class="checkmark" style="top: 26px;"></span>
                                    </label>

                                    {{-- <input class="form-check-input child" v-model="status" id="1" type="checkbox" name="status"> --}}
                                    {{-- <label class="form-check-label" for="1">{{__('admin.Enabeld Status')}}</label> --}}

                                    <div v-if="file_url">
                                        <p>Files : </p>
                                        <div><i class="fa fa-file"></i> <a :href="file_url" v-text="file_title"></a></div>
                                    </div>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="red" data-dismiss="modal">{{__('admin.close')}}</button>
                                <button type="reset" class="cyan" @click="clear()">{{__('admin.clear')}}</button>
                                <button type="button"  @click="save()" class="green">{{__('admin.save')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

        </div>
@endsection

@section('script')

<script>
$(function() {
    $('.sortable').sortable({
        tolerance:'pointer',
        cursor:'move',
        axis: 'y',
        update: function (event, ui) {
            var data = $(this).sortable();

            console.log(data[0].childNodes)
            arr = [];
            data[0].childNodes.forEach(function (ele,index) {
                arr[index] = ele.id
            })
            console.log(arr)
            // POST to server using $.post or $.ajax
            data = {
                _token: "{{ csrf_token() }}" ,
                data : arr
            };

            $.ajax({
                data: data,
                type: 'POST',
                url: "{{ route('training.contents.save_order')  }}",

                success: function (data) {
                    console.log(data)
                }
            });
        }
    });



   //  $(".sortable").sortable();
   //
   // $('.clickaaaa').click(function(){
   //
   //      // var idsInOrder = $(".sortable").sortable('toArray');
   //      var idsInOrder = $(".sortable").sortable();
   //      console.log(idsInOrder);
   //      return false;
   // });




    // $( ".sortable" ).sortable();
    // $( ".sortable" ).sortable({
    //     revert: true
    // });
    // $( "#draggable" ).draggable({
    // connectToSortable: "#sortable",
    // helper: "clone",
    // revert: "invalid"
    // });
    // $( "ul, li" ).disableSelection();
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" ></script>

<script src="https://cdn.jsdelivr.net/npm/@morioh/v-quill-editor/dist/editor.min.js" type="text/javascript"></script>
<script>
    window.contents = {!! json_encode($contents??[]) !!}
    window.public_path = {!! json_encode(CustomAsset('upload')??'') !!}
	var contents = new Vue({
		el:'#main-vue-element',
		data:{
            contents: window.contents,
            public_path: window.public_path,
            course_id:'{{$course->id}}',
            section_id : '',
            content_id : '',
            title: '',
			excerpt : '',
            file_title : '',
            file_url : '',
            status : false,
            shuffle_answers : false,
            start_date : '',
            end_date : '',
            duration : 0,
            pass_mark : 0,
            attempt_count : 1,
            pagination : 1,
            file : '',
            url : '',
            progress : '0%',
            model_type : 'section',
            save_type : 'add',
            base_url : window.location.origin,
            errors : {},
            options: {
                modules: {

                    'toolbar': [
                        [{ 'size': [] }],
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'script': 'super' }, { 'script': 'sub' }],
                        [{ 'header': '1' }, { 'header': '2' }, 'blockquote', 'code-block'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }],
                        [{ 'direction': 'rtl' }, { 'align': [] }],
                        ['link', 'image', 'video', 'formula'],
                        ['clean']
                    ],
                },
            }
		},
		methods: {
		    clear : function(){
                this.title = '';
                this.excerpt = '';
                this.file_url = '';
                this.file_title = '';
                this.status = false;
                this.shuffle_answers = false;
                this.url = '';
                this.start_date = '';
                this.end_date 	= '';
                this.duration 	= 0;
                this.pass_mark 	= 0;
                this.pagination = 1;
                this.progress 	= '0%';
                this.errors = {};

                this.duration = 0;
                this.pagination = 1;
                this.attempt_count = 1;


                if(this.file != '' ){
                    if(this.$refs.inputFile.type != undefined){
                        this.$refs.inputFile.type='text';
                        this.$refs.inputFile.type='file';
                    }
                }

                this.file = '';

            },

            OpenModal : function(type,content_id){
               this.clear(); // clear data
               this.save_type  = 'add';
               this.model_type = type;
               console.log(type)
               this.content_id = content_id;
               this.errors = {};
               $('#ContentModal').modal('show')
            },

            OpenSectionEditModal : function(content_id){
                let self = this;
                this.clear(); // clear data
                this.save_type  = 'edit';
                this.content_id = content_id;

                this.contents.forEach(function (section) {
                    if(section.id == content_id){
                        self.title = section.title;
                        self.excerpt =  section.details ?  section.details.excerpt : '';
                        self.model_type = 'section';
                    }
                    return true ;
                });


                $('#ContentModal').modal('show')
            },

            OpenEditModal : function(parent_id,content_id){
                this.clear(); // clear data
                let self = this;
                this.section_id = parent_id;
                this.content_id = content_id;
                this.save_type  = 'edit';
                this.errors = {};


                this.contents.forEach(function (section) {
                    if(section.id == parent_id){
                         section.contents.forEach(function (content) {
                            if(content.id == content_id) {
                                    self.title = content.title;
                                    self.status = content.status;
                                    self.excerpt =  content.details ?  content.details.excerpt : '';
                                    // self.duration =  content.duration ?  content.details.duration : '';
                                     content.exam ? self.start_date = moment(content.exam.start_date).format('YYYY-MM-DDTHH:mm')  : '';
                                     content.exam ? self.end_date = moment(content.exam.end_date).format('YYYY-MM-DDTHH:mm')  : '';
                                     content.exam ? self.duration = content.exam.duration : 0;
                                     content.exam ? self.pagination = content.exam.pagination : 1;
                                     content.exam ? self.attempt_count = content.exam.attempt_count :1;
                                     content.exam ? self.pass_mark = content.exam.pass_mark : 0;
                                     content.exam ? self.shuffle_answers = (content.exam.shuffle_answers == 1 ? true : false ) : false;
                                     self.model_type = content.post_type;
                                     self.url = content.url;

                                   if(content.upload){
                                       let path = '';
                                       switch (self.model_type) {
                                           case 'video':
                                               path = self.public_path + '/files/videos';
                                               break;
                                           case 'audio':
                                               path = self.public_path + '/files/audios';
                                               break;
                                           case 'presentation':
                                               path = self.public_path + '/files/presentations';
                                               break;
                                           case 'scorm':
                                               path = self.public_path + '/files/scorms';
                                               break;
                                           default :
                                               path = self.public_path + '/files/files';
                                       }

                                       self.file_url =  path + '/' + content.upload.file;
                                       self.file_title =  content.upload.name;
                                   }else if(content.url){
                                       self.file_url =  content.url;
                                       self.file_title =  content.url;
                                   }



                                console.log(content)

                            }
                        })
                    }
                    return true ;
                });

                $('#ContentModal').modal('show')
            },

            deleteContent : function (parent_id,content_id){
               let self = this;
               if(confirm("Are you sure ? ")){
                   this.contents = this.contents.filter(function (section) {
                       if(section.id == parent_id){
                           section.contents =  section.contents.filter(function (content) {
                               console.log(content)
                               return  content.id != content_id;
                           })
                       }
                       return true ;
                   });

                   this.deleteRequest(content_id)
               }

            },

            deleteSection : function(section_id){
                let self = this;
                if(confirm("Are you sure ? ")){
                    this.contents = this.contents.filter(function (section) {
                        if(section.id == section_id){
                            return  section.id != section_id;
                        }
                        return true ;
                    });

                   this.deleteRequest(section_id)
                }


            },

            deleteRequest : function(content_id){
                axios.get("{{route('training.delete_content')}}",{
                    params : {
                        content_id : content_id
                    }
                })
                    .then(response => {
                    })
                    .catch(e => {
                        console.log(e)
                    });
            },

            saveSection : function(){
                let self = this;
                if (self.title == ''  || self.title == null) {
                    self.errors = {'title': 'The title field is required.'};
                    return;
                }
                if(this.save_type  == 'add'){
                    axios.post("{{route('training.add_section')}}",
                        {
                            title : self.title,
                            excerpt : self.excerpt,
                            type : self.model_type,
                            course_id : self.course_id,
                            status : self.status,
                        })
                        .then(response => {
                            console.log(response['data']['errors'])

                            if(response['data']['errors']) {
                                self.errors =  response['data']['errors']
                                for (let property in self.errors) {
                                    self.errors[property] = self.errors[property][0];
                                }
                            }else{
                                this.contents.push(response.data.section);
                                this.errors = {};

                                $('#ContentModal').modal('hide')
                            }

                        })
                        .catch(e => {
                            console.log(e)
                        });
                }else{
                    self.contents.forEach(function (section) {
                        if(section.id == self.content_id){
                            section.title = self.title  ;
                            section.details = section.details != null ? {excerpt : ''} : section.details.excerpt ;
                            section.details.excerpt  = self.excerpt;
                            console.log(section)
                        }
                        return true ;
                    });

                    console.log(self.contents)

                    axios.post("{{route('training.update_section')}}",
                        {
                            title : self.title,
                            excerpt : self.excerpt,
                            type : self.model_type,
                            course_id : self.course_id,
                            content_id : self.content_id,
                            status : self.status,
                        })
                        .then(response => {
                            console.log(response['data']['errors'])
                            if(response['data']['errors']) {
                                self.errors =  response['data']['errors']
                                for (let property in self.errors) {
                                    self.errors[property] = self.errors[property][0];
                                }
                            }else{
                                this.errors = {};
                                $('#ContentModal').modal('hide')
                            }
                        })
                        .catch(e => {
                            console.log(e)
                        });
                }

            },

            saveContent: function(){

                let self = this;
                let formData = new FormData();
                let config = {
                    onUploadProgress(progressEvent) {
                        var percentCompleted = Math.round((progressEvent.loaded * 100) /
                            progressEvent.total);
                           self.progress = percentCompleted +'%'

                        return percentCompleted;
                    },

                    headers:{
                        'Content-Type' : 'multipart/form-data',
                    }
                };

                formData.append('course_id', self.course_id);
                formData.append('content_id', self.content_id);
                formData.append('title', self.title);
                formData.append('excerpt', self.excerpt);
                formData.append('url', self.url);
                formData.append('status', self.status);
                formData.append('type', self.model_type);
                formData.append('file', self.file);
                formData.append('start_date', self.start_date);
                formData.append('end_date', self.end_date);
                formData.append('duration', self.duration);
                formData.append('pagination', self.pagination);
                formData.append('attempt_count', self.attempt_count);
                formData.append('pass_mark', self.pass_mark);
                formData.append('shuffle_answers', self.shuffle_answers);

                if(self.save_type == 'add'){

                    axios.post("{{route('training.add_content')}}",
                        formData
                        ,config)
                        .then(response => {
                           console.log(response)
                            if(response['data']['errors']) {
                                self.errors =  response['data']['errors']
                                for (let property in self.errors) {
                                    self.errors[property] = self.errors[property][0];
                                }
                            }else{
                                console.log(response)
                                // this.contents.push(response.data.data);

                                self.contents.forEach(function (section) {
                                    if(section.id == self.content_id){
                                        section.contents.push(response.data.data);
                                    }
                                    return true ;
                                });
                                this.errors = {};
                                $('#ContentModal').modal('hide')
                            }

                        })
                        .catch(e => {
                            console.log('errors')
                            console.log(e)
                        });
                }else{

                    self.contents.forEach(function (section) {
                        if(section.id == self.content_id){
                            section.title = self.title  ;
                            section.details.excerpt = self.excerpt;
                        }
                        return true ;
                    });

                    this.contents.forEach(function (section) {
                        if(section.id == self.section_id){
                            section.contents.forEach(function (content) {
                                if(content.id == self.content_id) {
                                    content.title = self.title;
                                    content.status = self.status;
                                    if(content.details ){
                                        content.details.excerpt = self.excerpt
                                    }

                                    if(content.exam ){
                                       content.exam.start_date = self.start_date;
                                       content.exam.end_date =self.end_date;

                                       content.exam.duration =self.duration;
                                       content.exam.pagination =self.pagination;
                                       content.exam.attempt_count =self.attempt_count;
                                       content.exam.pass_mark =self.pass_mark;
                                       content.exam.shuffle_answers = self.shuffle_answers;


                                    }
                                    content.post_type = self.model_type;
                                    content.url = self.url;
                                }
                            })
                        }
                        return true ;
                    });

                    axios.post("{{route('training.update_content')}}",
                        formData
                        ,config)
                        .then(response => {
                            console.log(response)

                            if(response['data']['errors']) {
                                self.errors =  response['data']['errors']
                                for (let property in self.errors) {
                                    self.errors[property] = self.errors[property][0];
                                }
                            }else{
                                self.contents.forEach(function (section) {
                                    if(section.id == response.data.data.parent_id){
                                        section.contents.forEach(function (content) {
                                            if(content.id == response.data.data.id) {
                                                 content.title =  response.data.data.title;
                                                 content.status =  response.data.data.status;

                                                 content.url = response.data.data.url;

                                                if(response.data.data.upload){
                                                    let path = '';
                                                    switch (self.model_type) {
                                                        case 'video':
                                                            path = self.public_path + '/files/videos';
                                                            break;
                                                        case 'audio':
                                                            path = self.public_path + '/files/audios';
                                                            break;
                                                        case 'presentation':
                                                            path = self.public_path + '/files/presentations';
                                                            break;
                                                        case 'scorm':
                                                            path = self.public_path + '/files/scorms';
                                                            break;
                                                        default :
                                                            path = self.public_path + '/files/files';
                                                    }

                                                    console.log(content.upload == null)
                                                    content.upload  = content.upload == null  ? {} : content.upload;
                                                    console.log(content)
                                                    content.upload.file =  path + '/' + response.data.data.upload.file;
                                                    content.upload.name =  response.data.data.upload.name;
                                                }else if(response.data.data.url){
                                                      content.url = response.data.data.url;
                                                      content.url = response.data.data.url;
                                                }



                                                console.log(content)

                                            }
                                        })
                                    }
                                    return true ;
                                });


                                this.errors = {};
                                $('#ContentModal').modal('hide')
                            }

                        })
                        .catch(e => {
                            console.log(e)
                        });
                }
            },

			save: function(){
				let self = this;
				if(self.model_type == 'section'){
                    this.saveSection();
                }else{
				    if(this.validateContent()) {
                        return;
                    }
                    this.saveContent();
                }
			},

            validateContent : function () {
                switch (this.model_type) {
                    case 'video': return this.validateVideo(['mp4','mov','ogg','qt']); break;
                    case 'audio': return this.validateContentWithFile(['application/octet-stream','audio/mpeg','mpga','mp3','wav']); break;
                    case 'presentation': return this.validateContentWithFile(['ppt','pptx','pdf','doc','docx','xls','jpeg','png']); break;
                    case 'scorm': return this.validateContentWithFile(['zip']); break;
                }
            },

            validateContentWithFile : function (extensions_array) {
                var lock = true;
                if(this.file){
                    let file_extension = this.file.name.split(".")[1];
                    for(var i = 0; i < extensions_array.length ; i++){
                        if(extensions_array[i] == file_extension){
                            lock = false;
                        }
                    }

                    if(lock){
                        this.errors = {'file': 'The file type must be' + extensions_array.join(',')};
                        if(this.title == '' || this.title == null){
                            this.errors =  {'title': 'The title is required','file': 'The file type must be ' + extensions_array.join(',')};
                        }
                        return true;
                    }else{
                        if(this.title == '' || this.title == null){
                            this.errors =  {'title': 'The title is required'};
                            return true;
                        }
                    }
                    return false;


                }else{
                    if(this.save_type == 'add'){
                        this.errors = {'file': 'The file is required '};
                        if(this.title == '' || this.title == null){
                            this.errors =  {'title': 'The title is required','file': 'The file is required '};
                        }
                        return true;
                    }else{
                        if(this.title == '' || this.title == null){
                            this.errors =  {'title': 'The title is required'};
                            return true;
                        }
                    }
                    return false;
                }
            },

            validateVideo : function (extensions_array) {
                if(this.url && this.file){
                    if(this.title == null || this.title == ''){
                        this.errors =  {'file': 'The file or url is required','url': 'The file or url is required','title': 'The title is required'};
                    }else{
                        this.errors =  {'file': 'The file or url is required','url': 'The file or url is required'};
                    }
                    return true;
                }else if( (this.url == null || this.url == '') && (this.file == null || this.file == '')){
                    if(this.title == null || this.title == ''){
                        this.errors =  {'file': 'The file or url is required','url': 'The file or url is required','title': 'The title is required'};
                    }else{
                        this.errors =  {'file': 'The file or url is required','url': 'The file or url is required'};
                    }
                    return true;
                }else if(this.url){
                    if(this.title == null || this.title == ''){
                        this.errors =  {'title': 'The title is required'};
                        return true;
                    }
                }else if(this.file){
                    return this.validateContentWithFile(extensions_array);
                }
                return false;
            },

		},
	});
</script>

    <script>
        $(document).ready(function(){
            $('.icon-bottom').click(function() {
                $(this).parents('.card-body').children('#content-items').toggle("fast");
                $('i.fa.fa-chevron-up').toggle();
                $('i.fa.fa-chevron-down').toggleClass('d-none');
            });
        })
    </script>
@endsection
