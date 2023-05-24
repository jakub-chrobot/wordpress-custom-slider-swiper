<?php
/*
Plugin Name: Fluid Desk - custom Swiper Slider 
Description: A simple WordPress plugin that allows you to create a slider using SwiperJS library and custom fields.
Version: 1.0.0
Author: Jakub Chrobot   
*/

// init Carbon fields
use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
    require_once( 'vendor/autoload.php' );
    \Carbon_Fields\Carbon_Fields::boot();
}

// Register custom fields for slider
add_action( 'carbon_fields_register_fields', 'fluid_desk_swiperjs_slider_custom_fields' );
function fluid_desk_swiperjs_slider_custom_fields(){
    Container::make( 'post_meta', __( 'Fluid Slider' ) )
    ->where( 'post_type', '=', 'page' )
    ->add_fields( array(
    Field::make( 'html', 'fluid_desk_swiperjs_slider_info', _('Information'))
    ->set_html( 'Aby wyświetlić slider wstaw w docelowe miejsce wyświetlenia shortcode -> <u><b> [fluid_slider] </b></u>' ),
    Field::make( 'complex', 'fluid_desk_swiperjs_slider', __( 'Slider' ) )
        ->add_fields( array(
          Field::make( 'text' , 'fluid_desk_slide_names', _(' Rozwiązanie tymczasowe - nazwa slajdu' ) ),
          Field::make( 'checkbox', 'fluid_desk_active', __( 'Zaznacz jeśli slide ma być wyłączony' ) )
          ->set_option_value( 'yes' ),
          Field::make( 'text' , 'fluid_desk_slide_duration', _(' Czas trwania slajdu w MS - 1000ms = 1s') ),
            Field::make( 'image', 'fluid_desk_swiperjs_image_url', __( 'Grafika - desktop' ) )
                ->set_value_type( 'url' ),
            Field::make( 'image', 'fluid_desk_swiperjs_image_url_mobile', __( 'Grafika - mobile' ) )
                ->set_value_type( 'url' ),
            Field::make( 'file', 'fluid_desk_swiperjs_video_url', _(' Video / Animacja - desktop') )
                ->set_value_type( 'url' ),
            Field::make( 'file', 'fluid_desk_swiperjs_video_url_mobile', _(' Video / Animacja - mobile') )
                ->set_value_type( 'url' ),
            Field::make( 'text' , 'fluid_desk_swiperjs_url_to_post', _(' Odnośnik do strony / postu ') )
    ) ) )); }


