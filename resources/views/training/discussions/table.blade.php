<div class="card courses">
  <div class="card-header">

    {!!Builder::BtnGroupTable(true)!!}
    {!!Builder::TableAllPosts($count, $discussions->count())!!}

  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}} </th>
            <th scope="col">Title</th>
            <th scope="col">Created By</th>
            <th scope="col">Start Date</th>
            <th scope="col">End Date</th>
            <th class="text-right">{{__('admin.action')}} </th>

        </tr>
      </thead>
      <tbody>
      @foreach($discussions as $discussion)
      <tr data-id="{{$discussion->id}}">
        <td>
          <span class="td-title">{{$loop->iteration}}</span>
        </td>

          <td>
              <span><a href="{{route('user.reply_message',[$discussion->message->id,'type' => 'discussion'])}}" style="text-decoration: underline;color: #007bff;">{{$discussion->message->title}}</a></span>
          </td>

          <td>
              <span>{{$discussion->message->user->email}}</span>
          </td>

          <td>
              <span>{{$discussion->start_date}}</span>
          </td>

          <td>
              <span>{{$discussion->end_date}}</span>
          </td>

          <td class="text-right">
              @if($discussion->message->user_id == auth()->id())
                  {!!Builder::BtnGroupRows($discussion->message->title, $discussion->id, [], [
                        'post'=>$discussion->id,
                  ])!!}
              @endif
          </td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
<!-- /.card-body -->
{{-- {{ $courses->render() }} --}}
{{ $discussions->appends(['post_type' => $post_type??null, 'trash' => request()->trash??null])->render() }}
