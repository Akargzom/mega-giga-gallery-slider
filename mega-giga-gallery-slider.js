jQuery(document).ready(function ($) {
  if($('.MGGS_gallery-slider').hasClass('auto')){
    atp  = true;
    atps = $('.MGGS_gallery-slider').attr('data');
  }else{
    atp  = false;
    atps = false;
  }
    $('.MGGS_gallery-slider').each(function() {
        $(this).slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: atp,
          autoplaySpeed: atps,
          prevArrow: $(this).next('.MGGS_panel-control').find('.prev'),
          nextArrow: $(this).next('.MGGS_panel-control').find('.next'),
          dots: true,
          appendDots: $(this).next('.MGGS_panel-control').find('.slider-dots'),
          responsive: [{
            breakpoint: 651,
            settings: {
              dots: false,
            }
          }]
      });
  });
});