function fluid_slider(){ 
    
    ob_start();
    ?>

<!-- Swiper -->

<!--- <script
  src="https://code.jquery.com/jquery-3.6.4.min.js"
  integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
  crossorigin="anonymous"></script> --->

<div class="swiper mySwiper">

    <div class="swiper-wrapper">



        <?php $a = carbon_get_the_post_meta('fluid_desk_swiperjs_slider'); 
            
        $i = 0;
        foreach ( $a as $slide){ ?>

        <?php if($a[$i]['fluid_desk_active'] == 'yes'){ $i++; } else { ?>  

            <?php if(empty($a[$i]['fluid_desk_swiperjs_video_url'])) { ?>

      
    <div class="swiper-slide" data-swiper-autoplay="<?php echo $a[$i]['fluid_desk_slide_duration'];?>"  data-type-slide="img"> 

                
            <a class="mobile link_swiper-slide" href="<?php echo $a[$i]['fluid_desk_swiperjs_url_to_post']; ?>">
              <img class="swiper-slide-image" src="<?php echo $a[$i]['fluid_desk_swiperjs_image_url_mobile']; ?> ">
            </a>

            <a class="desktop link_swiper-slide" href="<?php echo $a[$i]['fluid_desk_swiperjs_url_to_post']; ?>">
              <img class="swiper-slide-image" src="<?php echo $a[$i]['fluid_desk_swiperjs_image_url']; ?> ">
            </a>



        </div>
        
                    <?php } else{ ?>

                        <div class="swiper-slide" data-swiper-autoplay="<?php echo $a[$i]['fluid_desk_slide_duration'];?>" data-type-slide="video"> 

                      <video id="#video_fluid" class="mobile" autoplay="autoplay" playsinline muted playsinline redirect="<?php echo $a[$i]['fluid_desk_swiperjs_url_to_post']; ?>" > 
                <source src="<?php echo $a[$i]['fluid_desk_swiperjs_video_url_mobile']; ?>" type="video/mp4" >
              </video>

              <video id="#video_fluid" class="desktop" autoplay="autoplay" playsinline muted redirect="<?php echo $a[$i]['fluid_desk_swiperjs_url_to_post']; ?>" > 
                <source src="<?php echo $a[$i]['fluid_desk_swiperjs_video_url']; ?>" type="video/mp4" >
              </video>
            
            </div>
            <?php } ?> 



        <?php $i++; } ?>
      
        <?php } ?>

    </div>

    <div class="swiper-button-next"></div>

    <div class="swiper-button-prev"></div>

    <div class="swiper-pagination"></div>

  </div>

   <!-- Initialize Swiper -->

   <link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"
/>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

    
<script>
  jQuery(document).ready(function($) {

    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 10,
        autoplay: {
        delay: 2500,
        speed: 2500,
        disableOnInteraction: false,
      },
      navigation: {

        nextEl: ".swiper-button-next",

        prevEl: ".swiper-button-prev",

      },

      pagination: {

        el: ".swiper-pagination",
        clickable: true,

      },

      effect: 'fade',
      preloadImages: true,
      loop: true,
   
});
    
    
   
swiper.on('slideChange', function () {
    console.log('*** swiper.realIndex', swiper.realIndex);
  
    var index = swiper.activeIndex;
    var currentSlide =  $(swiper.slides[index]);
  
  // console.log('*** Obecny slide', currentSlide);
  
  var currentSlideType = currentSlide.data('type-slide')

  console.log('Typ-slide', currentSlideType);
  
  if(currentSlideType == 'img'){
    
    swiper.autoplay.start();
    console.log('autoplay leci');
  
  } else {
    $('video').currentTime = 0;
    $('video').trigger('play');
    $('video').play;

    swiper.autoplay.stop(); 
    
    $('video').bind("ended", function() {
    swiper.autoplay.start();
    console.log('ended and autoplay run');
  });

  }
  
$('body').on('click touchstart', function() {

 
$('video').play;
$('video').trigger('play');

swiper.autoplay.stop(); 

$('video').bind("ended", function() {
swiper.autoplay.start();
console.log('ended and autoplay run');


});

});

});

  });


  </script>



  <style>
    @media(min-width:1220px){
	
	.swiper.mySwiper {
    width: 100%;
    height: 300px;
	}
	
	a.link_swiper-slide {
    width: 100%;
    height: 300px;
	}
	
	img.swiper-slide-image {
    image-rendering: initial !important;
    width: 100%;
    height: 300px;
		object-fit: fill;
}
	.swiper-pagination-bullet{
		width: 1rem !important;
		height: 1rem !important;
	}
	
	.swiper-button-prev, .swiper-rtl .swiper-button-next{
		left: 2rem !important;
	}
	
	.swiper-button-next, .swiper-rtl .swiper-button-prev{
		right: 2rem !important;
	}
	
}

@media(max-width: 500px){
	.swiper {
    height: 350px;
    width: 100%;
}
	
		img.swiper-slide-image {
    	width: 100%;
    	height: 350px;
    	object-fit: fill;
		}
	
}

.swiper {
    margin-top: 1.5rem;
    margin-bottom: 1.5rem;
}

.page-id-16911 .swiper {
    margin-top: 0rem !important;
    margin-bottom: 1.5rem;
}

.swiper-button-next, .swiper-button-prev{
	color: white !important;
}

.swiper-pagination-bullet-active{
	background: white !important;
}

.swiper-pagination .swiper-pagination-bullet {
  opacity: 1;
  background-color: transparent;
}

.swiper-pagination-bullet {
    border: 3px solid white !important;
		opacity: 1 !important;
}

.swiper-pagination{
	bottom: 30px !important;
}

.swiper-horizontal>.swiper-pagination-bullets .swiper-pagination-bullet, .swiper-pagination-horizontal.swiper-pagination-bullets .swiper-pagination-bullet {
    margin: 0px var(--swiper-pagination-bullet-horizontal-gap,20px) !important;
}

video{
  width: 100%;
  height: 100% !important;
  object-fit: fill;
}

video:hover{
  cursor: pointer;
}

@media(max-width:550px){
	.desktop{
		display: none !important;
	}

  .swiper-pagination-bullet {
    width: var(--swiper-pagination-bullet-width,var(--swiper-pagination-bullet-size,18px)) !important;
    height: var(--swiper-pagination-bullet-height,var(--swiper-pagination-bullet-size,18px)) !important;
  }

}

@media(min-width: 1000px){
	.mobile{
		display: none !important;
	}
}

</style>

<script>
  jQuery(document).ready(function($) {


    $('video').on("ended, paused", function () {
      swiper.autoplay.start();
    });
    
     $('video').click(function(){
    
      var link = $(this).attr('redirect');
      window.location = link;
      
  });

})
    
</script>  

<?php
    return ob_get_clean();

}

add_shortcode('fluid_slider', 'fluid_slider');