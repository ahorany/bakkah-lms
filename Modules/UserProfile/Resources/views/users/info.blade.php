@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{ __('education.Info') }} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')

    <div class="userarea-wrapper">
        <div class="row no-gutters">

            @include('userprofile::users.sidebar')

            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-5 user-info">
                        {{-- <h2 class="mb-4"><i class="far fa-user"></i> {{ __('education.Info') }}</h2> --}}
                        {{-- @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="m-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif --}}
                        <form action="{{ route('user.update', $user->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <?php
                                $url = '';
                                if(auth()->user()->upload) {
                                    $url = auth()->user()->upload->file;
                                    $url = CustomAsset('upload/full/'. $url);
                                }else {
                                    $url = 'https://ui-avatars.com/api/?background=fb4400&color=fff&name=' . auth()->user()->trans_name;
                                }
                            ?>
                            <div class="d-md-flex">
                                <div class="img">
                                    <div class="overlay"  onclick="$('#file').trigger('click'); return false;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    <img id="image_preview" src="{{ $url }}">
                                    <input type="file" name="file" id="file" style="display: none">
                                </div>

                                <div class="text mx-4">
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="en_name">{{__('education.english_name')}}</label>
                                                <input name="en_name" value="{{json_decode(auth()->user()->name)->en}}" type="text" id="en_name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="ar_name">{{__('education.arabic_name')}}</label>
                                                <input name="ar_name" value="{{json_decode(auth()->user()->name)->ar}}" type="text" id="ar_name" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="headline">{{__('education.Headline')}}</label>
                                                <input name="headline" value="{{auth()->user()->headline}}" type="text" id="headline" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group h-100">
                                                <label for="bio">{{__('education.Bio')}}</label>
                                                <input value="{{auth()->user()->bio}}" name="bio" type="text" id="bio" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="language">{{__('education.Language')}}</label>
                                                <select name="language" value="{{auth()->user()->language}}" class="form-control @error('language') is-invalid @enderror ">
                                                    <option value="-1">{{__('education.choose')}}</option>
                                                    <option value="en" {{(old('language', auth()->user()->language)=='en')?'selected="selected"':''}}>English</option>
                                                    <option value="ar" {{(old('language', auth()->user()->language)=='ar')?'selected="selected"':''}}>Arabic</option>
                                                </select>
                                                @error('gender_id')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="email">{{__('education.email')}}</label>
                                                <input name="email" value="{{auth()->user()->email}}" type="text" id="email" class="form-control">
                                            </div>
                                        </div>

                                        </div>

                                        <div class="row border-dash-top border-dash-bottom my-4 py-4">
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
                                        {{-- <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="company">{{__('education.Company')}}</label>
                                                <input name="company" value="{{$user->company}}" type="text" id="company" class="form-control">
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="job_title">{{__('education.Job Title')}}</label>
                                                <input name="job_title" value="{{$user->job_title}}" type="text" id="job_title" class="form-control">
                                            </div>
                                        </div> --}}

                                        {{-- <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label>{{__('education.Country')}}</label><br>
                                                <select name="country_id" class="form-control @error('country_id') is-invalid @enderror">
                                                    <option value="-1">{{__('education.choose')}}</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{$country->id}}" {{(old('country_id', $user->country_id)==$country->id)?'selected="selected"':''}}>{{$country->trans_name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('country_id')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                        </div> --}}

                                    </div>
                                            <div class="row mb-3" id="newRow">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="experience">{{__('education.Work Experience')}}</label>
                                                                <span id="addRow" style="cursor: pointer;" class="plus-input">+</span></label>
                                                            </div>
                                                        </div>
                                                        @foreach ($user->experiences as $experience)
                                                        <div class="col-lg-6 col-md-6">
                                                            <div class="form-group">
                                                                <input name="experience[]" value="{{old('experience', $experience->name??null)}}" type="text" class="form-control mb-3">
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                            </div>

                                    <div class="row border-dash-top mt-4 pt-4">
{{--
                                        <div class="col-12">
                                            <h5 class="mb-4">{{ __('education.Change Password') }}</h5>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="password">{{__('education.Password')}}</label>
                                                <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="new-password">{{__('education.New Password')}}</label>
                                                <input type="password" name="new_password" id="new-password" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="retype-password">{{__('education.Re-type New Password')}}</label>
                                                <input type="password" name="new_password_confirmation" id="retype-password" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <hr>
                                        </div> --}}
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="retype-password">{{__('education.Twitter')}}</label>
                                                <div class="input-group twitter">
                                                    <div class="input-group-prepend">
                                                    <span style="background:#02bcdf; color:#fff" class="input-group-text"><i class="fab fa-twitter"></i></span>
                                                    </div>
                                                    <input name="socials[twitter]" type="text" value="{{old('socials.twitter', $user->socials->where('type', 'twitter')->first()->link??null)}}" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="retype-password">{{__('education.Linkedin')}}</label>
                                                <div class="input-group twitter">
                                                    <div class="input-group-prepend">
                                                    <span style="background:#0181cc; color:#fff" class="input-group-text"><i class="fab fa-linkedin-in"></i></span>
                                                    </div>
                                                    <input name="socials[linkedin]" value="{{old('socials.linkedin', $user->socials->where('type', 'linkedin')->first()->link??null)}}" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="retype-password">{{__('education.Facebook')}}</label>
                                                <div class="input-group twitter">
                                                    <div class="input-group-prepend">
                                                    <span style="background:#0181cc; color:#fff" class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                                                    </div>
                                                    <input name="socials[facebook]" value="{{old('socials.facebook', $user->socials->where('type', 'facebook')->first()->link??null)}}" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="retype-password">{{__('education.Youtube')}}</label>
                                                <div class="input-group twitter">
                                                    <div class="input-group-prepend">
                                                    <span style="background:#d60000; color:#fff" class="input-group-text"><i class="fab fa-youtube"></i></span>
                                                    </div>
                                                    <input name="socials[youtube]" value="{{old('socials.youtube', $user->socials->where('type', 'youtube')->first()->link??null)}}" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 text-center mt-4">
                                            <button class="btn btn-primary">{{ __('education.Save Changes') }}</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
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
