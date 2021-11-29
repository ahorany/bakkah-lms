@extends('layouts.app')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')
    <div class="card p-30 mb-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mt-0">Hi, {{auth()->user()->trans_name}}</h2>
                <p class="lead">{{auth()->user()->bio}} </p>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                <img src="{{CustomAsset('assets/images/dash.png')}}" alt="">
            </div>
        </div>
    </div>

    <div class="card p-30 mb-5">
        <h3 class="mb-5">{{ __('education.Course Overview') }}</h3>
        <div class="row">
            @forelse($courses->courses as $course)
             <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-4">
                 <a href="{{CustomRoute('user.course_details',$course->id)}}">
                   <div class="text-center course-image p-30">
                    @isset($course->upload->file)
                        <img src="{{CustomAsset('upload/thumb200/'.$course->upload->file)}}" >
                    @endisset


                    <div class="progress">
                        <div style="width: {{$course->pivot->progress??0}}% !important;" class="bar"></div>
                    </div>
                    <small>{{$course->pivot->progress??0}}% Complete</small>
                </div>
                 </a>
            </div>
            @empty
               <p>Not found any course!!</p>
            @endforelse
        </div>
    </div>

{{--    <div class="row mb-5">--}}
{{--        <div class="col-xl-6 d-flex flex-column justify-content-between mb-4 mb-xl-0">--}}
{{--            <div class="card p-30 mb-4">--}}
{{--                <h3>Badges</h3>--}}
{{--                <ul class="badges">--}}
{{--                    <li class="bg-main">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="69.167" height="61.921" viewBox="0 0 69.167 61.921">--}}
{{--                            <g id="Group_17" data-name="Group 17" transform="translate(0 0)">--}}
{{--                              <path id="Path_25" data-name="Path 25" d="M44.314,12.463,28.442-6.36a1.492,1.492,0,0,0-1.14-.531H-7.137a1.488,1.488,0,0,0-1.14.531L-24.151,12.463a1.487,1.487,0,0,0-.211,1.59,1.444,1.444,0,0,0,.211.331h0L10.083,55.03,44.314,14.384h0a1.444,1.444,0,0,0,.211-.331,1.487,1.487,0,0,0-.211-1.59m-4.35-.531H24.693L13.032-3.905H26.608ZM-4.831,14.915,5.957,45.5-19.8,14.915Zm26.659,0L10.083,48.23-1.665,14.915ZM-.823,11.932,10.083-2.88l10.9,14.812Zm25.816,2.983H39.966L14.209,45.5ZM-6.443-3.905H7.131L-4.527,11.932H-19.8Z" transform="translate(24.502 6.891)" fill="#fff"/>--}}
{{--                            </g>--}}
{{--                          </svg>--}}
{{--                    </li>--}}
{{--                    <li class="bg-third">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="59.432" height="55.896" viewBox="0 0 59.432 55.896">--}}
{{--                            <g id="Group_20" data-name="Group 20" transform="translate(0 0)">--}}
{{--                              <path id="Path_27" data-name="Path 27" d="M18.089,21.615a1.864,1.864,0,0,1-1.339,3.2h-26.2a1.813,1.813,0,0,1-1.339-.524,1.816,1.816,0,0,1-.524-1.339,1.816,1.816,0,0,1,.524-1.339,1.813,1.813,0,0,1,1.339-.524h26.2a1.816,1.816,0,0,1,1.339.524M33.4-23.741a17.9,17.9,0,0,1-.409,4.191,10.461,10.461,0,0,1-2.153,4.892q-3.263,3.96-9.783,4.076A20.1,20.1,0,0,1,15-1.674,17.375,17.375,0,0,1,5.454,2.459V15.386H11.16a1.722,1.722,0,0,1,1.339.582,1.949,1.949,0,0,1,.524,1.339,1.74,1.74,0,0,1-.524,1.28,1.816,1.816,0,0,1-1.339.524H-3.861A1.813,1.813,0,0,1-5.2,18.587a1.74,1.74,0,0,1-.524-1.28A1.949,1.949,0,0,1-5.2,15.968a1.72,1.72,0,0,1,1.339-.582H1.728V2.459A17.669,17.669,0,0,1-7.879-1.674a19.067,19.067,0,0,1-6-8.908q-6.407-.234-9.549-4.076a10.427,10.427,0,0,1-2.153-4.892,17.8,17.8,0,0,1-.409-4.191q.114-1.282.234-2.1a1.747,1.747,0,0,1,1.862-1.512h8.851v-1.863a1.816,1.816,0,0,1,.524-1.339,1.813,1.813,0,0,1,1.339-.524H20.36a1.879,1.879,0,0,1,1.282.524,1.728,1.728,0,0,1,.582,1.339v1.863h9.083a1.748,1.748,0,0,1,1.863,1.512q.114.816.234,2.1m-48.21,9.315a20.427,20.427,0,0,1-.234-2.911v-6.289h-7.22q-.117,4.311,1.746,6.638a8.046,8.046,0,0,0,5.707,2.562M18.5-27.351H-11.314v10.014A16.328,16.328,0,0,0-6.947-5.925,13.767,13.767,0,0,0,3.591-1.15,13.77,13.77,0,0,0,14.13-5.925,16.327,16.327,0,0,0,18.5-17.338Zm11.18,3.725H22.223v6.289a21.637,21.637,0,0,1-.232,3.028,8.387,8.387,0,0,0,5.939-2.679q1.859-2.327,1.746-6.638" transform="translate(26.009 31.078)" fill="#fff"/>--}}
{{--                            </g>--}}
{{--                          </svg>--}}

