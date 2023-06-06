$(document).ready(function () {
  // Banner - Carousel
  $(".banner").owlCarousel({
    items: 1,
    loop: true,
    nav: false,
    dots: true,
  });

  // Work Popup
  $(".popup").magnificPopup({
    type: "image",
    gallery: {
      enabled: true,
    },
  });
});
