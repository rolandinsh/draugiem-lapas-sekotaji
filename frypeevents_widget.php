<?php
if (!defined('ABSPATH'))
    exit;

if (!function_exists('add_action')) {
    echo "Hi! I'm nice WordPress plugin from Umbrovskis.com, but I am more useful if You are using WordPress. So, don't me call directly!.";
    exit;
}

new MB_FrypeEvents_Plugin;

class MB_FrypeEvents_Plugin extends MB_FrypePage_Plugin
{

    public $eventsversion = '2.1.3';

    public function __construct()
    {
        do_action('meblogfrypeevent_preinit');
        add_shortcode('frypeevent', array($this, 'ffewShortcode'));
        add_shortcode('frypevent', array($this, 'ffewShortcode'));
        add_action('widgets_init', array($this, 'register_widget_ffpeventwidget'));

        do_action('meblogfrypeevent_sufinit');
    }

    public function register_widget_ffpeventwidget()
    {
        register_widget("Frype_EventWidget");
    }

    /*
     * Shortcodes Frype events
     * @since 2.1.1
     * @date 2011-11-24
     */

    public function ffewShortcode($atts)
    {
        extract(shortcode_atts(array(
            'id' => '18368189', // int() = 123456789
            'width' => '300', // px
            'users' => '5', // how many users show?
            'uqid' => '9513s57dsf5f66f852' // any alphanum. MUST be UNIQE per page
                        ), $atts));

        // USAGE / LIETOŠANA
        // [frypeevent id='18368189' width='300' users='5' uqid='951357456852']

        $fefs = "\n\n<!-- Draugiem.lv biznesa lapu sekotāju spraudnis " . $this->eventsversion . " via https://umbrovskis.com  / Pasākumi: $id ($uqid) -->\n";
        $fefs .='<style>#evFansBlock' . $id . $uqid . ' { width:' . $width . 'px;border: 1px solid #c9c9c9; }</style>';
        $fefs .='<div id="evFansBlock' . $id . $uqid . '"></div>';

        $fefs .='<script>';
        $fefs .=<<<EOT
	var fans = new DApi.EvFans( {name:'ev/$id/', count:$users} );
EOT;
        $fefs .="\n" . 'fans.append(\'evFansBlock' . $id . $uqid . '\');</script>';
        $fefs .="\n<!-- Draugiem.lv biznesa lapu sekotāju spraudnis " . $this->eventsversion . " via https://umbrovskis.com  / Pasākumi: $id ($uqid) beigas  -->\n" . '' . "\n";

        return $fefs;
    }

}

/* EVENTS
 * @date 2011-11-07 21:46
 * @since 2.1.1a
 */

/**
 * Frype_EventWidget Class
 */
class Frype_EventWidget extends WP_Widget
{

    /** constructor */
    function __construct()
    {
        parent::WP_Widget('frype_eventwidget', __('Frype Event Widget', 'frypepage_widget'), array('description' => __('Frype Event Widget', 'frypepage_widget')));
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $frypeeventid = apply_filters('widget_frypeeventid', $instance['frypeeventid']);
        $frypeeventcount = apply_filters('widget_frypeeventcount', $instance['frypeeventcount']);
        $frypeeventwwidth = apply_filters('widget_frypeeventwwidth', $instance['frypeeventwwidth']);
        $frypeeventiniqiue = apply_filters('widget_frypeeventiniqiue', $instance['frypeeventiniqiue']);

        echo $before_widget;
        if ($title) {
            echo $before_title . $title . $after_title;
        }


        echo '<!-- Draugiem.lv biznesa lapu sekotāju spraudnis via https://umbrovskis.com  / Event: ' . $frypeeventid . '  -->';
        ?>
        <div id="evFansBlock<?php echo $frypeeventid; ?>"></div>
        <style>#evFansBlock<?php echo $frypeeventid; ?> { width:<?php echo $frypeeventwwidth; ?>px;border: 1px solid #c9c9c9; }</style>
        <script type="text/javascript">
            var fans = new DApi.EvFans({
                name: 'ev/<?php echo $frypeeventid; ?>/',
                count:<?php echo $frypeeventcount; ?>
            });
            fans.append('evFansBlock<?php echo $frypeeventid; ?>');
        </script>
        <?php
        echo '<!-- Draugiem.lv biznesa lapu sekotāju spraudnis ' . FFPVERSION . ' via https://umbrovskis.com  / ' . $frypeeventid . ' beigas  -->';
        echo $after_widget;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['frypeeventid'] = strip_tags($new_instance['frypeeventid']);
        $instance['frypeeventcount'] = strip_tags($new_instance['frypeeventcount']);
        $instance['frypeeventwwidth'] = strip_tags($new_instance['frypeeventwwidth']);

        return $instance;
    }

    /** @see WP_Widget::form */
    function form($eventinstance)
    {
        $eventdefaults = array(
            'title' => __('Draugiem.lv/events', 'frypepage_widget'),
            'frypeeventid' => '',
            'frypeeventwwidth' => 300,
            'frypeeventcount' => 5,
        );
        $instance = wp_parse_args((array) $eventinstance, $eventdefaults);
        ?>

        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Frype Event Widget Title:', 'frypepage_widget'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('frypeeventid'); ?>"><?php _e('Frype Event ID:', 'frypepage_widget'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('frypeeventid'); ?>" name="<?php echo $this->get_field_name('frypeeventid'); ?>" type="text" value="<?php echo esc_attr($instance['frypeeventid']); ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('frypeeventcount'); ?>"><?php _e('How many users to show?', 'frypepage_widget'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('frypeeventcount'); ?>" name="<?php echo $this->get_field_name('frypeeventcount'); ?>" type="text" value="<?php echo esc_attr($instance['frypeeventcount']); ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('frypeeventwwidth'); ?>"><?php _e('Width:', 'frypepage_widget'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('frypeeventwwidth'); ?>" name="<?php echo $this->get_field_name('frypeeventwwidth'); ?>" type="text" value="<?php echo esc_attr($instance['frypeeventwwidth']); ?>" />
        </p>

        <?php
    }

}

// class Frype_EventWidget
// register Frype_EventWidget widget

