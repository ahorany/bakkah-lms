<?php
$liked_it = '';
$loved_it = '';
if(!is_null($eloquent->likes()->where('created_by', auth()->id())->first())){

    $msg = $eloquent->likes()->where('created_by', auth()->id())->first();
    if($msg->operation==506){
        $liked_it = 'btn-primary';
    }
    else if($msg->operation==507){
        $loved_it = 'btn-primary';
    }
}
?>
<button :class="liked_it['{{$eloquent->id}}']" class="btn btn-link {{$liked_it}}" @click="Like($event, 'liked_it', '{{$table_name}}', '{{$eloquent->id}}')">
    {{__('education.Like')}} ({{$eloquent->liked_it}})
</button>
<button :class="loved_it" class="btn btn-link  {{$loved_it}}" @click="Like($event, 'loved_it', '{{$table_name}}', '{{$eloquent->id}}')">
    {{__('education.Love')}} ({{$eloquent->loved_it}})
</button>
<button class="btn btn-link">{{__('education.Reply')}}</button>
