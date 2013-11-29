<?php
/**
 * This file contains the button creation scripts function for the buttons plugin
 *
 * @since 1.0.0
 *
 * @package    MP Buttons
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2013, Move Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
 
/**
 * Enqueue JS and CSS for buttons 
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */

/**
 * Enqueue css and js
 *
 */
function mp_buttons_thickboxes(){
	
	//Create Thickbox
	echo '<div id="mp-buttons-thickbox" style="display: none;">';
	?>
		<div class="wrap" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
            
            <p>Use the form below to insert a button </p>
            
            <div class="mp_field">
            
                <div class="mp_title"><label for="mp_button_icon"><strong>Button Icon:</strong> <em>If you want to have an icon on this button, pick one here.</em></label></div>     
                <input type="hidden" class="mp-buttons-icon-field" />
                 
                <?php
                //Font thumbnail
                echo '<div class="mp_button_font_icon_thumbnail">';
                    echo '<div class="">';
                        echo '<div class="mp-iconfontpicker-title" ></div>';
                    echo '</div>';
                echo '</div>';
                ?>
                
                <a class="mp-button-icon-select button"><?php _e('Select Icon', 'mp-buttons'); ?></a>
            	
                <?php		
		
				//Get all font styles in the css document and put them in an array
				$pattern = '/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';
				$subject = file_get_contents( plugins_url( '/fonts/font-awesome-4.0.3/css/font-awesome.css', dirname( __FILE__ ) ) );
				preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);
				
				$icons = array();
			
				foreach($matches as $match){
					$icons[$match[1]] = $match[1];
				}	
				
				?>
				
				<div class="mp-buttons-icon-picker-area" style="display: none;">
				
					<?php
					foreach( $icons as $icon ){
						
						echo '<a href="#" class="mp-button-icon-picker-item">';
													
							echo '<div class="' . $icon . ' mp-button-icon">';
								
								echo '<div class="mp-iconfontpicker-title" >' . $icon . '</div>';
							
							echo '</div>';
						
						echo '</a>';
							 
					} 
					?>
					
				</div>
		
            </div>
            
            <div class="mp_field">
            	
                <div class="mp_title"><label for="mp_button_text"><strong>Button Text:</strong> <em>What should the button say?</em></label></div>   
            	<input type="text" class="mp-buttons-text-field" />
                
            </div>
            
            <div class="mp_field">
            	
                <div class="mp_title"><label for="mp_button_link"><strong>Button Link:</strong> <em>Where should this button go when clicked?</em></label></div>   
            	<input type="url" class="mp-buttons-link-field" />
                
            </div>
            
            <div class="mp_field">
            	
                <div class="mp_title"><label for="mp_button_window"><strong>Button Open Type:</strong> <em>Where/How should this link open?</em></label></div>   
            	
                
                <select class="mp-buttons-open-type-field" />
                  <option value="_blank">Open in a new Window/Tab</option>
                  <option value="_self">Open in this window</option>
                </select>
                
            </div>
            
            <p class="submit">
            
                <input type="button" class="button-primary" value="<?php echo __('Insert Button', 'mp_buttons') ?>" onclick="mp_buttons_insert();" />
                <a id="cancel-mp-buttons-insert" class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'mp_buttons' ); ?>"><?php _e( 'Cancel', 'mp_buttons' ); ?></a>
            
            </p>
                
		</div>
        
	<?php
	//End Thickbox
	echo '</div>';
		
}
add_action( 'admin_footer', 'mp_buttons_thickboxes' );

/**
 * Media Button
 *
 * Returns the "Insert Button" TinyMCE button.
 *
 * @access     public
 * @since      1.0.0
 * @global     $pagenow
 * @global     $typenow
 * @global     $wp_version
 * @param      string $context The string of buttons that already exist
 * @return     string The HTML output for the media buttons
*/
function mp_buttons_media_button( $context ) {
	
	global $pagenow, $typenow, $wp_version;
	
	$output = '';

	/** Only run in post/page creation and edit screens */
	if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) ) {
		
		//Check current WP version - If we are on an older version than 3.5
		if ( version_compare( $wp_version, '3.5', '<' ) ) {
			
			//Output old style button
			$output = '<a href="#TB_inline?width=640&inlineId=mp-buttons-thickbox" class="thickbox" title="' . __('Add Button', 'mp_core') . '">' . $img . '</a>';
			
		//If we are on a newer than 3.5 WordPress	
		} else {
			
			//Output new style button
			$output = '<a href="#TB_inline?width=640&inlineId=mp-buttons-thickbox" class="thickbox button" title="' . __('Add Button', 'mp_core') . '">';
			
			//Media Button Image
			$output .= '<span class="wp-media-buttons-icon" id="mp-buttons-media-icon"></span>';
			
			//Finish the output
			$output.= __('Add Button', 'mp_core') . '</a>';
		}
	}
	
	//Add new button to list of buttons to output
	return $context . $output;
}
add_filter( 'media_buttons_context', 'mp_buttons_media_button' );