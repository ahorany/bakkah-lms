<div class="share-box">
    {{-- <span class="mr-4">{{__('education.Share Post')}}:</span> --}}
    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{url()->full()}}" class="facebook">
        <span>{!! __('education.Share on') !!} {!! __('education.Facebook') !!}</span>
        <i class="fab fa-facebook"></i></a>
    <a target="_blank" href="https://twitter.com/intent/tweet?text={{$post->title}}?&amp;url={{url()->full()}}" class="twitter">
        <span>{!! __('education.Share on') !!} {!! __('education.Twitter') !!}</span>
        <i class="fab fa-twitter"></i></a>
    <a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{url()->full()}}&amp;title={{$post->title}}?&amp;summary=&amp;source=Bakkah" class="linkedin">
        <span>{!! __('education.Share on') !!} {!! __('education.Linkedin') !!}</span>
        <i class="fab fa-linkedin"></i></a>
    <a target="_blank" href="mailto:someone@example.com?subject={{$post->title}}?&amp;body={{url()->full()}}" class="email_">
        <span>{!! __('education.Share on') !!} {!! __('education.Mail') !!}</span>
        <i class="fas fa-envelope"></i></a>
    <a target="_blank" href="http://www.reddit.com/submit/?{{url()->full()}}" class="reddit">
        <span>{!! __('education.Share on') !!} {!! __('education.Reddit') !!}</span>
        <i class="fab fa-reddit"></i></a>
    {{-- <a target="_blank" href="whatsapp://send?text={{$post->title}} - {{url()->full()}}" class="whatsapp">
        <span>{!! __('education.Share on') !!} {!! __('education.Whatsapp') !!}</span>
        <i class="fab fa-whatsapp"></i></a> --}}
</div> <!-- /.share-box -->
