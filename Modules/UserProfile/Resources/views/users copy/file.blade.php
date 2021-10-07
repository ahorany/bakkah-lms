@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.My Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('content')
<style>
.userarea-wrapper{
    background: #fafafa;
}
.file .card-title {
    background: #848484;
    color: #fff;
}
.arrow i {
    border: 1px solid;
    border-radius: 50%;
    padding: 5px 10px;
    text-align: center;
    margin: 0 5px;
    font-size: 20px;
    cursor:pointer;
}
.arrow i:hover {
    color: #fb4400;
}
</style>
    <div class="userarea-wrapper">
        <div class="row no-gutters">
            @include('userprofile::users.sidebar')
            <div class="col-md-9 col-lg-10">
                <div class="main-user-content m-4">
                    <div class="p-5 user-info file"  style="width:100%; margin:0 auto">
                        <small>Dashboard / My Course / ITEL</small>
                        <h1 style="font-weight: 700; margin: 5px 0 10px;">ITEL Course</h1>
                        <p>PDF File</p>
                        <!-- <iframe src="https://docs.google.com/gview?url={{CustomAsset('upload/pdf/slides.pdf')}}" style="" width="100%" height="500px" allowfullscreen="" webkitallowfullscreen=""></iframe> -->
                        
                        <div class="card" style="width: 100%; border-radius: 10px; border: 1px solid #d6d6d6; overflow: hidden;">
                            <div class="card-title px-5 py-3">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-12">
                                        <h3>ITEL Course</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-0">
                                <iframe src="https://www.alwatanvoice.com/arabic/index.html" style="" width="100%" height="500px" allowfullscreen="" webkitallowfullscreen=""></iframe>
                            </div>
                            <div class="arrow text-center py-3">
                                <i class="fas fa-angle-left"></i>
                                <span class="num">2 / 9</span>
                                <i class="fas fa-angle-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
