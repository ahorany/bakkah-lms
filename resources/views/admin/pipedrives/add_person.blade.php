<form method="post" action="{{route('add_deal_post')}}">

    @csrf
    <input type="text">

    <input type="submit" value="send">
</form>