{{--                    </li>--}}
{{--                    <li class="bg-main">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="56.826" height="59.5"--}}
{{--                            viewBox="0 0 56.826 59.5">--}}
{{--                            <g id="Group_185" data-name="Group 185"--}}
{{--                                transform="translate(-2057.568 -821.037)">--}}
{{--                                <path id="Path_168" data-name="Path 168"--}}
{{--                                    d="M2189.418,947.232c.83.978,8.615,8.78,9.888,10.368a4,4,0,0,1,.554,4.549,4.406,4.406,0,0,1-4.141,2.464,4.727,4.727,0,0,1-3.356-1.713c-1.888-2.235-7.038-7.491-8.933-9.72-.125-.147-.261-.283-.445-.481-.19.344-.363.627-.507.924q-2.072,4.261-4.136,8.526a1.821,1.821,0,0,1-2.552.981,3.023,3.023,0,0,1-1.81-2.13q-1.733-6.184-3.472-12.367-2.679-9.516-5.367-19.03a4.38,4.38,0,0,1-.088-2.62,2.525,2.525,0,0,1,3.517-1.513,38.345,38.345,0,0,1,3.624,1.6q13.381,6.27,26.759,12.546a3.156,3.156,0,0,1,1.94,2.159,1.837,1.837,0,0,1-1.379,2.413q-4.486,1.325-8.971,2.647C2190.19,946.945,2189.846,947.082,2189.418,947.232Z"--}}
{{--                                    transform="translate(-86.581 -84.08)" fill="#fff" />--}}
{{--                                <path id="Path_169" data-name="Path 169"--}}
{{--                                    d="M2240.981,904.173a2.267,2.267,0,0,1,1.646-2.224c2.374-.729,4.761-1.426,7.167-2.039a2.191,2.191,0,0,1,2.834,1.646,2.224,2.224,0,0,1-1.6,2.82c-2.313.726-4.643,1.41-6.993,2A2.3,2.3,0,0,1,2240.981,904.173Z"--}}
{{--                                    transform="translate(-147.967 -63.555)" fill="#fff" />--}}
{{--                                <path id="Path_170" data-name="Path 170"--}}
{{--                                    d="M2066.626,952.236a2.379,2.379,0,0,1,2.626,1.693,2.168,2.168,0,0,1-1.2,2.629,21.488,21.488,0,0,1-2.862.935c-1.573.454-3.142.936-4.738,1.292a2.2,2.2,0,0,1-2.8-1.7,2.246,2.246,0,0,1,1.669-2.788c1.807-.569,3.637-1.064,5.459-1.58C2065.5,952.515,2066.222,952.341,2066.626,952.236Z"--}}
{{--                                    transform="translate(0 -105.844)" fill="#fff" />--}}
{{--                                <path id="Path_171" data-name="Path 171"--}}
{{--                                    d="M2207.163,842.29a2.336,2.336,0,0,1-2.088-3.512c1.182-2.163,2.392-4.311,3.608-6.454a2.09,2.09,0,0,1,2.379-1.127,2.263,2.263,0,0,1,1.921,1.907,2.465,2.465,0,0,1-.245,1.359q-1.8,3.362-3.711,6.667A2.088,2.088,0,0,1,2207.163,842.29Z"--}}
{{--                                    transform="translate(-118.745 -8.158)" fill="#fff" />--}}
{{--                                <path id="Path_172" data-name="Path 172"--}}
{{--                                    d="M2078.733,881.35a2.318,2.318,0,0,1-1.613,2.365,2.825,2.825,0,0,1-1.808-.28q-3.311-1.72-6.533-3.611a2.273,2.273,0,0,1-.829-3.11,2.239,2.239,0,0,1,3.078-.9c2.254,1.187,4.473,2.442,6.684,3.707A1.926,1.926,0,0,1,2078.733,881.35Z"--}}
{{--                                    transform="translate(-8.13 -43.958)" fill="#fff" />--}}
{{--                                <path id="Path_173" data-name="Path 173"--}}
{{--                                    d="M2111.676,1006.607a6.5,6.5,0,0,1,.364-1.045q1.8-3.27,3.632-6.518a2.328,2.328,0,0,1,3.092-.886,2.2,2.2,0,0,1,.946,3.084q-1.8,3.3-3.65,6.575a2.237,2.237,0,0,1-2.64,1.072A2.486,2.486,0,0,1,2111.676,1006.607Z"--}}
{{--                                    transform="translate(-43.651 -142.675)" fill="#fff" />--}}
{{--                                <path id="Path_174" data-name="Path 174"--}}
{{--                                    d="M2140.592,832.774a2.075,2.075,0,0,1-2.057-1.5c-.747-2.449-1.469-4.907-2.1-7.387a2.206,2.206,0,0,1,1.661-2.777,2.27,2.27,0,0,1,2.773,1.617c.716,2.356,1.4,4.724,2.024,7.105A2.236,2.236,0,0,1,2140.592,832.774Z"--}}
{{--                                    transform="translate(-63.555 0)" fill="#fff" />--}}
{{--                            </g>--}}
{{--                        </svg>--}}
{{--                    </li>--}}
{{--                    <li class="bg-fourth">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="56.801" height="56.825" viewBox="0 0 56.801 56.825">--}}
{{--                            <g id="Group_23" data-name="Group 23" transform="translate(0 0)">--}}
{{--                              <path id="Path_29" data-name="Path 29" d="M5.263,18.095l2.653,2.323A17.81,17.81,0,0,0,6.7,22.85a12.407,12.407,0,0,1-2.046,3.209,8.061,8.061,0,0,1-3.7,1.825,28.621,28.621,0,0,1-7.185.719H-8V26.832q0-6.193,1.217-8.626a9.106,9.106,0,0,1,4.422-3.982A20.265,20.265,0,0,0,.51,12.565l2.1,2.765A20.181,20.181,0,0,1-.817,17.32a5.968,5.968,0,0,0-2.707,2.323q-.72,1.328-.94,5.42A12.928,12.928,0,0,0,.179,24.4a5.62,5.62,0,0,0,2.155-1.273A13.765,13.765,0,0,0,3.6,21.081a14.859,14.859,0,0,1,1.659-2.986M38.1-10.274a6.864,6.864,0,0,1-2.1,5.032,6.864,6.864,0,0,1-5.03,2.1,6.855,6.855,0,0,1-5.029-2.1,6.858,6.858,0,0,1-2.1-5.032,6.866,6.866,0,0,1,2.1-5.032,6.861,6.861,0,0,1,5.029-2.1,6.87,6.87,0,0,1,5.03,2.1,6.872,6.872,0,0,1,2.1,5.032m-3.538,0a3.471,3.471,0,0,0-1.049-2.544,3.473,3.473,0,0,0-2.544-1.05,3.469,3.469,0,0,0-2.542,1.05,3.466,3.466,0,0,0-1.05,2.544,3.464,3.464,0,0,0,1.05,2.544,3.463,3.463,0,0,0,2.542,1.05,3.466,3.466,0,0,0,2.544-1.05,3.468,3.468,0,0,0,1.049-2.544m14.1-12.442a44.069,44.069,0,0,1-.442,7.52A37.316,37.316,0,0,1,46.275-6.68a21.558,21.558,0,0,1-5.969,8.737A20.357,20.357,0,0,1,37.156,4.27q-2.048,1.219-5.2,2.821T27.815,9.248q-3.539,1.991-3.87,2.211v9.18a1.674,1.674,0,0,1-.663,1.327L16.76,28.49a1.9,1.9,0,0,1-1.217.444,1.845,1.845,0,0,1-.552-.11,1.672,1.672,0,0,1-1.1-1.217l-2.211-9.18L7.7,14q-3.1-3.428-3.87-4.313l-10.5-2.434A1.711,1.711,0,0,1-8,6.1,1.637,1.637,0,0,1-7.67,4.38L-1.149-2.7A1.812,1.812,0,0,1,.179-3.25h9.838q.663-1.106,2.1-4.092,4.531-8.96,6.412-11.17a22.464,22.464,0,0,1,8.953-6.526,47.487,47.487,0,0,1,9.342-2.267,38.018,38.018,0,0,1,7.241-.554,15.4,15.4,0,0,0,2.874.056A1.607,1.607,0,0,1,48.6-26.145a22.2,22.2,0,0,0,.056,3.429m-3.593-1.66a47.261,47.261,0,0,0-16.25,2.655,18.981,18.981,0,0,0-7.627,5.53q-1.551,1.882-5.86,10.508Q13.334-1.811,12.559-.6a1.731,1.731,0,0,1-1.548.884H.952l-3.98,4.2L5.153,6.372a1.422,1.422,0,0,1,.884.552q.552.554,8.623,9.512a2.492,2.492,0,0,1,.331.773l1.659,6.747,3.758-4.092V10.575a1.591,1.591,0,0,1,.554-1.327q.663-.554,5.085-3.1Q27.04,5.6,29.97,4.1T34.945,1.45A14.316,14.316,0,0,0,37.875-.6a17.755,17.755,0,0,0,5.085-7.3q2.322-6.3,2.1-16.48" transform="translate(8.113 27.891)" fill="#fff"/>--}}
{{--                            </g>--}}
{{--                          </svg>--}}

