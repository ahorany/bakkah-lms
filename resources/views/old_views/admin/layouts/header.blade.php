<?php
$icon = session()->get('infastructure__icon');
$title = session()->get('infastructure__title');
if(!isset($trash))
  $trash = null;
$trash_title = !is_null($trash)?' <span class="text-danger text-sm"><i class="fa fa-trash"></i></span>' : '';
// $trash_title = !is_null($trash)?' <span class="text-danger">( '.__('admin.'.$trash). ' )</span>' : '';
$title1 = '<i class="'.$icon.'"></i> '.$title.$trash_title;
?>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{!!$title1!!}{{--@yield('page_title')--}}</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('user.home')}}">{{__('admin.home')}}</a></li>
          {{--@if ($__env->yieldContent('page_title'))--}}
          <li class="breadcrumb-item active">{!!$title!!}{{--@yield('page_title')--}}</li>
          {{--@endif--}}
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
