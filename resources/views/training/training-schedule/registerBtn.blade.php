<br>
<?php
$currency = 'SAR';
$btn = 'success';
if(request()->has('coin_id')){
    if(request()->coin_id==335){
        $currency = 'USD';
        $btn = 'info';
    }
}
?>
<a href="{{route('education.learning.changeCurrency', [
    'slug'=>$session->slug,
    'session_id'=>$session->session_id,
    'currency'=>$currency,
])}}" class="btn btn-xs btn-{{$btn}}" target="x">
    <i class="fas fa-registered"></i> Register {{$currency}}</a>
