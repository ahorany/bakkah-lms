<ul>
    <li><span class="text-secondary">{{__('admin.name')}}:</span> {{$post->userId->trans_name??null}}</li>
    <li><span class="text-secondary">{{__('admin.email')}}:</span> {{$post->userId->email??null}}</li>
    <li><span class="text-secondary">{{__('admin.mobile')}}:</span> {{$post->userId->mobile??null}}</li>
    <li><span class="text-secondary">{{__('admin.invoice number')}}:</span> {{$post->invoice_number??null}}</li>
    <li><span class="text-secondary">{{__('admin.country')}}:</span> {{$post->userId->countries->trans_name??null}}</li>
    <li><span class="text-secondary">{{__('admin.job_title')}}:</span> {{$post->userId->job_title??null}}</li>
    <li><span class="text-secondary">{{__('admin.company')}}:</span> {{$post->userId->company??null}}</li>
    <li><span class="text-secondary">{{__('admin.username')}}:</span> {{$post->userId->username_lms??null}}</li>
    <li><span class="text-secondary">{{__('admin.password')}}:</span> {{$post->userId->password_lms??null}}</li>
</ul>
{{--<span class="title">{{__('admin.name')}} :</span> <span class="value">{{$post->userId->trans_name??null}}</span><br>
<span class="title">{{__('admin.email')}} :</span> <span class="value">{{$post->userId->email??null}}</span><br>
<span class="title">{{__('admin.mobile')}} :</span> <span class="value">{{$post->userId->mobile??null}}</span><br>
<span class="title">{{__('admin.invoice number')}} :</span> <span class="value">{{$post->invoice_number??null}}</span><br>
<span class="title">{{__('admin.country')}} :</span> <span class="value">{{$post->userId->countries->trans_name??null}}</span><br>
<span class="title">{{__('admin.job_title')}} :</span> <span class="value">{{$post->userId->job_title??null}}</span><br>
<span class="title">{{__('admin.company')}} :</span> <span class="value">{{$post->userId->company??null}}</span><br>
@if(auth()->user()->role_id!=2)
    <span class="title">{{__('admin.username')}} :</span> <span class="value">{{$post->userId->username_lms??null}}</span>
    <span class="title">{{__('admin.password')}} :</span> <span class="value">{{$post->userId->password_lms??null}}</span>
@endif
--}}
