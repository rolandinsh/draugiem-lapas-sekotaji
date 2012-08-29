<?php
/**
 * Plugin Name: Draugiem.lv biznesa lapu sekotāju spraudnis
 * Plugin URI: http://darbi.mediabox.lv/draugiem-lvlapas-fanu-wordpress-spraudnis/?utm_source=WPplugin%3Adraugiemlv-lapas-fan-page&utm_medium=wordpressplugin&utm_campaign=FreeWordPressPlugins&utm_content=v-2-0-3
 * Description: Parāda draugiem.lv/lapas lietotājus, to skaitu, logo un iespēju kļūt par lapas fanu, Shows draugiem.lv/lapas users, fan count, logo and possibility to became a fan
 * Version: 2.2.3
 * Stable tag: 2.2.3
 * Requires at least: 2.6
 * Tested up to: 3.4.1
 * Author: Rolands Umbrovskis
 * Author URI: http://umbrovskis.com
 * License: SimpleMediaCode
 * License URI: http://simplemediacode.com/license/gpl/
 */

/*  Copyright 2010  Rolands Umbrovskis (webapp at mediabox dot lv)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Exit if accessed directly (security)
 * jeb parādam mēli :P
 * @since 2.1
 * @todo Ja būs nepieciešamība izveidot dinamisko failu (JS/CSS), šis ir jāpārskata
 */
if (!defined('ABSPATH')) exit;

/**
 * Add function to widgets_init that will load meblog Draugiem Lapas fanu widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'meblogfrypepage_load_widgets' );

define('FFPVERSION','2.2.3');
define('FRYPEFANPAGEF','draugiemlvlapas-fan-page');
define('FRYPEFANPAGED',dirname(__FILE__)); // widget path location @since 0.1.6
define('FRYPEFANPAGEINC',FRYPEFANPAGED.'/includes_fd'); // widget path location @since 2.1.1
define('FRYPEFANPAGEURI',plugin_dir_url(__FILE__)); // widget url location @since 2.1
define('FRYPEFANPAGEI',plugins_url(FRYPEFANPAGEF).'/img'); // Image location @since 0.1.6
define('FRYPEFANPAGEINFO','http://mediabox.lv/wordpress-spraudni/draugiem-lv-biznesa-lapu-fanu-wordpress-spraudnis/'); // Plugin info
if (!defined('DRAUGIEMJSAPI')) {
	$ishttpsurl = (!empty($_SERVER['HTTPS'])) ? "https:" : "http:"; 
	define('DRAUGIEMJSAPI',$ishttpsurl.'//www.draugiem.lv/api/api.js');
} // unified constants across plugins @since 2.2.3
/**
 * Shortname
 * @since 2.0
 */
define('FFPSH','ffpsh');
/**
 * Opt-in email subscribe link
 * dev test 2011-08-15
 * @since 2.1
 */
define('OPTINLVURI1','http://darbi.mediabox.lv/wordpress-jaunumi-e-pasta/'); // fix 2.1.1
define('OPTINENURI1','http://xh.lv/smcnewsletter'); // fix 2.1.1


/**
 * Don't call me BABY (directly)
 * @since 2.1
 * @release 17
 */
if ( !function_exists( 'add_action' ) ) {
	echo "Hi! I'm nice WordPress plugin from Umbrovskis.com, but I am more useful if You are using WordPress. So, don't me call directly!.";
	exit;
}

/**
 * Is not login or register page
 * @since 2.2.2
 */
if ( !function_exists( 'smc_is_login_page' ) ) {
	function smc_is_login_page() {
		return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
	}
}
if ( !function_exists( 'smc_draugiem_say_headinit' ) ) {
	function smc_draugiem_say_headinit() {
		if( !is_admin()&&!smc_is_login_page()){
			wp_register_script('draugiem_api',DRAUGIEMJSAPI,array(),'1.232', false);
			wp_enqueue_script('draugiem_api');
		}
	}    
	add_action('init', 'smc_draugiem_say_headinit');
}


