<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global  $post , $gema75_ril_frontend , $gema75_read_it_later;


//remove the "add to RIL" action
//remove_action( 'the_content', array( $gema75_ril_frontend, 'show_readitlater_after_content' ),30 );
//remove_action( 'the_content', array( $gema75_ril_frontend, 'show_read_it_later_after_title' ),30 );


//logged in users
if(get_current_user_id() > 0){
	$userid= get_current_user_id();
	$user_readitlater_list = get_option('gema75_readitlater_for_user_id_'.$userid);
}else{
	//non logged in users
	$user_readitlater_list = $gema75_ril_frontend->get_ril_non_logged_in(); 
}


if(isset($user_readitlater_list['posts_in_ril']) && count($user_readitlater_list['posts_in_ril'])>=1){

	foreach($user_readitlater_list['posts_in_ril'] as $single_post){ 
	
		$post_id = absint( $single_post['id'] );

		if ( $post_id ) {

			// Get the post data 
			$post = get_post( $post_id );

			setup_postdata( $post );
		
			?>
			
			<div style="height:150px;">
				<div style="width: 40%;float: left;padding:1% 2%;"> <a href="<?php  the_permalink(); ?>">   
				<?php  the_post_thumbnail(); ?> </a></div>
				<div style="width: 55%;float: left;"><p> <a href="<?php  the_permalink(); ?>"> <?php echo the_title(); ?></a></p>
				<?php the_excerpt(); ?>
				<a href="#" class="removeFromRILButton" data-readitlater-id="<?php echo $post_id ;?>" ><?php echo $gema75_read_it_later->remove_from_readitlater_text ;?></a></div>

			</div>
			
			<?php
			
			
		}

	}
	
	wp_reset_postdata();
	

} else {
	echo "Your Read It Later List is empty";
}
