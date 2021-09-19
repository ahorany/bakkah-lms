@extends(ADMIN.'.general.index')
<?php
    $coin_id = 334;
    if(isset($_GET['coin_id'])){
        $coin_id = $_GET['coin_id'];
    }
?>
@section('table')
    @include('training.'.$folder.'.search', ['coin_id'=>$coin_id])

    <div class="cart-table">
	    @include('training.'.$folder.'.table', ['coin_id'=>$coin_id])
    </div>
@endsection
