<div class="card">
    <div class="card-header">
        {!!Builder::BtnGroupTable()!!}

        <div class="float-right d-inline-flex align-items-center justify-content-between">
            {!!Builder::TableAllPosts($count, $certificates->count())!!}
        </div>

    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th class="">{{__('admin.index')}}</th>
                <th class="">{{__('admin.name')}}</th>
                <th class="">{{__('training.background')}}</th>
                <th class="">{{__('training.preview')}}</th>
                <th class="">{{__('training.print')}}</th>
            </tr>
            </thead>
            <tbody>

            @foreach($certificates as $post)
                <tr data-id="{{$post->id}}">
                    <td>
                        <span class="td-title">{{$loop->iteration}}</span>
                    </td>

                    <td>
                        <span style="display: block;">{{$post->trans_title??null}}</span>
                        {!! Builder::BtnGroupRows($post->title, $post->id, ['Edit', 'Destroy', 'Dublicate'], [
                        'post'=>$post->id,
                        ]) !!}
                    </td>

                    <td>
                        <span class="light">
                        {!!Builder::getCertificate($post->upload??null)!!}
                        </span>
                    </td>

                    <td>
                        <span class="light">
                            <a  class="btn btn-sm btn-primary btn-table btn-preview" style="visibility:visible;"
                                href="{{route('training.certificates.preview', ['id'=> $post->id ] )}}" target="_blank" >
                            <i class="fa fa-eye"></i> {{__('admin.preview')}}
                            </a>
                        </span>
                    </td>

                    <td>
                        <a href="{{route('training.certificates.preview_pdf', ['id'=> $post->id ] )}}"

                             target="_blank" class="btn btn-success btn-xs mb-1">
                                            Certificate
                        </a>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<?php
    // $array = Builder::get_appends(request()->all());
    // href="'{{url('certificates/certificate')}}/'  + cart.id"
    // href="http://127.0.0.1:8000/certificates/certificate/18131"
?>
{{-- {{ $certificates->appends($array)->links() }} --}}

<script>
$(function(){

    // $('.replicate').click(function(){
    //     var r = confirm("Are You Sure ?");
    //     if (r == false) {
    //         return false;
    //     }

    // });
});
</script>
