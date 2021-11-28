<div class="col-md-12">
	@include('Html.alert')
	@include('Html.errors')
</div>

<div class="row">
	<div class="col-md-9">
		@csrf
		{!!Builder::Hidden('post_type', $post_type)!!}
		<div class="card card-default">

		    <div class="card-body">
		      <div class="container-fluid">
		        <div class="row">

		        	@yield('col9')

		        </div>
		      </div>
		    </div>

		</div>
		<!-- /.card -->

        @yield('seo')

		{!!Builder::BtnGroupForm()!!}
	</div>

	<div class="col-md-3">

		{!!Builder::BtnGroupForm()!!}
		@if(isset($title))
		<div class="card card-default">
		  <div class="card-header">{{$title}}</div>
		  <div class="card-body">

		  	@yield('col3')

		  </div>
		</div>
		@endif

		@yield('col3_block')

		@if(isset($image_title))
		<div class="card card-default">
		  <div class="card-header">{{$image_title}}</div>
		  <div class="card-body">

		  	@yield('image')

		  </div>
		</div>
		@endif
	</div>
</div>
