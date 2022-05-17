
<h2>Step 1: Upload xls file & Save Data In DB</h2>
<form method="POST" enctype="multipart/form-data">
    @if(session()->has('color'))
        <div style="color: {{session()->get('color')}}">{{session()->get('msg')}}</div>
        <br>
        <br>
    @endif

    @csrf
    <input type="file" name="file" />
    @error('file')
        <div style="color: #ff0000">{{$message}}</div>
    @enderror
    <br>
    <br>
    <button type="submit">Upload</button>
</form>

<hr>

<h2>Step 2: Send Mails</h2>
<form method="POST" action="{{route('migration.users.mails',[request()->course_id])}}">
    @csrf
    <input placeholder="Master Id" type="text" name="master_id" />
    <button type="submit">Send Mails</button>
</form>

<hr>

<h2>Step 3: Migrate Data</h2>
<form method="POST" action="{{route('migration.users.save',[request()->course_id])}}">
    @if(session()->has('color'))
        <div style="color: {{session()->get('color')}}">{{session()->get('msg')}}</div>
        <br>
        <br>
    @endif

    @csrf
    <input placeholder="Master Id" type="text" name="master_id" />
    @error('master_id')
         <div style="color: #ff0000">{{$message}}</div>
    @enderror

    <button type="submit">Migrate</button>
</form>

