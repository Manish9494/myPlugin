<?php 

if( !function_exists('utm_social_sharing_shortcode') ){

/*create shortcode */
function utm_social_sharing_shortcode_fun() {
 
$contents = '
  <div class="post-share">
        <h4>Share Articles</h4>
    <ul class="list-inline" style="text-decoration:none;">';

    	$facebook_logo = get_theme_mod( 'facebook-media-setting' );
    	$twitter_logo  =  get_theme_mod( 'twitter-media-setting' ); 
        $linked_logo  =  get_theme_mod( 'linkedin-media-setting' ); 

    	$permalink =  rawurlencode( get_the_permalink() );
    	$post_title =  rawurlencode( get_the_title() );

    	$values = get_post_custom( $post->ID );
    	$var1 = $values['utm_facebook_sharing'];
    	$var2 = $values['utm_twitter_sharing'];
    	$var3 = $values['utm_linked_sharing'];
    	if (in_array("yes",$var1)){
    		$contents .='<li>'; 
    		$contents .= '<a class="facebook" href="//www.facebook.com/sharer.php?u='.$permalink.'&amp;t='.$post_title.'" title="Share on Facebook" target="_blank" data-toggle="tooltip" data-placement="top"><img src="'.$facebook_logo.'"></a>';
    		$contents .='</li>'; 
    	}
    	if (in_array("yes",$var2)){
    		$contents .='<li>'; 
    		$twitter_post_title =  rawurlencode( sprintf( esc_html__( ''.get_the_title().': %s', 'twentyseventeen-child' ), get_the_permalink() ) );
    		$contents .=' <a class="twitter" href="//twitter.com/home?status='. $twitter_post_title.'" title="Share on Twitter!" target="_blank" data-toggle="tooltip" data-placement="top"><img src="'.$twitter_logo.'"></a>';
    		$contents .='</li>'; 
    	}
        if (in_array("yes",$var3)){ 
            $contents .='<li>';
            $contents .= '<a class="linkedin" href="//www.linkedin.com/shareArticle?url='.rawurlencode( get_the_permalink() ).'&amp;mini=true&amp;title='.rawurlencode( get_the_title() ) .'" title="Share on Linkedin!" target="_blank" data-toggle="tooltip" data-placement="top"><img src="'.$linked_logo.'"></a>';
            $contents .='</li>'; 
            
            $contents .= '</ul>';
            $contents .='</div>';  
        }
        return $contents;
  }
  add_shortcode( 'utm_social_sharing_shortcode', 'utm_social_sharing_shortcode_fun' );
}
