@if(count($errors))
	<div class="alert alert-danger alert-dismissible">
	  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	  <!-- <h5><i class="icon fas fa-ban"></i> Alert!</h5> -->
	  <ul>
		  @foreach($errors->all() as $error)
		  	<li>{{$error}}</li>
		  @endforeach
	  </ul>
	</div>
@endif