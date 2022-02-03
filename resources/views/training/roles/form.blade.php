@extends('layouts.crm.form')


{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

{{Builder::SetNameSpace('training.')}}
{{Builder::SetPublishName('publish')}}

@section('col9')

	{!!Builder::Hidden('user_id', $eloquent->id??null)!!}
	{!!Builder::Input('name', 'name', null, ['col'=>'col-md-12'])!!}
{{--	{!!Builder::Input('ar_name', 'ar_name', null, ['col'=>'col-md-6'])!!}--}}
    <label>Permissions</label>
    <ul id="treeview1">
        <li>
            <ul>
                </li>
                    @foreach($permission as $value)
                    <label
                        style="font-size: 16px; margin: 5px">{{ Form::checkbox('permission[]', $value->id, isset($rolePermissions[$value->id]), array('class' => 'name')) }}
                        {{ $value->title }}</label>
                    @endforeach
                </li>
            </ul>
        </li>
    </ul>
@endsection

{{--@section('image')--}}
{{--<ul class="list-unstyled">--}}
{{--    @foreach($permission as $page)--}}
{{--    <?php--}}
{{--        $has_treeview = is_null($page->route_name) ? 'menu-item-submenu' : '';--}}
{{--    ?>--}}
{{--    <li class="input_item">--}}
{{--        <div class="form-check">--}}
{{--            --}}{{-- <input class="form-check-input parent" name="pages[]" {{ $eloquent->infrastructures->pluck('id')->contains($page->id) ? 'checked' : '' }} type="checkbox" value="{{ $page->id }}" id="page_{{ $page->id }}">--}}
{{--            <label class="form-check-label" for="page_{{ $page->id }}">{{ $page->trans_title }}</label> --}}

{{--            <label class="container-check form-check-label" for="page_{{ $page->id }}" style="padding: 10px 30px 0; font-size: 15px;">--}}
{{--                {{ $page->trans_title }}--}}
{{--                <input class="form-check-input parent" name="pages[]" {{ $eloquent->infrastructures->pluck('id')->contains($page->id) ? 'checked' : '' }} type="checkbox" value="{{ $page->id }}" id="page_{{ $page->id }}">--}}
{{--                <span class="checkmark" style="top: 12px;"></span>--}}
{{--            </label>--}}

{{--        </div>--}}
{{--        @if($has_treeview=='menu-item-submenu')--}}
{{--        <ul class="list-unstyled px-5 mt-2">--}}
{{--            @foreach($infrastructures->where('parent_id', $page->id) as $infa_child)--}}
{{--            <li>--}}
{{--                <div class="form-check child">--}}
{{--                    <label class="container-check form-check-label" for="page_{{ $infa_child->id }}" style="padding: 10px 30px 0; font-size: 15px;">--}}
{{--                        {{ $infa_child->trans_title }}--}}
{{--                        <input class="form-check-input child" style="display: inline-block;" name="pages[]" {{ $eloquent->infrastructures->pluck('id')->contains($infa_child->id) ? 'checked' : '' }} type="checkbox" value="{{ $infa_child->id }}" id="page_{{ $infa_child->id }}">--}}
{{--                        <span class="checkmark" style="top: 12px;"></span>--}}
{{--                    </label>--}}
{{--                    --}}{{-- <input class="form-check-input child" name="pages[]" {{ $eloquent->infrastructures->pluck('id')->contains($infa_child->id) ? 'checked' : '' }} type="checkbox" value="{{ $infa_child->id }}" id="page_{{ $infa_child->id }}">--}}
{{--                    <label class="form-check-label" for="page_{{ $infa_child->id }}">{{ $infa_child->trans_title }}</label> --}}
{{--                </div>--}}
{{--            </li>--}}
{{--            @endforeach--}}
{{--        </ul>--}}
{{--        @endif--}}
{{--    </li>--}}
{{--    @endforeach--}}
{{--</ul>--}}
{{--@endsection--}}

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