{{--                    </li>--}}
{{--                    <li class="bg-gray">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="51.651" height="54.984" viewBox="0 0 51.651 54.984">--}}
{{--                            <g id="Group_26" data-name="Group 26" transform="translate(0 0)">--}}
{{--                              <path id="Path_31" data-name="Path 31" d="M25.465,13.022,34.214,2.086a2.5,2.5,0,0,0,.3-2.643,2.507,2.507,0,0,0-2.254-1.417H11.434V-6.139A2.5,2.5,0,0,0,10.7-7.9a2.5,2.5,0,0,0-1.768-.732H-14.392a2.5,2.5,0,0,0-2.5,2.5V43.847a2.5,2.5,0,0,0,2.5,2.5,2.5,2.5,0,0,0,2.5-2.5V21.352H6.437v4.165a2.5,2.5,0,0,0,2.5,2.5H32.261a2.5,2.5,0,0,0,1.953-4.06Zm-37.357,3.333V-3.639H6.437V16.355ZM11.434,23.02V3.026l15.628,0L20.312,11.46a2.5,2.5,0,0,0,0,3.125l6.751,8.436Z" transform="translate(16.89 8.637)" fill="#fff"/>--}}
{{--                            </g>--}}
{{--                          </svg>--}}
{{--                    </li>--}}
{{--                    <li class="bg-fourth">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="64.078" height="64.156"--}}
{{--                            viewBox="0 0 64.078 64.156">--}}
{{--                            <g id="Group_186" data-name="Group 186"--}}
{{--                                transform="translate(-2056.667 -217.856)">--}}
{{--                                <path id="Path_175" data-name="Path 175"--}}
{{--                                    d="M2120.574,288.088c1.922-1.895,3.751-3.687,5.64-5.55.554.522,20.382,20.275,29.69,29.6a12.523,12.523,0,0,1,1.8,2.068,3.905,3.905,0,0,1-5.261,5.475,7.374,7.374,0,0,1-1.562-1.231C2141.026,308.607,2120.869,288.446,2120.574,288.088Z"--}}
{{--                                    transform="translate(-50.066 -50.673)" fill="#fff" />--}}
{{--                                <path id="Path_176" data-name="Path 176"--}}
{{--                                    d="M2078.891,222.692l-17.31,17.272a38.428,38.428,0,0,1-3.752-4.377c-1.909-2.987-1.417-6.62,1.116-9.417,1.672-1.846,3.468-3.582,5.259-5.316,3.962-3.836,8.59-4.058,12.583-.276C2077.565,221.315,2078.891,222.692,2078.891,222.692Z"--}}
{{--                                    transform="translate(0 0)" fill="#fff" />--}}
{{--                                <path id="Path_177" data-name="Path 177"--}}
{{--                                    d="M2094.159,323.39s1.817-1.984,2.7-2.953c.787.771,1.341,1.355,1.92,1.934q13.968,13.958,27.927,27.927c.528.528,1.491,1.338,1.667,1.98a2.108,2.108,0,0,1-.487,2.1,2.362,2.362,0,0,1-1.932.493c-.489-.079-1.2-.862-1.615-1.272Q2109.664,338.93,2095,324.249C2094.774,324.018,2094.159,323.39,2094.159,323.39Z"--}}
{{--                                    transform="translate(-29.372 -80.363)" fill="#fff" />--}}
{{--                                <path id="Path_178" data-name="Path 178"--}}
{{--                                    d="M2158.628,258.921s1.817-1.984,2.7-2.953c.787.771,1.341,1.355,1.92,1.934q13.969,13.958,27.927,27.927c.528.529,1.491,1.338,1.668,1.98a2.109,2.109,0,0,1-.488,2.1,2.36,2.36,0,0,1-1.931.493c-.489-.079-1.2-.862-1.615-1.272q-14.673-14.668-29.333-29.349C2159.242,259.549,2158.628,258.921,2158.628,258.921Z"--}}
{{--                                    transform="translate(-79.878 -29.858)" fill="#fff" />--}}
{{--                                <path id="Path_179" data-name="Path 179"--}}
{{--                                    d="M2264.78,440.176c.667-2.663,1.9-3.7,4.473-4,3.322-.39,5.208-2.858,5.971-6.963a3.66,3.66,0,0,1,2.076-2.659l1.687-.771c1.591,6.342,3.138,12.509,4.8,19.121Z"--}}
{{--                                    transform="translate(-163.038 -162.892)" fill="#fff" />--}}
{{--                            </g>--}}
{{--                        </svg>--}}

