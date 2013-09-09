<?php

/**
 * Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.
 *
 * @since 0.1
 * @version 0.2
 * @date 2011-01-21
*/

// Fun starts here

class MeblogFrypePage_Widget extends WP_Widget {

/**
 * Widget setup.
*/
	function MeblogFrypePage_Widget() {
		/* Widget settings. */
		$widget_ops = array(
			'classname' => 'meblogfrypepage',
			'description' => __('Shows draugiem.lv/lapas users', 'frypepage_widget')
		);
		/* Widget control settings. */
		$control_ops = array(
			'width' => 300,
			'height' => 200,
			'id_base' => 'meblogfrypepage-widget'
		);
		/* Create the widget. */
		$this->WP_Widget(
			'meblogfrypepage-widget',
			__('Draugiem Lapas Widget', 'frypepage_widget'),
			$widget_ops,
			$control_ops
		);
		//Additional links on the plugin page
		// REMOVED 0.2.1:@28

	}

	/**
	 * Display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$name = $instance['name'];
		$wwidth = $instance['wwidth'];
		$wheight = $instance['wheight'];
		$show_usersfrp = $instance['show_usersfrp'];
		$show_cssfrp = $instance['show_cssfrp'];
		// Maybe remove in next release!
		$show_pageaboutlenght = $instance['show_aboutpagelenght'];
		$show_saycount = $instance['show_saycount'];
		$show_saytexts = $instance['show_saytexts'];
		
		$dfp_u = $instance['dfp_u'];

		
		$widgetid = $dfp_u; /* magic! *** unique id */
		if(!$widgetid||$widgetid==''){$widgetid=md5(mt_rand(100000000,time()));}
		if(!$wheight){$wheight = '230';}
		// Maybe remove in next release!
		if(!is_numeric($show_pageaboutlenght)||!$show_pageaboutlenght){$show_pageaboutlenght='200';}

