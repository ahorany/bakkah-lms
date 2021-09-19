@if(isset($modal_campaign))
    @if(isset($modal_campaign->upload->file))
        <div class="advertisement-popup">
            <div class="popup-content">
                <a href="{{$modal_campaign->url}}">
                    <img src="{{CustomAsset('upload/full/'.$modal_campaign->upload->file)}}" alt="popup advertisement">
                </a>
                <div class="popup-close"><i class="fa fa-times"></i></div>
            </div>
        </div>
    @endif
@endif
