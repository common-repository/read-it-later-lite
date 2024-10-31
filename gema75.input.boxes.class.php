<?php
class Gema75_input_boxes{


        public function input( $input_field ) {
            $field_value = get_option( $input_field['id'], isset($input_field['std']) ? $input_field['std'] : '' );
            
            $field_name = $input_field['id'];
			$field_id = $input_field['id'];

			$colspan = '';
			
            $output_html = '';

            switch( $input_field['type'] ) {
                case 'text':

                    $output_html  .= "<input type='text' id='{$field_id}' name='{$field_name}' value='{$field_value}' class='regular-text code' />";

                    if( isset($input_field['description']) && $input_field['description'] != '' ) {
                        $output_html .= "<p class='description'>{$input_field['description']}</p>";
                    }

                    break;

                case 'textarea': $output_html = "<textarea name='{$field_name}' id='{$field_id}' class='large-text code' rows='5' cols='30'>{$field_value}</textarea>";
                    if( isset($input_field['description']) && $input_field['description'] != '' ) {
                        $output_html .= "<p class='description'>{$input_field['description']}</p>";
                    }
                    break;

					
                case 'colorpicker':
                    $std = isset( $input_field['std'] ) ? $input_field['std'] : '';

                    $output_html = "<input type='text' id='{$field_id}' name='{$field_name}' value='{$field_value}' class='medium-text code panel-colorpicker' data-default-color='{$std}' />";
                    if( isset($input_field['description']) && $input_field['description'] != '' ) {
                        $output_html .= "<p class='description'>{$input_field['description']}</p>";
                    }
                    break;					

					
					
                case 'select': $output_html  = "<select name='{$field_name}' id='{$field_id}'>";
                    foreach( $input_field['options'] as $v=>$label ) {
                        $output_html .= "<option value='{$v}'". selected($field_value, $v, false) .">{$label}</option>";
                    }
                    $output_html .= "</select>";
                    if( isset($input_field['description']) && $input_field['description'] != '' ) {
                        $output_html .= "<p class='description'>{$input_field['description']}</p>";
                    }
                    break;

                case 'upload':
                    $output_html  = '<div class="uploader">';
                    $output_html .= "  <input type='text' id='{$field_id}' name='{$field_name}' value='{$field_value}' class='regular-text code' > <input type='button' name='' id='{$field_id}_button' class='button' value='Upload'>";
                    $output_html .= '</div>';
                    if( isset($input_field['description']) && $input_field['description'] != '' ) {
                        $output_html .= "<p class='description'>{$input_field['description']}</p>";
                    }
                    break;

            }

            echo $output_html;
        }
}