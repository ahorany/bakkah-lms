@extends(FRONT.'.education.layouts.master')
@section('useHead')
    <title>{{__('education.Login')}} | {{ __('home.DC_title') }}</title>
@endsection
@section('content')

<style>
    #map {
        height: 500px;
        width: 100%;
    }
    div#map>div:nth-child(2) {
       display: none;
    }
</style>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script>
    let map, infoWindow, pos;
    let loc = { lat: 31.042673, lng: 34.770580 };
    function marker(pos , map){
        marker = new google.maps.Marker({
            position: pos,
            map: map,
        });
    }

    function my_location(){
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: 31.042673, lng: 34.770580 },
            zoom: 10,
        });
        return map;
    }

    function geocodeLatLng(geocoder, map, infowindow , pos) {
        const latlng = pos
        geocoder
        .geocode({ location: latlng })
        .then((response) => {
            if (response.results[0]) {
                map.setZoom(11);
                const marker = new google.maps.Marker({
                position: latlng,
                map: map,
                });
                infowindow.setContent(response.results[0].formatted_address);
                infowindow.open(map, marker);
            } else {
                window.alert("No results found");
            }
        })
        .catch((e) => window.alert("Geocoder failed due to: " + e));
    }

    function initMap() {
        map = my_location();
        // marker(loc,map)
        infoWindow = new google.maps.InfoWindow();
        // const locationButton = document.createElement("button");
        // locationButton.textContent = "Pan to Current Location";
        // locationButton.classList.add("custom-map-control-button");
        // map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
        // locationButton.addEventListener("click", () => {
            // if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        infoWindow.setPosition(pos);
                        map.setCenter(pos);
                        marker(pos,map);

                        // const geocoder = new google.maps.Geocoder();
                        // const infowindow = new google.maps.InfoWindow();
                        // geocodeLatLng(geocoder, map, infowindow, pos);
                    }
                    // ,() => {
                    //     handleLocationError(true, infoWindow, map.getCenter());
                    // }
                );
            // } else {
            //     handleLocationError(false, infoWindow, map.getCenter());
            // }
        // });
    }

    // function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    // infoWindow.setPosition(pos);
    // infoWindow.setContent(
    //     browserHasGeolocation
    //     ? "Error: The Geolocation service failed."
    //     : "Error: Your browser doesn't support geolocation."
    // );
    // infoWindow.open(map);
    // }

    $(document).ready(function(){
        $("table").parent().css({"display": "none"});
    });
</script>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-4 form-login">
            <div class="card pb-4">
            <form action="{{route('user.loginSubmit')}}" method="POST">
                @csrf
                <div class="text-center my-4">
                    <h2>{{ __('education.Login Account') }}</h2>
                    {{-- <p>{{__('education.Welcome! Please, fill email and password to sign in into your account.')}}</p> --}}
                </div>
                @if(request()->has('redirectTo'))
                    <input type="hidden" name="redirectTo" value="{{ request()->redirectTo }}">
                @endif

                @if(request()->has('redirect'))
                    <input type="hidden" name="redirect" value="{{ request()->redirect }}">
                    <input type="hidden" name="action" value="wishlist">
                    <input type="hidden" name="option" value="{{ request()->option }}">
                    <input type="hidden" name="session_id" value="{{ request()->session_id }}">
                @endif

                <div class="form-group position-relative">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('education.Email or Username') }}" class="form-control @error('email') is-invalid @enderror">
                    <i class="fas fa-envelope"></i>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group position-relative">
                    <input type="password" name="password" placeholder="{{ __('education.Password') }}" class="form-control @error('password') is-invalid @enderror">
                    <i class="fas fa-shield-alt"></i>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group d-flex justify-content-between align-items-center">
                    <div>
                        <input id="save" type="checkbox" name="checkbox">
                        <label style="color: #767676; margin: 0;" for="save">Save Password</label>
                    </div>
                    <a style="color: #242a2e" href="{{ url('password/reset') }}" class="my-3">{{ __('education.Forgot Your Password?') }}</a>
                </div>

                {{-- <a href="{{ url('password/reset') }}" class="d-block my-3">{{ __('education.Forgot Your Password?') }}</a> --}}

                <button class="btn btn-primary btn-block mb-3">{{__('education.Login')}}</button>

                <hr class="mt-5">
                <p class="or">OR</p>

                <div class="social-media text-center my-4">
                    <a href="{{url('redirect','facebook')}}" style="background: #3b5999"><i class="fab fa-facebook-f"></i></a>
                    <a href="{{url('redirect','twitter')}}" style="background: #55acee"><i class="fab fa-twitter"></i></a>
                    <a href="{{url('redirect','linkedin')}}" style="background: #3c83d9"><i class="fab fa-linkedin-in"></i></a>
                    <a href="{{url('redirect','google')}}" style="background: #fb4d3e"><i class="fab fa-google-plus-g"></i></a>
                </div>

                @php
                $url = route('user.register');
                if(request()->has('redirectTo')) {
                    $url = route('user.register') . '/?redirectTo='. request()->redirectTo;
                }

                if(request()->has('redirect')) {
                    $url = route('user.register') . '/?redirect='. request()->redirect .'&action=wishlist&option=' . request()->option . '&session_id=' . request()->session_id;
                }
                @endphp
                <p class="text-center">{{__('education.Don’t have an account?')}} <a href="{{$url}}">{{__('education.Create an account')}}</a></p>
            </form>
        </div>
        </div>
    </div>
    {{-- <div class="mt-5" id="map"></div> --}}
</div>
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcI2eDufRoytCK-fpuiPkkK7_n_JK4bhE&callback=initMap&libraries=&v=weekly" async ></script> --}}

@endsection
