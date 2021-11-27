jQuery(function ($) {

    // $("label").click(function(){
    //     $(this).parent().find("label").css({"background-color": "#eaeaea"});
    //     $(this).css({"background-color": "#fb4400"});
    //     $(this).nextAll().css({"background-color": "#fb4400"});
    //   });

    //   $("#payment-form label").click(function(){
    //     $(this).parent().find("label").css({"background-color": "transparent"});
    //     $(this).css({"background-color": "transparent"});
    //     $('button#pay-button').css({"background-color": "#303d47"});
    //     $(this).nextAll().css({"background-color": "transparent"});
    //   });

    //   $("#payment-form > label").click(function(){
    //     $('button#pay-button').css({"background-color": "#303d47"});
    //   });

    //   $("button#pay-button").hover(function(){
    //     $(this).css("background-color", "#fb4400");
    //     }, function(){
    //     $(this).css("background-color", "#303d47");
    //   });

    //   $(".star label").click(function(){
    //     $(this).parent().find("label").css({"color": "#eaeaea"});
    //     $(this).css({"color": "#fb4400"});
    //     $(this).nextAll().css({"color": "#fb4400"});
    //     $(this).css({"background-color": "transparent"});
    //     $(this).nextAll().css({"background-color": "transparent"});
    //   });


    $('.loading-screen').fadeOut();
    $('.page-content-wrapper').css('opacity', 1);

    jQuery('[data-toggle="tooltip"]').tooltip({
        trigger: 'hover'
    });

    new WOW().init();

    $('.advertisement_bar .close-banner').on('click', function () {
        jQuery(this).hide();
        jQuery('.advertisement_bar').remove()
        $('.education-header').removeClass('has-advertisment');
        //localStorage.setItem('isshow', 1);
    })

    var isshow = localStorage.getItem('isshow');
    if (isshow == null) {
        jQuery('body').addClass('advertisement');
        jQuery('.advertisement_bar').show();
    }

    // Popup Advertisement
    jQuery('.advertisement-popup .popup-content .popup-close').on('click', function () {
        jQuery('.advertisement-popup .popup-content').removeClass('open');
        jQuery('.advertisement-popup').fadeOut();
        localStorage.setItem('popup-show-new', 1);
    });

    var popupShow = localStorage.getItem('popup-show-new');
    if (popupShow == null) {
        setTimeout(function () {
            jQuery('.advertisement-popup').fadeIn();
            jQuery('.advertisement-popup .popup-content').addClass('open');

        }, 3000);
    }

    var lang = $('html').attr('lang');

    var rtl = false;
    if (lang == 'ar') {
        rtl = true;
    }

    let items = $('.slider-wrapper').data('items');

    $('.related-articles').owlCarousel({
        loop: false,
        margin: 30,
        rtl: rtl,
        nav: false,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            }
        }
    });

    // console.log(items);
    $('.related-courses').owlCarousel({
        loop: false,
        margin: 0,
        autoplay: true,
        rtl: rtl,
        nav: false,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 5
            }
        }
    });

    $('.course-schedule-slider').owlCarousel({
        loop: false,
        margin: 30,
        rtl: rtl,
        nav: false,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            800: {
                items: 2
            }
        }
    });

    $('.most-popular').owlCarousel({
        loop: false,
        margin: 30,
        rtl: rtl,
        nav: false,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            800: {
                items: 4
            }
        }
    });

    $('.main-slider').owlCarousel({
        loop: false,
        rtl: rtl,
        nav: false,
        items: 1
    });



    $('.widget ul li a').removeAttr("data-toggle");

    $(document).scroll(function () {
        var top = $(document).scrollTop();
        if (top > 400) {
            $('.back-to-top').addClass('active');
        } else {
            $('.back-to-top').removeClass('active');
        }
    });

    $('.back-to-top').click(function () {
        $('html').animate({
            scrollTop: 0
        }, 1000);
    })

    var eventFired = false;
    var glance = $('.glance');
    if (glance.length) {
        var oTop = glance.offset().top - window.innerHeight;
    }

    setTimeout(function () {
        if ($("#course-deatilas-tabs")[0]) {
            var hieghtThreshold = $("#course-deatilas-tabs").offset().top;
            var headerHeight = $("header").height();
            var advertisement_barHeight = $(".advertisement_bar").outerHeight();
            $(window).scroll(function () {
                var scroll = $(window).scrollTop();


                if (scroll >= (hieghtThreshold - headerHeight)) {
                    $("#course-deatilas-tabs").addClass('fixed');
                    $("#course-deatilas-tabs").css('top', headerHeight);
                } else {
                    $("#course-deatilas-tabs").removeClass('fixed');
                    $("#course-deatilas-tabs").css('top', 'auto');
                }
            });

            $('.advertisement_bar .close-banner').on('click', function () {
                $("#course-deatilas-tabs").addClass('no-adv');
            })
        }
    }, 2000);


    // smoth scroll
    $('#course-deatilas-tabs a').on('click', function (e) {
        e.preventDefault();
        var target = $(this).attr('href');

        var offsetTop = $(target).offset().top;

        $('html, body').animate({
            scrollTop: offsetTop - 180
        }, 700);
    })

    // toggle accordion icon
    $('.accordion-single .card-header button').on('click', function () {
        $(this).find('i').toggleClass('fa-chevron-up');
        // $('.accordion-single .card-header button').not(this).find('i').removeClass('fa-chevron-up');

        $(this).parents('.accordion-single').find('.collapse').slideToggle();
    });

    // Mega Menu
    $('#ExploreMenu, .explore-mega-menu .dropdown-menu').on('mouseenter', function () {
        $('.explore-mega-menu .menu-wrapper').show()
        $('.explore-mega-menu .dropdown-menu').show()
    })
        .on('mouseleave', function () {
            $('.explore-mega-menu .menu-wrapper').hide();
            $('.education-header .explore-mega-menu .menu-wrapper button.dropdown-item:first-of-type').addClass('active');
        })

    $('.education-header .explore-mega-menu .menu-wrapper > .dropdown-menu .dropdown-item').hover(function () {
        $(this).removeClass('active');
    })


    // Mobile Menu
    $('.navbar-toggler').on('click', function () {
        $navMenuCont = $($(this).data('target'));
        $navMenuCont.css('left', 0);
        $(".menu-overlay").fadeIn(100);

    });
    $(".menu-overlay").on('click', function (event) {
        //$(".navbar-toggler").trigger("click");
        $('#navbarSupportedContent').css('left', -1000)
        $('.userarea-wrapper aside').css('left', -1000)
        $(".menu-overlay").fadeOut(100);
    });

    // Course tabs
    // $('.course-categories-tabs a').on('click', function(e) {
    //     e.preventDefault();
    //     var target = $(this).data('target');
    //     var text = $(this).find('span').text();

    //     $('.course-categories-tabs a').removeClass('active');
    //     $(this).addClass('active');

    //     $('#category-title').text(text);

    //     $('.course-categories-content .course-column').hide();
    //     $('.course-categories-content .'+target).show();
    // })


    // Course fixed table box course-info-box
    if ($(".course-info-box")[0] && $( window ).width() > 770) {
        $('.education-header').removeClass('sticky-top');

        var course_box = $('.course-info-box');
        var course_info = $('.course-info').height();
        $(window).scroll(function () {
            var scroll = $(window).scrollTop();
            if (scroll > course_info) {
                $('.slider-menu').addClass('active');
                course_box.addClass('fixed');
            } else {
                $('.slider-menu').removeClass('active');
                course_box.removeClass('fixed');
            }


            // if ($('footer').isInViewport()) {
            //     $('.slider-menu').removeClass('active');
            //     course_box.removeClass('fixed');
            // }

        });

        $.fn.isInViewport = function() {
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();

            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height() - 150;

            return elementBottom > viewportTop && elementTop < viewportBottom;
        };
    }

    $('.course-info__buttons li a').on('click', function (e) {
        e.preventDefault();
        $('.course-info__buttons li a').removeClass('active');
        $(this).addClass('active');

        var target = $(this).attr('href');

        $('.course-info-box > div').hide();
        $(target).show();

        if(target == '#self-study-card') {
            $('.faq.online-training').hide();
            $('.faq.self-study').show();

            if( $( window ).width() < 767) {
                $('.mobile-online-price').hide();
                $('.mobile-self-price').show();
            }

        }else {
            $('.faq.self-study').hide();
            $('.faq.online-training').show();

            if( $( window ).width() < 767) {
                $('.mobile-self-price').hide();
                $('.mobile-online-price').show();
            }

        }

    });


    $('.copy_this').on('click', function() {
        var copyText = $(this).siblings('p');
        copyText.select();
        document.execCommand("copy");
    });

    // if( $( window ).width() > 767) {
    //     $("[data-toggle=popover]").popover({
    //         html: true,
    //         trigger: 'manual',
    //         contain: 'true',
    //         content: function () {
    //             var content = $(this).attr("data-popover-content");
    //             return $(content).children(".popover-body").html();
    //         }
    //     })
    //     .on("mouseenter", function () {
    //         var _this = this;
    //         $(this).popover("show");
    //         $('body').find(".popover").on("mouseleave", function () {
    //             $(_this).popover('hide');
    //         });
    //     })
    //     .on("mouseleave", function () {
    //         var _this = this;
    //         setTimeout(function () {
    //             if (!$(".popover:hover").length) {
    //                 $(_this).popover("hide")
    //             }
    //         }, 100);
    //     });
    // }


