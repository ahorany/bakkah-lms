<table>
    <tr>
        <th>ID</th>
        <th style="width:25px;">Name</th>
        <th style="width:20px;">Email</th>
        <th style="width:15px;">Mobile</th>
        <th style="width:50px;">Message</th>
        <th style="width:15px;">From Learning Or Consulting</th>
        <th style="width:15px;">Request Type</th>
        <th style="width:15px;">Date</th>
    </tr>
    @foreach($contacts as $post)
        <tr>
            <td>{{$post->id}}</td>
            <td>{{$post->name}}</td>
            <td>{{$post->email}}</td>
            <td>{{$post->mobile}}</td>
            <td>{{$post->message}}</td>
            <td>"{{$post->post_type}}"</td>
            <td>"{{$post->requestType->trans_name??null}}"</td>
            <td>{{$post->created_at->isoFormat('D MMM Y')}}</td>
        </tr>
    @endforeach
</table>
