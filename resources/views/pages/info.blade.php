@extends('layouts.app')

@section('useHead')
    <title>{{__('education.My Info')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('style')
    <style>
        .user-info .img .overlay {
            position: absolute;
            top: 10px;
            left: 10px;
            width: calc(100% - 20px);
            height: calc(100% - 20px);
            padding: 10px;
            background: rgba(0,0,0,0.2);
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            border-radius: 50%;
            opacity: 0;
        }
        .user-info .img {
            flex: 0 0 170px;
            max-width: 170px;
            height: 170px;
            padding: 10px;
            border: 2px dashed var(--mainColor);
            border-radius: 50%;
            margin: 0 auto 20px;
            position: relative;
        }
        .user-info .img img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
        }
        .form-group{
            margin-bottom: 20px;
        }
        /* .form-control#bio{
            height: 83%;
        } */
    </style>

@endsection

@section('content')
     <div class="card p-5 user-info">
        @if (session()->has('Success'))
            <div class="error-notice">
                <div class="oaerror success">
                    {{ session('Success') }}
                </div>
            </div>
        @endif
        <form action="{{ route('user.update', $user->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <?php
                $url = '';
                // dd(file_exists(auth()->user()->upload->file));
                if($user->file) {
                    // if (file_exists(auth()->user()->upload->file) == false){
                    //     $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . auth()->user()->trans_name;
                    // }else{
                        $url = $user->file;
                        $url = CustomAsset('upload/full/'. $url);
                    // }
                }else {
                    $url = 'https://ui-avatars.com/api/?background=6a6a6a&color=fff&name=' . $user->user_name;
                }
            ?>
            <div class="d-md-flex">
                <div class="img">
                    <div class="overlay"  onclick="$('#file').trigger('click'); return false;">
                        <i class="fas fa-image"></i>
                    </div>
                    <img id="image_preview" src="{{ $url }}">
                    <input type="file" name="file" id="file" style="display: none">
                    @error('file')
                        <small class="text-danger" style="color: red;">{{$message}}</small>
                    @enderror
                </div>

                <div class="text mx-4">
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">{{__('education.name')}}</label>
                                <input name="name" value="{{$user->user_name}}" type="text" id="name" class="form-control">
                                @error('name')
                                <small class="text-danger" style="color: red;">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
{{--                        <div class="col-lg-6" style="display: none;">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="headline">{{__('education.Headline')}}</label>--}}
{{--                                <input name="headline" value="{{$user->headline}}" type="text" id="headline" class="form-control">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        {{-- <div class="col-lg-6">
                            <div class="form-group h-100">
                                <label for="bio">{{__('education.Bio')}}</label>
                                <textarea rows="4"  name="bio"  id="bio" class="form-control">{{auth()->user()->bio}}</textarea>
                            </div>
                        </div> --}}
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="language">{{__('education.Language')}}</label>
                                <select name="language" value="{{$user->lang}}" class="form-control @error('language') is-invalid @enderror ">
                                    <option value="-1">{{__('education.choose')}}</option>
                                    <option value="en" {{(old('language', $user->lang)=='en')?'selected="selected"':''}}>English</option>
                                    <option value="ar" {{(old('language', $user->lang)=='ar')?'selected="selected"':''}}>Arabic</option>
                                </select>
                                @error('language')
                                    <small class="text-danger" style="color: red;">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email">{{__('education.email')}}</label>
                                <input disabled="true" name="email" value="{{$user->email}}" type="text" id="email" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{__('education.Gender')}}</label>
                                <select name="gender_id" value="{{$user->gender_id}}" class="form-control @error('gender_id') is-invalid @enderror ">
                                    <option value="-1">{{__('education.choose')}}</option>
                                    @foreach($genders as $gender)
                                        <option value="{{$gender->id}}" {{(old('gender_id', $user->gender_id)==$gender->id)?'selected="selected"':''}}>{{$gender->trans_name}}</option>
                                    @endforeach
                                </select>
                                @error('gender_id')
                                    <small class="text-danger" style="color: red;">{{$message}}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="mobile">{{__('education.Mobile')}}</label>
                                <input name="mobile" value="{{$user->mobile}}" type="mobile" id="mobile" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="company">{{__('education.Company')}}</label>
                                <input name="company" value="{{$user->company}}" type="text" id="company" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="job_title">{{__('education.Job Title')}}</label>
                                <input name="job_title" value="{{$user->job_title}}" type="text" id="job_title" class="form-control">
                            </div>
                        </div>

                        </div>

                    {{-- <div class="row border-dash-top  my-4 py-4">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{__('education.Gender')}}</label>
                                <select name="gender_id" value="{{auth()->user()->gender_id}}" class="form-control @error('gender_id') is-invalid @enderror ">
                                    <option value="-1">{{__('education.choose')}}</option>
                                    @foreach($genders as $gender)
                                        <option value="{{$gender->id}}" {{(old('gender_id', $user->gender_id)==$gender->id)?'selected="selected"':''}}>{{$gender->trans_name}}</option>
                                    @endforeach
                                </select>
                                @error('gender_id')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="mobile">{{__('education.Mobile')}}</label>
                                <input name="mobile" value="{{auth()->user()->mobile}}" type="mobile" id="mobile" class="form-control">
                            </div>
                        </div>
                    </div> --}}

                    <div class="row">

                        <div class="col-12">
                            <button class="btn btn-primary">{{ __('education.Save Changes') }}</button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>


    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                $('#image_preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#file").change(function() {
            readURL(this);
        });

        let i = 0;

$("#addRow").click(function () {
    var html = `
            <div class="col-lg-6 mt-3">
                <div class="form-group">
                    <input name="experience[]" type="text" class="form-control">
                </div>
            </div>
    `;

    $('#newRow').append(html);
    i++;
});

    </script>
@endsection
