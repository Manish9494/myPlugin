<?php 

/*create section in customizer*/
if( !function_exists('redbud_custom_customize_register') ){

  function redbud_custom_customize_register( $wp_customize ) {

    /*create a panel */
    $wp_customize->add_panel( 'social_links_panel', array(
      'priority' => 102,
      'theme_supports' => '',
      'title' => __( 'Social Media Section', 'twentyseventeen-child' ),
    ) );

    /*create a section in above panel*/
    $wp_customize->add_section( 'social_media' , array(
      'title' => __('Social Media','twentyseventeen-child'),
      'panel' => 'social_links_panel',
      'priority' => 10
    ) );

    $redbud_social_items= array(
      'facebook'      => 'Facebook',
      'twitter'     => 'Twitter',
      'linkedin'   => 'linkedin',
    );

    foreach ($redbud_social_items as $key => $value) :
     /*adding setting to social media Icon*/
     $wp_customize->add_setting($key.'-media-setting',
      array(
        'default'      => '',
        'transport'=>'postMessage'
        )
      );
     /*adding control to social media Icon*/
      $wp_customize->add_control(
        new WP_Customize_Image_Control(
          $wp_customize,
          $key.'-media-setting',
          array(
            'label'    => $value.' Icon',
            'settings' => $key.'-media-setting',
            'section'  => 'social_media'
          )
        )
      );
    endforeach;
  }
  add_action( 'customize_register', 'redbud_custom_customize_register' );
}
