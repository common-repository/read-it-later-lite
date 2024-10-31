<?php

global $gema75_read_it_later;


//start framework
$option = new Gema75_input_boxes();


//enqueue color pickers
wp_enqueue_script('wp-color-picker');
wp_enqueue_style( 'wp-color-picker' );





//save admin options on submit
if(isset($_POST['gema75_readitlater_submit'])) {

	$gema75_read_it_later->save_admin_options();
}

//get saved options 
$gema75_read_it_later_options = $gema75_read_it_later->get_admin_saved_options()	;



?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
        $('#readitlater_bg_color,#readitlater_text_color').wpColorPicker({
        	color: true,
        });
	});
</script>

<div class="wrap" style="background-color: #fff;padding: 20px;">
	<h2>Read It Later Options</h2>

	<form method="post" >
		<table class="form-table">
		
			<tr valign="top">
				<th scope="row">Show after title </th>
				<td  colspan="3">
	
					<?php	
					$boxi = array(
						'type' => 'select',
						'std' => $gema75_read_it_later_options['show_readitlater_link_after_title'],
						'id'=>'show_readitlater_after_title_input',
						'options' => array('yes'=>'Yes','no'=>'No'),
						'description' => 'Show "Read it later" after title '
					);
					
					$option->input($boxi);

					?>

				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Show after the content </th>
				<td  colspan="3">
	
					<?php	
					$boxi2 = array(
						'type' => 'select',
						'id' => 'show_on_shop_page',
						'std' => $gema75_read_it_later_options['show_readitlater_link_after_content'],
						'options' => array('yes'=>'Yes','no'=>'No'),
						'description' => 'Show "Read it later" after the post content '
					);

					$option->input($boxi2);
					?>

				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Page with shortcode </th>
				<td  colspan="3">
					<?php	
					
						  wp_dropdown_pages(array('name'=>'gema75_page_id_with_ril_shortcode','selected'=>$gema75_read_it_later_options['page_id_with_readitlater_shortcode']));
					?>			
		
					<p class="description">Select the page that has the shortcode inserted:<strong>  [gema75_ril] </strong></p>
					
				</td>
			</tr>			

			<tr valign="top">
				<th scope="row"><h3> Text customization </h3>	 </th>
				<td  colspan="3">&nbsp;</td>
			</tr>			
		
			<tr valign="top">
				<th scope="row">"Read it later" Text </th>
				<td  colspan="3">
					<?php	
					$boxi_read_it_later_text = array(
						'type' => 'text',
						'id' => 'read_it_later_text',
						'std' => $gema75_read_it_later_options['read_it_later_text'],
						'description' => 'Customize "Read it later" text'
					);

					$option->input($boxi_read_it_later_text);
					?>					
				</td>
			</tr>		

			<tr valign="top">
				<th scope="row">"Remove all from list" Text </th>
				<td  colspan="3">
					<?php	
					$boxi_remove_all_from_ril_text = array(
						'type' => 'text',
						'id' => 'remove_all_from_ril_text',
						'std' => $gema75_read_it_later_options['remove_all_from_ril_text'],
						'description' => 'Customize "Remove all from list" text'
					);

					$option->input($boxi_remove_all_from_ril_text);
					?>					
					
					<br><br>
					<a href="http://wootheme-plugins.com/product/read-it-later-wordpress-plugin/"  target="_blank">Click here to see and purchase the full  features and options for this plugin</a>
				</td>
			</tr>			

			
			
			<tr>
				<td>
					<input type="submit" name="gema75_readitlater_submit" value="Save Changes">
				</td>
			</tr>
		</table>
	</form>			
</div>