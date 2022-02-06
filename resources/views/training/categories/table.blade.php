<?php
use App\Models\Training\CourseRegistration;
?>

<div class="card categories">
  <div class="card-header">
      <?php $create_role = false; ?>
      @can('course.create')
          <?php $create_role = true; ?>
      @endcan
    {!!Builder::BtnGroupTable($create_role)!!}
    {!!Builder::TableAllPosts($count, $categories->count())!!}
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.name')}}</th>
            <th class="text-right">{{__('admin.action')}}</th>
        </tr>
      </thead>
      <tbody>

      <?php $btn_roles = null; ?>
      @can('course.edit')
          <?php  $btn_roles[] = 'Edit'; ?>
      @endcan

      @can('course.delete')
          <?php   $btn_roles[] = 'Destroy' ?>
      @endcan

      @foreach($categories as $post)
      <tr data-id="{{$post->id}}">
            <td>
                <span class="td-title">{{$loop->iteration}}</span>
            </td>
            <td>
                <span style="display: block;" class="title">{{$post->trans_title ?? null}}</span>
            </td>

            <td class="d-sm-table-cell text-right">
                {!!Builder::BtnGroupRows($post->trans_title, $post->id, $btn_roles, [
                    'post'=>$post->id,
                ])!!}

            </td>
      </tr>

      @endforeach
      </tbody>
    </table>
  </div>
</div>

{{ $categories->appends(['post_type' => $post_type??null, 'trash' => request()->trash??null, 'category_search' => request()->category_search??null, 'category_id' => request()->category_id??-1])->render() }}
