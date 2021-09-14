<div class="share-box">
    <span class="mr-4">{{__('education.Share Post')}}:</span>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{url()->full()}}" target="_blank" class="facebook">
        <i class="fab fa-facebook"></i></a>
    <a target="_blank" href="https://twitter.com/intent/tweet?text={{$post->title}}?&amp;url={{url()->full()}}" class="twitter">
        <i class="fab fa-twitter"></i></a>
    <a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url={{url()->full()}}&amp;title={{$post->title}}?&amp;summary=&amp;source=Bakkah" class="linkedin">
        <i class="fab fa-linkedin"></i></a>
    <a target="_blank" href="mailto:someone@example.com?subject={{$post->title}}?&amp;body={{url()->full()}}" class="email_">
        <i class="fas fa-envelope"></i></a>
    <a target="_blank" href="http://www.reddit.com/submit/?{{url()->full()}}" class="reddit">
        <i class="fab fa-reddit"></i></a>
</div> <!-- /.share-box -->
