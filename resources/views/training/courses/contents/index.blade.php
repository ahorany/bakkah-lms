@extends(ADMIN.'.general.index')

@section('table')
<div class="toLoad" id="contents">
	{{$course->trans_title}}<br><br>

	<button type="button" @click="OpenModal('section',null)" class="btn btn-warning">
	{{__('admin.add_section')}}
  	</button>

	<div class="card">
		<div class="card-header" v-for="(content,index) in contents" v-if="!content.parent_id">
			@{{content.title}}<br>@{{content.excerpt}}<br>
			<button type="button" @click="OpenModal('video',content.id)" class="btn btn-primary btn-sm px-3" id="video" ><i class="fa fa-video"></i> {{__('admin.video')}}</button>
			<button type="button" @click="OpenModal('audio',content.id)" class="btn btn-primary btn-sm px-3" id="audio" ><i class="fa fa-headphones"></i> {{__('admin.audio')}}</button>
			<button type="button" @click="OpenModal('presentation',content.id)" class="btn btn-primary btn-sm px-3" id="presentation" ><i class="fa fa-file-powerpoint"></i> {{__('admin.presentaion')}}</button><br>
			<br>
			<div class="card-body"  v-for="(entry, index) in contents"  v-if="entry.parent_id === content.id">
				<h5 class="card-title">@{{entry.title}} </h5>
				<p class="card-text"> @{{entry.excerpt}}</p>
			</div>
			<br>
		</div>
	</div>

	<div class="modal fade" id="ContentModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">{{__('admin.add_section')}}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="col-md-12">
					<div class="form-group">
						<label>Title </label>
						<input type="text" v-model="title" name="title" class="form-control" placeholder="title">
					</div>
				</div>
				<div v-if="model_type == 'section'" class="modal-diff-content">
                        <textarea v-model="excerpt"  cols="30" rows="10"></textarea>
                </div>

                <div v-else class="modal-diff-content">
                    <input type="file" @change="file = $event.target.files[0]" class="form-control">
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
			contents: window.contents,
            model_type : 'section',
            file : '',
            content_id : '',
			// modal_content: '',
		},
		methods: {
			{{--OpenModal: function(){--}}

			{{--	let self = this;--}}
			{{--	axios.get("{{route('training.showModal')}}", {--}}
			{{--		params:{--}}
			{{--			course_id: self.course_id,--}}
			{{--		}--}}
			{{--	})--}}
			{{--	.then(response => {--}}

            {{--        self.modal_content = response.data;--}}
			{{--		// $('.modal-diff-content').html(response.data);--}}
			{{--		$('#ContentModal').modal('show');--}}
			{{--	})--}}
			{{--	.catch(e => {--}}
			{{--		// vm.errors.push(e)--}}
			{{--	});--}}
			{{--},--}}
			{{--OpenChildModal: function(type){--}}
			{{--	let self = this;--}}
			{{--	axios.get("{{route('training.showChildModal')}}", {--}}
			{{--		params:{--}}
			{{--			course_id: self.course_id,--}}
			{{--			type 		: type,--}}
			{{--		}--}}
			{{--	})--}}
			{{--	.then(response => {--}}
			{{--		console.log(response.data);--}}
			{{--		$('.modal-diff-content').html(response.data);--}}
			{{--		$('#ContentModal').modal('show');--}}
			{{--	})--}}
			{{--	.catch(e => {--}}
			{{--		// vm.errors.push(e)--}}
			{{--	});--}}
			{{--	},--}}

           OpenModal : function(type,content_id){
                 this.model_type = type;
                 this.content_id = content_id;
                $('#ContentModal').modal('show')
            },
			Add: function(){

				let self = this;



                let formData = new FormData();

                //Using this config in my request, the response gives me the mentioned waring of missing boundary
                const config = {
                    headers:{
                        'Content-Type' : 'multipart/form-data',
                    }
                };

                formData.append('file', self.file);
                formData.append('course_id', self.course_id);
                formData.append('excerpt', self.excerpt);
                formData.append('title', self.title);
                formData.append('content_id', self.content_id);
                formData.append('type', self.model_type);

                axios.post("{{route('training.add_section')}}",
                    formData
				,config)
				.then(response => {
					this.title 		= '';
					this.excerpt 	= '';
					this.contents = response.data;
					console.log(response)
				})
				.catch(e => {
					// vm.errors.push(e)
				});
			},


			Clear:function(){
				this.title 		= '';
				this.excerpt 	= '';
			},
		},
	});
</script>
@endpush
