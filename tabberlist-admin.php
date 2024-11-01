<?php 
global $tabberlist;
if (isset($_POST['tabberListSave'])) {
	$tabberlist->update();
}

?>
<div class="wrap">
	<div id='icon-tools' class='icon32'></div>
	<h2>TabberList Admin</h2>
	<p class='description'>Welcome to TabberList. Select a skin, and start entering makin' some lists!</p>
	<form name="form_development" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
	<?php $tabberlist->selectSkin(); ?>
	<?php $tabberlist->getTitle(); ?>
	<div id='tabberlistOuterTabs' class='tabberlistAdminTabs'>
		<ul>
			<li><a href='#categories'>Edit Categories</a></li>
			<li><a href='#list'>Edit Lists</a></li>
		</ul>
	</div>
	<div id='categories' class='clear tab'>
	<p class='description'>Use the field below to add a new category. To delete categories, check the boxes next to the categories you would like to delete and hit "Save Changes". To have a category's items not counted in the final total, uncheck the box in the "Counted" column.</p>

	<table class='form-table adminTabberTable'>
				<tr valign='top'>
					<th scope='row'><label for='newCat'>Add New Category</label></th>
					<td><input type='text' name='newCat' id='newCat' size='60' /></td>
				</tr>
			</table>
			<?php $tabberlist->tabberListDisplayCats(); ?>
			
	</div>
	<div id='list' class='clear tab'>
		<h3>Current Categories:</h3>
			<div id='tabberlistAdmin' class='tabberlistAdminTabs'>
				<?php $tabberlist->adminCatTabs(); ?>
				<?php $tabberlist->adminCatDivs(); ?>
			</div>
	</div>
			<p class="submit">
				<input type="submit" name="tabberListSave" id="tabberListSave" class='button-primary' value="Save Changes" />
			</p>	

	</form>
</div>

