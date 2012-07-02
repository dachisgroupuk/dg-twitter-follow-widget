<?php
/*
Plugin Name: DG Twitter Follow
Description: Shows the twitter follow links and buttons.
This is a simple plugin to make the follow button configurable via the WordPress administration system
Author: Dachis Group
Author URI: http://www.dachisgroup.com
Version: 0.1
*/

add_action( 'widgets_init', create_function( '', 'register_widget( "DGTwitterFollow_Widget" );' ) );

class DGTwitterFollow_Widget extends WP_Widget {

    /** More information on the Twitter follow buttons can be found at:
     * https://dev.twitter.com/docs/twitter-for-websites
     */

    /**
     * Constructor
     *
     */
    function DGTwitterFollow_Widget( ){

        parent::WP_Widget(
	      false,
	      $name = 'DG - Twitter follow',
	      $widget_options = array(
	        'description' => 'Displays a twitter follow button',
	      )
	    );

        add_shortcode('twitter-follow', array( &$this, 'followButton' ) );
    }


    function widget( $args, $instance )
    {
        extract( $args );

	    echo $before_widget;
        $this->followButton( $instance );
	    echo $after_widget;
    }

    /**
     * Generate the Twitter follow buttons
     *
     * @param array $args
     * @return void
     */
    function followButton( $args ){

        $default = array(
                         'screen_name' => 'dachisgroup_eu',
                         'show_count' => 'yes',
                         'show_screen_name' => 'yes',
                         'button_size' => 'medium',
                         'language' => 'en',
                         'width' => false,
                         'align' => 'left',
                         'custom_styling' => 'no'
                        );

        $attributes = array_merge( $default, $args );

        extract( $attributes );

        /** -------- Notes ----------
         *
         * show_count = data-show-count
         * button_size = data-size (medium or large)
         * width = data-width
         * align = data-align ( left or right )
         * show_screen_name = data-show-screen-name ( true or false )
         * custom_styling = dnt (true or false)
         * language = data-lang
         */
        $settings = '';

        $settings .= ( $show_count == true || $show_count =='yes' )? ' data-show-count="true"' : ' data-show-count="false"' ;
        $settings .= ( $button_size == 'medium' )? ' data-size="medium"' : ' data-size="large"' ;
        $settings .= ( !empty($width) )? ' data-width="' . $width . '"' : '' ;
        $settings .= ( $align == 'right' )? ' data-align="right"' : ' data-align="left"' ;
        $settings .= ( $show_screen_name == 'yes' || $show_screen_name == 'true' )? ' data-show-screen-name="true"' : ' data-show-screen-name="false"' ;
        $settings .= ( $custom_style == 'yes' || $custom_style == 'true' )? ' dnt="true"' : '' ;
        $settings .= ' language="'.$language.'"' ;

        $settings = trim($settings);
        $link = 'https://twitter.com/'.$screen_name;

        $assembled_link = sprintf( __('<a href="%s" class="twitter-follow-button" %s>Follow @%s</a>'), $link, $settings, $screen_name );

        echo $assembled_link;
        ?>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="http://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        <?php
    }

    /**
     * Update the data
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    function update($new_instance, $old_instance) {
	    // create a back up we can access it when updating content
	    $instance                       = $old_instance;
	    $instance['screen_name']        = strip_tags($new_instance['screen_name']);
	    $instance['show_count']         = strip_tags($new_instance['show_count']);
	    $instance['show_screen_name']   = strip_tags($new_instance['show_screen_name']);
        $instance['button_size']        = strip_tags($new_instance['button_size']);
        $instance['language']           = strip_tags($new_instance['language']);
        $instance['width']              = strip_tags($new_instance['width']);
        $instance['align']              = strip_tags($new_instance['align']);
        $instance['custom_styling']     = strip_tags($new_instance['custom_styling']);

	    return $instance;
	  }

	  /** @see WP_Widget::form */
	  function form($instance) {
	    ?>
	    <p>
	        <label for="<?php echo $this->get_field_id('screen_name'); ?>"><?php _e('Screen name:'); ?></label>
	        <input class="widefat" id="<?php echo $this->get_field_id('screen_name'); ?>" name="<?php echo $this->get_field_name( 'screen_name' ); ?>" type="text" value="<?php echo esc_attr( $instance['screen_name'] ) ; ?>" />
	    </p>
        <p>
	        <label for="<?php echo $this->get_field_id('show_count'); ?>"><?php _e('Show number of followers:'); ?></label>
	        <input type="checkbox" id="<?php echo $this->get_field_id('show_count'); ?>" name="<?php echo $this->get_field_name( 'show_count' ); ?>" value="yes" <?php echo ( $instance['show_count'] == 'yes' || $instance['show_count'] == true )? ' checked="true"' : '' ;?> />
	    </p>
        <p>
	        <label for="<?php echo $this->get_field_id('show_screen_name'); ?>"><?php _e('Show screen name:'); ?></label>
            <input type="checkbox" id="<?php echo $this->get_field_id('show_screen_name'); ?>" name="<?php echo $this->get_field_name( 'show_screen_name' ); ?>" value="yes" <?php echo ( $instance['show_screen_name'] == 'yes' || $instance['show_screen_name'] == true )? ' checked="true"' : '' ;?> />
	    </p>
        <p>
	        <label for="<?php echo $this->get_field_id('button_size'); ?>"><?php _e('Button size:'); ?></label>
            <select id="<?php echo $this->get_field_id('button_size'); ?>" name="<?php echo $this->get_field_name( 'button_size' ); ?>">
                <option value="medium" <?php echo ( isset( $instance['button_size'] ) && $instance['button_size'] == 'medium' )? ' selected="selected"' : '' ;?>>Medium</option>
                <option value="large" <?php echo ( isset( $instance['button_size'] ) && $instance['button_size'] == 'large' )? ' selected="selected"' : '' ;?>>Large</option>
            </select>
	    </p>
        <!--
        <p>
	        <label for="<?php echo $this->get_field_id('language'); ?>"><?php _e('Language:'); ?></label>
	        <input class="widefat" id="<?php echo $this->get_field_id('language'); ?>" name="<?php echo $this->get_field_name( 'language' ); ?>" type="text" value="<?php echo esc_attr( $instance['language'] ) ; ?>" />
        </p>
        -->
        <p>
	        <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:'); ?></label>
	        <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo esc_attr( $instance['width'] ) ; ?>" />
	    </p>
        <p>
	        <label for="<?php echo $this->get_field_id('align'); ?>"><?php _e('Align:'); ?></label>
            <select id="<?php echo $this->get_field_id('align'); ?>" name="<?php echo $this->get_field_name( 'align' ); ?>">
                <option value="left" <?php echo ( isset( $instance['align'] ) && $instance['align'] == 'left' )? ' selected="selected"' : '' ;?>>Left</option>
                <option value="right" <?php echo ( isset( $instance['align'] ) && $instance['align'] == 'right' )? ' selected="selected"' : '' ;?>>Right</option>
            </select>
	    </p>
        <p>
	        <label for="<?php echo $this->get_field_id('custom_styling'); ?>"><?php _e('Use custom styling:'); ?></label>
            <input type="checkbox" id="<?php echo $this->get_field_id('custom_styling'); ?>" name="<?php echo $this->get_field_name( 'custom_styling' ); ?>" value="yes" <?php echo ( $instance['custom_styling'] == 'yes' || $instance['custom_styling'] == true )? ' checked="true"' : '' ;?> />
        </p>
	    <?php
	  }

}
