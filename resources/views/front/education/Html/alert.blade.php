@if(Session::has('msg'))
	<div style="direction: ltr;" class="alert alert-{{Session::get('class')}} alert-dismissible" role="alert"><!-- fade show-->
	  <div>
		  <strong>{{Session::get('title')}} </strong> {{session('msg')}}
	  </div>
	  <button type="button" class="close" data-dismiss="alert">
		<span aria-hidden="true">&times;</span>
	  </button>
	</div>
@endif