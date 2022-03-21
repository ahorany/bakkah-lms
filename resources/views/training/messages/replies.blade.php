@foreach ($message->replies as $reply)
<div class="reply">
    <div class="head">
        <h4 class="username">
            {{$reply->user->trans_name??null}}
        </h4>
        <small style="line-height: 1.5rem; color:#999999;">{{$reply->created_at}}</small>
    </div>
    <hr>
    <p>{{$reply->title}}</p>

    @include('training.messages.like_reply_btn', [
        'table_name'=>'replies',
        'eloquent'=>$reply,
        // 'likeable_id'=>$reply->id,
    ])
</div>
@endforeach