function meblogfrypepage_set_plugin_meta($links, $file) {
	$plugin = plugin_basename(__FILE__);
	// create link
	if ($file == $plugin) {
		return array_merge( $links, array( 

			'<a href="http://atbalsts.mediabox.lv/diskusija/draugiem-lv-biznesa-lapu-wordpress-spraudnis/#new-post">' . __('Support Forum','frypepage_widget') . '</a>',
			'<a href="http://atbalsts.mediabox.lv/temats/ieteikumi/#new-post">' . __('Feature request','frypepage_widget') . '</a>',
			'<a href="http://atbalsts.mediabox.lv/wiki/Draugiem.lv_biznesa_lapu_fanu_Wordpress_spraudnis">' . __('Wiki page','frypepage_widget') . '</a>',
			//'<a href="http://darbi.mediabox.lv/draugiem-lvlapas-fanu-wordpress-spraudnis/">www</a>',
			'<a href="http://umbrovskis.com/ziedo/">' . __('Donate','frypepage_widget') . '</a>'
			// ,'<a href="http://umbrovskis.com/">Umbrovskis.com</a>'
		));
	}
	return $links;
}

add_filter( 'plugin_row_meta', 'meblogfrypepage_set_plugin_meta', 10, 2 );

/**
 * Register our widget.
 * 'MeblogFrypePage_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function meblogfrypepage_load_widgets() {
	register_widget( 'MeblogFrypePage_Widget' );
}

/**
 * Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.
 *
 * @since 0.1
 * @version 0.2
 * @since 0.1.8
 * @date 2011-01-21
*/
load_plugin_textdomain( 'frypepage_widget', false, dirname(plugin_basename(__FILE__)) . '/lang/' ); 

// Fun starts here
class MeblogFrypePage_Widget extends WP_Widget {
/**
 * Widget setup.
*/
	function MeblogFrypePage_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'meblogfrypepage', 'description' => __('Shows draugiem.lv/lapas users', 'frypepage_widget') );
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 200, 'id_base' => 'meblogfrypepage-widget' );
		/* Create the widget. */
		$this->WP_Widget( 'meblogfrypepage-widget', __('Draugiem Lapas Widget', 'frypepage_widget'), $widget_ops, $control_ops );
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

		
		$widgetid = $args['widget_id']; /* magic! *** unique id */
		if(!$widgetid) $widgetid=987654;
		if(!$wheight){$wheight = '230';}
		// Maybe remove in next release!
		if(!is_numeric($show_pageaboutlenght)||!$show_pageaboutlenght){$show_pageaboutlenght='200';}
		/* Before widget (defined by themes). */
		echo $before_widget;
echo "\n\n<!-- Draugiem.lv biznesa lapu sekotāju spraudnis ".FFPVERSION." via http://umbrovskis.com | MediaBox.lv | SimpleMediaCode.com -->\n".''."\n";
		/* Display the widget title if one was input (before and after defined by themes). */
		if ($title)	echo $before_title . $title . $after_title;?>
<div id="fansblock<?php echo $widgetid;?>"></div>
<style type="text/css">#fansblock<?php echo $widgetid;?>{width:<?php echo $wwidth;?>px; height:<?php echo $wheight;?>px;border: 1px solid #c9c9c9; }#fansblock<?php echo $widgetid;?> div {overflow:hidden; height:100%; min-height:264px;}#fansblock<?php echo $widgetid;?> iframe{ overflow:hidden; height:100%; min-height:264px;}.dfoot{margin-top:4px;}</style>

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
<div class="dfoot"></div>
<?php 

/* After widget (defined by themes). */
		echo $after_widget;
