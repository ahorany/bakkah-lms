@extends(ADMIN.'.general.index')

@section('table')
<div class="toLoad" id="contents">

    <div style="margin-bottom: 20px">
        Course name : {{$course->trans_title}}
        <button type="button" @click="OpenModal('section',null)" class="btn btn-warning">
            {{__('admin.add_section')}}
        </button>
    </div>



	<div  class="card" v-for="(content,index) in contents">
		<div class="card-header" >
            <h3>@{{content.title}}</h3>
            <div v-if="content.details" style="margin: 20px;">@{{content.details.excerpt}}</div>
			<button type="button" @click="OpenModal('video',content.id)" class="btn btn-primary btn-sm px-3" id="video" ><i class="fa fa-video"></i> {{__('admin.video')}}</button>
			<button type="button" @click="OpenModal('audio',content.id)" class="btn btn-primary btn-sm px-3" id="audio" ><i class="fa fa-headphones"></i> {{__('admin.audio')}}</button>
			<button type="button" @click="OpenModal('presentation',content.id)" class="btn btn-primary btn-sm px-3" id="presentation" ><i class="fa fa-file-powerpoint"></i> {{__('admin.presentaion')}}</button>
            <button type="button" @click="OpenModal('scorm',content.id)" class="btn btn-primary btn-sm px-3" id="scorm" ><i class="fa fa-file-powerpoint"></i> {{__('admin.scorm')}}</button><br>
            <br>
            <button  type="button" @click="OpenModal('exam',content.id)" class="btn btn-primary btn-sm px-3" id="exam" ><i class="fa fa-file-powerpoint"></i> {{__('admin.exam')}}</button><br>
            <br>



            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>

                <tbody>
                <tr v-if="content.contents"  v-for="(entry, index) in content.contents"  >
                    <td>@{{entry.title}}</td>
                    <td>
                        <div class="BtnGroupRows" data-id="150">
                            <a v-if="entry.post_type == 'exam'"  class="btn btn-sm btn-primary" :href="base_url  + '/training' + '/add_questions' + '/'+ entry.id "> <i class="fa fa-pencil-alt"></i> Add Questions</a>

                            <button @click="OpenEditModal(content.id,entry.id)"  class="btn btn-sm btn-primary" >
                                <i class="fa fa-pencil-alt"></i> Edit</button>

                            <button @click="deleteContent(content.id,entry.id)"  class="btn btn-sm btn-danger" >
                                <i class="fa fa-trash"></i> Delete</button>
                        </div>
                    </td>


                </tr>

                </tbody>
            </table>


			<br>
		</div>
	</div>

	<div class="modal fade" id="ContentModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">@{{ model_type }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="col-md-12">
					<div class="form-group">
						<label>Title </label>
						<input type="text" v-model="title" name="title" class="form-control" placeholder="title">
                        <div v-show="'title' in errors">
                            <span style="color: red;font-size: 13px">@{{ errors.title }}</span>
                        </div>
                    </div>
				</div>

                <template v-if="model_type == 'exam' ">
                    <div  class="modal-diff-content">
                        <input type="datetime-local" v-model="start_date" name="start_date" class="form-control" placeholder="start date">
                        <div v-show="'start_date' in errors">
                            <span style="color: red;font-size: 13px">@{{ errors.start_date }}</span>
                        </div>
                    </div>

                    <div  class="modal-diff-content">
                        <input type="datetime-local" v-model="end_date" name="end_date" class="form-control" placeholder="end date">
                        <div v-show="'end_date' in errors">
                            <span style="color: red;font-size: 13px">@{{ errors.end_date }}</span>
                        </div>
                    </div>
                </template>


				<div v-if="model_type == 'section' || model_type == 'exam' " class="modal-diff-content">
                        <textarea v-model="excerpt"  class="form-control"  rows="5" placeholder="Details"></textarea>
                    <div v-show="'excerpt' in errors">
                        <span style="color: red;font-size: 13px">@{{ errors.excerpt }}</span>
                    </div>
                </div>




                <div v-else-if="model_type != 'video'" class="modal-diff-content">
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
                            <a class="nav-link active" id="pills-file-tab" data-toggle="pill" href="#pills-file" role="tab" aria-controls="pills-file" aria-selected="true">Upload</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-url-tab" data-toggle="pill" href="#pills-url" role="tab" aria-controls="pills-url" aria-selected="false">Url</a>
                        </li>

                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-file" role="tabpanel" aria-labelledby="pills-file-tab">
                            <input type="file" @change="file = $event.target.files[0]" ref="inputFile" class="form-control">
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



			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					<i class="fas fa-times"></i> {{__('admin.close')}}</button>
				<button type="reset" class="btn btn-primary" @click="Clear()">
					<i class="fas fa-eraser"></i> {{__('admin.clear')}}</button>
				<button type="button"  @click="Add()" class="btn btn-primary">
					<i class="fa fa-save"></i> {{__('admin.save')}}</button>

			</div>
		</div>
	</div>
</div>


</div>



@endsection

@push('vue')
<script>
	window.contents = {!! json_encode($contents??[]) !!}

	var contents = new Vue({
		el:'#contents',
		data:{
			course_id:'{{$course->id}}',
			title: '',
			excerpt : '',
            start_date : '',
            end_date : '',
			contents: window.contents,
            model_type : 'section',
            save_type : 'add',
            file : '',
            url : '',
            content_id : '',
            progress : '0%',
            base_url : window.location.origin,
            errors : {},
		},
		methods: {

           OpenModal : function(type,content_id){

               // clear
               this.title = '';
               this.url = '';
               this.start_date 		= '';
               this.end_date 		= '';


               this.save_type  = 'add';
               this.model_type = type;
               this.content_id = content_id;
               this.errors = {};
                $('#ContentModal').modal('show')
            },

            OpenEditModal : function(parent_id,content_id){
                let self = this;
                this.url = '';
                this.model_type = 'other';
                this.content_id = content_id;
                this.save_type  = 'edit';
                this.errors = {};


                this.contents.forEach(function (section) {
                    if(section.id == parent_id){
                         section.contents.forEach(function (content) {
                            if(content.id == content_id) {
                                    self.title = content.title;
                                    // self.excerpt = content.details.excerpt;
                                    // self.start_date = content.exams.start_date;
                                    // self.end_date = content.exams.end_date;
                                    self.model_type = content.post_type;
                                    self.url = content.url;
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
               }


            },

			Add: function(){

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

                formData.append('file', self.file);
                formData.append('url', self.url);
                formData.append('course_id', self.course_id);
                formData.append('excerpt', self.excerpt);
                formData.append('title', self.title);
                formData.append('start_date', self.start_date);
                formData.append('end_date', self.end_date);
                formData.append('content_id', self.content_id);
                formData.append('type', self.model_type);
                if(self.save_type == 'add'){
                    axios.post("{{route('training.add_section')}}",
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
                                if(this.file != '' ){
                                    this.$refs.inputFile.type='text';
                                    this.$refs.inputFile.type='file';
                                }

                                this.title 		= '';
                                this.start_date 		= '';
                                this.end_date 		= '';
                                this.excerpt 	= '';
                                this.file 	= '';
                                this.url 	= '';
                                this.progress 	= '0%';
                                this.contents = response.data.data;
                                this.errors = {};

                                $('#ContentModal').modal('hide')
                            }



                        })
                        .catch(e => {
                            console.log(e)
                        });
                }else{
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
                                if(this.file != '' ){
                                    this.$refs.inputFile.type='text';
                                    this.$refs.inputFile.type='file';
                                }

                                this.title 		= '';
                                this.excerpt 	= '';
                                this.file 	= '';
                                this.url 	= '';
                                this.progress 	= '0%';
                                this.contents = response.data.data;
                                this.errors = {};

                                $('#ContentModal').modal('hide')
                            }

                        })
                        .catch(e => {
                            console.log(e)
                        });
                }

			},


			Clear:function(){
				this.title 		= '';
				this.excerpt 	= '';

                if(this.file != '' ){
                    this.$refs.inputFile.type='text';
                    this.$refs.inputFile.type='file';
                }
			},
		},
	});
</script>
@endpush
