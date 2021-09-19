<!-- Event snippet for Checkout conversion page -->
<?php
$coinID = 'SAR';
$total = 0;
if(session()->has('coinID')){
    if(session()->get('coinID')==335){
        $coinID = 'USD';
    }
}

if(session()->has('total')){
    $total = session()->get('total');
}
?>
<script>
    gtag('event', 'conversion', {
        'send_to': 'AW-971233488/bSyCCKvu4OoBENCxj88D',
        'value': {{$total}},
        'currency': {{$coinID}}
    });
</script>