echo "\n<!-- Draugiem.lv biznesa lapu sekotāju spraudnis ".FFPVERSION."  beidzas footer -->\n\n";
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

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
		'title' => __('Draugiem.lv/lapas', 'frypepage_widget'),
		'name' => __('umbrovskiscom', 'frypepage_widget'),
		'wwidth' => '240',
		'wheight' => '234',
		'show_usersfrp' => '0',
		'show_cssfrp' =>WP_PLUGIN_URL.'/'.FRYPEFANPAGEF.'/js/widget.css',
		'show_aboutpagelenght'=> '1000',
		'show_saycount' => '3',
		'show_saytexts'=>'0'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

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
        <p>Šis darbs ir licencēts ar GPL.<br />
        <img src="<?php echo FRYPEFANPAGEI;?>/creative-commons-by-nc-sa-88x31.png" width="88" height="31" alt="" /><br />
        <span class="small">Atļaujas ārpus šīs licences ietvariem var iegūt <a href="http://umbrovskis.lv/autortiesibas/" target="_blank" title="Autortiesības">umbrovskis.lv</a> [&copy; 1982-<?php echo date('Y')+70;?>]</span></p>
        
        
<div  style="display:none">
<?php 
/**
 * @todo sekot līdzi vai parametri netiks atjaunoti, ja nē, tad dzēsīšu ārā
 * @date 2011-08-12
*/
 ?>
		<!-- Show CSS? Input -->
		<p><label for="<?php echo $this->get_field_id( 'show_cssfrp' ); ?>"><?php _e('StyleSheet (CSS) URL', 'frypepage_widget'); ?></label><br />
        <span class="small"><?php _e('Theme (CSS) URL', 'frypepage_widget'); ?>: <strong><?php bloginfo('stylesheet_url'); ?></strong></span><br />
        <span class="small"><strong><?php echo WP_PLUGIN_URL.'/'.FRYPEFANPAGEF.'/js/widget.css'; ?></strong></span><br />
        	<input id="<?php echo $this->get_field_id( 'show_cssfrp' ); ?>" name="<?php echo $this->get_field_name( 'show_cssfrp' ); ?>" value="<?php echo $instance['show_cssfrp']; ?>" style="width:100%;" />
		</p>
		<!-- Show lenght? Input -->
		<p><label for="<?php echo $this->get_field_id( 'show_aboutpagelenght' ); ?>"><?php _e('Lenght of page description', 'frypepage_widget'); ?></label><br />
        	<input id="<?php echo $this->get_field_id( 'show_aboutpagelenght' ); ?>" name="<?php echo $this->get_field_name( 'show_aboutpagelenght' ); ?>" value="<?php echo $instance['show_aboutpagelenght']; ?>" style="width:100%;" />
		</p>

</div>
		
        <!-- HELP link-->
        <p><img src="<?php echo WP_PLUGIN_URL.'/'.FRYPEFANPAGEF.'/img/'; /* @todo FRYPEFANPAGEI */ ?>help.png" width="16" height="16" alt="" /> <a href="<?php echo FRYPEFANPAGEINFO;?>?utm_campaign=WordPress_Plugins&utm_content=<?php echo FRYPEFANPAGEF.'-v'.FFPVERSION;?>_adminhelp&utm_medium=textlink&utm_source=<?php echo get_home_url();?>" title="draugiem.lv/lapas fanu lapa" target="_blank"><?php _e('Help?', 'frypepage_widget'); ?></a> <?php _e('(in new window/tab)', 'frypepage_widget'); ?></p>
<?php
	}
}
/*
* Unregister widget. Just in case if something wasn't cleaned.
* @since 0.1.8
* @date 2011-03-13
*/
register_deactivation_hook( __FILE__, 'ffp_deactivate_plugin' );
function ffp_deactivate_plugin(){unregister_widget( 'MeblogFrypePage_Widget' );}


/*
* Shortcodes
* @since 2.1
* @date 2011-09-15
*/