		/* Before widget (defined by themes). */
		echo $before_widget;
echo "\n\n<!-- Draugiem.lv biznesa lapu sekotāju spraudnis via http://umbrovskis.com | MediaBox.lv | SimpleMediaCode.com -->\n".''."\n";
		/* Display the widget title if one was input (before and after defined by themes). */
		if ($title)	echo $before_title . $title . $after_title;?>
<div id="fansblock<?php echo $widgetid;?>" class="usersfrp"></div>
<style>
<?php echo "#fansblock{$widgetid}{width:{$wwidth}px; height:{$wheight}px !important;border: 1px solid #c9c9c9; }#fansblock{$widgetid} div {overflow:hidden; height:100%; min-height:264px;}#fansblock{$widgetid} iframe{ overflow:hidden; height:100%; min-height:264px;}.dfoot{margin-top:4px;} ";?>
</style>
<?php 
/* so we can overwrite defaults and generated values */
if($show_cssfrp):?><link type="text/css" media="all" href="<?php echo $show_cssfrp;?>?v=<?php echo $widgetid;?>" /><?php endif;?>
<script type="text/javascript">
var fans = new DApi.BizFans( {
	name:'<?php echo mb_strtolower($name, 'UTF-8');?>',
<?php if($show_usersfrp==0):?>
	showFans:0,
<?php else:?>
	showFans:1,
<?php endif;?>
	count:<?php echo $show_usersfrp;?>,
	showSay:<?php echo $show_saytexts;?>,
	saycount:<?php echo $show_saycount;?>
} );
fans.append( 'fansblock<?php echo $widgetid;?>' );		
</script>
<div class="dfoot">
	<span class="dfoot-text"><a href="http://mediabox.lv" title="WordPress mājas lapu izstrāde"><span style="color:#900">Media</span><span style="color:#000;">Box.lv</span></a> WordPress spraudnis</span>
</div>
<?php 

/* After widget (defined by themes). */
		echo $after_widget;
echo "\n<!-- Draugiem.lv biznesa lapu sekotāju spraudnis beidzas footer -->\n\n";
	}
	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['name'] = strip_tags( $new_instance['name'] );
		/* No need to strip tags for sex and show_usersfrp. */
		$instance['wwidth'] = $new_instance['wwidth'];
		$instance['wheight'] = $new_instance['wheight'];
		$instance['show_usersfrp'] = $new_instance['show_usersfrp'];
		$instance['show_cssfrp'] = $new_instance['show_cssfrp'];
		$instance['show_aboutpagelenght'] = $new_instance['show_aboutpagelenght'];
		/* @since 2.0 */
		$instance['show_saycount'] = $new_instance['show_saycount'];
		$instance['show_saytexts'] = $new_instance['show_saytexts'];
		/* @since 2.3 */
		$instance['dfp_u'] = $new_instance['dfp_u'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {
		
		$ffpplugin = new MB_FrypePage_Plugin;
		
		/* Set up some default widget settings. */
		$defaults = array(
		'title' => __('Draugiem.lv/lapas', 'frypepage_widget'),
		'name' => __('umbrovskiscom', 'frypepage_widget'),
		'wwidth' => '240',
		'wheight' => '234',
		'show_usersfrp' => '0',
		'show_cssfrp' =>WP_PLUGIN_URL.'/'.$ffpplugin->ffpfolder.'/'.$ffpplugin->cssfolder.'/draugiem-lapas-sekotaji.css',
		'show_aboutpagelenght'=> '1000',
		'show_saycount' => '3',
		'dfp_u' => md5(mt_rand(100000000,time())),
		'show_saytexts'=>'0'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'frypepage_widget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" placeholder="<?php esc_attr_e('Title:', 'frypepage_widget'); ?>" />
		</p>

		<!-- Your Name: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'name' ); ?>"><?php _e('Page URL:', 'frypepage_widget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" value="<?php echo $instance['name']; ?>" style="width:100%;" placeholder="<?php esc_attr_e('Page URL:', 'frypepage_widget'); ?>" />
		</p>

		<!-- Width: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'wwidth' ); ?>"><?php _e('Width:', 'frypepage_widget'); ?></label><br />
            <span class="small"><?php _e('recomended:', 'frypepage_widget'); ?> 200</span><br />
			<input id="<?php echo $this->get_field_id( 'wwidth' ); ?>" name="<?php echo $this->get_field_name( 'wwidth' ); ?>" value="<?php echo $instance['wwidth']; ?>" style="width:100%;" 
			placeholder="<?php esc_attr_e('Width:', 'frypepage_widget'); ?>" />
        </p>

		<!-- Height: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'wheight' ); ?>"><?php _e('Height:', 'frypepage_widget'); ?></label><br />
			<input id="<?php echo $this->get_field_id( 'wheight' ); ?>" name="<?php echo $this->get_field_name( 'wheight' ); ?>" value="<?php echo $instance['wheight']; ?>" style="width:100%;"
			 placeholder="<?php esc_attr_e('Height:', 'frypepage_widget'); ?>" />
        </p>

		<!-- Show Users? Input -->
		<p><label for="<?php echo $this->get_field_id( 'show_usersfrp' ); ?>"><?php _e('How many users to show?', 'frypepage_widget'); ?></label><br />
        <span class="small"><?php _e('0 for none', 'frypepage_widget'); ?></span><br />
        	<input id="<?php echo $this->get_field_id( 'show_usersfrp' ); ?>" name="<?php echo $this->get_field_name( 'show_usersfrp' ); ?>" value="<?php echo $instance['show_usersfrp']; ?>" 
			style="width:100%;" placeholder="<?php esc_attr_e('How many users to show?', 'frypepage_widget'); ?>" />
		</p>
		<!-- Show Say? Input -->
		<p><label for="<?php echo $this->get_field_id( 'show_saytexts' ); ?>"><?php _e('Show SAY?', 'frypepage_widget'); ?></label><br />
        <select id="<?php echo $this->get_field_id( 'show_saytexts' ); ?>" name="<?php echo $this->get_field_name( 'show_saytexts' ); ?>" >
			<option value="1"<?php if($instance['show_saytexts']==1){ echo ' selected';}?>><?php _e('yes', 'frypepage_widget'); ?></option>
			<option value="0"<?php if($instance['show_saytexts']==0){ echo ' selected';}?>><?php _e('no', 'frypepage_widget'); ?></option>
        </select>
		</p>
		<!-- Show Say count? Input -->
		<p><label for="<?php echo $this->get_field_id( 'show_saycount' ); ?>"><?php _e('How much SAY entries to show?', 'frypepage_widget'); ?></label><br />
        <span class="small"><?php _e('0 for none', 'frypepage_widget'); ?></span><br />
        	<input id="<?php echo $this->get_field_id( 'show_saycount' ); ?>" name="<?php echo $this->get_field_name( 'show_saycount' ); ?>" value="<?php echo $instance['show_saycount']; ?>" style="width:100%;" />
		</p>
		
		<!-- Show CSS? Input -->
		<p><label for="<?php echo $this->get_field_id( 'show_cssfrp' ); ?>"><?php _e('StyleSheet (CSS) URL', 'frypepage_widget'); ?></label><br />
        <span class="small"><?php _e('Theme (CSS) URL', 'frypepage_widget'); ?>: <strong><?php bloginfo('stylesheet_url'); ?></strong></span><br />
        <span class="small"><strong><?php echo WP_PLUGIN_URL.'/'.$ffpplugin->ffpfolder.'/'.$ffpplugin->cssfolder.'/draugiem-lapas-sekotaji.css'; ?></strong></span><br />
