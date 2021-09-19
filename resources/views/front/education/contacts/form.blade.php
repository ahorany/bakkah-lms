<?php
use App\Helpers\Recaptcha;
?>
{!! Recaptcha::script() !!}
<div class="row">
    <div class="col-md-12">
        <form action="{{route('education.static.contactusStore')}}" method="post">
            @csrf
            {!! Recaptcha::execute() !!}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" name="name" value="{{old('name')}}"  class="form-control @error('name') is-invalid @enderror"  placeholder="{{__('education.your_name')}}">
                        @error('name')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="email" name="email" value="{{old('email')}}"  class=" form-control @error('email') is-invalid @enderror"  placeholder="{{__('education.your_email')}}">
                        @error('email')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="tel" name="mobile" value="{{old('mobile')}}"  class="form-control @error('mobile') is-invalid @enderror"  placeholder="{{__('education.your_mobile')}}">
                        @error('mobile')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <select name="request_type" class="form-control @error('request_type') is-invalid @enderror" >
                            <option value="-1">{{__('education.request_type')}}</option>
                            @foreach($request_type as $request)
                                <option value="{{$request->id}}" {{old('request_type')==$request->id?'selected="selected"':''}}>{{$request->trans_name}}</option>
                            @endforeach
                        </select>
                        @error('request_type')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <textarea name="message" cols="40" rows="5" class=" form-control @error('message') is-invalid @enderror"  placeholder="{{__('education.your_message')}}">{{old('message')}}</textarea>
                        @error('message')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <p style="padding-top: 15px;"><input type="submit" value="{{__('education.send')}}" class=" btn btn-secondary btn-lg"></p>

        </form>
    </div> <!-- /.col -->
</div> <!-- /.row -->