// progress bars
$(".progress").each(function() {

    var value = $(this).attr('data-value');
    var left = $(this).find('.progress-left .progress-bar');
    var right = $(this).find('.progress-right .progress-bar');

    if (value > 0) {
      if (value <= 50) {
        right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)')
      } else {
        right.css('transform', 'rotate(180deg)')
        left.css('transform', 'rotate(' + percentageToDegrees(value - 50) + 'deg)')
      }
    }

  })

  function percentageToDegrees(percentage) {

    return percentage / 100 * 360

  }


  //user sidebar menu
  $('.user-float').on('click', function () {
        $('.userarea-wrapper aside').css('left', 0);
        $(".menu-overlay").fadeIn(100);

    });
    // filter courses
    $('.filter-course .btn').on('click', function() {
        var target = $(this).data('target');

        $('.filter-course .btn').removeClass('active');
        $(this).addClass('active');

        var category_target = $('.course-categories-tabs a.active').data('target');

        console.log(category_target);

        if(category_target == undefined) {
            if( target == 'all') {
                $('.course-categories-content .course-column').show();
            }else {
                $('.course-categories-content .course-column').hide();
                $('.course-categories-content .'+target).show();
            }
        }else {
            if( target == 'all') {
                $('.course-categories-content .course-column.'+category_target).show();
            }else {
                $('.course-categories-content .course-column').hide();
                $('.course-categories-content .'+category_target+'.'+target).show();
            }
        }


    });

});


function labnolIframe(div) {
    var iframe = document.createElement('iframe');
    iframe.setAttribute(
      'src',
      'https://www.youtube.com/embed/' + div.dataset.id + '?autoplay=1&rel=0'
    );
    iframe.setAttribute('frameborder', '0');
    iframe.setAttribute('allowfullscreen', '1');
    iframe.setAttribute(
      'allow',
      'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture'
    );
    div.parentNode.replaceChild(iframe, div);
  }

  function initYouTubeVideos() {
    var playerElements = document.getElementsByClassName('youtube-player');
    for (var n = 0; n < playerElements.length; n++) {
      var videoId = playerElements[n].dataset.id;
      var div = document.createElement('div');
      div.setAttribute('data-id', videoId);
      var thumbNode = document.createElement('img');
      thumbNode.src = '//i.ytimg.com/vi/ID/hqdefault.jpg'.replace(
        'ID',
        videoId
      );
      div.appendChild(thumbNode);
      var playButton = document.createElement('div');
      playButton.setAttribute('class', 'play');
      div.appendChild(playButton);
      div.onclick = function () {
        labnolIframe(this);
      };
      playerElements[n].appendChild(div);
    }
  }

  document.addEventListener('DOMContentLoaded', initYouTubeVideos);


