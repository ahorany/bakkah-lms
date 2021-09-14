<div class="modal-cart-item row align-items-center">
    <div class="col-8 mb-4 d-flex align-items-center">
        <i class="fas fa-check"></i>
        <img class="mx-3" width="80" style="object-fit: contain;" height="64" alt="The Python Mega Course: Build 10 Real World Applications" src="{{CustomAsset('upload/thumb300/'.$course->file)}}">
        <div class="cart-title boldfont">{{$course->trans_title}}</div>
    </div>
    <div class="col-4 mb-4">
        <a href="#" class="btn btn-primary btn-block">{{ __('education.Go to cart') }}</a>
    </div>
    @foreach($trainingOptionFeatures as $trainingOptionFeature)
    <div class="col-12">
        <label class="chk_container">
            {{$trainingOptionFeature->feature->trans_title}}
            {{$trainingOptionFeature->final_price}}
            <input type="checkbox" @click="addCartFeature('{{$cart->id}}', '{{$trainingOptionFeature->id}}')" name="mail_subscribe" value="1"> <span class="checkmark"></span>
        </label>
    </div>
    @endforeach
</div>
