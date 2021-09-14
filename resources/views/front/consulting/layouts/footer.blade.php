<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/bootstrap.min.js')}}'></script>
<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/wow.min.js')}}'></script>
<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/script-main.js')}}'></script>
<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/consulting/js/all.js')}}'></script>

<script>
    $(function() {
        var lang = $('html').attr('lang');
        var rtl = false;
        if(lang == 'ar') {
            rtl = true;
        }
        $('.our-services-slider').owlCarousel({
            loop: true,
            dots: false,
            nav: true,
            rtl: rtl,
            navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 3
                }
            }
        });
    });
</script>

</body>

</html>
