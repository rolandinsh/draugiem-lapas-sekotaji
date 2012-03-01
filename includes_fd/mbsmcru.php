<?php

/*  Extras :) 
* @since 0.1.6
* @edited 2011-01-21
*/
if (!function_exists('mediabox_feedlink_ffp')) {
function mediabox_feedlink_ffp(){
	include_once(ABSPATH . WPINC . '/feed.php');
	$mediaboxrss = fetch_feed('http://mediabox.lv/rss-tech.php');
		if (!is_wp_error( $mediaboxrss ) ) : // Checks that the object is created correctly 
    	// Figure out how many total items there are, but limit it to 5. 
    	$maxitems = $mediaboxrss->get_item_quantity(2); 
    	// Build an array of all the items, starting with element 0 (first element).
    	$mediaboxrss_items = $mediaboxrss->get_items(0, $maxitems); 
	endif;

echo'<div style="float:right; display:inline; width:198px;"><a href="http://simplemediacode.com/" title="Visit SimpleMediaCode.com"><img src="'.FRYPEFANPAGEI.'/simple-media-code-logo-web.png" class="alignright" alt="SimpleMediaCode.com"/></a></div>';
echo '<div style="padding: 10px 0 10px; float:left; display:inline;"><a href="http://feeds.feedburner.com/mediaboxlv"><img src="'.get_bloginfo('wpurl').'/wp-includes/images/rss.png" alt="" /> Subscribe via RSS</a><br />';
echo '<img src="'.FRYPEFANPAGEI.'/email_add.png" alt="via email" /> <a href="'.OPTINLVURI1.'?utm_campaign=WordPress_Plugins&utm_content='.FRYPEFANPAGEF.'-v'.FFPVERSION.'_adminhelp&utm_medium=textlink-subscribeviaemail&utm_source='.get_home_url().'">Subscribe via email</a>';
		if(WPLANG!='lv'){ 
		?><br />
        <img src="<?php echo FRYPEFANPAGEI;?>/email_add.png" alt="via email" />
        <a href="<?php echo OPTINLVURI1;?>" rel="nofollow" title="Subscribe to SimpleMediaCode.com newsletter"><strong>Subscribe</strong> to our newsletter</a>
<?php 
		}
echo '
</div>';

echo '<div style="border-bottom: 1px solid #000; clear:both; font:9px Verdana, Geneva, sans-serif; display:block;"><ul><li>';
if(WPLANG=='lv'){
echo 'WordPress mājas lapu izstrāde, WP spraudņu veidošana. Uzzini vairāk: <a href="http://mediabox.lv/pakalpojumi/?utm_campaign=WordPress_Plugins&utm_content='.FRYPEFANPAGEF.'-v'.FFPVERSION.'_adminhelp&utm_medium=textlink-webdevservices&utm_source='.get_home_url().'" title="Mājas lapu izstrādes pakalpojumi">MediaBox.lv pakalpojumi</a>';}
else{
echo 'WordPress homepages, WP plugin and theme development. Find out: <a href="http://simplemediacode.com/services/?utm_campaign=WordPress_Plugins&utm_content='.FRYPEFANPAGEF.'-v'.FFPVERSION.'_adminhelp&utm_medium=textlink-webdevservices&utm_source='.get_home_url().'" title="Website developmet ob WordPress">SimpleMediaCode.com Services</a>';
	} echo'</li></ul></div>';	
?>
<?php echo '<div>';
			if ($maxitems == 0): 
		echo '<p><a href="http://simplemediacode.com/" title="Visit SimpleMediaCode.com">SimpleMediaCode.com</a></p>
			<p><a href="http://mediabox.lv/" title="MediaBox.lv">MediaBox.lv</a></p>
			<p><a href="http://umbrovskis.com/" title="Umbrovskis.com">Umbrovskis.com</a></p>';
			else:
			// Loop through each feed item and display each item as a hyperlink.
				foreach ( $mediaboxrss_items as $item ) : ?>
				<p><a href='<?php echo $item->get_permalink(); ?>' title='<?php echo $item->get_title(). ' ('.$item->get_date('Y-M-d H:i:s').')'; ?>'><?php echo $item->get_title(); ?></a></p>
				<?php endforeach;
			endif;
		echo '</div>';
		
		if(WPLANG=='lv'){
			?>
			<p>Ieskaties <strong><a href="http://mediabox.lv/" title="MediaBox.lv">MediaBox.lv</a></strong></p>
			<?php 
		}else{
			?>
			<p>Visit <strong><a href="http://simplemediacode.com/" title="Visit SimpleMediaCode.com">SimpleMediaCode.com</a></strong></p>
			<?php 	
		} 

		
		// opt-in
		if(WPLANG=='lv'){
		?>
		<div id="mc_embed_signup">
<form action="http://mediabox.us2.list-manage1.com/subscribe/post?u=786b1708223a9b1b96758420c&amp;id=50924cdc57" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
	<label for="mce-EMAIL">Saņem vēl nepublicētos jaunumus e-pastā</label>
	<input type="email" value="<?php echo get_option('admin_email');?>" name="EMAIL" class="email" id="mce-EMAIL" placeholder="e-pasta adrese" required>
	<div class="clear"><input type="submit" value="Abonēt" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
</form>

</div>
		<?php 
		}

}

function mediabox_ffp_add_dashboard_widgets() {
	if(WPLANG=='lv'){ $adminwidnosaukums = 'MediaBox.lv/wordpress';}else{ $adminwidnosaukums = 'SimpleMediaCode.com/wordpress';}
	wp_add_dashboard_widget('mediabox_ffp_dashboard_widget', $adminwidnosaukums, 'mediabox_feedlink_ffp');	
} 

add_action('wp_dashboard_setup', 'mediabox_ffp_add_dashboard_widgets' );
}
