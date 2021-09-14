{!!Builder::File('file', 'file')!!}
{!!Builder::UploadForm()!!}
{!!Builder::Input('upload_title', 'title', Builder::$eloquent->upload->title??null)!!}
{!!Builder::Excerpt('upload_excerpt', 'alt_text', Builder::$eloquent->upload->excerpt??null)!!}
{!!Builder::Excerpt('upload_caption', 'caption', Builder::$eloquent->upload->caption??null)!!}
{!!Builder::CheckBox('exclude_img', 'exclude_img', Builder::$eloquent->upload->exclude_img??null)!!}