{{--                    </li>--}}
{{--                    <li class="bg-fourth">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="56.801" height="56.825" viewBox="0 0 56.801 56.825">--}}
{{--                            <g id="Group_23" data-name="Group 23" transform="translate(0 0)">--}}
{{--                              <path id="Path_29" data-name="Path 29" d="M5.263,18.095l2.653,2.323A17.81,17.81,0,0,0,6.7,22.85a12.407,12.407,0,0,1-2.046,3.209,8.061,8.061,0,0,1-3.7,1.825,28.621,28.621,0,0,1-7.185.719H-8V26.832q0-6.193,1.217-8.626a9.106,9.106,0,0,1,4.422-3.982A20.265,20.265,0,0,0,.51,12.565l2.1,2.765A20.181,20.181,0,0,1-.817,17.32a5.968,5.968,0,0,0-2.707,2.323q-.72,1.328-.94,5.42A12.928,12.928,0,0,0,.179,24.4a5.62,5.62,0,0,0,2.155-1.273A13.765,13.765,0,0,0,3.6,21.081a14.859,14.859,0,0,1,1.659-2.986M38.1-10.274a6.864,6.864,0,0,1-2.1,5.032,6.864,6.864,0,0,1-5.03,2.1,6.855,6.855,0,0,1-5.029-2.1,6.858,6.858,0,0,1-2.1-5.032,6.866,6.866,0,0,1,2.1-5.032,6.861,6.861,0,0,1,5.029-2.1,6.87,6.87,0,0,1,5.03,2.1,6.872,6.872,0,0,1,2.1,5.032m-3.538,0a3.471,3.471,0,0,0-1.049-2.544,3.473,3.473,0,0,0-2.544-1.05,3.469,3.469,0,0,0-2.542,1.05,3.466,3.466,0,0,0-1.05,2.544,3.464,3.464,0,0,0,1.05,2.544,3.463,3.463,0,0,0,2.542,1.05,3.466,3.466,0,0,0,2.544-1.05,3.468,3.468,0,0,0,1.049-2.544m14.1-12.442a44.069,44.069,0,0,1-.442,7.52A37.316,37.316,0,0,1,46.275-6.68a21.558,21.558,0,0,1-5.969,8.737A20.357,20.357,0,0,1,37.156,4.27q-2.048,1.219-5.2,2.821T27.815,9.248q-3.539,1.991-3.87,2.211v9.18a1.674,1.674,0,0,1-.663,1.327L16.76,28.49a1.9,1.9,0,0,1-1.217.444,1.845,1.845,0,0,1-.552-.11,1.672,1.672,0,0,1-1.1-1.217l-2.211-9.18L7.7,14q-3.1-3.428-3.87-4.313l-10.5-2.434A1.711,1.711,0,0,1-8,6.1,1.637,1.637,0,0,1-7.67,4.38L-1.149-2.7A1.812,1.812,0,0,1,.179-3.25h9.838q.663-1.106,2.1-4.092,4.531-8.96,6.412-11.17a22.464,22.464,0,0,1,8.953-6.526,47.487,47.487,0,0,1,9.342-2.267,38.018,38.018,0,0,1,7.241-.554,15.4,15.4,0,0,0,2.874.056A1.607,1.607,0,0,1,48.6-26.145a22.2,22.2,0,0,0,.056,3.429m-3.593-1.66a47.261,47.261,0,0,0-16.25,2.655,18.981,18.981,0,0,0-7.627,5.53q-1.551,1.882-5.86,10.508Q13.334-1.811,12.559-.6a1.731,1.731,0,0,1-1.548.884H.952l-3.98,4.2L5.153,6.372a1.422,1.422,0,0,1,.884.552q.552.554,8.623,9.512a2.492,2.492,0,0,1,.331.773l1.659,6.747,3.758-4.092V10.575a1.591,1.591,0,0,1,.554-1.327q.663-.554,5.085-3.1Q27.04,5.6,29.97,4.1T34.945,1.45A14.316,14.316,0,0,0,37.875-.6a17.755,17.755,0,0,0,5.085-7.3q2.322-6.3,2.1-16.48" transform="translate(8.113 27.891)" fill="#fff"/>--}}
{{--                            </g>--}}
{{--                          </svg>--}}

