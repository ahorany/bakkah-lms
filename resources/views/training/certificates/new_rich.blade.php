<script src="{{CustomAsset(ADMIN.'-dist/tinymce/tinymce.min.js')}}"></script>
@if(isset($childs))
@foreach($childs as $post)
<div class="form-group col-md-12" style="background-color:bisque;" >
<div class="form-group col-md-12" style="width:100%;" >
    <label for="align_{{$post->id}}">{{__('training.align')}} : </label>

    <select name="align_{{$post->id}}" class="form-control" style="width:250px" data-show-flag="true" >
    <option value="-1"> {{__('training.choose_value')}} </option>
        @foreach ($aligns as $key => $value)
            <option value="{{ $value->id }}" {{ $post->align == $value->id  ? "selected" :""}} > {{$value->trans_name }}</option>
        @endforeach
    </select>

  </div>
  <a href="{{route('training.certificates.delete_rich', ['id'=> $post->id ] )}}"  class="btn btn-danger btn-xs mb-1 delete_rich">
                   Delete
</a>
  {{-- <textarea style="width:100%;height:100px;" name="content_{{$post->id}}" class="form-control tinymce" rows="4">{{ $post->content }}</textarea> --}}

  {!!Builder::Textarea('content_'.$post->id, null, $post->content??null, [
    'row'=>3,
    // 'attr'=>'maxlength="155"',
    // 'col'=>'col-md-12 upload_caption',
    'tinymce'=>'tinymce'
])!!}
</div>
<hr>
@endforeach
@endif


<script type="text/javascript">
    $(function() {
        $('.delete_rich').click(function() {
            return confirm("Are You sure ?");
        });
    });
</script>





