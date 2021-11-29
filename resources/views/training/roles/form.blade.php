@extends('layouts.crm.form')


{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

{{Builder::SetNameSpace('training.')}}
{{Builder::SetPublishName('publish')}}

@section('col9')

	{!!Builder::Hidden('user_id', $eloquent->id??null)!!}
	{!!Builder::Input('en_name', 'en_name', null, ['col'=>'col-md-6'])!!}
	{!!Builder::Input('ar_name', 'ar_name', null, ['col'=>'col-md-6'])!!}

@endsection

@section('image')
<?php $image_title = __('admin.Pages'); ?>
<ul class="list-unstyled">
    @foreach($pages as $page)
    <?php
        $has_treeview = is_null($page->route_name) ? 'menu-item-submenu' : '';
    ?>
    <li class="mb-4 input_item">
        <div class="form-check">
            <input class="form-check-input parent" name="pages[]" {{ $eloquent->infrastructures->pluck('id')->contains($page->id) ? 'checked' : '' }} type="checkbox" value="{{ $page->id }}" id="page_{{ $page->id }}">
            <label class="form-check-label" for="page_{{ $page->id }}">{{ $page->trans_title }}</label>
        </div>
        @if($has_treeview=='menu-item-submenu')
        <ul class="list-unstyled px-5 mt-2">
            @foreach($infrastructures->where('parent_id', $page->id) as $infa_child)
            <li class="mb-2">
                <div class="form-check child">
                    <input class="form-check-input child" name="pages[]" {{ $eloquent->infrastructures->pluck('id')->contains($infa_child->id) ? 'checked' : '' }} type="checkbox" value="{{ $infa_child->id }}" id="page_{{ $infa_child->id }}">
                    <label class="form-check-label" for="page_{{ $infa_child->id }}">{{ $infa_child->trans_title }}</label>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
    </li>
    @endforeach
</ul>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.child').on('change', ':checkbox', function() {
                if ($(this).is(':checked')) {
                    var currentRow = $(this).closest('.input_item');
                    var targetedRow = currentRow.find('.parent').first();
                    targetedRow.prop('checked', true);
                }
            });
        });
    </script>
@endsection
