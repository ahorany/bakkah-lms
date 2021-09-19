@extends(ADMIN.'.general.index')

@section('table')
<div class="toLoad" id="contents">
	{{$course->trans_title}}<br><br>
	
	<button type="button" @click="OpenModal()" class="btn btn-warning">
	{{__('admin.add_section')}}
  	</button>

	<div class="card">
		<div class="card-header" v-for="(content,index) in contents" v-if="!content.parent_id">
			@{{content.title}}<br>@{{content.excerpt}}<br>
			<button type="button" @click="OpenChildModal('video')" class="btn btn-primary btn-sm px-3" id="video" ><i class="fa fa-video"></i> {{__('admin.video')}}</button>
			<button type="button" @click="OpenChildModal('audio')" class="btn btn-primary btn-sm px-3" id="audio" ><i class="fa fa-headphones"></i> {{__('admin.audio')}}</button>
			<button type="button" @click="OpenChildModal('presentation')" class="btn btn-primary btn-sm px-3" id="presentation" ><i class="fa fa-file-powerpoint"></i> {{__('admin.presentaion')}}</button><br>
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
				<div class="modal-diff-content"></div>
			</div>
		
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					<i class="fas fa-times"></i> {{__('admin.close')}}</button>
				<button type="reset" class="btn btn-primary" @click="Clear()">
					<i class="fas fa-eraser"></i> {{__('admin.clear')}}</button>
				<button type="button"  @click="AddSection()" class="btn btn-primary">
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
			// excerpt : '',
			contents: window.contents,
		},
		methods: {
			OpenModal: function(){

				let self = this;
				axios.get("{{route('training.showModal')}}", {
					params:{
						course_id: self.course_id,
					}
				})
				.then(response => {
					$('.modal-diff-content').html(response.data);
					$('#ContentModal').modal('show');
				})
				.catch(e => {
					// vm.errors.push(e)
				});
			},
			OpenChildModal: function(type){
				let self = this;
				axios.get("{{route('training.showChildModal')}}", {
					params:{
						course_id: self.course_id,
						type 		: type,
					}
				})
				.then(response => {
					console.log(response.data);
					$('.modal-diff-content').html(response.data);
					$('#ContentModal').modal('show');
				})
				.catch(e => {
					// vm.errors.push(e)
				});
				},
			AddSection: function(){
				
				let self = this;
				axios.get("{{route('training.add_section')}}", {
					params:{
						course_id 	: self.course_id,
						title 		: self.title,
						excerpt     : self.excerpt,
					}
				})
				.then(response => {
					alert(response.data[0]);
					alert(response.data);
					this.title 		= '';
					this.excerpt 	= '';
					this.contents = response.data;
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
