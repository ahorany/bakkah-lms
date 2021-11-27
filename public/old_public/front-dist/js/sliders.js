// $(window).load(function(){
//     $('.subscrib_mail').click();
//     $('.mega-request_proposal a').attr('data-toggle','modal');
// });
jQuery(document).ready(function ($) {

$('.uk-nav-offcanvas li.menu-item-has-children > a').click(function(){
    $(this).parent('li').find('ul').slideToggle(200);
    return false;
});
    $(".search a").click(function(){

        $(this).find("i").toggleClass('fa-search');
        $(this).find("i").toggleClass('fa-times');
        $(".search").toggleClass('ActiveForm');

    });

     $(".nav li").hover(function(){
        $(this).toggleClass('ActiveDropdown');
    });

// $('.subscrib_mail').magnificPopup({
//   type:'inline',
//   midClick: true
// });

/******************* MAIN Slider ***********************/
var l = $("html").attr('lang'); var rtl = false;
if(l=='ar'){
	rtl = true;
}
   $('#main_slider').owlCarousel({
    loop:true,
    nav:true,
    rtl : rtl,
    dots:true,
    autoplay:true,
    autoplayTimeout:5000,
    autoplayHoverPause:false,
    animateOut: 'fadeOut',
    animateIn: 'fadeIn',
    singleItem: true,
    navText: ['<i class="fa fa-angle-left"></i>' , '<i class="fa fa-angle-right"></i>'],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:1
        }
    },
      // onTranslate: set_animation,
      // onInitialized : set_animation,
      // onChange  :remove_animation
});



/******************* Clients Slider ***********************/
   $('#clients_slider').owlCarousel({
    loop:true,
    // margin:10,
    nav:true,
    dots:false,
    navText: ['<i class="fa fa-angle-left"></i>' , '<i class="fa fa-angle-right"></i>'],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:6
        }
    }
})
/******************* Courses Slider ***********************/

  $('#courses_slider').owlCarousel({
    loop:true,
    margin:10,
    autoplay:true,
    // rtl:true,
    autoplayTimeout:5000,
    autoplayHoverPause:false,
    nav:false,
    slideBy:4,
    navText: ['<i class="fa fa-angle-left"></i>' , '<i class="fa fa-angle-right"></i>'],
    dots:false,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:4
        }
    }
  });



// fixed-menu
 $(window).scroll(function () {
    //You've scrolled this much:
       if($(window).scrollTop() >= 100){
        $(".header-nav").addClass("fixed-menu");
       }else{
        $(".header-nav").removeClass("fixed-menu");

       }
});


if ( $('html').attr('lang') == 'ar') {
    $('.sidebar .widget_nav_menu h2').text('أقسام مركز المعرفة')
}

$('.sidebar .widget_nav_menu h2').click(function(){
    var link = $('.sidebar .widget_nav_menu ul li:first-of-type a').attr('href');
    window.location.href = link;
})


// Banner Advertisement
jQuery('.advertisement_bar .close-banner').click(function() {
    jQuery(this).hide();
    jQuery('.advertisement_bar').remove()
    localStorage.setItem('isshow', 1);
    jQuery('body').removeClass('advertisement');
});

var isshow = localStorage.getItem('isshow');
if (isshow== null) {
    jQuery('body').addClass('advertisement');
    jQuery('.advertisement_bar').show();
}


// Popup Advertisement
jQuery('.advertisement-popup .popup-content .popup-close').click(function() {
    jQuery('.advertisement-popup .popup-content').removeClass('open');
    jQuery('.advertisement-popup').fadeOut();
    localStorage.setItem('popup-show-new', 1);
});

var popupShow = localStorage.getItem('popup-show-new');
if (popupShow== null) {
    setTimeout(function() {
         jQuery('.advertisement-popup').fadeIn();
         jQuery('.advertisement-popup .popup-content').addClass('open');

    }, 3000);
}


});

function set_animation() {
    jQuery('#main_slider .item h2').addClass('slideInUp');
    jQuery('#main_slider .item p').addClass('slideInDown');
}
function remove_animation(){
    jQuery('#main_slider .item h2').removeClass('slideInUp');
    jQuery('#main_slider .item p').removeClass('slideInDown');
}

function close_popup(msg){
    jQuery(".subscribe_form").html(msg);
   // jQuery(".mfp-close").click();
}

function error_cap2(m){
    jQuery('#errorCap2').html(m);
    jQuery('.errorCap2').html(m);
}

function error_file(m){
    jQuery('#error_file').html(m);
}
