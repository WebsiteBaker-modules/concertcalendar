<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2008, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

require('../../config.php');

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// include core functions of WB 2.7 to edit the optional module CSS files (frontend.css, backend.css)
@include_once(WB_PATH .'/framework/module.functions.php');

// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(WB_PATH .'/modules/concert/languages/'.LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file EN.php
	require_once(WB_PATH .'/modules/concert/languages/EN.php');
} else {
	// a module language file exists for the language defined by the user, load it
	require_once(WB_PATH .'/modules/concert/languages/'.LANGUAGE .'.php');
}

// check if backend.css file needs to be included into the <body></body> of modify.php
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH ."/modules/concert/backend.css")) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/concert/backend.css');
	echo "\n</style>\n";
}

$query_page_content = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_concert_settings` WHERE `section_id` = '$section_id'");
$fetch_page_content = $query_page_content->fetchRow();

$ccheader = stripslashes($fetch_page_content['header_data']);
$ccfooter = stripslashes($fetch_page_content['footer_data']);
$ccloop = stripslashes($fetch_page_content['ccloop']);
?>

<h2><?php echo $MOD_CONCERT['SETTINGS']; ?></h2>
<?php
// include the button to edit the optional module CSS files
// Note: CSS styles for the button are defined in backend.css (div class="mod_moduledirectory_edit_css")
// Place this call outside of any <form></form> construct!!!
if(function_exists('edit_module_css')) {
	edit_module_css('concert');
}
?>

<form name="edit" action="<?php echo WB_URL; ?>/modules/concert/save_set.php" method="post" style="margin: 0;">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">

<table class="row_a" cellpadding="2" cellspacing="0" border="0" align="center" width="100%">
	<tr>
		<td width="40%">
			<?php echo $MOD_CONCERT['DISPDETAILEDVIEW']; ?> :
		</td>
		<td width="60%">
			<input type="radio" name="detailed_view" id="detailed_view1" value="1" <?php if($fetch_page_content['detailed_view'] == "1") { echo "checked"; } ?>>
			<font onclick="document.getElementById('detailed_view1').checked = true; return false;" style="cursor: default;"><?php echo $TEXT['YES']; ?></font>
			<input type="radio" name="detailed_view" id="detailed_viewt0" value="0" <?php if($fetch_page_content['detailed_view'] != "1") { echo "checked"; } ?>>
			<font onclick="document.getElementById('detailed_view0').checked = true; return false;" style="cursor: default;"><?php echo $TEXT['NO']; ?></font>
		</td>
	</tr>
	<tr>
		<td width="40%">
			<?php echo $MOD_CONCERT['DISPUPCOMINGVIEW']; ?> :
		</td>
		<td width="60%">
			<input type="radio" name="upcoming_view" id="upcoming_view1" value="1" <?php if($fetch_page_content['upcoming_view'] == "1") { echo "checked"; } ?>>
			<font onclick="document.getElementById('upcoming_view1').checked = true; return false;" style="cursor: default;"><?php echo $TEXT['YES']; ?></font>
			<input type="radio" name="upcoming_view" id="upcoming_view0" value="0" <?php if($fetch_page_content['upcoming_view'] == "0") { echo "checked"; } ?>>
			<font onclick="document.getElementById('upcoming_view0').checked = true; return false;" style="cursor: default;"><?php echo $TEXT['NO']; ?></font>
		</td>
	</tr>
    <tr>
		<td width="40%">
			<?php echo $MOD_CONCERT['UPCOMINGNUM']; ?> :
		</td>
		<td width="60%">
			<input type="text" name="upcoming_num" id="upcoming_num" value="<?php echo $fetch_page_content['upcoming_num']; ?>" />
		</td>
	</tr>
	<tr>
		<td width="40%">
			<?php echo $MOD_CONCERT['DISPPREVIOUSVIEW']; ?> :
		</td>
        <td width="60%">
			<input type="radio" name="previous_view" id="previous_view1" value="1" <?php if($fetch_page_content['previous_view'] == "1") { echo "checked"; } ?>>
			<font onclick="document.getElementById('previous_view1').checked = true; return false;" style="cursor: default;"><?php echo $TEXT['YES']; ?></font>
			<input type="radio" name="previous_view" id="previous_view0" value="0" <?php if($fetch_page_content['previous_view'] == "0") { echo "checked"; } ?>>
			<font onclick="document.getElementById('previous_view0').checked = true; return false;" style="cursor: default;"><?php echo $TEXT['NO']; ?></font>
		</td>
	</tr>
    <tr>
		<td width="40%">
			<?php echo $MOD_CONCERT['PREVIOUSNUM']; ?> :
		</td>
		<td width="60%">
			<input type="text" name="previous_num" id="previous_num" value="<?php echo $fetch_page_content['previous_num']; ?>" />
		</td>
	</tr>
	<tr>
		<td width="40%">
			<?php echo $TEXT['DATE_FORMAT']; ?> :
		</td>
		<td width="60%">
			<select name="dateview_form">
			<option value ="0" <?php if ($fetch_page_content['dateview'] == 0) { echo "selected"; } ?> ><?php echo $MOD_CONCERT['YEAR']."-".$MOD_CONCERT['MONTH']."-".$MOD_CONCERT['DAY']; ?></option>
			<option value ="1" <?php if ($fetch_page_content['dateview'] == 1) { echo "selected"; } ?> ><?php echo $MOD_CONCERT['DAY'].".".$MOD_CONCERT['MONTH'].".".$MOD_CONCERT['YEAR']; ?></option>
			<option value ="2" <?php if ($fetch_page_content['dateview'] == 2) { echo "selected"; } ?> ><?php echo $MOD_CONCERT['MONTH']."-".$MOD_CONCERT['DAY']."-".$MOD_CONCERT['YEAR']; ?></option>
			</select>            
		</td>
	</tr>
    <tr>
    	<td><?php echo $MOD_CONCERT['LINKDATE']; ?> :</td>
        <td>
        	<input type="radio" name="date_link" id="date_link1" value="1" <?php if($fetch_page_content['date_link'] == "1") { echo "checked"; } ?>>
			<font onclick="document.getElementById('date_link1').checked = true; return false;" style="cursor: default;"><?php echo $TEXT['YES']; ?></font>
			<input type="radio" name="date_link" id="date_link0" value="0" <?php if($fetch_page_content['date_link'] != "1") { echo "checked"; } ?>>
			<font onclick="document.getElementById('date_link0').checked = true; return false;" style="cursor: default;"><?php echo $TEXT['NO']; ?></font>
        </td>
    </tr>
    <tr>
    	<td> <?php echo $MOD_CONCERT['TOGGLE']; ?> :</td>
        <td>
        	<input type="radio" name="toggle" id="toggle1" value="1" <?php if($fetch_page_content['toggle'] == "1") { echo "checked"; } ?>>
			<font onclick="document.getElementById('toggle1').checked = true; return false;" style="cursor: default;"><?php echo $TEXT['YES']; ?></font>
			<input type="radio" name="toggle" id="toggle0" value="0" <?php if($fetch_page_content['toggle'] != "1") { echo "checked"; } ?>>
			<font onclick="document.getElementById('toggle0').checked = true; return false;" style="cursor: default;"><?php echo $TEXT['NO']; ?></font>
        </td>
    </tr>
	<tr>
		<td class="setting_name" width="100">
			<?php echo $MOD_CONCERT['CCHEADER']; ?> :
		</td>
		<td class="setting_name">
			<textarea name="ccheader" style="width: 98%; height: 40px;"><?php echo $ccheader; ?></textarea>
		</td>
	</tr>
    <tr>
		<td class="setting_name" width="100">
			<?php echo $MOD_CONCERT['CCLOOP']; ?> :
		</td>
		<td class="setting_name">
			<textarea name="ccloop" style="width: 98%; height: 40px;"><?php echo $ccloop; ?></textarea>
			<?php echo $MOD_CONCERT['TO_USE']; ?>: [NAME], [DATE], [PLACE], [CLUB], [TIME], [PRICE], [INFO]
		</td>
	</tr>
	<tr>
		<td class="setting_name" width="100">
			<?php echo $MOD_CONCERT['CCFOOTER']; ?> :
		</td>
		<td class="setting_name">
			<textarea name="ccfooter" style="width: 98%; height: 40px;"><?php echo $ccfooter; ?></textarea>
		</td>
	</tr>
</table>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="left">
			<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;"></form>
		</td>
		<td align="right">
			<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
		</td>
	</tr>
</table>

<?php

// Print admin footer
$admin->print_footer();

?>