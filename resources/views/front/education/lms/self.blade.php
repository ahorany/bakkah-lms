<h2>Self Learning</h2>
<form action="{{route('lms.self_submit')}}" method="post">
    @csrf
    user_id: <input type="text" name="user_id" value="1">
    <br>

    payment_id: <input type="text" name="payment_id" value="1">
    <br>

    <button type="submit">test</button>
</form>