{{--                    </li>--}}
{{--                    <li class="bg-gray">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="51.651" height="54.984" viewBox="0 0 51.651 54.984">--}}
{{--                            <g id="Group_26" data-name="Group 26" transform="translate(0 0)">--}}
{{--                              <path id="Path_31" data-name="Path 31" d="M25.465,13.022,34.214,2.086a2.5,2.5,0,0,0,.3-2.643,2.507,2.507,0,0,0-2.254-1.417H11.434V-6.139A2.5,2.5,0,0,0,10.7-7.9a2.5,2.5,0,0,0-1.768-.732H-14.392a2.5,2.5,0,0,0-2.5,2.5V43.847a2.5,2.5,0,0,0,2.5,2.5,2.5,2.5,0,0,0,2.5-2.5V21.352H6.437v4.165a2.5,2.5,0,0,0,2.5,2.5H32.261a2.5,2.5,0,0,0,1.953-4.06Zm-37.357,3.333V-3.639H6.437V16.355ZM11.434,23.02V3.026l15.628,0L20.312,11.46a2.5,2.5,0,0,0,0,3.125l6.751,8.436Z" transform="translate(16.89 8.637)" fill="#fff"/>--}}
{{--                            </g>--}}
{{--                          </svg>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--            <div class="card p-30">--}}
{{--                <div class="d-flex align-items-center awward-level">--}}
{{--                    <img src="assets/images/awward.png" alt="">--}}
{{--                    <div>--}}
{{--                        <h4>Awards Level</h4>--}}
{{--                        <p>Congratulations! you are at 82.</p>--}}
{{--                        <div class="d-flex align-items-center">--}}
{{--                            <div class="progress">--}}
{{--                                <div class="bar"></div>--}}
{{--                            </div>--}}
{{--                            <span>82/90</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-xl-6">--}}
{{--            <div class="card h-100 calendar">--}}
{{--                <div class="sideb">--}}
{{--                    <div class="header">--}}
{{--                        <i class="fa fa-angle-left" aria-hidden="true">--}}
{{--                            <svg id="Group_103" data-name="Group 103" xmlns="http://www.w3.org/2000/svg" width="14.836" height="24.835" viewBox="0 0 14.836 24.835">--}}
{{--                                <path id="Path_99" data-name="Path 99" d="M161.171,218.961a1.511,1.511,0,0,1-1.02-.4l-11.823-10.909a1.508,1.508,0,0,1,0-2.215l11.823-10.912a1.508,1.508,0,0,1,2.045,2.215l-10.625,9.8,10.625,9.8a1.508,1.508,0,0,1-1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="#333"/>--}}
{{--                              </svg>--}}

{{--                        </i>--}}
{{--                        <h3>--}}
{{--                            <span class="month"></span>--}}
{{--                            <span class="year"></span>--}}
{{--                        </h3>--}}
{{--                        <i class="fa fa-angle-right" aria-hidden="true"><svg id="Group_104" data-name="Group 104" xmlns="http://www.w3.org/2000/svg" width="14.836" height="24.835" viewBox="0 0 14.836 24.835">--}}
{{--                            <path id="Path_99" data-name="Path 99" d="M149.351,218.961a1.511,1.511,0,0,0,1.02-.4l11.823-10.909a1.508,1.508,0,0,0,0-2.215l-11.823-10.912a1.508,1.508,0,0,0-2.045,2.215l10.625,9.8-10.625,9.8a1.508,1.508,0,0,0,1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="#333"/>--}}
{{--                          </svg>--}}
{{--                          </i>--}}
{{--                    </div>--}}
{{--                    <div class="calender">--}}
{{--                        <table>--}}
{{--                            <thead>--}}
{{--                                <tr class="weedays">--}}
{{--                                    <th data-weekday="sun" data-column="0">Sun</th>--}}
{{--                                    <th data-weekday="mon" data-column="1">Mon</th>--}}
{{--                                    <th data-weekday="tue" data-column="2">Tue</th>--}}
{{--                                    <th data-weekday="wed" data-column="3">Wed</th>--}}
{{--                                    <th data-weekday="thu" data-column="4">Thu</th>--}}
{{--                                    <th data-weekday="fri" data-column="5">Fri</th>--}}
{{--                                    <th data-weekday="sat" data-column="6">Sat</th>--}}
{{--                                </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                                <tr class="days" data-row="0">--}}
{{--                                    <td data-column="0"></td>--}}
{{--                                    <td data-column="1"></td>--}}
{{--                                    <td data-column="2"></td>--}}
{{--                                    <td data-column="3"></td>--}}
{{--                                    <td data-column="4"></td>--}}
{{--                                    <td data-column="5"></td>--}}
{{--                                    <td data-column="6"></td>--}}
{{--                                </tr>--}}
{{--                                <tr class="days" data-row="1">--}}
{{--                                    <td data-column="0"></td>--}}
{{--                                    <td data-column="1"></td>--}}
{{--                                    <td data-column="2"></td>--}}
{{--                                    <td data-column="3"></td>--}}
{{--                                    <td data-column="4"></td>--}}
{{--                                    <td data-column="5"></td>--}}
{{--                                    <td data-column="6"></td>--}}
{{--                                </tr>--}}
{{--                                <tr class="days" data-row="2">--}}
{{--                                    <td data-column="0"></td>--}}
{{--                                    <td data-column="1"></td>--}}
{{--                                    <td data-column="2"></td>--}}
{{--                                    <td data-column="3"></td>--}}
{{--                                    <td data-column="4"></td>--}}
{{--                                    <td data-column="5"></td>--}}
{{--                                    <td data-column="6"></td>--}}
{{--                                </tr>--}}
{{--                                <tr class="days" data-row="3">--}}
{{--                                    <td data-column="0"></td>--}}
{{--                                    <td data-column="1"></td>--}}
{{--                                    <td data-column="2"></td>--}}
{{--                                    <td data-column="3"></td>--}}
{{--                                    <td data-column="4"></td>--}}
{{--                                    <td data-column="5"></td>--}}
{{--                                    <td data-column="6"></td>--}}
{{--                                </tr>--}}
{{--                                <tr class="days" data-row="4">--}}
{{--                                    <td data-column="0"></td>--}}
{{--                                    <td data-column="1"></td>--}}
{{--                                    <td data-column="2"></td>--}}
{{--                                    <td data-column="3"></td>--}}
{{--                                    <td data-column="4"></td>--}}
{{--                                    <td data-column="5"></td>--}}
{{--                                    <td data-column="6"></td>--}}
{{--                                </tr>--}}
{{--                                <tr class="days" data-row="5">--}}
{{--                                    <td data-column="0"></td>--}}
{{--                                    <td data-column="1"></td>--}}
{{--                                    <td data-column="2"></td>--}}
{{--                                    <td data-column="3"></td>--}}
{{--                                    <td data-column="4"></td>--}}
{{--                                    <td data-column="5"></td>--}}
{{--                                    <td data-column="6"></td>--}}
{{--                                </tr>--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}



    <div class="row mb-5">
        <div class="col-lg-6 mb-5 mb-lg-0">
            <div class="card h-100 p-30">
                <h3>{{ __('education.Last Video View') }}</h3>
{{--                <div class="h-100 d-flex justify-content-center align-items-center video-btn">--}}
{{--                    <button><svg xmlns="http://www.w3.org/2000/svg" width="26.818" height="30.542"--}}
{{--                            viewBox="0 0 26.818 30.542">--}}
{{--                            <path id="Path_92" data-name="Path 92" d="M1586.871,1164.139V1133.6l26.818,15.165Z"--}}
{{--                                transform="translate(-1586.871 -1133.597)" fill="#fff" />--}}
{{--                        </svg>--}}
{{--                    </button>--}}
{{--                </div>--}}
                @if($last_video)
                    <video controls>
                        <source  src="{{CustomAsset('upload/files/videos/'.$last_video->file)}}">
                    </video>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-30">
                <h3>{{ __('education.Next Video') }}</h3>
                <ul class="video-list">
                    @foreach($next_videos as $next_video)
                      <li>
                        <div class="play">
                            <svg xmlns="http://www.w3.org/2000/svg" width="17.325" height="19.732" viewBox="0 0 17.325 19.732">
                                <path id="Path_92" data-name="Path 92" d="M1586.871,1153.329V1133.6l17.325,9.8Z" transform="translate(-1586.871 -1133.597)" fill="#fff"/>
                              </svg>
                        </div>
                        <div class="text">
                            <h5><a href="{{CustomRoute('user.course_preview',$next_video->id)}}">{{$next_video->title}}</a> </h5>
{{--                            <p>Assess your Knowledge - Pre-Learning</p>--}}
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-5">

        <div class="col-xl-6">
            <div class="card h-100 justify-content-center p-30">

                <div class="d-flex flex-column flex-sm-row flex-wrap">
                    <div class="course-cards bg-main">
                        <svg xmlns="http://www.w3.org/2000/svg" width="71.3" height="62.387" viewBox="0 0 71.3 62.387">
                            <path id="Icon_open-task" data-name="Icon open-task" d="M0,0V62.387H62.387v-32L53.475,39.3V53.475H8.912V8.912h32L49.821,0ZM62.387,0,35.65,26.737l-8.912-8.912-8.912,8.912L35.65,44.562,71.3,8.912Z" fill="#fff"/>
                          </svg>
                        <div>
                            <span>Course Completed</span>
                            <b>{{ isset($complete_courses[1]) ? str_pad($complete_courses[1]->courses_count, 2, '0', STR_PAD_LEFT) : 0 }}</b>
                        </div>
                    </div>

                    <div class="course-cards bg-third">
                        <svg xmlns="http://www.w3.org/2000/svg" width="68.387" height="68.387" viewBox="0 0 68.387 68.387">
                            <path id="Path_163" data-name="Path 163" d="M403.839,783.393H352.548A8.546,8.546,0,0,1,344,774.845V723.554a8.546,8.546,0,0,1,8.548-8.548h35.946l-3.569,3.573-4.958,4.975H352.548v51.291h51.291V747.263l4.9-4.894,3.65-3.65v36.126a8.546,8.546,0,0,1-8.548,8.548Zm-6.711-41.46-10.515,10.515a11.878,11.878,0,0,1-4.646,2.629l-10.258,2.565a1.514,1.514,0,0,1-1.7-.325,1.543,1.543,0,0,1-.321-1.7l2.565-10.258a11.787,11.787,0,0,1,2.637-4.65l10.515-10.515,7.8-7.788,11.746,11.737-7.822,7.792Zm11.583-11.566-11.754-11.737,1.175-1.171A8.305,8.305,0,0,1,409.879,729.2l-1.167,1.171v-.009Z" transform="translate(-344 -715.006)" fill="#fff" fill-rule="evenodd"/>
                          </svg>

                        <div>
                            <span>Course in Progress</span>
                            <b>{{isset($complete_courses[0]) ? str_pad($complete_courses[0]->courses_count, 2, '0', STR_PAD_LEFT) : 0}}</b>
                        </div>
                    </div>

{{--                    <div class="course-cards bg-fourth">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="71.3" height="71.3" viewBox="0 0 71.3 71.3">--}}
{{--                            <path id="Path_164" data-name="Path 164" d="M254.387,629.475h-8.913v8.912a8.91,8.91,0,0,1-8.912,8.913h-35.65A8.91,8.91,0,0,1,192,638.387v-35.65a8.91,8.91,0,0,1,8.913-8.912h8.913v-8.913A8.91,8.91,0,0,1,218.737,576h35.65a8.91,8.91,0,0,1,8.912,8.912v35.65a8.91,8.91,0,0,1-8.912,8.913Zm-53.475,8.912h35.65v-35.65h-35.65v35.65Zm53.475-53.475h-35.65v8.913h17.825a8.91,8.91,0,0,1,8.912,8.912v17.825h8.913v-35.65Z" transform="translate(-192 -576)" fill="#fff" fill-rule="evenodd"/>--}}
{{--                          </svg>--}}

{{--                        <div>--}}
{{--                            <span>Your Certification</span>--}}
{{--                            <b>02</b>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="course-cards bg-gray">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="85.644" height="75.049" viewBox="0 0 85.644 75.049">--}}
{{--                            <g id="Group_178" data-name="Group 178" transform="translate(-448.032 56.166)">--}}
{{--                              <g id="Group_319" data-name="Group 319" transform="translate(448.818 -55.416)">--}}
{{--                                <g id="Group_176" data-name="Group 176" transform="translate(0 0)">--}}
{{--                                  <path id="Path_166" data-name="Path 166" d="M512.178,34.445H472.911a2.768,2.768,0,0,1-2.4-1.384L450.88-.946a2.77,2.77,0,0,1,0-2.768L470.514-37.72a2.768,2.768,0,0,1,2.4-1.384h39.267a2.766,2.766,0,0,1,2.4,1.384L534.21-3.714a2.77,2.77,0,0,1,0,2.768L514.576,33.061A2.766,2.766,0,0,1,512.178,34.445ZM474.51,28.909h36.071L528.615-2.33,510.581-33.568H474.51L456.474-2.33Z" transform="translate(-450.509 39.104)" fill="#fff" stroke="#fff" stroke-width="1.5"/>--}}
{{--                                </g>--}}
{{--                                <g id="Group_177" data-name="Group 177" transform="translate(17.687 12.156)">--}}
{{--                                  <path id="Path_167" data-name="Path 167" d="M500.974,16.264a2.769,2.769,0,0,1-1.289-.318L487.638,9.61l-12.05,6.335a2.767,2.767,0,0,1-4.016-2.918l2.3-13.418-9.748-9.5a2.766,2.766,0,0,1-.7-2.837,2.77,2.77,0,0,1,2.235-1.884l13.471-1.958,6.025-12.206a2.769,2.769,0,0,1,2.483-1.543h0a2.767,2.767,0,0,1,2.482,1.543l6.025,12.206,13.471,1.958a2.766,2.766,0,0,1,2.235,1.884,2.768,2.768,0,0,1-.7,2.837L501.4-.391l2.235,13.035A2.771,2.771,0,0,1,501,16.264.182.182,0,0,0,500.974,16.264ZM472-9.942l6.774,6.6a2.767,2.767,0,0,1,.8,2.45l-1.6,9.324,8.374-4.4a2.778,2.778,0,0,1,2.576,0l8.374,4.4L495.7-.889a2.766,2.766,0,0,1,.8-2.45l6.774-6.6L493.908-11.3a2.773,2.773,0,0,1-2.084-1.514L487.638-21.3l-4.187,8.483a2.773,2.773,0,0,1-2.084,1.514Z" transform="translate(-463.288 30.321)" fill="#fff" stroke="#fff" stroke-width="0.5"/>--}}
{{--                                </g>--}}
{{--                              </g>--}}
{{--                            </g>--}}
{{--                          </svg>--}}


{{--                        <div>--}}
{{--                            <span>Your Points</span>--}}
{{--                            <b>35</b>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>

            </div>
        </div>
        <div class="col-xl-6 mb-5 mb-xl-0">
            {{--            <div class="card p-30">--}}
            {{--                <div class="line-chart">--}}
            {{--                    <h3>Learning Time Overview</h3>--}}
            {{--                    <canvas class="w-100" id="myChart" height="250"></canvas>--}}
            {{--                </div>--}}
            {{--            </div>--}}
        </div>
    </div>
@endsection


@section('script')
{{--    <script>--}}
{{--        var month = [--}}
{{--            "January",--}}
{{--            "February",--}}
{{--            "March",--}}
{{--            "April",--}}
{{--            "May",--}}
{{--            "June",--}}
{{--            "July",--}}
{{--            "August",--}}
{{--            "September",--}}
{{--            "October",--}}
{{--            "November",--}}
{{--            "December"--}}
{{--        ];--}}
{{--        var weekday = [--}}
{{--            "Sunday",--}}
{{--            "Monday",--}}
{{--            "Tuesday",--}}
{{--            "Wednesday",--}}
{{--            "Thursday",--}}
{{--            "Friday",--}}
{{--            "Saturday"--}}
{{--        ];--}}
{{--        var weekdayShort = [--}}
{{--            "sun",--}}
{{--            "mon",--}}
{{--            "tue",--}}
{{--            "wed",--}}
{{--            "thu",--}}
{{--            "fri",--}}
{{--            "sat"--}}
{{--        ];--}}
{{--        var monthDirection = 0;--}}

{{--        function getNextMonth() {--}}
{{--            monthDirection++;--}}
{{--            var current;--}}
{{--            var now = new Date();--}}
{{--            if (now.getMonth() == 11) {--}}
{{--                current = new Date(now.getFullYear() + monthDirection, 0, 1);--}}
{{--            } else {--}}
{{--                current = new Date(now.getFullYear(), now.getMonth() + monthDirection, 1);--}}
{{--            }--}}
{{--            initCalender(getMonth(current));--}}
{{--        }--}}

{{--        function getPrevMonth() {--}}
{{--            monthDirection--;--}}
{{--            var current;--}}
{{--            var now = new Date();--}}
{{--            if (now.getMonth() == 11) {--}}
{{--                current = new Date(now.getFullYear() + monthDirection, 0, 1);--}}
{{--            } else {--}}
{{--                current = new Date(now.getFullYear(), now.getMonth() + monthDirection, 1);--}}
{{--            }--}}
{{--            initCalender(getMonth(current));--}}
{{--        }--}}
{{--        Date.prototype.isSameDateAs = function (pDate) {--}}
{{--            return (--}}
{{--                this.getFullYear() === pDate.getFullYear() &&--}}
{{--                this.getMonth() === pDate.getMonth() &&--}}
{{--                this.getDate() === pDate.getDate()--}}
{{--            );--}}
{{--        };--}}

{{--        function getMonth(currentDay) {--}}
{{--            var now = new Date();--}}
{{--            var currentMonth = month[currentDay.getMonth()];--}}
{{--            var monthArr = [];--}}
{{--            for (i = 1 - currentDay.getDate(); i < 31; i++) {--}}
{{--                var tomorrow = new Date(currentDay);--}}
{{--                tomorrow.setDate(currentDay.getDate() + i);--}}
{{--                if (currentMonth !== month[tomorrow.getMonth()]) {--}}
{{--                    break;--}}
{{--                } else {--}}
{{--                    monthArr.push({--}}
{{--                        date: {--}}
{{--                            weekday: weekday[tomorrow.getDay()],--}}
{{--                            weekday_short: weekdayShort[tomorrow.getDay()],--}}
{{--                            day: tomorrow.getDate(),--}}
{{--                            month: month[tomorrow.getMonth()],--}}
{{--                            year: tomorrow.getFullYear(),--}}
{{--                            current_day: now.isSameDateAs(tomorrow) ? true : false,--}}
{{--                            date_info: tomorrow--}}
{{--                        }--}}
{{--                    });--}}
{{--                }--}}
{{--            }--}}
{{--            return monthArr;--}}
{{--        }--}}

{{--        function clearCalender() {--}}

{{--            var tr = document.querySelectorAll('table tbody tr');--}}

{{--            tr.forEach(element => {--}}
{{--                element.querySelectorAll('td').forEach(function(td) {--}}
{{--                    td.classList.remove('active', 'selectable', 'currentDay', 'between', 'hover');--}}
{{--                    td.innerHTML = '';--}}
{{--                })--}}
{{--            })--}}

{{--            document.querySelectorAll("td").forEach(function (td) {--}}
{{--                td.removeEventListener('mouseenter', null);--}}
{{--                td.removeEventListener('mouseleave', null);--}}
{{--                td.removeEventListener('click', null);--}}
{{--                // $(this).unbind('mouseenter').unbind('mouseleave');--}}
{{--            });--}}

{{--            clickCounter = 0;--}}
{{--        }--}}

{{--        function initCalender(monthData) {--}}
{{--            var row = 0;--}}
{{--            var classToAdd = "";--}}
{{--            var currentDay = "";--}}
{{--            var today = new Date();--}}

{{--            clearCalender();--}}
{{--            // var i = 0;--}}
{{--            monthData.forEach(function (value) {--}}
{{--                var weekday = value.date.weekday_short;--}}
{{--                var day = value.date.day;--}}
{{--                var column = 0;--}}

{{--                document.querySelector(".sideb .header .month").innerHTML = value.date.month;--}}
{{--                document.querySelector(".sideb .header .year").innerHTML = value.date.year;--}}


{{--                if (value.date.current_day) {--}}
{{--                    currentDay = "currentDay";--}}
{{--                    classToAdd = "selectable";--}}
{{--                }--}}
{{--                if (today.getTime() < value.date.date_info.getTime()) {--}}
{{--                    classToAdd = "selectable";--}}

{{--                }--}}
{{--                document.querySelectorAll("tr.weedays th").forEach(function (th) {--}}
{{--                    if (th.dataset.weekday === weekday) {--}}
{{--                        column = th.dataset.column;--}}
{{--                        return;--}}
{{--                    }--}}
{{--                });--}}

{{--                if(classToAdd.length) {--}}
{{--                    document.querySelectorAll("tr.days")[row].querySelectorAll('td')[column].classList.add(classToAdd);--}}
{{--                }--}}

{{--                if(currentDay.length) {--}}
{{--                    document.querySelectorAll("tr.days")[row].querySelectorAll('td')[column].classList.add(currentDay);--}}
{{--                }--}}

{{--                document.querySelectorAll("tr.days")[row].querySelectorAll('td')[column].innerHTML = day;--}}

{{--                currentDay = "";--}}
{{--                if (column == 6) {--}}
{{--                    row++;--}}
{{--                }--}}
{{--            });--}}

{{--            document.querySelectorAll("td.selectable")--}}
{{--                .forEach(td => {--}}
{{--                    td.addEventListener('click', () => {--}}
{{--                        document.querySelectorAll('td.selectable').forEach(function(td) {--}}
{{--                            td.classList.remove('active', 'between', 'hover');--}}
{{--                            // td.classList.remove('active', 'between', 'hover', 'currentDay');--}}
{{--                        })--}}
{{--                        td.classList.toggle('active');--}}
{{--                    })--}}
{{--                })--}}
{{--        }--}}
{{--        initCalender(getMonth(new Date()));--}}

{{--        document.querySelector(".fa-angle-left").onclick = () => getPrevMonth();--}}
{{--        document.querySelector(".fa-angle-right").onclick = () => getNextMonth();--}}

{{--    </script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>--}}
{{--    <script>--}}
{{--        var ctx = document.getElementById('myChart')--}}
{{--        // eslint-disable-next-line no-unused-vars--}}
{{--        var myChart = new Chart(ctx, {--}}
{{--            type: 'line',--}}
{{--            data: {--}}
{{--                labels: [--}}
{{--                    'Jan',--}}
{{--                    'Feb',--}}
{{--                    'Mar',--}}
{{--                    'Apr',--}}
{{--                    'May',--}}
{{--                    'Jun',--}}
{{--                    'Jul ',--}}
{{--                    'Aug',--}}
{{--                    'Sep',--}}
{{--                    'Oct',--}}
{{--                    'Nov',--}}
{{--                    'Dec',--}}
{{--                ],--}}
{{--                datasets: [{--}}
{{--                    data: [100, 50, 20, 155, 20, 33, 75, 88, 45, 90, 10, 50],--}}
{{--                    lineTension: 0,--}}
{{--                    backgroundColor: 'transparent',--}}
{{--                    borderColor: '#D1D1D1',--}}
{{--                    borderWidth: 2,--}}
{{--                    pointBackgroundColor: '#fb4400'--}}
{{--                }]--}}
{{--            },--}}
{{--            options: {--}}
{{--                scales: {--}}
{{--                    xAxes: [{--}}
{{--                        gridLines: {--}}
{{--                            display: false--}}
{{--                        }--}}
{{--                    }],--}}
{{--                    yAxes: [{--}}
{{--                        display: false,--}}
{{--                    }]--}}
{{--                },--}}
{{--                legend: {--}}
{{--                    display: false--}}
{{--                }--}}
{{--            }--}}
{{--        })--}}
{{--    </script>--}}
@endsection