function ffp_shortcode($atts){
	extract(shortcode_atts(array(
	'name'		=>	'umbrovskiscom', // page name (without http://)
	'width'		=>	'300',
	'height'		=>	'230',
	'users'		=>	'0', // how many users show?
	'say'		=>	'0', // show = 1; hide=0
	'saytext'	=>	'0', // count
	'fwid'		=>	'951357456852' // any alphanum. MUST be UNIQE per page
	), $atts));
// ------------------

$fwshort = "\n\n<!-- Draugiem.lv biznesa lapu sekotāju spraudnis ".FFPVERSION." via http://umbrovskis.com  / $fwid  -->\n";
$fwshort .='<style>#fansblock'.$fwid.'{width:'.$width.'px; height:'.$height.'px;}#fansblock'.$fwid.' div{ overflow:hidden; height:100%;}#fansblock'.$fwid.' iframe{ overflow:hidden; height:100%; min-height:264px;}</style>'; 
$fwshort .='<div id="fansblock'.$fwid.'"></div>';
	
	if($users==0): 
		$showfanss = '0';
		else:
		$showfanss = '1';
	endif;
	$ffps_name = mb_strtolower($name, 'UTF-8');
	$ffp_fanbid='fansblock'.$fwid;

$fwshort .='<script>';
$fwshort .=<<<EOT
var fans2 = new DApi.BizFans({name:'$ffps_name',showFans:$showfanss, count:$users, showSay:$say, saycount:$saytext });
EOT;
$fwshort .='fans2.append(\''.$ffp_fanbid.'\');</script>';
$fwshort .="\n<!-- Draugiem.lv biznesa lapu sekotāju spraudnis ".FFPVERSION." via http://umbrovskis.com  / $fwid beigas  -->\n".''."\n";
// ------------------

return $fwshort;

}
add_shortcode('frypefans', 'ffp_shortcode');

/*
* @since 0.1.7
* @date 2010-10-02
* @edited 2010-11-22
*/
function fryped_head_ffp(){
	echo "<!-- via ( ".FRYPEFANPAGEF." / ".FRYPEFANPAGEINFO." )--><script type=\"text/javascript\">function dfp_DraugiemSay(titlePrefix ){window.open('http://www.draugiem.lv/say/ext/add.php?title=' + encodeURIComponent(document.title)+'&link=' + encodeURIComponent(window.location) + (titlePrefix ? '&titlePrefix=' + encodeURIComponent( titlePrefix ) : '' ),'','location=1,status=1,scrollbars=0,resizable=0,width=530,height=400'); return false;}</script><!-- end via ( ".FRYPEFANPAGEF." / ".FRYPEFANPAGEINFO." )-->";
	}
// 2010-11-22 All javascripts in footer :)
// Maybe remove? @since 2.0
// add_filter('wp_footer', 'fryped_head_ffp',20);


// --------------------------------------------------------------------------------
/* EVENTS
 * @date 2011-11-07 21:46
 * @since 2.1.1a
*/

/**
 * Frype_EventWidget Class
 */