<input 
	id="<?php echo $this->get_field_id( 'show_cssfrp' ); ?>" 
	name="<?php echo $this->get_field_name( 'show_cssfrp' ); ?>"
	value="<?php echo $instance['show_cssfrp']; ?>"
	style="width:100%;" 
/>
		</p>
		
<!-- Unique ID Input -->
<?php 
		$dfp_uid = $instance['dfp_u'];
		if($dfp_uid==''||!$dfp_uid): $dfp_uid = md5(mt_rand(100000000,time())); endif;
?>
		<p><label for="<?php echo $this->get_field_id( 'dfp_u' ); ?>"><?php _e('Unique ID', 'frypepage_widget'); ?></label><br />
        	<input id="<?php echo $this->get_field_id( 'dfp_u' ); ?>"
				name="<?php echo $this->get_field_name( 'dfp_u' ); ?>"
				value="<?php echo $dfp_uid; ?>"
				style="width:100%;"
			/>
		</p>
        <p>Licences noteikumi | <a href="http://umbrovskis.lv/autortiesibas/" target="_blank" title="Autortiesības">Umbrovskis.lv</a> [&copy; 1982-<?php echo date('Y')+70;?>]</span></p>
        
        
		<div  style="display:none">
<?php 
		/**
		 * @todo sekot līdzi vai parametri netiks atjaunoti, ja nē, tad dzēsīšu ārā
		 * @date 2011-08-12
		*/
?>

				<!-- Show lenght? Input -->
				<p><label for="<?php echo $this->get_field_id( 'show_aboutpagelenght' ); ?>"><?php _e('Lenght of page description', 'frypepage_widget'); ?></label><br />
					<input id="<?php echo $this->get_field_id( 'show_aboutpagelenght' ); ?>" name="<?php echo $this->get_field_name( 'show_aboutpagelenght' ); ?>" value="<?php echo $instance['show_aboutpagelenght']; ?>" style="width:100%;" />
				</p>
		</div>

        <!-- HELP link-->
        <p><img src="<?php echo plugins_url('draugiemlvlapas-fan-page').'/img/'; /* @todo FRYPEFANPAGEI */ ?>help.png" width="16" height="16" alt="" /> <a href="<?php echo $ffpplugin->ffpinfo;?>?utm_campaign=WordPress_Plugins&utm_content=<?php echo $ffpplugin->ffpfolder.'-v'.$ffpplugin->version;?>_adminhelp&utm_medium=textlink&utm_source=<?php echo get_home_url();?>" title="draugiem.lv/lapas fanu lapa" target="_blank"><?php _e('Help?', 'frypepage_widget'); ?></a> <?php _e('(in new window/tab)', 'frypepage_widget'); ?></p>
<?php
	}
}
