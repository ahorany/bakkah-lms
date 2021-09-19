<style>
    .price-card .title {
        width: 150px;
    }
    span.value {
        font-weight: bold;
        font-size: 12px;
        color: #666;
    }
    .table-total-info {
        font-size: 14px;
    }
    .table-total-info td {
        padding-left: 1rem !important;
    }
    .paid-value {
        font-size: 13px; font-weight: normal;
    }
</style>
{{Builder::SetTrash($trash)}}
{{-- {{Builder::SetPostType($post_type)}} --}}
{{Builder::SetFolder('webinarsRegistrations')}}
{{Builder::SetObject('WebinarsRegistration')}}
{{Builder::SetNameSpace('training.')}}

<div class="card">
    <form method="post" action="{{route('training.WebinarRegistration.webi_register_certificate')}}">
        @csrf
        <div class="card-header">
            {!!Builder::BtnGroupTable(false)!!}
            <div class="d-inline-flex align-items-center px-4 justify-content-between">
                <input type="checkbox" id="selectall"><label for="selectall" class="m-0 px-2">Select all</label>
                {{-- {!!Builder::TableAllPosts($count, $cartMasters->count())!!} --}}
                {{--<a class="btn btn-success mx-1 export-btn py-1 px-2" id="exportYes-btn" target="_blank"
                href="{{route('training.webinarsRegistrations.webi_register_certificate', 933)}}">Certificate</a>--}}
            </div>
            {!!Builder::TableAllPosts($count, $webinarsRegistrations->count())!!}
        </div>

        <button type="submit" class="btn btn-success mx-1 export-btn py-1 px-2" name="test">Send Certificate</button>

        <div class="card-body table-responsive p-0">
            <table class="table table-condensed" style="font-size: 14px;">
                <thead>
                <tr>
                    <th>CID</th>
                    <th>{{__('admin.check')}}</th>
                    <th>{{__('admin.title')}}</th>
                    <th>{{__('admin.user')}}</th>
                    <th>{{__('admin.email')}}</th>
                    <th class="">{{__('admin.mobile')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($webinarsRegistrations as $webinarsRegistration)
                    <tr style="height: 45px;" data-id="{{$webinarsRegistration->id}}">
                        <td> <span>{{$webinarsRegistration->id}}</span> </td>
                        <td><span>

                            <input type="checkbox" class="selectedId" name="selectedId[]" value="{{$webinarsRegistration->id}}" />
                            <span class="px-2">{{$webinarsRegistration->certificate_sent_at??null}}</span>
                            <!-- <i style="color:red; cursor:pointer;" class="fas fa-times"></i> -->

                        </span></td>
                        <td>
                            <span style="display: block;">{{$webinarsRegistration->webinar->trans_title??null}}</span>
                            {{--{!!Builder::BtnGroupRows($webinarsRegistration->webinar->trans_title??null, $webinarsRegistration->id, ['Destroy'], [
                                'webinarsRegistration'=>$webinarsRegistration->id,
                            ])!!}--}}
                        </td>
                        <td> <span>{{$webinarsRegistration->userId->trans_name}}</span> </td>
                        <td> <span>{{$webinarsRegistration->userId->email}}</span> </td>
                        <td> <span>{{$webinarsRegistration->userId->mobile}}</span> </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </form>
</div>
<!-- /.card-body -->
{{-- {{ $webinarsRegistrations->render() }} --}}
{{ $webinarsRegistrations->appends(['webinar_id' => request()->webinar_id ?? -1, 'user_search' => request()->user_search ?? null])->render() }}

<script>
    jQuery(function (){
        jQuery('.page-link').click(function (){

            var btnName = jQuery(this);
            var page = jQuery(this).data('page');
            AjaxSearch(btnName, page);
            return false;
        });

        jQuery('[name="trash"], [name="list"]').click(function (){

            var btnName = jQuery(this);
            var page = jQuery(this).data('page');
            var name = btnName.attr('name');

            if(name=='trash') {
                jQuery('[name="trash"]').val('trash');
            }
            else{
                jQuery('[name="trash"]').val('');
            }

            AjaxSearch(btnName, page);
            return false;
        });

        jQuery('tr').hover(function(){
            var data_id = $(this).attr('data-id');
            jQuery('.BtnGroupRows[data-id="'+data_id+'"]'+' .btn-table').css('visibility', 'visible');
        }, function(){
            var data_id = $(this).attr('data-id');
            jQuery('.BtnGroupRows[data-id="'+data_id+'"]'+' .btn-table').css('visibility', 'hidden');
        });

        jQuery('[name="delete"], [name="restore"]').click(function(){

            var form = jQuery(this);
            var btnHtml = form.html();
            var id = form.data('id');
            if(form.attr('name')=='delete') {
                jQuery('[name="delete_btn"][data-id="' + id + '"]').html('Removing...');
            }else{
                jQuery('[name="restore_btn"][data-id="' + id + '"]').html('Restoring...');
            }

            jQuery.ajax({
                type:'post',
                url:form.attr('action'),
                data:form.serialize(),
                success:function(data){
                    jQuery('tr[data-id="'+id+'"]').remove();
                }
                ,errors:function(er){
                    console.log(er);
                }
            });
            return false;
        });
    });
    $(document).ready(function () {
    $('#selectall').click(function () {
        $('.selectedId').prop('checked', this.checked);
    });

    $('.selectedId').change(function () {
        var check = ($('.selectedId').filter(":checked").length == $('.selectedId').length);
        $('#selectall').prop("checked", check);
    });

});

</script>
