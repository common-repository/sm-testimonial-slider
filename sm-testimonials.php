<?php
/*
Plugin Name:   SM Testimonial Slider
Plugin URI : https://profiles.wordpress.org/mehtashail/
Description:  Testimonial Slider
Version: 1.0.0
Author: Shail Mehta
Text Domain: wporg
Author URL : https://profiles.wordpress.org/mehtashail/
*/

defined( 'ABSPATH' ) or die( 'Hey, what are you doing here?' );


if( ! class_exists('sm_testimonial_slider') ) {

  require_once dirname( __FILE__ ). '/inc/init.php';
  class SM_Testimonial_slider
  {
    function __construct() {
        SM_Testimonial_sliderInit::register();
    }

    function activate()
    {
      // refrish database
      flush_rewrite_rules();
    }

    function deactivate() {
      // refrish database
      flush_rewrite_rules();
    }

  }

}

// activation and deactivation
$sm_testimonial_slider = new SM_Testimonial_slider();
register_activation_hook( __FILE__ , array($sm_testimonial_slider, 'activate') );
register_deactivation_hook( __FILE__ , array($sm_testimonial_slider, 'deactivate') );
