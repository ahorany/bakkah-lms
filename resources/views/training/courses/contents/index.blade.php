@extends(ADMIN.'.general.index')

@section('table')
<div class="toLoad">
    {{$course->id}}<br>
	{{$course->trans_title}}<br><br>
	<input type="text" value="{{$course->id}}" id="course_id" >
	<!-- data-toggle="modal" data-target="#AddDetailsModal" -->
	<button type="button" class="btn btn-warning add_contents">
	{{__('admin.add_section')}}
  	</button>

	<div class="panel-group">
		@foreach($contents->where('parent_id','') as $content)
			<div class="panel panel-default">
				<div class="panel-heading">{{$content->title}}</div>
				<div class="panel-body">{{$content->excerpt}}</div>
				
				<button type="button" class="btn btn-primary child" id="video" >
					<i class="fa fa-save"></i> {{__('admin.video')}}</button>
				@foreach($contents->where('parent_id',$content->id) as $child)
					----- {{$child->title}}
					----- {{$child->excerpt}}
					<br>
				@endforeach
			</div>
			<br>
		@endforeach
		
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

				{!!Builder::Input('title', 'title', null, ['col'=>'col-md-12'])!!}

				<div class="modal-diff-content">
				</div>

				</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					<i class="fas fa-times"></i> {{__('admin.close')}}</button>
				<button type="reset" class="btn btn-primary" @click="Clear()">
					<i class="fas fa-eraser"></i> {{__('admin.clear')}}</button>
				<button type="button" class="btn btn-primary add_section" >
					<i class="fa fa-save"></i> {{__('admin.save')}}</button>
       
			</div>
			</div>
		</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function(){

	$('.add_contents').click(function(){
		// $('#ContentModal').modal('show');

		$.ajax({
			url:"{{ route('training.showModal') }}" ,
			type:'get',
			data:{},
			success: function(data){
				$('.modal-diff-content').html(data);
				$('#ContentModal').modal('show');
			},
		});
		return false;
	});


	$('.add_section').click(function(){
		var course_id= $('#course_id').val();
		var title= $('input[name="title"]').val();
		var excerpt=  $('textarea[name="excerpt"]').val();
		
		if(title == '')
		{
			alert('enter title please');
			return false;
		}
		$.ajax({
			url:"{{ route('training.add_section') }}" ,
			type:'get',
			data:{
				course_id 	: course_id,
				title 		: title,
				excerpt     : excerpt,
			},
			success: function(data){
				// var slug = course_id;
				// var url = '{{ route("training.contents", ":slug") }}';

				// url = url.replace(':slug', slug);
				
				// window.location.href=url;
			},
		});

	});


	
	$('.child').click(function(){

		$.ajax({
			url:"{{ route('training.showModal') }}" ,
			type:'get',
			data:{},
			success: function(data){
				$('.modal-diff-content').html(data);
				$('#ContentModal').modal('show');
			},
		});
		return false;

	});
	

	
	

});
</script>
@endpush