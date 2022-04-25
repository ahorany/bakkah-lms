<div class="d-flex align-items-center flex-wrap">
    @if($previous)
    {{-- <span class="previous-title"><a style="color: #9c9c9c;" href="{{$previous_url}}">({{$previous->title}})</a></span> --}}
        <button title="{{$previous->title}}" class="next_prev" onclick="location.href ='{{$previous_url}}'">
            <svg id="Group_103" data-name="Group 103" xmlns="http://www.w3.org/2000/svg" width="14.836" height="24.835" viewBox="0 0 14.836 24.835">
                <path id="Path_99" data-name="Path 99" d="M161.171,218.961a1.511,1.511,0,0,1-1.02-.4l-11.823-10.909a1.508,1.508,0,0,1,0-2.215l11.823-10.912a1.508,1.508,0,0,1,2.045,2.215l-10.625,9.8,10.625,9.8a1.508,1.508,0,0,1-1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="#fff"/>
            </svg>
            <span>{{__('education.Previous')}}</span>
        </button>
    @endif

    @if($next)
        <button title="{{$next->title}}" class="next next_prev" @isset($next_url) onclick="location.href ='{{$next_url}}'" @endisset >
            <span id="demo">
                <span>{{__('education.Next')}}</span>
                <svg id="Group_104" data-name="Group 104" xmlns="http://www.w3.org/2000/svg" width="14.836" height="24.835" viewBox="0 0 14.836 24.835">
                    <path id="Path_99" data-name="Path 99" d="M149.351,218.961a1.511,1.511,0,0,0,1.02-.4l11.823-10.909a1.508,1.508,0,0,0,0-2.215l-11.823-10.912a1.508,1.508,0,0,0-2.045,2.215l10.625,9.8-10.625,9.8a1.508,1.508,0,0,0,1.025,2.616Z" transform="translate(-147.843 -194.126)" fill="#fff"/>
                </svg>
            </span>
        </button>
    @endif
</div>
