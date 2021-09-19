@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}
{{Builder::SetPublishName('save')}}

@section('col9')
	{!!Builder::Input('en_title', 'en_title', null, ['col'=>'col-md-6'])!!}
	{!!Builder::Input('ar_title', 'ar_title', null, ['col'=>'col-md-6'])!!}

    {!!Builder::Textarea('en_details', 'en_details', null, [
      'row'=>10,
      'tinymce'=>'tinymce-small',
  ])!!}

    {!!Builder::Textarea('ar_details', 'ar_details', null, [
      'row'=>10,
      'tinymce'=>'tinymce-small',
  ])!!}
@endsection

@section('col3_block')
    <div class="card card-default">
        <div class="card-body">
            <h5 class="card-title mb-3">Notes</h5>
            <div class="card-text">
                <div class="mb-3"><code>{{'${name}'}}</code><div class="px-2">Lorem Ipsum has been the industry's standard dummy text ever since</div></div>
                <div class="mb-3"><code>{{'${title}'}}</code><div class="px-2">Lorem Ipsum has been the industry's standard dummy text ever since</div></div>

            </div>

        </div>
    </div>
@endsection

