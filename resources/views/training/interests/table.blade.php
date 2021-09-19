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
{{Builder::SetPostType($post_type)}}
{{Builder::SetFolder('interests')}}
{{Builder::SetObject('interest')}}
{{Builder::SetNameSpace('training.')}}
<div class="card">
    <div class="card-header">
        {!!Builder::BtnGroupTable(false)!!}
        {!!Builder::TableAllPosts($count, $interests->count())!!}
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-condensed" style="font-size: 14px;">
            <thead>
            <tr>
                <th class="">CID</th>
                <th class="" style="width:400px;">{{__('admin.title')}}</th>
                <th class="" style="width:400px;">{{__('admin.user')}}</th>
                <th class="" style="width:400px;">{{__('admin.email')}}</th>
                <th class="">{{__('admin.mobile')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($interests as $post)
                <tr data-id="{{$post->id}}">
                    <td> <span class="td-title">{{$post->id}}</span> </td>
                    <td>
                        <span style="display: block;">{{$post->course->trans_title??null}}</span>
                        {!!Builder::BtnGroupRows($post->course->trans_title??null, $post->id, ['Destroy'], [
                           'post'=>$post->id,
                        ])!!}
                    </td>
                    <td> <span class="td-title">{{$post->userId->trans_name??null}}</span></td>
                    <td> <span class="td-title">{{$post->userId->email??null}}</span></td>
                    <td> <span class="td-title">{{$post->userId->mobile??null}}</span></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- /.card-body -->
{{ $interests->render() }}
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
</script>
