{!!Builder::File('file', 'file')!!}
{!!Builder::UploadForm()!!}
{!!Builder::Input('upload_title', 'title', Builder::$eloquent->upload->title??null)!!}
{!!Builder::Textarea('upload_excerpt', 'alt_text', Builder::$eloquent->upload->excerpt??null, [
    'row'=>3,
    'attr'=>'maxlength="155"',
    'col'=>'col-md-12 upload_excerpt',
])!!}
{!!Builder::Textarea('upload_caption', 'caption', Builder::$eloquent->upload->caption??null, [
    'row'=>3,
    'attr'=>'maxlength="155"',
    'col'=>'col-md-12 upload_caption',
])!!}
{!!Builder::CheckBox('exclude_img', 'exclude_img', Builder::$eloquent->upload->exclude_img??null)!!}
