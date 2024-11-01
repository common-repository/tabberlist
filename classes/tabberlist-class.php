<?php
if ( !class_exists('tabberlistclass') ) {
	class tabberlistclass {
		public $tabberlistcat = array();
		public $tabberSkins = array();
		public $tabberStyles = array();
		public $currentSkin;
		public $currentStyle;
		public $title;
		public $totalTitle;
		
		function __construct() {
			$this->get_skins();
			$this->getStyles();
		}
		
		function installDefaults() {
		
		}
		
		function get_skins() {
			$dirContents = scandir(WP_PLUGIN_DIR."/tabberlist/skins/");
			$i = 0;
			foreach ( $dirContents as $id => $directory ) {
				$path_parts = pathinfo($directory);
				$path_parts['filename'];
				if ( $path_parts['extension'] == "" && $directory != "." && $directory != ".." ) {
					$this->tabberSkins[$i] = $directory;
					$i++;
				}
			}
			update_option('tabberSkinName', $this->tabberSkins);
			
		}
		function getTitle() {
		$this->title = get_option('tabberTitle');
		$this->totalTitle = get_option('tabberTotalTitle');
		$tabberShowLink = get_option('tabberShowLink');
		echo "<table class='form-table'>";
		echo "<tr valign='top'>";
		echo "<th scope='row'><label for='tabberTitle'>List title</label></th>";
		echo "<td><input type='text' name='tabberTitle' size='60' id='tabberTitle' value=\"".stripslashes(htmlentities($this->title))."\"  />";
		echo "</tr><tr scope='row'>";
		echo "<th scope='row'><label for='tabberTotalTitle'>Grand total title</label></th>";
		echo "<td><input type='text' name='tabberTotalTitle' size='60' id='tabberTotalTitle' value=\"".stripslashes(htmlentities($this->totalTitle))."\"  />";
		echo "</td>";
		echo "</tr><tr scope='row'>";
		echo "<th scope='row'><label for='tabberLogoShow'>Show TabberList logo and link?</label></th>";
		echo "<td><input type='checkbox' name='tabberLogoShow' id='tabberLogoShow' value=\"on\"";  
		if ($tabberShowLink == "on") {
			echo " checked='checked' ";
		}
		echo "/>";
		echo "</td>";

		echo "</tr></table>";
		
			
		}
		
		function updateTitle() {
			update_option('tabberTitle', $_POST['tabberTitle']);
			if ($_POST['tabberTotalTitle'] == "") {
				$this->totalTitle = "Combined Total";
			} else {
				$totalTitle = $_POST['tabberTotalTitle'];
			}
			update_option('tabberTotalTitle', $totalTitle);
		}

		function updateShowLink() {
			if ($_POST['tabberLogoShow'] != "") {
				update_option('tabberShowLink', "on");
			} else {
				update_option('tabberShowLink', "");
			}
		}
		
		
		function updateSkin() {
			update_option('tabberSkin', $_POST['tabberSkin']);
		}
		function selectSkin() {
			$this->currentSkin = get_option('tabberSkin');
			echo "<table class='form-table'>";
			echo "<tr valign='top'>";
			echo "<th scope='row'>Select a skin</th>";
			echo "<td><select name='tabberSkin' id='tabberSkin'>";
			
			foreach ($this->tabberSkins as $id => $skin) {
				echo "<option value='$id' ";
				if ( $this->currentSkin == $id ) {
					echo "selected='selected' ";
				}
				echo ">$skin</option>";
			}
			
			echo "</td></tr></table>";
		}
		function getStyles() {
			$this->tabberStyles[0] = "tabbed";
			$this->tabberStyles[1] = "accordian";
			update_option('tabberStyle', $this->tabberStyles);
			
		}
		function updateStyle() {
			update_option('tabberStyle', $_POST['tabberStyle']);
		}

		function selectStyle() {
			$this->currentStyle = get_option('tabberStyle');
			echo "<table class='form-table'>";
			echo "<tr valign='top'>";
			echo "<th scope='row'>Select list style</th>";
			echo "<td><select name='tabberStyle' id='tabberStyle'>";
			
			foreach ($this->tabberStyles as $id => $style) {
				echo "<option value='$id' ";
				if ( $this->currentStyle == $id ) {
					echo "selected='selected' ";
				}
				echo ">$style</option>";
			}
			
			echo "</td></tr></table>";
		}
		
		function getTabberListCat($option, $value) {
			global $wpdb;
			if ( $option != "" ) {
				$sqlOption = " WHERE $option IN ( $value )";
			}
			$wpdb->show_errors();
			$this->tabberlistcat = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."tabberlistcat$sqlOption", ARRAY_A);
			$data = $this->tabberlistcat;
			return $data;
		}
		function getTabberlistData($option, $value) {
			global $wpdb;
			if ( $option != "" ) {
				$sqlOption = " WHERE $option = $value";
			}
			$data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."tabberlist{$sqlOption}", ARRAY_A);
			return $data;
		}
		
		function adminCatTabs() {
			echo "<ul>";
			if ( count($this->tabberlistcat) > 0 ) {
				foreach ($this->tabberlistcat as $key => $value) {
					echo "<li><a href='#tab{$key}'>".stripslashes($this->tabberlistcat[$key]['category'])."</a></li>";
				}
			}
			echo "</ul>";
		}
		function adminCatDivs() {
		if ( count($this->tabberlistcat) > 0 ) {
			foreach ( $this->tabberlistcat as $key => $value) {
				echo "<div id='tab{$key}' class='tab clear'>"; 	
				echo "<table class='widefat fixed adminTabberTable tabberItems-".$this->tabberlistcat[$key]['id']."'>";
				echo "<thead>";
				echo "<tr class='thead'>";
				echo "<th class='column-comments'>ID</th>";
				echo "<th class='manage-column column-title'>Item</th>";
				echo "<th class='manage-column column-title'>URL</th>";
				echo "<th class='column-posts'>New</th>";
				echo "<th class='column-posts'>Strikethrough</th>";
				echo "</tr>";
				echo "</thead>";

			$link =  "<p><a href='tabberItems-".$this->tabberlistcat[$key]['id']."' class='tabberAddItem'>Add another row</a></p>";

			
				$tabberListItems = $this->getTabberlistData('cat_id', $this->tabberlistcat[$key]['id']);
					if (sizeof($tabberListItems) > 0) {
						foreach ( $tabberListItems as $innerKey => $innerValue ) {
							
							echo "<tr valign='top'>";
							echo "<td>".$tabberListItems[$innerKey]['id']."</td>";
							echo "<td>";
							echo "<input type='text' name='item[".$this->tabberlistcat[$key]['id']."][".$tabberListItems[$innerKey]['id']."][name]' id='item-name-".$tabberListItems[$innerKey]['id']."' value=\"".stripslashes(htmlentities($tabberListItems[$innerKey]['item']))."\"  />";
							echo "</td>";
							echo "<td>";
							echo "<input type='text' name='item[".$this->tabberlistcat[$key]['id']."][".$tabberListItems[$innerKey]['id']."][url]' id='item-url-".$tabberListItems[$innerKey]['id']."' value='".htmlentities(stripslashes($tabberListItems[$innerKey]['url']))."'  />";
							echo "</td>";
							echo "<td>";
							echo "<input type='checkbox' name='item[".$this->tabberlistcat[$key]['id']."][".$tabberListItems[$innerKey]['id']."][new]' id='item-new-".$tabberListItems[$innerKey]['id']."' value='on'  ";
							if ($tabberListItems[$innerKey]['new'] == "on") {
								echo "checked='checked' ";
							}
							
							echo "/>";
							echo "</td>";
							echo "<td>";
							echo "<input type='checkbox' name='item[".$this->tabberlistcat[$key]['id']."][".$tabberListItems[$innerKey]['id']."][deleted]' id='item-deleted-".$tabberListItems[$innerKey]['id']."' value='on' ";
							if ($tabberListItems[$innerKey]['deleted'] == "on") {
								echo "checked='checked' ";
							}

							echo " />";
							echo "</td>";
							
							echo "</tr>";
						}
					} else {
							echo "<tr valign='top'>";
							echo "<td>NEW</td>";
							echo "<td>";
							echo "<input type='text' name='item[".$this->tabberlistcat[$key]['id']."][initialise][name]' id='item-name-".$this->tabberlistcat[$key]['id']."-0' value=''  />";
							echo "</td>";
							echo "<td>";
							echo "<input type='text' name='item[".$this->tabberlistcat[$key]['id']."][initialise][url]' id='item-url-".$this->tabberlistcat[$key]['id']."-0' value=''  />";
							echo "</td>";
							echo "<td>";
							echo "<input type='checkbox' name='item[".$this->tabberlistcat[$key]['id']."][initialise][new]' id='item-new-".$this->tabberlistcat[$key]['id']."-0' value='on' checked='checked' />";
							echo "</td>";
							echo "<td>";
							echo "<input type='checkbox' name='item[".$this->tabberlistcat[$key]['id']."][initialise][deleted]' id='item-deleted-".$this->tabberlistcat[$key]['id']."-0' value='on' />";						echo "</td>";
							
							echo "</tr>";
					
					}
					
				echo "</table>";
				echo $link;
				echo "</div>";
				
			}
			}
		}
		
		function tabberListDisplayCats() {
			$this->getTabberListCat('','');
			if (sizeof($this->tabberlistcat) > 0 ) {
				echo "<table class='widefat fixed adminTabberTable'>";
				echo "<thead>";
				echo "<tr class='thead'>";
				echo "<th class='column-comments'>ID</th><th class='manage-column'>Category Name</th><th class='manage-column'>Category Descrition (optional)</th><th class='column-posts'>Delete</th><th class='column-posts'>Counted</th>";
				echo "</tr>";
				echo "</thead>";
				
				echo "<tbody>";
				
					foreach ( $this->tabberlistcat as $key => $value) {
						echo "<tr>";		
						echo "<td>" . $this->tabberlistcat[$key]['id'] . "</td>";
						echo "<td><input type='text' value=\"".stripslashes(htmlentities($this->tabberlistcat[$key]['category'])) . "\" size='60' id='cateogry-".$this->tabberlistcat[$key]['id'] . "' name='category[".$this->tabberlistcat[$key]['id'] . "][name]' /></td>";
						echo "<td><input type='text' value=\"".stripslashes(htmlentities($this->tabberlistcat[$key]['description'])) . "\" id='category-description-".$this->tabberlistcat[$key]['id'] ."' name='category[".$this->tabberlistcat[$key]['id']."][description]' /></td>";
						echo "<td><input type='checkbox' name='delete[]' value='".$this->tabberlistcat[$key]['id'] . "' id='delete[]' class='tabberDeleteCheck' /></td>";
						echo "<td><input type='checkbox' name='category[".$this->tabberlistcat[$key]['id'] . "][counted]' value='on' id='category-counted-".$this->tabberlistcat[$key]['id'] ."' class='tabberCountedCheck' ";
						if ( $this->tabberlistcat[$key]['counted'] == "on" ) {
							echo "checked='checked' ";
						}
						echo "/></td>";

						echo "</tr>";
					}
				
				echo "</tbody>";
				echo "</table>";
			} else {
				echo "<p>Enter a category above	and Save Changes to begin</p>";
			}
		}
		
		function update() {
			$this->dbAddCats();
			$this->dbUpdateCats();
			$this->dbDeleteCats();
			$this->updateItems();
			$this->updateSkin();
			$this->updateStyle();
			$this->updateTitle();
			$this->updateShowLink();
		}
		
		function updateItems() {
			if (sizeof($_POST['item']) > 0) {
				foreach($_POST['item'] as $key => $value) {
					$category = $key;
					foreach ($_POST['item'][$key] as $innerKey => $innerValue) {
						if (strpos($innerKey, "new-") !== FALSE && $_POST['item'][$key][$innerKey]['name'] != "") {
							$this->dbAddItems($key,$innerKey);
						} elseif ($innerKey == "initialise" && $_POST['item'][$key]['initialise']['name'] != "") {
							$this->dbAddItems($key,$innerKey);
						} elseif ($_POST['item'][$key][$innerKey]['name'] == "") {
							$this->dbDeleteItems($innerKey);
						} else {
							$this->dbUpdateItems($key,$innerKey);
						}
					}
				}
			}
		}
		
		function dbAddItems($category,$id) {
			global $wpdb;
			$item = $_POST['item'][$category][$id]['name'];
			$url = $_POST['item'][$category][$id]['url'];
			$new = $_POST['item'][$category][$id]['new'];
			$deleted = $_POST['item'][$category][$id]['deleted'];
			if ($deleted != "") {
				$deletedTime = time();
			}
			$wpdb->show_errors();
			$insert = $wpdb->query("INSERT INTO " . $wpdb->prefix . "tabberlist (dateAdded,dateDeleted,item,url,new,deleted,cat_id) VALUES ('".time()."','$deletedTime','".$wpdb->escape($item)."','".$wpdb->escape($url)."','$new','$deleted','$category')");
			

		}
		function dbUpdateItems($category,$id) {
			global $wpdb;
			$item = $_POST['item'][$category][$id]['name'];
			$url = $_POST['item'][$category][$id]['url'];
			$new = $_POST['item'][$category][$id]['new'];
			$deleted = $_POST['item'][$category][$id]['deleted'];
			if ($deleted != "") {
				$deletedTime = time();
			} else {
				$deletedTime = "";
			}
			$wpdb->show_errors();
			$update = $wpdb->query("UPDATE " . $wpdb->prefix . "tabberlist SET 
							dateDeleted='$deletedTime',
							item='".$wpdb->escape($item)."',
							url='".$wpdb->escape($url)."',
							new='$new',
							deleted='$deleted'
							WHERE id='$id'");
		}
		
		function dbDeleteItems($value) {
			global $wpdb;
			$results = $wpdb->get_results( "DELETE FROM ".$wpdb->prefix."tabberlist WHERE id='$value'" );
			
		}
		
		function dbUpdateCats() {
			global $wpdb;
			if ( count($_POST['category']) > 0 ) {
				foreach( $_POST['category'] as $key => $value ) {
					$wpdb->show_errors();
					$wpdb->update( $wpdb->prefix . 'tabberlistcat', array( 'category' => $value['name'], 'description' => $value['description'], 'counted' => $value['counted'] ), array( 'ID' => $key ), array( '%s', '%s', '%s' ), array( '%d' ) );
				}
			}
		}
		
		function dbAddCats() {
			global $wpdb;
			if ($_POST['newCat'] != "") {
				$newCat = $_POST['newCat'];
				$insert = $wpdb->query("INSERT INTO " . $wpdb->prefix . "tabberlistcat (category) VALUES ('" . $wpdb->escape($newCat) . "')");

			}
			
		}
		
		function dbDeleteCats() {
			global $wpdb;
			if(isset($_POST['delete'])) {
				$i = 0;
				foreach ( $_POST['delete'] as $key => $value ) {
					$results = $wpdb->get_results( "DELETE FROM ".$wpdb->prefix."tabberlist WHERE cat_id='$value'" );
					if ($i == 0) {
						$values .= "'".$value."'";
					} else {
						$values .= ", '".$value."'";
					}
					$results = $wpdb->get_results( "DELETE FROM ".$wpdb->prefix."tabberlistcat WHERE id IN ($values)" );
					$i++;
				}
			}
		}
	}
}
?>