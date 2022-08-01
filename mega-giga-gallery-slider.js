jQuery(document).ready(function ($) {
  if($('.MGGS_gallery-slider').hasClass('auto')){
    atp  = true;
    atps = $('.MGGS_gallery-slider').attr('data');
  }else{
    atp  = false;
    atps = false;
  }
  let thumbNum;
  if($('.MGGS_gallery-slider').hasClass('thumb')){
    thumbNum = $('.MGGS_gallery-slider').attr('data-2');
    console.log(thumbNum);
    $('.MGGS_gallery-thumbs').css('width', $('.MGGS_gallery-slider').width() + '' );
    $('.MGGS_gallery-slider').each(function() {
      $(this).slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: atp,
        autoplaySpeed: atps,
        asNavFor: '.MGGS_gallery-thumbs',
        prevArrow: $(this).next('.MGGS_panel-control').find('.prev'),
        nextArrow: $(this).next('.MGGS_panel-control').find('.next'),
        dots: false,
        // appendDots: $(this).next('.MGGS_panel-control').find('.slider-dots'),
        responsive: [{
          breakpoint: 651,
          settings: {
            dots: false,
          }
        }]
    });
    });
    let inf,
    cm;
    if ($('.MGGS_gallery-icon.landscape').lenght<8){
      inf = false;
      cm = true;
    }else{
      inf = true;
      cm = false;
    }
    $('.MGGS_gallery-thumbs').each(function() {
      $(this).slick({
        slidesToShow: thumbNum,
        slidesToScroll: 3,
        autoplay: atp,
        asNavFor: '.MGGS_gallery-slider',
        autoplaySpeed: atps,
        focusOnSelect: true,
        infinite: inf,
        draggable: false,
        dots: false,
        centerMode: cm,
        variableWidth: false,
        arrows: false,
        responsive: [{
          breakpoint: 992,
          settings: {
              slidesToShow: 4,
              arrows: false,
          }
      },
      {
      breakpoint: 650,
      settings: {
          slidesToShow: 3,
          arrows: false,
          centerMode: true,
          centerPadding: '0px',
      }
  }]
    });
});
  }
  else{
    $('.MGGS_gallery-slider').each(function() {
        $(this).slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: atp,
          autoplaySpeed: atps,
          prevArrow: $(this).next('.MGGS_panel-control').find('.prev'),
          nextArrow: $(this).next('.MGGS_panel-control').find('.next'),
          dots: true,
          arrows: true,
          appendDots: $(this).next('.MGGS_panel-control').find('.slider-dots'),
          responsive: [{
            breakpoint: 651,
            settings: {
              dots: false,
            }
          }]
      });
  });
}
});