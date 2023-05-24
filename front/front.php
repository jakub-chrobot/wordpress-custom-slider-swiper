<!-- Swiper -->

<div class="swiper mySwiper">

    <div class="swiper-wrapper">



        <?php $a = carbon_get_theme_option('fluid_desk_swiperjs_slider'); 

        $i = 0;

        foreach ( $a as $slide){ ?>

        
        <a class="link_swiper-slide" href="<?php echo $slide[$i]['fluid_desk_swiperjs_url_to_post'];?>">
        <div class="swiper-slide"> 



            <?php if(!empty($slide[$i]['fluid_desk_swiperjs_image_url'])) { ?>

                

            <img class="swiper-slide-image" src="<?php echo $slide[$i]['fluid_desk_swiperjs_image_url']; ?> ">



            <? php } else{ ?>



            <video src="<?php echo $slide[$i]['fluid_desk_swiperjs_video_url']; ?> ">

            

            <?php } ?> 



        </div>
        </a>



        <?php } ?>



    </div>

    <div class="swiper-button-next"></div>

    <div class="swiper-button-prev"></div>

    <div class="swiper-pagination"></div>

  </div>



   <!-- Initialize Swiper -->

   <script>

    var swiper = new Swiper(".mySwiper", {

      navigation: {

        nextEl: ".swiper-button-next",

        prevEl: ".swiper-button-prev",

      },

      pagination: {

        el: ".swiper-pagination",

      },

    });

  </script>