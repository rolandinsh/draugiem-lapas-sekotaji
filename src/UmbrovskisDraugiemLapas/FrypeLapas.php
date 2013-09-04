<?php
namespace UmbrovskisDraugiemLapas;
//new FrypeLapas;

class FrypeLapas{
	public $version = '3.6.0';
	public $frypiapiv = '2277';
	public $relx = 201309041649;
	public $draugiemjsapi ='//www.draugiem.lv/api/api.js';
	public $ffpfolder ='draugiemlvlapas-fan-page';
	public $ffpinfo ='http://mediabox.lv/wordpress-spraudni/draugiem-lv-biznesa-lapu-fanu-wordpress-spraudnis/';

	const FFPSH = 'ffpsh';
	const OPTINLVURI1 = 'http://darbi.mediabox.lv/wordpress-jaunumi-e-pasta/';
	const OPTINENURI1 = 'http://xh.lv/smcnewsletter';

	function __construct() {
		do_action( 'meblogfrypepage_preinit' );
		add_action( 'widgets_init', array( $this, 'load_widgets' ));
		add_action( 'init', array( $this, 'inits' ));
		add_action( 'plugin_row_meta', array( $this, 'set_plugin_meta' ), 10, 2);
		add_action( 'frypefans', array( $this, 'ffp_shortcode' ));
		add_action( 'wp_head', array( $this, 'ffp_headgen' ));
		
		// Load a text domain
		load_plugin_textdomain( 'frypepage_widget', false, dirname(plugin_basename(__FILE__)) . '/lang/' ); 
		do_action( 'meblogfrypepage_sufinit' );
	}

/**
 * Register our widget.
 * 'MeblogFrypePage_Widget' is the widget class used below.
 *
 * @since 0.1
 */
	public function load_widgets(){
		register_widget( 'MeblogFrypePage_Widget' );
	}
/**
 * meta genarator
 *
 * @since 3.5.0
*/
	public function ffp_headgen(){
		echo "\n".'<meta name="generator" content="http://mediabox.lv/wordpress-spraudni/?utm_source='.$this->ffpfolder.'-'.$this->version.'" />'."\n";	
	}

	public function inits(){
		$ishttpsurl = is_ssl() ? 'https:' : 'http:';
		 
		if( !is_admin()&&!smc_is_login_page()){
			wp_register_script('draugiem_api',$ishttpsurl.$this->draugiemjsapi,array(),$this->frypiapiv, false);
			wp_enqueue_script('draugiem_api');
			wp_register_style( 'draugiem_sekotaji', WP_PLUGIN_URL.'/'.$this->ffpfolder.'/js/draugiem-lapas-sekotaji.css', array(), $this->version, 'all' );
			wp_enqueue_style('draugiem_sekotaji');
		}
	}
/**
 * Set plugin meta information.
 *
 * @since 0.1
*/
	function set_plugin_meta($links, $file) {
		$plugin = plugin_basename(__FILE__);
		if ($file == $plugin) {
			return array_merge( $links, array( 
				//'<a href="http://atbalsts.mediabox.lv/temats/svarigas-3-4-0-versijas-izmainas/" target="_blank"><span style="color: #c00; margin:0; border: 1px solid #E6DB55; padding: 2px 3px; background-color:#FFFFE0;border-radius: 3px;">' . __('Important!!!','frypepage_widget') . '</span></a>',
				'<a href="http://atbalsts.mediabox.lv/?utm_source='.get_home_url().'&utm_medium=wordpressplugin&utm_campaign=FreeWordPressPlugins&utm_content=MeblogFrypePage_Widget-'.$this->version.'">' . __('Support','frypepage_widget') . '</a>',
				//'<a href="http://atbalsts.mediabox.lv/temats/ieteikumi/#new-post">' . __('Feature request','frypepage_widget') . '</a>',
				//'<a href="http://atbalsts.mediabox.lv/wiki/Draugiem.lv_biznesa_lapu_fanu_Wordpress_spraudnis">' . __('Wiki page','frypepage_widget') . '</a>',
				'<a href="http://umbrovskis.com/ziedo/">' . __('Donate','frypepage_widget') . '</a>'
			));
		}
		return $links;
	}

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
		$fwshort = "\n\n<!-- Draugiem.lv biznesa lapu sekotāju spraudnis via http://Umbrovskis.com | http://MediaBox.lv | http://SimpleMediaCode.com / $fwid  -->\n";
		$fwshort .='<style>#fansblock'.$fwid.'{width:'.$width.'px; height:'.$height.'px; overflow: hidden;}#fansblock'.$fwid.' div{ overflow:hidden; height:100%;}#fansblock'.$fwid.' iframe{ overflow:hidden; height:100%; min-height:264px;}</style>'; 
		$fwshort .='<div id="fansblock'.$fwid.'"></div>';
		
		if($users==0): 
			$showfanss = '0';
			else:
			$showfanss = '1';
		endif;
		$ffps_name = mb_strtolower($name, 'UTF-8');
		$ffp_fanbid='fansblock'.$fwid;
	
		$fwshort .='<script>';
/*
@todo cleanup/ optimize
*/
		$fwshort .=<<<EOT
var fans2 = new DApi.BizFans({name:'$ffps_name',showFans:$showfanss, count:$users, showSay:$say, saycount:$saytext });
EOT;
		$fwshort .='fans2.append(\''.$ffp_fanbid.'\');</script>';
		$fwshort .="\n<!-- Draugiem.lv biznesa lapu sekotāju spraudnis via http://Umbrovskis.com | http://MediaBox.lv | http://SimpleMediaCode.com / $fwid beigas  -->\n".''."\n";
	// ------------------
	
		return $fwshort;

	}

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

/*
* Unregister widget. Just in case if something wasn't cleaned.
* @since 0.1.8
* @date 2011-03-13
*/
register_deactivation_hook( __FILE__, 'ffp_deactivate_plugin' );
function ffp_deactivate_plugin(){unregister_widget( 'MeblogFrypePage_Widget' );}


include_once(dirname(__FILE__).'/frypepage_widget.php');
include_once(dirname(__FILE__).'/frypeevents_widget.php');