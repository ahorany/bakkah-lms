<table>
    <tr>
        <th style="width:20px;">Email</th>
    </tr>
    @foreach($users as $post)
    <tr>
        <td>{{$post->UserId->email??null}}</td>
    </tr>
    @endforeach
</table>
