<?php
/*
Plugin Name: Read It Later Lite
Plugin URI: http://codecanyon.net/user/Gema75/portfolio
Description: Add posts and pages to a Read it Later list . Lite version
Version: 1.2
Author: Gema75
Author URI: http://codecanyon.net/user/Gema75
*/



if (!class_exists('Gema75_Read_It_Later')) {

	class Gema75_Read_It_Later {

		public $show_readitlater_link_after_content = 'no';

		public $show_readitlater_link_after_title = 'no';
		
		public $page_id_with_readitlater_shortcode = '1';
		
		//"Read it later" text
		public $read_it_later_text = 'Read it later';
		
		//"Added to Read it later list" text
		public $added_to_ril_list_text = 'Added to Read it later list';
		
		//"Already on the list" text
		public $already_on_ril_text = 'Already on the list';

		
		//"remove" from RIL
		public $remove_from_readitlater_text = 'Remove';		
		
		//"remove all" from RIL  
		public $remove_all_from_ril_text = 'Remove all';

		//text color
		public $readitlater_text_color= '#336699';
		


		function __construct(){
			
			define( 'GEMA75_READITLATER_PLUGIN_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );
			define( 'GEMA75_READITLATER_PLUGIN_DIR', plugin_dir_path(__FILE__) );
			

			//add admin menu
			add_action( 'admin_menu', array($this,'add_admin_menu') );	

	 		$get_saved_options = get_option('gema75_readitlater_saved_admin_options',true);

			//add default options on plugin activation
			if(!isset($get_saved_options['first_time_installation'])) {
				register_activation_hook( __FILE__, array( $this, 'add_default_options_first_time' ) );
			}


			//get saved options for admin 
			$this->get_admin_saved_options();

			//footer scripts

			add_action('wp_footer',array($this,'enqueue_scripts_and_styles'));

		}
		
		
		function add_admin_menu(){
			add_menu_page( 'Read It Later', 'Read It Later','manage_options', 'gema_readitlater_settings',array( $this, 'show_options_page' ) );
		}	

		
		function show_options_page(){
		
			//include inputs framework
			require_once( GEMA75_READITLATER_PLUGIN_DIR . 'gema75.input.boxes.class.php');

			//open page
			require_once( GEMA75_READITLATER_PLUGIN_DIR . 'admin_options.php');

		}	




		function enqueue_scripts_and_styles(){

		
				//load our own style
				wp_enqueue_style( 'gema75-style-css',GEMA75_READITLATER_PLUGIN_URL.'/styles.css' );
				
				//scripts
				wp_enqueue_script( 'fo-scripts-jquery',GEMA75_READITLATER_PLUGIN_URL.'/includes/scripts.js',array( 'jquery'), '1.3.0', true );
				
				//localize Javascript
				wp_localize_script( 'fo-scripts-jquery', 'gema75_readitlater_js_strings',$this->localize_js() );
				
		}
		
		
		function localize_js() {

			return array(
				'AlreadyExists' 				=> $this->already_on_ril_text,
				'removeFromRIL' 				=> $this->remove_from_readitlater_text,
				'addedToRilList' 				=> $this->added_to_ril_list_text,
				'readitlater_text_color'		=> $this->readitlater_text_color,
				'admin_ajax_url'				=> admin_url( 'admin-ajax.php')
			);
		}

		function get_admin_saved_options(){

			$saved_options = get_option('gema75_readitlater_saved_admin_options',true);


			if($saved_options['show_readitlater_link_after_title'] === 'yes'){
				$this->show_readitlater_link_after_title = 'yes' ;
				
			}

			if($saved_options['show_readitlater_link_after_content']==='yes'){
				$this->show_readitlater_link_after_content = 'yes' ;
			}
			
			if(isset($saved_options['page_id_with_readitlater_shortcode'])){
				$this->page_id_with_readitlater_shortcode = $saved_options['page_id_with_readitlater_shortcode'] ;
			}			

			if(isset($saved_options['read_it_later_text'])){
				$this->read_it_later_text = $saved_options['read_it_later_text'] ;
			}
			
			if(isset($saved_options['added_to_ril_list_text'])){
				$this->added_to_ril_list_text = $saved_options['added_to_ril_list_text'] ;
			}			
	
			
			if(isset($saved_options['already_on_ril_text'])){
				$this->already_on_ril_text = $saved_options['already_on_ril_text'] ;
			}			
			
			if(isset($saved_options['remove_from_readitlater_text'])){
				$this->remove_from_readitlater_text = $saved_options['remove_from_readitlater_text'] ;
			}			
			
			if(isset($saved_options['remove_all_from_ril_text'])){
				$this->remove_all_from_ril_text = $saved_options['remove_all_from_ril_text'] ;
			}			


			return $saved_options;

		}


		function save_admin_options(){

			$opts_array = array();

			$opts_array['show_readitlater_link_after_title'] = (isset($_POST['show_readitlater_after_title_input'])) ? sanitize_text_field($_POST['show_readitlater_after_title_input']) : 'no';
			
			$opts_array['show_readitlater_link_after_content']   = (isset($_POST['show_on_shop_page'])) ? sanitize_text_field($_POST['show_on_shop_page']) : 'no';
			
			$opts_array['page_id_with_readitlater_shortcode']   = (isset($_POST['gema75_page_id_with_ril_shortcode'])) ? sanitize_text_field($_POST['gema75_page_id_with_ril_shortcode']) : '1';
			

		
			$opts_array['read_it_later_text']   = (isset($_POST['read_it_later_text'])) ? sanitize_text_field($_POST['read_it_later_text']) : 'Read it later';
			$opts_array['added_to_ril_list_text']   = (isset($_POST['added_to_ril_list_text'])) ? sanitize_text_field($_POST['added_to_ril_list_text']) : 'Added to Read it later list';
			$opts_array['already_on_ril_text']   = (isset($_POST['already_on_ril_text'])) ? sanitize_text_field($_POST['already_on_ril_text']) : 'Already on the list';
			$opts_array['remove_from_readitlater_text']   = (isset($_POST['remove_from_readitlater_text'])) ? sanitize_text_field($_POST['remove_from_readitlater_text']) : 'Remove';
			$opts_array['remove_all_from_ril_text']   = (isset($_POST['remove_all_from_ril_text'])) ? sanitize_text_field($_POST['remove_all_from_ril_text']) : 'Remove all';
		

			//update option 
			update_option('gema75_readitlater_saved_admin_options',$opts_array);

			
		}	

		
		/*
		*  adds default plugin admin options 	
		*/
		static function add_default_options_first_time() {

		    $opts = array(
		    		'show_readitlater_link_after_title' 			=> 'no',
		    		'show_readitlater_link_after_content' 			=> 'yes',
					'page_id_with_readitlater_shortcode'			=> '1',
		    		'read_it_later_text'							=> 'Read it later',
		    		'remove_from_readitlater_text'					=> 'Remove',
		    		'remove_all_from_ril_text'					=> 'Remove all',
		    		'first_time_installation'						=> 'no' //mark as installed
		    		);

			update_option('gema75_readitlater_saved_admin_options', $opts);
			
			flush_rewrite_rules();
		}
		

		/*
		*	Locate slideout template 
		*/
		public function locate_slideout_template($file,$atts=array()){

			$return_template = GEMA75_READITLATER_PLUGIN_DIR . '/gema75_read_it_later_templates/'.$file;

			return $return_template;
			
		}

		
		/*
		*	Add shortcode 
		*/
		public function gema75_ril_shortcode(){
		
			ob_start();
			
			$template = $this->locate_slideout_template('read_it_later.php');
			
			include($template);
			
			$rendered_output = ob_get_clean();
			
			return $rendered_output;
			
		}
	
		
	} //end class Gema75_Read_It_Later

	



} //end if class exists Gema75_Read_It_Later


$GLOBALS['gema75_read_it_later'] = new Gema75_Read_It_Later();

require_once( GEMA75_READITLATER_PLUGIN_DIR . 'readitlater.frontend.class.php');


global $gema75_read_it_later;

add_shortcode( 'gema75_ril', array( $gema75_read_it_later, 'gema75_ril_shortcode' ) );
