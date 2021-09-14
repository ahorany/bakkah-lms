<div class="row">
    <div class="col-md-10">
    <!-- <h2>{{$course->trans_title}}</h2> -->
        <p>{{__('education.Kindly fill in the Registration Form below')}}</p>
    </div>
    <div class="col-md-2">
        <a href="{{CustomRoute('education.courses.single', ['slug'=>$course->slug])}}" class="btn btn-secondary btn-block d-none d-md-block">{{__('education.View Course')}} <i style="transform: scaleX(-1);" class="fas fa-reply"></i></a>
    </div>
</div>
