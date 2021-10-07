@extends(FRONT.'.education.layouts.master')

{{-- @section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(72)??null])
@endsection --}}

@section('style')
    <style>
        .false_color {
            background: #dc3545;
            color: #fff;
            font-weight: 700;
            font-size: 12px;
            display: block;
            width: 70%;
            margin: 0 auto;
            border-radius: 20px;
            padding: 3px 0;

        }
        .true_color {
            background: #28a745;
            color: #fff;
            font-weight: 700;
            font-size: 12px;
            display: block;
            width: 70%;
            margin: 0 auto;
            border-radius: 20px;
            padding: 3px 0;
        }

    </style>
@endsection

@section('content')
    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="card p-5 user-info">
                        <div class="row align-items-center">
                            <div class="col-md-6 col-12">
                                <h4>{{ __('education.my_lists') }}</h4>
                            </div>
                            <div class="col-md-6 col-12" style="text-align: right">
                                <a href="{{route('user.complaint',$type)}}" class="btn" style="background: #fb4400; color: #fff;">Add</a>
                            </div>
                        </div>

                        <table class="table table-hover my-4 text-center">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Course</th>
                                <th scope="col">Status</th>
                                <th scope="col">Date</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($complaints as $complaint)
                                    <tr>
                                        <th scope="row">{{$loop->iteration}}</th>
                                        <td>{{$complaint->products->trans_title}}</td>
                                        <td>
                                            <span class="{{($complaint->status == 1) ? "true_color" : "false_color"}}">{{($complaint->status == 1) ? "Done" : "In Progress"}}</span>
                                        </td>
                                        <td>{{$complaint->created_at}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
