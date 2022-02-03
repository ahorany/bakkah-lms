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
