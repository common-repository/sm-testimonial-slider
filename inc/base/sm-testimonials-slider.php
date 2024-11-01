<?php

/**
* @package SMTestimonials
*/
?>

<!-- Testimonials -->
<div class="section-area ">
  <div class="container">
		<div class="testimonial-carousel owl-carousel owl-btn-1 col-12 p-lr0">
      <?php
        $args = array(
          'type' => 'post',
          'post_type' => 'testimonials' );
        $blog_list = new WP_Query($args);
        if( $blog_list->have_posts() ):
          while( $blog_list->have_posts() ): $blog_list->the_post(); ?>
            <div class="item">
              <div class="testimonial-box">
                <?php $backgroundImg = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );?>
                <div class="testimonial-thumb" style="background-image: url('<?php echo $backgroundImg[0]; ?>');" >
                </div> <!-- thumbnails-img -->
                <div class="testimonial-info">
                  <h5 class="name"><?php the_title(); ?></h5>
                  <p>
                    <?php

                    ?>
                  </p>
                </div>
                <div class="testimonial-content">
                  <p><?php the_content(); ?></p>
                </div>
              </div>
            </div><!-- item -->
          <?php endwhile; ?>
        <?php endif;
        wp_reset_postdata();
      ?>



		</div><!-- testimonial-carousel -->
	</div><!-- container -->
</div>
<!-- Testimonials END -->

<script>
  (function($) {

      'use strict';

        var BasicFunction = function(){

          var checkSelectorExistence = function(selectorName) {
            if(jQuery(selectorName).length > 0){return true;}else{return false;}
          };

          var setCourseCarousel = function() {
            if(!checkSelectorExistence('.courses-carousel')){return;}
            jQuery('.courses-carousel').owlCarousel({
              loop:true,
              autoplay:true,
              margin:0,
              nav:false,
              dots: true,
              navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
              responsive:{
                0:{
                  items:1
                },
                480:{
                  items:2
                },
                1024:{
                  items:3
                },
                1200:{
                  items:4
                }
              }
            });
          }



          var setTestimonialCarousel = function() {
            if(!checkSelectorExistence('.testimonial-carousel')){return;}
            jQuery('.testimonial-carousel').owlCarousel({
              loop:true,
              autoplay:true,
              margin:30,
              nav:false,
              dots:true,
              navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
              responsive:{
                0:{
                  items:1
                },
                480:{
                  items:1
                },
                1024:{
                  items:2
                },
                1200:{
                  items:2
                }
              }
            });

          }

          return {
            initialHelper:function(){
              setTestimonialCarousel();
            }
          }

      }(jQuery);



    /* jQuery ready  */
    jQuery(document).on('ready',function() {BasicFunction.initialHelper();});


  })(jQuery);
</script>