class Frype_EventWidget extends WP_Widget {
	/** constructor */
	function __construct() {
		parent::WP_Widget( 'frype_eventwidget', __('Frype Event Widget','frypepage_widget'), array( 'description' => __('Frype Event Widget','frypepage_widget') ) );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$frypeeventid = apply_filters( 'widget_frypeeventid', $instance['frypeeventid'] );
		$frypeeventcount = apply_filters( 'widget_frypeeventcount', $instance['frypeeventcount'] );
		$frypeeventwwidth = apply_filters( 'widget_frypeeventwwidth', $instance['frypeeventwwidth'] );
		
		echo $before_widget;
		if ( $title ) { echo $before_title . $title . $after_title;}
		
/*<!-- mazā poga
<div id="eventFanButton<?php echo $frypeeventid;?>"></div>
<script type="text/javascript">
var e = new DApi.Events(<?php echo $frypeeventid;?>);
e.append('eventFanButton<?php echo $frypeeventid;?>');
</script>
-->
*/

echo '<!-- Draugiem.lv biznesa lapu sekotāju spraudnis '.FFPVERSION.' via http://umbrovskis.com  / Event: '.$frypeeventid.'  -->';
?>
<div id="evFansBlock<?php echo $frypeeventid;?>"></div>
<style>#evFansBlock<?php echo $frypeeventid;?> { width:<?php echo $frypeeventwwidth; ?>px;border: 1px solid #c9c9c9; }</style>
<script type="text/javascript">
var fans = new DApi.EvFans( {
	name:'ev/<?php echo $frypeeventid;?>/',
	count:<?php echo $frypeeventcount; ?>
} );
fans.append( 'evFansBlock<?php echo $frypeeventid;?>' );
</script>
<?php 
echo '<!-- Draugiem.lv biznesa lapu sekotāju spraudnis '.FFPVERSION.' via http://umbrovskis.com  / '.$frypeeventid.' beigas  -->';
		echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['frypeeventid'] = strip_tags($new_instance['frypeeventid']);
		$instance['frypeeventcount'] = strip_tags($new_instance['frypeeventcount']);
		$instance['frypeeventwwidth'] = strip_tags($new_instance['frypeeventwwidth']);

		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $eventinstance ) {
		$eventdefaults = array(
			'title' => __('Draugiem.lv/events', 'frypepage_widget'),
			'frypeeventid' => '',
			'frypeeventwwidth' => 300,
			'frypeeventcount' => 5,
		);
		$instance = wp_parse_args( (array) $eventinstance, $eventdefaults );

		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Frype Event Widget Title:', 'frypepage_widget'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance[ 'title' ] ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('frypeeventid'); ?>"><?php _e('Frype Event ID:', 'frypepage_widget'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('frypeeventid'); ?>" name="<?php echo $this->get_field_name('frypeeventid'); ?>" type="text" value="<?php echo esc_attr( $instance[ 'frypeeventid' ] ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('frypeeventcount'); ?>"><?php _e('How many users to show?', 'frypepage_widget'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('frypeeventcount'); ?>" name="<?php echo $this->get_field_name('frypeeventcount'); ?>" type="text" value="<?php echo esc_attr( $instance[ 'frypeeventcount' ] ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('frypeeventwwidth'); ?>"><?php _e('Width:', 'frypepage_widget'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('frypeeventwwidth'); ?>" name="<?php echo $this->get_field_name('frypeeventwwidth'); ?>" type="text" value="<?php echo esc_attr( $instance[ 'frypeeventwwidth' ] ); ?>" />
		</p>
        
        
		<?php 
	}

} // class Frype_EventWidget
// register Frype_EventWidget widget
add_action( 'widgets_init', create_function( '', 'register_widget("Frype_EventWidget");' ) );
/*
* Shortcodes Frype events
* @since 2.1.1
* @date 2011-11-24
*/

function ffew_shortcode($atts){
	extract(shortcode_atts(array(
	'id'		=>	'18368189', // int() = 123456789
	'width'		=>	'300', // px
	'users'		=>	'5', // how many users show?
	'uqid'		=>	'9513s57dsf5f66f852' // any alphanum. MUST be UNIQE per page
	), $atts));

// USAGE / LIETOŠANA
// [frypeevent id='18368189' width='300' users='5' uqid='951357456852']

$fefs = "\n\n<!-- Draugiem.lv biznesa lapu sekotāju spraudnis ".FFPVERSION." via http://umbrovskis.com  / Pasākumi: $id ($uqid) -->\n";
$fefs .='<style>#evFansBlock'.$id.$uqid.' { width:'.$width.'px;border: 1px solid #c9c9c9; }</style>'; 
$fefs .='<div id="evFansBlock'.$id.$uqid.'"></div>';

$fefs .='<script>';
$fefs .=<<<EOT
var fans = new DApi.EvFans( {name:'ev/$id/',	count:$users} );
EOT;
$fefs .="\n".'fans.append(\'evFansBlock'.$id.$uqid.'\');</script>';
$fefs .="\n<!-- Draugiem.lv biznesa lapu sekotāju spraudnis ".FFPVERSION." via http://umbrovskis.com  / Pasākumi: $id ($uqid) beigas  -->\n".''."\n";

return $fefs;

}
add_shortcode('frypeevent', 'ffew_shortcode');
add_shortcode('frypevent', 'ffew_shortcode');

include_once(FRYPEFANPAGEINC.'/mbsmcru.php');