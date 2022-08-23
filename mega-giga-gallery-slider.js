jQuery(document).ready(function ($) {
  if($('.MGGS_gallery-slider').hasClass('auto')){
    atp  = true;
    atps = $('.MGGS_gallery-slider').attr('data');
  }else{
    atp  = false;
    atps = false;
  }
  
  let thumbNum,
    tabThumbNum,
    mobThumbNum;
  
  if($('.MGGS_gallery-slider').hasClass('thumb')){
    thumbNum = $('.MGGS_gallery-slider').attr('data-2');
    tabThumbNum = $('.MGGS_gallery-slider').attr('data-3');
    mobThumbNum = $('.MGGS_gallery-slider').attr('data-4');
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
        responsive: [{
          breakpoint: 651,
          settings: {
            arrows: false,
          }
        }]
      });
    });
    let inf,
      tabInf,
      tabCm,
      mobInf,
      mobCm,
      cm;
    if ($('.MGGS_gallery-icon.landscape').lenght <= thumbNum){
      inf = false;
      cm = true;
    }else{
      inf = true;
      cm = false;
    }
    if ($('.MGGS_gallery-icon.landscape').lenght <= tabThumbNum){
      tabInf = false;
      tabCm = true;
    }else{
      tabInf = true;
      tabCm = false;
    }
    if ($('.MGGS_gallery-icon.landscape').lenght <= mobThumbNum){
      mobInf = false;
      mobCm = true;
    }else{
      mobInf = true;
      mobCm = false;
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
            slidesToShow: tabThumbNum,
            centerMode: tabCm,
            infinite: tabInf
          }
        }, {
          breakpoint: 650,
          settings: {
            slidesToShow: mobThumbNum,
            centerMode: mobCm,
            infinite: mobInf
          }
        }]
      });
    });
  }else{
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
            arrows: false,
          }
        }]
      });
    });
  }
});
