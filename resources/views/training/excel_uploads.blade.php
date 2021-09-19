@extends(ADMIN.'.general.index')

@section('table')
    <div class="excel-upload">
        <h2>Excel Upload</h2>
        <hr>
        @if (session('status'))

        @endif
	    <form method="POST" action="{{route('training.excel')}}" enctype="multipart/form-data">
            @csrf
            <label>Excel Upload</label><br />
            <input type="file" name="file"><br />
            <button type="submit" class="mt-3">Submit</button>
        </form>
    </div>
@endsection
