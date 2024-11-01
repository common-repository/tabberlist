<?php
/*
Plugin Name: TabberList
Plugin URI: http://line-in.co.uk/plugins/tabberlist
Description: A skinnable plugin by <a href='http://line-in.co.uk'>Simon Fairbairn</a> that makes it easy to manage categorised lists with funky tabs, based around Sean Catchpole's awesome <a href='http://www.sunsean.com/idTabs/'>idTabs</a>.
Version: 1.0.4
Author: Simon Fairbairn
Author URI: http://line-in.co.uk
*/
/*  
	Copyright 2009  Simon Fairbairn  (email : contact@simonfairbairn.com)

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

include('classes/tabberlist-class.php');
$tabberlist = new tabberlistclass;

if (!function_exists('tabberlist_display')) {

	function tabberlist_display($atts) {

		// Setup variables
		global $tabberlist;
		static $count;
		$combinedTotal = 0;
		if (!isset($count)) {
			$count = 0;
		}
		$style = get_option('tabberStyle');
		$totalTitle = get_option('tabberTotalTitle');
		$style = 0;
		$showLink = get_option('tabberShowLink');
		
		// Get shortcode attributes
		extract(shortcode_atts(array( 'cat' => '', 'title' => '' ), $atts));
		
		if ($cat == "") {
			$cats = $tabberlist->getTabberListCat('','');
		} else {
			$cat = "'".$cat."'";
			$cat = str_replace(",", "','", $cat);
			$cats = $tabberlist->getTabberListCat('id',$cat);
		}
		if ($title == "") {
			$tabberTitle = get_option('tabberTitle');
		} else {
			$tabberTitle = $title;
			
		}
		
		
		$maxCats = sizeof( $cats );
		// Begin build - Outer table and then category container
		$outerDivs = "<div class='tabberTable' id='tabberTable-$count'><div class='tabberTableTabs tabberTableTabs-$count'>";
		$outerDivsClose = "</div></div>";
		
		if ($tabberTitle != "") {
			$listTitle = "<h2 class='tabberTitle'>".stripslashes($tabberTitle)."</h2>";
		}
		


		$j = 1;
		if (sizeof($cats) > 0) {
			foreach ( $cats as $id => $category ) {
				$itemsContainer[$id] .= "<div id='category-{$category['id']}-$count' class='tabberlist-category-div'>";
				$itemsContainerClose[$id] .= "</div>";
				$itemsTitles[$id] .= "<h3>".stripslashes($category['category'])."</h3>";
				if ($category['description'] != "") {
					$itemsTitles[$id] .= "<h4>".stripslashes($category['description'])."</h4>";
				}

				$catId = $category['id'];
				$items = $tabberlist->getTabberlistData('cat_id',$catId);
				$itemList[$id] .= "<ul class='tabberlist-category-ul tabberlist-category-ul-{$category['id']}'>";
				$k = 0;
				if ($items != "") {
					$maxItems = sizeof( $items );
					for ( $i = 0; $i < $maxItems; $i++ ) {
						$k ++;
						if ( $i == 0 ) {
							$listClass = " tabberlist-first-item";
						} elseif ( $i == $maxItems - 1) {
							$listClass = " tabberlist-last-item";
						} else { 
							$listClass = "";
						}
						
						if ( $items[$i]['new'] == "on" ) {
							$new = " tabberlist-new-item";
						}
						if ( $items[$i]['deleted'] == "on" ) {
							$deleted = " tabberlist-deleted-item";
							$k--;
						}
						$itemList[$id] .= "<li id='tabberlist-item-{$items[$i]['id']}-$count' class='tabberlist-item$new$deleted$listClass'>";
						if ( $items[$i]['url'] != "") {
							$itemList[$id] .= "<a href='{$items[$i]['url']}'>";
						
						}
						$itemList[$id] .= stripslashes($items[$i]['item']);
						if ( $items[$i]['url'] != "") {
							$itemList[$id] .= "</a>";
						}
						$itemList[$id] .= "</li>";
						unset($new);
						unset($deleted);
					}
				}
				$itemList[$id] .= "</ul>";
				if ($category['counted'] == "on") {
					$combinedTotal = $combinedTotal + $k;
				}
				$firstDigit = substr($k, 0, 1);
				
				if ( $k > 9 ) {
					$secondDigit = substr($k, 1, 1);
					$secondTotal = "<strong class='tabberCatTotal tabberCatTotal-$secondDigit'><span>$secondDigit</span></strong>";
				}
				if ( $k > 99 ) {
					$thirdDigit = substr($k, 2, 1);
					$thirdTotal = "<strong class='tabberCatTotal tabberCatTotal-$thirdDigit'><span>$thirdDigit</span></strong>";
				}
				$k = 0;
				
				$catTotal = "<strong class='tabberCatTotal tabberCatTotal-$firstDigit'><span>$firstDigit</span></strong>$secondTotal$thirdTotal";
				unset($secondTotal);
				unset($thirdTotal);
				
				$finalCatTotal[$id] = "<div class='tabberlist-category-total tabberlist-category-{$category['id']}-total'>";
				if ($category['counted'] == "on") {
					$finalCatTotal[$id] .= "Category total: $catTotal";
				} else {
					$finalCatTotal[$id] .= "Not Counted";
				}
				$finalCatTotal[$id] .= "</div>";
				$noscript[$id] = "<noscript><div class='tabberlist-noscript'><a href='#tabberCats-$count'>Back to top</a></div></noscript>";
			}
		}
		
		
		$j = -1;
		$categoryListOpen .= "<ul class='tabberCats' id='tabberCats-$count'>";
		for ( $k = 0; $k < $maxCats; $k++ ) {
			if ( $k == 0 ) {
				$newClass = " class='tabberlist-first-category' ";
			} elseif ( $k == $maxCats - 1 ) {
				$newClass = " class='tabberlist-last-category' ";
			} else { 
				$newClass = "";
			}
			$categoryListLiOpen[$k] .= "<li$newClass><a href='#category-{$cats[$k]['id']}-$count'>".stripslashes($cats[$k]['category'])."</a>";
			
			$categoryListLiClose[$k] .= "</li>";
			
			$link[$j] = "<p class='tabberNext'><a href='#tabberCats-$count' class='tabberNextTab'>Next Category</a></p>";
			$j++;
		}
		$categoryListClose .= "</ul>";
		if ($maxCats > 1) {
			$itemOuterContainer .= "<div class='tabberlist-items-container'>";
		} else {
			$itemOuterContainer .= "<div class='tabberlist-items-container-single'>";
		}
		$itemOuterContainerClose = "</div>";
		

		
		$firstCombined = substr($combinedTotal, 0, 1);
		if ( $combinedTotal > 9 ) {
			$secondCombined = substr($combinedTotal, 1, 1);
			$secondCombinedTotal = "<strong class='tabberTotal tabberTotal-$secondCombined'><span>$secondCombined</span></strong>";
		}
		if ( $combinedTotal > 99 ) {
			$thirdCombined = substr($combinedTotal, 2, 1);
			$thirdCombinedTotal = "<strong class='tabberTotal tabberTotal-$thirdCombined'><span>$thirdCombined</span></strong>";
		}
		if ($maxCats == 1) {
			$single = "-single";
		}
		$total = "<strong class='tabberTotal tabberTotal-$firstCombined'><span>$firstCombined</span></strong>$secondCombinedTotal$thirdCombinedTotal";
		$grandTotal = "<div class='tabberlist-combined-total$single tabberTable-$count-total'>".stripslashes($totalTitle).": $total</div>";
		
		if ($showLink == "on") {
			$logo .= "<div class='tabberlist-logo-container'><a href='http://line-in.co.uk/plugins/tabberlist' class='tabberlist-logo'><span>TabberList</span></a></div>";
		}

		// Construct the output 
		$return .= $outerDivs;
		
		if ($maxCats > 0) {
			$return .= $categoryListOpen;
			foreach ( $categoryListLiOpen as $key => $value ) {
					$return .= $categoryListLiOpen[$key];
					$return .= $categoryListLiClose[$key];
				}
			
			$return .= $categoryListClose;
			}		
			$return .= $listTitle;
		$return .= $grandTotal;

			$return .= $itemOuterContainer;
			if(sizeof($itemsContainer) > 0) {
				foreach( $itemsContainer as $key => $value ) {
					$return .= $itemsContainer[$key];
					$return .=  $itemsTitles[$key];
								$return .= $finalCatTotal[$key];

					$return .= $itemList[$key];
					$return .= $link[$key];

					$return .= $noscript[$key];
					$return .= $itemsContainerClose[$key];
				}
			}
			$return .= $itemOuterContainerClose;
			
		$return .= $logo;
		$return .= $outerDivsClose;
		
		
		$count++;
		return $return;
	}
}
add_shortcode('tabberlist', 'tabberlist_display');


if (!function_exists('tabberlist_install')) {
	function tabberlist_install() {
		global $wpdb;
		$tabberlist_version = "0.7.0";
		$hasInstalled = get_option("tabberlist_version");
		$list_table_name = $wpdb->prefix . "tabberlist";
		$category_table_name = $wpdb->prefix . "tabberlistcat";
		if ($hasInstalled != $tabberlist_version) {
			if( $wpdb->get_var("show tables like '$list_table_name'") != $list_table_name) {
				$sql = "CREATE TABLE " . $list_table_name . " (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					dateAdded VARCHAR(15),
					dateDeleted VARCHAR(15),
					item tinytext NOT NULL,
					url tinytext NOT NULL,
					new VARCHAR(10) NOT NULL,
					deleted VARCHAR(10) NOT NULL,
					cat_id int(5) NOT NULL,
					UNIQUE KEY id (id)
				);";
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
				add_option("tabberlist_version", $tabberlist_version);
			} 
			if ($wpdb->get_var("show tables like '$category_table_name'") != $category_table_name) {
				$sql = "CREATE TABLE " . $category_table_name . " (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					category tinytext NOT NULL,
					description tinytext,
					counted VARCHAR(10) NOT NULL,
					UNIQUE KEY id (id)
				);";
				dbDelta($sql);
			}
			
			$dir = WP_PLUGIN_DIR . "/tabberlist/tabberlistDefaults.php";
			if ( file_exists($dir) ) {
				include($dir);
				$checkThis = get_option('tabberDefaults');
				if ($checkThis != "hasRun") {
					installDefaults();
				}
				add_option('tabberDefaults', 'hasRun');
			} else {
				$welcome_name = "Congratulations, you just completed the installation!";
				$insert = "INSERT INTO " . $list_table_name .
						" (dateAdded, item, new, cat_id) " .
						"VALUES ('" . time() . "','" . $wpdb->escape($welcome_name) . "','', '1')";
				$results = $wpdb->query( $insert );
				$insert = "INSERT INTO " . $category_table_name .
						" (category, counted) ".
						" VALUES ('Category 1', 'on')";
				$results = $wpdb->query( $insert );
			}
			
		}
		
		
		$tabberSkins[0] = "default";
		add_option('tabberSkin', '0');
		add_option('tabberSkinName', $tabberSkins);
		add_option('tabberTotalTitle', "Combined Total");
		add_option('tabberTitle', "My TabberList");
		add_option('tabberShowLink', "");
		
	}
}

if (!function_exists('tabberlist_uninstall') ) {
	function tabberlist_uninstall() {
	}
}

if (!function_exists('tabberlist_menu')) {
	function tabberlist_menu()
	{
		include 'tabberlist-admin.php';
	}
 }

if (!function_exists('tabberlist_actions') ) {
	function tabberlist_actions()
	{
		add_options_page("TabberList", "TabberList", 1, "TabberList", "tabberlist_menu");
	}
}
if (!function_exists('tabberlist_skin_styles') ) {

	function tabberlist_skin_styles() { 
		$skin = get_option('tabberSkin'); 
		$skinName = get_option('tabberSkinName');
		$directory = $skinName[$skin];
		wp_register_style('tabberlist-skin-style', WP_PLUGIN_URL . "/tabberlist/skins/$directory/skin.css");
		wp_enqueue_style('tabberlist-skin-style');
		wp_enqueue_script('jquery');
		wp_enqueue_script('tabberlist-skin-js', WP_PLUGIN_URL . "/tabberlist/skins/$directory/skin.js");
	}
 }
 
if (!function_exists('tabberlist_add_styles') ) {
	function tabberlist_add_styles() { 
		wp_register_style('tabberlist-stylesheet', WP_PLUGIN_URL . '/tabberlist/css/main.css');
		wp_enqueue_style('tabberlist-stylesheet');
		wp_enqueue_script('tabberlist-js', WP_PLUGIN_URL . '/tabberlist/js/tabberlist.js');
	}
}
if (!function_exists('tabberlist_add_styles_ie') ) {
	function tabberlist_add_styles_ie() { 
		$skin = get_option('tabberSkin'); 
		$skinName = get_option('tabberSkinName');
		$directory = $skinName[$skin];
		echo "<!--[if lte IE 6]>";
		echo "<link rel='stylesheet' id='tabberlist-skin-style-css-ie'  href='".WP_PLUGIN_URL . "/tabberlist/skins/$directory/ie.css' type='text/css' media='all' />";

		echo "<![endif]-->";

	}
}
add_action('init', 'tabberlist_skin_styles');
add_action('wp_head', 'tabberlist_add_styles_ie');
add_action('admin_init', 'tabberlist_add_styles');
add_action('admin_menu', 'tabberlist_actions');
register_activation_hook(__FILE__,'tabberlist_install');
register_deactivation_hook('tabberlist/tabberlist-main.php', 'tabberlist_uninstall');

?>