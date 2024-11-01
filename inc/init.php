<?php

/**
* @package SMTestimonials
*/

class SM_Testimonial_sliderInit
{

  public static function register()
  {
    add_action( 'wp_enqueue_scripts', array( 'SM_Testimonial_sliderInit', 'enqueue'));
    add_action('init', array( 'SM_Testimonial_sliderInit', 'custom_post_type') );
    add_action('add_meta_boxes', array('SM_Testimonial_sliderInit', 'add_meta_boxes') );
    add_action( 'save_post', array('SM_Testimonial_sliderInit', 'save_meta_box') );
    add_action('manage_testimonials_posts_columns', array('SM_Testimonial_sliderInit', 'set_custom_columns') );
    add_action('manage_testimonials_posts_custom_column', array('SM_Testimonial_sliderInit', 'set_custom_columns_data'), 10, 2 );
    // add_filter('manage_edit_testimonials_sortable_columns',  array('Testimonial_sliderInit', 'set_custom_columns_sortable') );

    add_action( 'admin_menu', array('SM_Testimonial_sliderInit', 'my_plugin_menu') );

    add_shortcode( 'sm-testimonials-slider', array('SM_Testimonial_sliderInit', 'sm_testimonials_slider') );
  }

  static function sm_testimonials_slider() {
    ob_start();
    require_once plugin_dir_path(__FILE__) . 'base/sm-testimonials-slider.php' ;
    return ob_get_clean();
  }

  public static function my_plugin_menu() {
    add_submenu_page( 'edit.php?post_type=testimonials', 'shortcode', 'shortcode', 'manage_options', 'shortcode', array('SM_Testimonial_sliderInit', 'my_plugin_menu_callback') );
  }


  public static function my_plugin_menu_callback() {
    ?>
      <div class="wrap">
        <h1>Testimonials Shortcode</h1>
        <p>Testimonials Slider Shortcode</p>
        <p><code>[sm-testimonials-slider]</code></p>
      </div>
    <?php
  }

  // enqueue all our scripts
  static function enqueue()
  {
    // enqueue style
    wp_enqueue_style('PluginStyle',  plugin_dir_url(__FILE__) . '../assets/css/smplugin-style.css', array(), false, 'all' );
    wp_enqueue_style('owlsmPluginStyle',  plugin_dir_url(__FILE__) . '../assets/css/owl.carousel.css', array(), false, 'all' );
    wp_enqueue_style('fontawesome',  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), false, 'all' );

    // enqueue script
    wp_enqueue_script( 'owlsmPluginjs', plugin_dir_url(__FILE__) . '../assets/js/owl.carousel.js', array(), false, false );
  }

  // custom_post_type
  static function custom_post_type()
  {

    $labels = array(
    'name'          => 'Testimonials',
    'singular_name' => 'Testimonial',
    'add_new'       => 'Add Testimonial',
    'all_items'     => 'All Testimonials',
    'add_new_item'  => 'Add Testimonial',
    'edit_item'     => 'Edit Testimonial',
    'new_item'      => 'New Testimonial',
    'view_item'     => 'View Testimonial',
    'search_items'  => 'Search Testimonials',
    'not_found'     => 'No Testimonials Found in Sraech',
    'not_found_in_trash' => 'No Testimonials Found in Trash',
    'parent_item_colon'  => 'Parent Testimonials'
    );

    $args = array(
      'labels'        => $labels,
      'public'        => true,
      'has_archive'   => false,
      'menu_icon'     => 'dashicons-format-status',
      'exclude_from_search' => true,
      'publicy_queryable' => false,
      'supports'      => array( 'title', 'editor', 'thumbnail' ),


    );
    register_post_type( 'testimonials' , $args );
  }


  public static function add_meta_boxes()
  {

    add_meta_box(
      'testimonial_author',
      'Author Info',
      array( 'SM_Testimonial_sliderInit', 'render_author_box'),
      'testimonials',
      'normal',
      'high'
    );

  }


  public static function render_author_box( $post )
  {
    wp_nonce_field('sm_testimonials', 'sm_testimonials_nonce' );
    $value = get_post_meta( $post->ID, '_sm_testimonials_key', true);

    // $name   = isset( $value['name'] ) ? $value['name'] : '';
    $job    = isset( $value['job'] ) ? $value['job'] : '';
    ?>

    <table>
      <tr>
        <td><label for="sm_testimonials_job" >Job : </label></td>
        <td><input type="text" style="width: 30em !important; padding: 8px 13px;" id="sm_testimonials_job" name="sm_testimonials_job" value="<?php echo esc_attr( $job ); ?>"></td>
      </tr>
    </table>

    <?php
  }

  public static function save_meta_box( $post_id )
  {
    if(! isset( $_POST['sm_testimonials_nonce'] ) ) {
      return $post_id;
    }

    $nonce = $_POST['sm_testimonials_nonce'];

    if (! wp_verify_nonce( $nonce, 'sm_testimonials')) {
      return $post_id;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
      return $post_id;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
      return $post_id;
    }

    $data = array(
      // 'name' => sanitize_text_field( $_POST['sm_testimonials_name'] ),
      'job' => sanitize_text_field( $_POST['sm_testimonials_job'] ),
      'email' => sanitize_text_field( $_POST['sm_testimonials_email'] ),
    );

    update_post_meta( $post_id, '_sm_testimonials_key', $data );

  }

  public static function set_custom_columns( $columns )
  {
    $title = $columns['title'];
    $date  = $columns['date'];
    unset( $columns['title'], $columns['date'] );
    $columns['title'] = $title;
    $columns['job'] = 'Job';
    $columns['email'] = 'E-mail';
    $columns['thumbnail'] = 'Image';
    $columns['date'] = $date;

    return $columns;
  }

  public static function set_custom_columns_data( $column, $post_id) {

    $value = get_post_meta( $post_id, '_sm_testimonials_key', true);
    $job    = isset( $value['job'] ) ? $value['job'] : '';
    $email  = isset( $value['email'] ) ? $value['email'] : '';

    switch ( $column ) {

      case 'job':
        echo $job;
        break;

      case 'email':
        echo '<a href="mailto:'. $email . ' "> ' . $email .' </a>';
        break;

      case 'thumbnail':
        echo ' <img src=" ' . get_the_post_thumbnail_url($post_id) .' " width="120px" > ';
        break;

    }

  }

  public  function set_custom_columns_sortable( $columns )
  {

    $columns['job'] = 'job';
    $columns['email'] = 'email';
    $columns['thumbnail'] = 'image';

    return $column;
  }

//
}
