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

// prevent this file from being accessed directly
if(!defined('WB_PATH')) die(header('Location: index.php'));

// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(WB_PATH .'/modules/concert/languages/'.LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file EN.php
	require_once(WB_PATH .'/modules/concert/languages/EN.php');
} else {
	// a module language file exists for the language defined by the user, load it
	require_once(WB_PATH .'/modules/concert/languages/'.LANGUAGE .'.php');
}

//removes empty events from the table so they will not be displayed
$database->query("DELETE FROM `".TABLE_PREFIX."mod_concert_dates` WHERE `page_id` = '$page_id' and `section_id` = '$section_id' and `concert_name`=''");

$query_page_content = $database->query("SELECT * FROM `".TABLE_PREFIX."pages` WHERE `page_id` = '$page_id'");
$fetch_page_content = $query_page_content->fetchRow();
$page_created = $fetch_page_content['link'];

$query_page_content = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_concert_settings` WHERE `section_id` = '$section_id'");
$fetch_page_content = $query_page_content->fetchRow();
$dateview = $fetch_page_content['dateview'];

?>

<script type="text/javascript">
	var now = '<?php echo date("Y/m/d"); ?>';
	var WB_URL = '<?php echo WB_URL; ?>';
</script>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left" width="50%">
		<input type="button" value="<?php echo $MOD_CONCERT['ADDCONCERT']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/concert/add_concert.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 100%;" />
	</td>
	<td align="right" width="50%">
		<input type="button" value="<?php echo $TEXT['SETTINGS']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/concert/modify_settings.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 100%;" />
	</td>
</tr>
</table>

<br />

<h2><?php echo $MOD_CONCERT['CONCERTS'] ?></h2>
<?php

$query_dates = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_concert_dates` WHERE `section_id` = '$section_id' ORDER BY concert_date");
if ($query_dates->numRows() > 0) {
	$row = 'a';
	?>
	<input type="hidden" id="copydate" name="copydate" value="" />
	<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<?php
	while( $result = $query_dates->fetchRow() ) {
		$alt2date=$result['concert_date'];
		list($a2year, $a2month, $a2day) = preg_split('#[/.-]#', $alt2date);
		$ydash="-";
		$ydot=".";
		if ($a2year=="2000"){
			$a2year="";
			$alt2date=substr($alt2date,5,5);
			$ydash="";
			$ydot="";
		}
		if ($dateview == 1 ) { 
			$altdate=$a2day.".".$a2month.$ydot.$a2year;
		} elseif ($dateview == 2 ) { 
			$altdate=$a2month."-".$a2day.$ydash.$a2year;
		} else {
			$altdate=$alt2date;
		}
		?>
		<tr class="row_<?php echo $row; ?>" height="20">
			<td align="left" style="padding-left: 5px;">
				<a id="changelink_trigger_<?php echo $result['concert_id']; ?>" href="<?php echo WB_URL; ?>/modules/concert/change_concert.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&concert_id=<?php echo $result['concert_id']; ?>" title="<?php echo $TEXT['MODIFY']; ?>"><img src="<?php echo ADMIN_URL; ?>/images/modify_16.png" border="0" alt="<?php echo $TEXT['MODIFY']; ?>" /></a>	
 				<img id="trigger_<?php echo $result['concert_id']; ?>" src="<?php echo ADMIN_URL; ?>/images/plus_16.png" border="0" alt="<?php echo $MOD_CONCERT['COPY']; ?>" title="<?php echo $MOD_CONCERT['COPY']; ?>" />
 				<span id="anchor_trigger_<?php echo $result['concert_id']; ?>">&nbsp;<span>
			</td>
			<td align="left" style="padding-left: 5px;">
				<?php echo $altdate ?>
			</td>
			<td align="left" style="padding-left: 5px;">	
				<?php echo $result['concert_name']  ?>
			</td>
			<td align="left" style="padding-left: 5px;">
				<?php echo $result['concert_club'];	?>
			</td>
			<td align="right" style="padding-right: 5px; padding-left: 5px;" >
				<a href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/concert/delete_concert.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&concert_id=<?php echo $result['concert_id']; ?>');" title="<?php echo $TEXT['DELETE']; ?>"><img src="<?php echo ADMIN_URL; ?>/images/delete_16.png" border="0" alt="<?php echo $TEXT['DELETE']; ?>" /></a>
			</td>
		</tr>
		<?php
		// Alternate row color
		if($row == 'a') {
			$row = 'b';
		} else {
			$row = 'a';
		}
	} 
} else {
	echo $TEXT['NONE_FOUND'];
}
?>
</table>