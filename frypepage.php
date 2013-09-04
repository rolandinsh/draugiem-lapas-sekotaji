<?php
/**
 * Plugin Name: Draugiem.lv biznesa lapu sekotāju spraudnis
 * Plugin URI: http://darbi.mediabox.lv/draugiem-lvlapas-fanu-wordpress-spraudnis/?utm_source=WPplugin%3Adraugiemlv-lapas-fan-page&utm_medium=wordpressplugin&utm_campaign=FreeWordPressPlugins&utm_content=v-3-5-1
 * Description: Parāda draugiem.lv/lapas lietotājus, to skaitu, logo un iespēju kļūt par lapas fanu, Shows draugiem.lv/lapas users, fan count, logo and possibility to became a fan
 * Version: 3.6.0
 * Stable tag: 3.5.1
 * Requires at least: 3.3
 * Tested up to: 3.6
 * Author: Rolands Umbrovskis
 * Author URI: http://umbrovskis.com
 * License: SimpleMediaCode
 * License URI: http://simplemediacode.com/license/gpl/
 */

/*  
	Copyright 2010  Rolands Umbrovskis (info at mediabox dot lv)

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
 * Don't call me BABY (directly)
 * @since 2.1
 * @release 17
 */
if ( !function_exists( 'add_action' ) ) {
	echo "Hi! I'm nice WordPress plugin from Umbrovskis.com, but I am more useful if You are using WordPress. So, don't me call directly!.";
	exit;
}


require 'vendor/autoload.php';

new \UmbrovskisDraugiemLapas\FrypeLapas();
//$draugiemwpapp = new \UmbrovskisDraugiemLapas\FrypeLapas();
//$draugiemwpapp->init();
