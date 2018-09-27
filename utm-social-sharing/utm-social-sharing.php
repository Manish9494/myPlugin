<?php 

/**
 * Plugin Name: Utm Social Sharing
 * Plugin URI: www.example.com
 * Description: This is simple plugin to share post and pages in social network.
 * Version: 1.0
 * Author: Manish Shrestha
 * Author URI: www.example.com
 * Text Domain: utm_social_sharing
 */
require_once plugin_dir_path( __FILE__ ).'/inc/utm-customizer.php'; 
require_once plugin_dir_path( __FILE__ ).'/inc/utm-shortocode.php'; 
//require_once( get_template_directory_uri() .'/inc/utm-customizer.php');

function utm_sharing_scripts() {

wp_enqueue_style('sharing-style', plugins_url( '/css/sharing-plugin-style.css', __FILE__ ),'','', 'all');        

}
add_action( 'wp_enqueue_scripts', 'utm_sharing_scripts' );


if( !function_exists('utm_check_sharing_button')){
  function utm_check_sharing_button($utm_var){
    if ("yes" == $utm_var){
      $checked  = 'checked="checked"'; 
    }else{
      $checked = "";
    }
    return $checked;
  }
}

/*create meta box title in add meta boxes action hook*/
if (!function_exists('utm_custom_meta_box')){
  function utm_custom_meta_box()
  {
    add_meta_box( 'utm_custom_metabox_email', 'Utm Social Sharing', 'utm_email_meta_box_callback', 'post', 'side', 'high' );
  }
  add_action( 'add_meta_boxes', 'utm_custom_meta_box' );
}


/*create callback function from where we crete meta box by html tag */
if (!function_exists('utm_email_meta_box_callback')){
  function utm_email_meta_box_callback() {

    ?>
     <i>Share on Social Network: </i><br>
     <hr>
     <?php 
     $values = get_post_custom( $post->ID );
     if($values['utm_facebook_sharing']){
       $var1 = implode($values['utm_facebook_sharing']);
       $var2 = implode($values['utm_twitter_sharing']);
       $var3 = implode($values['utm_linked_sharing']);
       $var4 = implode($values['utm_before_content']);
       $var5 = implode($values['utm_after_content']);
     }
     ?>
  <label for="utm_facebook_sharing">
    <input name="utm_facebook_sharing" type="checkbox" id="utm_facebook_sharing" value="yes" <?php echo utm_check_sharing_button($var1); ?>/>
    <span>Facebook</span>
  </label><br>
    <label for="utm_twitter_sharing">
    <input name="utm_twitter_sharing" type="checkbox" id="utm_twitter_sharing" value="yes" 
   <?php echo utm_check_sharing_button($var2); ?>/>
    <span>Twitter</span>
  </label><br>
    <label for="utm_linked_sharing">
    <input name="utm_linked_sharing" type="checkbox" id="utm_linked_sharing" value="yes"  
   <?php echo utm_check_sharing_button($var3); ?> />
    <span>Linked</span>
  </label><br>
  <hr>
  <i>Sharing Control:</i>
  <hr>
    <label for="utm_before_content">
    <input name="utm_before_content" type="checkbox" id="utm_before_content" value="yes"
    <?php echo utm_check_sharing_button($var4); ?> />
    <span>Display sharing before Content</span>
  </label><br>
  <label for="utm_after_content">
    <input name="utm_after_content" type="checkbox" id="utm_after_content" value="yes" 
    <?php echo utm_check_sharing_button($var5); ?>/>
    <span>Display sharing after Content</span>
  </label><br>
    <?php
  }
}


/*Save custom meta box data to our database by update_post_meta*/
function my_meta_box_save( $post_id )
{

 // if the current user can't edit this post, exit.
 if( !current_user_can( 'edit_post' ) ) return;

 // Checks save status
 $is_autosave = wp_is_post_autosave( $post_id );
 $is_revision = wp_is_post_revision( $post_id );
 $is_valid_nonce = ( isset( $_POST[ 'example_nonce' ] ) && wp_verify_nonce( $_POST[ 'example_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 // Exit depending on the save status
 if ( $is_autosave || $is_revision || !$is_valid_nonce ) return;

   // Checking if the data is set before saving
   if( isset( $_POST['utm_facebook_sharing'] ) ) {
     // Repeat the following line for each input field, with proper field name
    update_post_meta( $post_id, 'utm_facebook_sharing','yes');
  } else {
    update_post_meta( $post_id, 'utm_facebook_sharing', 'no' );
  }

   if( isset( $_POST['utm_twitter_sharing'] ) ) {
     // Repeat the following line for each input field, with proper field name
    update_post_meta( $post_id, 'utm_twitter_sharing','yes');
  } else {
    update_post_meta( $post_id, 'utm_twitter_sharing', 'no' );
  }

    if( isset( $_POST['utm_linked_sharing'] ) ) {
     // Repeat the following line for each input field, with proper field name
    update_post_meta( $post_id, 'utm_linked_sharing','yes');
  } else {
    update_post_meta( $post_id, 'utm_linked_sharing', 'no' );
  }


    if( isset( $_POST['utm_before_content'] ) ) {
     // Repeat the following line for each input field, with proper field name
    update_post_meta( $post_id, 'utm_before_content','yes');
  } else {
    update_post_meta( $post_id, 'utm_before_content', 'no' );
  }


    if( isset( $_POST['utm_after_content'] ) ) {
     // Repeat the following line for each input field, with proper field name
    update_post_meta( $post_id, 'utm_after_content','yes');
  } else {
    update_post_meta( $post_id, 'utm_after_content', 'no' );
  }

}
add_action( 'save_post', 'my_meta_box_save' );


if( !function_exists('filter_the_content_in_the_main_loop') ){

  add_filter( 'the_content', 'filter_the_content_in_the_main_loop' );
   
  function filter_the_content_in_the_main_loop( $content ) {

    // Check if we're inside the main loop in a single post page.
    //  if ( is_single() && in_the_loop() ) {
        global $post;
        $update_values = get_post_custom( $post->ID );

        //var_dump($update_values);
        
        if( is_array($update_values['utm_before_content']) ) {
          $utm_var4 = implode($update_values['utm_before_content']);
          $utm_var5 = implode($update_values['utm_after_content']);
        }
        if ($utm_var4) {
          if ("yes" == $utm_var4 ){
           $varx = do_shortcode( '[utm_social_sharing_shortcode]' );
           $custom_content  = $varx; 
          }
        }
  
       $custom_content .= $content;
       if ( $utm_var5 ) {
        if ("yes" == $utm_var5 ){
        // $custom_content .= $content;
          $varx = do_shortcode( '[utm_social_sharing_shortcode]' );
          $custom_content .= $varx;

        // echo do_shortcode( '[custom_social]' );
        }
        
      }
     
       return $custom_content;  
      }
      //return $content;
 // }
}

