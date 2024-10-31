<?php

if (!class_exists('Gema75_Read_It_Later_Frontend_User') && class_exists('Gema75_Read_It_Later')) {



	class Gema75_Read_It_Later_Frontend_User {
	
		//holds all post/pages in RIL as an array
		public $ril_list_array = array();
	
	
		function __construct(){
			global $gema75_read_it_later;
			
			if ( !session_id() ){ session_start(); }

				add_action( 'wp', array($this,'add_ril_filters' ));

				//add ajax function to add remove from RIL
				add_action( 'wp_ajax_maybe_add_to_ril_ajax', array( $this, 'maybe_add_to_ril_ajax'));
				add_action( 'wp_ajax_nopriv_maybe_add_to_ril_ajax', array( $this, 'maybe_add_to_ril_ajax' ));
				
				//add ajax function to remove a product from RIL
				add_action( 'wp_ajax_remove_post_from_ril_list_ajax', array( $this, 'remove_post_from_ril_list_ajax'));
				add_action( 'wp_ajax_nopriv_remove_post_from_ril_list_ajax', array( $this, 'remove_post_from_ril_list_ajax' ));
				
				
				//add ajax function to remove all products from RIL
				add_action( 'wp_ajax_remove_all_products_from_wishlist_ajax', array( $this, 'remove_all_products_from_wishlist_ajax'));
				add_action( 'wp_ajax_nopriv_remove_all_products_from_wishlist_ajax', array( $this, 'remove_all_products_from_wishlist_ajax' ));
				
				//add ajax function to get the RIL
				add_action( 'wp_ajax_get_ril_ajax', array( $this, 'get_ril_ajax'));
				add_action( 'wp_ajax_nopriv_get_ril_ajax', array( $this, 'get_ril_ajax' ));
				
				//add ajax to save RIL for logged in users if they click "save RIL"
				add_action( 'wp_ajax_save_wishlist_ajax_for_logged_in_users', array( $this, 'save_wishlist_ajax_for_logged_in_users'));
				add_action( 'wp_ajax_nopriv_save_wishlist_ajax_for_logged_in_users', array( $this, 'save_wishlist_ajax_for_logged_in_users' ));
				
				
				
		
		}
		
		
		/*
		*  Adds the filters for the content
		*/
		function add_ril_filters(){
		
			global $gema75_read_it_later;
			
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

				if( (is_page() || is_single()  || is_category() || is_archive())  &&  (!is_woocommerce() && !is_account_page() && !is_cart()  && !is_checkout()) ){
				
					if(get_post_type()=='post' || get_post_type()=='page'){
					
						//show "read it later" after the content
						if($gema75_read_it_later->show_readitlater_link_after_content==='yes' ){
							add_filter( 'the_content', array($this,'show_readitlater_after_content') , 30 ); 
						}

						//show "read it later" after the title
						if($gema75_read_it_later->show_readitlater_link_after_title ==='yes'){
							add_filter( 'the_content', array($this,'show_read_it_later_after_title') , 30 ); 
						}
						
					}
				
				}
			
			}else{
				
				if( (is_page() || is_single() || is_category() || is_archive())){
				
					if(get_post_type()=='post'){
					
						//show "read it later" after the content
						if($gema75_read_it_later->show_readitlater_link_after_content==='yes' ){
							add_filter( 'the_content', array($this,'show_readitlater_after_content') , 30 ); 
						}

						//show "read it later" after the title
						if($gema75_read_it_later->show_readitlater_link_after_title ==='yes'){
							add_filter( 'the_content', array($this,'show_read_it_later_after_title') , 30 ); 
						}
						
					}
				
				}
				
			}
			
		}
		
		
		//shows "Read it later" after the content
		function show_readitlater_after_content($content){
			global $post , $gema75_read_it_later ;

			//if logged in 
			if(is_user_logged_in() && in_the_loop()){
				
					$current_user_id = get_current_user_id();
					
					$current_user_readitlater_list = get_option('gema75_readitlater_for_user_id_'.$current_user_id);
					
					if(isset($current_user_readitlater_list['posts_in_ril'][$post->ID])){
					
						$content = $content . ' <div>  <a href="#"><span class="gema75_read_it_later_text " data-readitlater-id="'.$post->ID.'"> ' . $gema75_read_it_later->already_on_ril_text . ' </span></a>  </div>'   ;
					
					}else{

						$content = $content . ' <div> <a href="#"> <span class="gema75_read_it_later_text addToReadItLaterButton" data-readitlater-id="'.$post->ID.'"> ' . $gema75_read_it_later->read_it_later_text . ' </span></a>  </div>';
						
					}
					
					return $content;

			}
			
			//Non logged in users
			if(!is_user_logged_in() && in_the_loop()){
				
				if(!isset($_SESSION['gema75_ril_post_array'][$post->ID])){

					$content = $content . ' <div> <a href="#"><span class="gema75_read_it_later_text addToReadItLaterButton" data-readitlater-id="'.$post->ID.'"> ' . $gema75_read_it_later->read_it_later_text . ' </span></a>  </div>';
				
				}else {

					$content = $content . ' <div>  <a href="#"><span class="gema75_read_it_later_text " data-readitlater-id="'.$post->ID.'"> ' . $gema75_read_it_later->already_on_ril_text . ' </span></a>  </div>'   ;
					
				}
			
			}
			
			return $content;

		}
		
		

		//shows "add to readitlater" link/button on single product page
		function show_read_it_later_after_title($content){

			global $post , $gema75_read_it_later ;

			//if logged in 
			if(is_user_logged_in() && in_the_loop()){
			
				
				
					$current_user_id = get_current_user_id();
					
					$current_user_readitlater_list = get_option('gema75_readitlater_for_user_id_'.$current_user_id);
					
					if(isset($current_user_readitlater_list['posts_in_ril'][$post->ID])){
					
						$content = ' <div>  <a href="#"><span class="gema75_read_it_later_text " data-readitlater-id="'.$post->ID.'"> ' . $gema75_read_it_later->already_on_ril_text . ' </span></a>  </div>' . $content  ;
					
					}else{

						$content = ' <div>  <a href="#"><span class="gema75_read_it_later_text addToReadItLaterButton" data-readitlater-id="'.$post->ID.'"> ' . $gema75_read_it_later->read_it_later_text . ' </span></a>  </div>' . $content  ;
						
					}
					
					return $content;

			}	
			
			//Non logged in users
			if(!is_user_logged_in() && in_the_loop()){
				
				if(!isset($_SESSION['gema75_ril_post_array'][$post->ID])){

					$content =  ' <div> <a href="#"><span class="gema75_read_it_later_text addToReadItLaterButton" data-readitlater-id="'.$post->ID.'"> ' . $gema75_read_it_later->read_it_later_text . ' </span></a>  </div>' . $content ;
				
				}else {

					$content =  ' <div>  <a href="#"><span class="gema75_read_it_later_text " data-readitlater-id="'.$post->ID.'"> ' . $gema75_read_it_later->already_on_ril_text . ' </span></a>  </div>' . $content  ;
					
				}
			
			}			
			
			
			return $content;
		
		}
		
		
		
		
		
		
		/*
		*  Ajax endpoint when clicking "add to cart" text
		*/
		function maybe_add_to_ril_ajax(){

				
			
				$id = (int) $_POST['post'];

				$posti = get_post($id);
				
				$featured_image = GEMA75_READITLATER_PLUGIN_URL . '/includes/transparent.png';
				
				//get featured image
				if (has_post_thumbnail($posti->ID ) ){
				
						$featured_image =  wp_get_attachment_url( get_post_thumbnail_id( $posti->ID) );

				}
				
				
				$post_array = array(
					'id'			=> $id,
					'title' 		=> $posti->post_title,
					'image'			=> $featured_image,
					'permalink' 	=> get_permalink($id),
					
				);
				

				//if logged in , save RIL  as option
				if(is_user_logged_in()){
				
					$current_user_id = get_current_user_id();
					
					$current_user_wishlist = get_option('gema75_readitlater_for_user_id_'.$current_user_id);
					
					if(isset($current_user_wishlist['posts_in_ril'][$id])){
					
						die(json_encode("postAlreadyInRIL"));
					
					}else{

						$current_user_wishlist['posts_in_ril'][$id]=$post_array;
						
					}

					update_option('gema75_readitlater_for_user_id_'.$current_user_id ,$current_user_wishlist );

					die(json_encode($post_array));
					
				} else {
					//non logged in user
					
					//check if post id is on the RIL 
					if(isset($_SESSION['gema75_ril_post_array'][$id])){
					
						die(json_encode("postAlreadyInRIL"));
						
					}
					

					//save the post id on the session variable
					$_SESSION['gema75_ril_post_array'][$id]=$post_array;
					
					$this->ril_list_array[$id] = $post_array;
					
					die(json_encode($post_array));
					
					
				}

			}



		/*
		*  Returns products in RIL
		*/
		public function get_ril_ajax(){

			//check if the loggedin user has a saved RIL 
			if(is_user_logged_in()){
			
				$current_user_id = get_current_user_id();
				
				$current_user_ril_list = get_option('gema75_readitlater_for_user_id_'.$current_user_id);
				
				if(isset($current_user_ril_list['posts_in_ril']) && count($current_user_ril_list['posts_in_ril'])>=1 ){
					
					$response = array();
					$response['posts_in_ril_list'] = $current_user_ril_list['posts_in_ril'];
					
					die(json_encode($response));
					
				}else {
					
					die(json_encode('Your RIL is empty'));
					
				}
				
			}else{

				if( isset($_SESSION['gema75_ril_post_array']) && count($_SESSION['gema75_ril_post_array'])>=1){
					$response = array();
					$response['posts_in_ril_list'] = $_SESSION['gema75_ril_post_array'];
					die(json_encode($response));
					
				} else {
					//print_r($_SESSION['gema75_ril_post_array']);
					die(json_encode('Your RIL is empty'));
				}

			}

		}	

		/*
		*  Returns posts in RIL  as array
		*/
		public function get_ril_non_logged_in(){
		
				if( isset($_SESSION['gema75_ril_post_array']) && count($_SESSION['gema75_ril_post_array'])>=1){
					$response = array();
					$response['posts_in_ril'] = $_SESSION['gema75_ril_post_array'];

				} else {
					$response = 'Your RIL is empty';
				}
				
				return $response;
		}
		

		/*
		*  Removes a post from RIL
		*/
		public function remove_post_from_ril_list_ajax(){
			
			$id = (int) $_POST['post'];
		
			//check if the loggedin user has a saved RIL 
			if(is_user_logged_in()){
				
				$current_user_id = get_current_user_id();
				
				$current_user_ril_list = get_option('gema75_readitlater_for_user_id_'.$current_user_id);
				
				if(isset($current_user_ril_list['posts_in_ril'][$id])){
					
					unset($current_user_ril_list['posts_in_ril'][$id]);
					
					update_option('gema75_readitlater_for_user_id_'.$current_user_id ,$current_user_ril_list );
					
					die(json_encode($current_user_ril_list['posts_in_ril']));
				
				}else{
				
					die(json_encode('Your list is empty'));
					
				}
				
			}else {
			
				if(count($_SESSION['gema75_ril_post_array'])>=1){
				
					$response = array();
				
					if(isset($_SESSION['gema75_ril_post_array'][$id])){
						
						unset($_SESSION['gema75_ril_post_array'][$id]);
						
						$this->ril_list_array = $_SESSION['gema75_ril_post_array'];	
						
						$response['posts_in_ril_list'] = $_SESSION['gema75_ril_post_array'];

					}	
					
					die(json_encode($response));
					
				} else {
					
					die(json_encode('Your wishlist is empty'));
				
				}
				
			}
			
			
		}



		/*
		*  Removes all posts/pages from RIL list
		*/
		public function remove_all_products_from_wishlist_ajax(){
			
			//check if the loggedin user has a saved RIL list 
			if(is_user_logged_in()){
			
				$current_user_id = get_current_user_id();
			
				delete_option('gema75_readitlater_for_user_id_'.$current_user_id);
				
				$response = array();
				$response['posts_in_ril_list']=array();
				die(json_encode($response));
			
			}else{		
			
				if(isset($_SESSION['gema75_ril_post_array']) && count($_SESSION['gema75_ril_post_array'])>=1){
				
					$response = array();

						unset($_SESSION['gema75_ril_post_array']);
						$this->ril_list_array = array();	
						$response['posts_in_ril_list'] = $this->ril_list_array;

					die(json_encode($response));
					
				} else {
					//print_r($_SESSION['gema75_ril_post_array']);
					die(json_encode('Your wishlist was empty'));
				
				}
			
			}
			
			
			
		}
		
	
		
	} // end class Gema75_Read_It_Later_Frontend_User
	
} //end if class exists Gema75_Read_It_Later_Frontend_User	

$gema75_ril_frontend =  new Gema75_Read_It_Later_Frontend_User();
