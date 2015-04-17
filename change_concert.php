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

// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(WB_PATH .'/modules/concert/languages/'.LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file EN.php
	require_once(WB_PATH .'/modules/concert/languages/EN.php');
} else {
	// a module language file exists for the language defined by the user, load it
	require_once(WB_PATH .'/modules/concert/languages/'.LANGUAGE .'.php');
}



// Get Settings from database
$query_page_content = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_concert_settings` WHERE `section_id` = '$section_id'");
$fetch_page_content = $query_page_content->fetchRow();
$dateview = $fetch_page_content['dateview'];
if (isset($_GET['concert_id'])){
	$concert_id = $_GET['concert_id'];
}

// Get Data from database
$query_content = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_concert_dates` WHERE `concert_id` = '$concert_id'");
$fetch_content = $query_content->fetchRow();
$this_date = $fetch_content['concert_date'];
list($year, $month, $day) = explode('-', $this_date);

?>
<h2><?php echo $TEXT['ADD'].'/'.$TEXT['MODIFY'].' '.$MOD_CONCERT['CONCERT']; ?></h2>

<form name="modify" action="<?php echo WB_URL; ?>/modules/concert/save_concert.php" method="post" style="margin: 0;">
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
<input type="hidden" name="concert_id" value="<?php echo $concert_id; ?>">

<?php
$concert_name = $fetch_content['concert_name'];
$concert_date = $fetch_content['concert_date'];
$concert_place = $fetch_content['concert_place'];
$concert_club = $fetch_content['concert_club'];
$concert_time = $fetch_content['concert_time'];
$concert_price = $fetch_content['concert_price'];
$concert_desc = $fetch_content['concert_desc'];

list($year, $month, $day) = preg_split('#[/.-]#', $concert_date);

?>

<script type="text/javascript" language="JavaScript">
<!--
// script modified by Marc Geldon (http://www.proitsystems.de)
// original script by Arash Ramin (http://www.digitalroom.net)

function setCurrentDate() {
	// changes the date selector menus to the current date
	var currentDate = new Date();
	document.modify.year.selectedIndex = 0;
	document.modify.month.selectedIndex = currentDate.getMonth();
	setDays();
	document.modify.day.selectedIndex = currentDate.getDate() - 1;
}

function setDays() {
	var y = document.modify.year.options[document.modify.year.selectedIndex].value;
	var m = document.modify.month.selectedIndex;
	var d;
	// find number of days in current month
	if ( (m == 3) || (m == 5) || (m == 8) || (m == 10) ) {
		days = 30;
	} else if (m == 1) {
		// check for leapyear - Any year divisible by 4, except those divisible by 100 (but NOT 400)
		if ( (Math.floor(y/4) == (y/4)) && ((Math.floor(y/100) != (y/100)) || (Math.floor(y/400) == (y/400))) ) {
			days = 29;
		} else {
			days = 28;
		}
	} else {
		days = 31;
	}
	// if (days in new month > current days) then we must add the extra days
	if (days > document.modify.day.length) {
		for (i = document.modify.day.length; i < days; i++) {
			document.modify.day.length = days;
			document.modify.day.options[i].text = i + 1;
			document.modify.day.options[i].value = i + 1;
		}
	}
	// if (days in new month < current days) then we must delete the extra days
	if (days < document.modify.day.length) {
		document.modify.day.length = days;
		if (document.modify.day.selectedIndex == -1) {
			document.modify.day.selectedIndex = days - 1;
		}
	}
}

//-->
</script>

<table class="row_a" cellpadding="2" cellspacing="0" border="0" align="center" width="100%">
	<tr>
		<td width="25%" valign="top">
			<?php echo $MOD_CONCERT['INSERTDATE']; ?> :
		</td>
		<td>
			<?php
			function SelectYear($year_interval, $year, $TITLE){
				echo $TITLE.":<select name=\"year\">\n";
				$CurrYear=date("Y");
				if($year != '' and ($year!="2000")) {
					$CurrYear=$year;
				}
				$i=$CurrYear-$year_interval+1;
				while ($i < $CurrYear+$year_interval) {
					if (($i == $CurrYear)and($year!="2000")) {
						echo "<option selected> $i</option>\n";
					} else {
						echo "<option> $i</option>\n";
					}
					$i++;
				}
				echo "</select>\n";
			}
			function SelectMonth($month, $TITLE){
				echo " ".$TITLE.":<select name=\"month\" onchange=\"javascript:setDays();\">\n";
				$i=1;
				$CurrMonth=date("m");
				while ($i <= 12) {
					if(isset($month)) {
						if($month == $i || ($i == substr($month,1,1) && (substr($month,0,1) == 0))) {
							echo"<option selected> $month\n";
							$i++;
						} else {
							if($i<10) {
								echo "<option> 0$i\n"; 
							} else { 
								echo "<option> $i\n"; 
							} 
							$i++; 
						}
					} else {
						if($i == $CurrMonth) { 
							if($i<10) { 
								echo "<option selected> 0$i\n"; 
							} else { 
								echo "<option selected> $i\n"; 
							}
						} else { 
							if($i<10){ 
								echo "<option> 0$i\n"; 
							} else { 
								echo "<option> $i\n"; 
							} 
						}
						$i++; 
					}
				}
				echo "</select>\n"; 
			}
			function SelectDay($day, $TITLE) {
				echo " ".$TITLE.":<select name=\"day\">\n";
				$i=1;
				$CurrDay=date("d");
				/* we just create entries from 1 to 28 in the day selection field.
				   because the rest is created by the javascript if needed. But if TODAY
				   is a day > 28, then we also have to create the rest */
				if(($CurrDay > 28) and ($CurrDay <= 31)) {
					$i_to = $CurrDay;
				} else {
					$i_to = 28;
				}
				if(isset($day)) {
					$i_to = $day;
				}	
				while ($i <= $i_to) {
					if(isset($day)) {
						if($day == $i || ($i == substr($day,1,1) && (substr($day,0,1) == 0))) {
							echo"<option selected> $day\n";
							$i++;
						} else {
							if($i<10) {
								echo "<option> 0$i\n";
							} else {
								echo "<option> $i\n";
							}
							$i++;
						}
					} else {
						if($i == $CurrDay){
							if($i<10) {
								echo "<option selected> 0$i\n";
							} else {
								echo"<option selected> $i\n";
							}
						} else {
							if($i<10) {
								echo "<option> 0$i\n";
							} else {
								echo "<option> $i\n";
							}
						}
						$i++;
					}
				}
				echo "</select>\n";
			}
			if ($dateview == 1 ) {
				SelectDay($day,$MOD_CONCERT['DAY']);
				SelectMonth($month,$MOD_CONCERT['MONTH']);
				SelectYear(5,$year,$MOD_CONCERT['YEAR']);
			} elseif ($dateview == 2 ) {
				SelectMonth($month,$MOD_CONCERT['MONTH']);
				SelectDay($day,$MOD_CONCERT['DAY']);
				SelectYear(5,$year,$MOD_CONCERT['YEAR']);
			} else {
				SelectYear(5,$year,$MOD_CONCERT['YEAR']);
				SelectMonth($month,$MOD_CONCERT['MONTH']);
				SelectDay($day,$MOD_CONCERT['DAY']);
			}
			?>
		</td>
	</tr>
    	<tr>
		<td width="25%" valign="top">
			<?php echo $MOD_CONCERT['ENTER_NAME']; ?> :
		</td>
		<td>
			<input name="concert_name" style="width: 200px;" value="<?php echo $concert_name; ?>">
		</td>
	</tr>
	<tr>
		<td width="25%" valign="top">
			<?php echo $MOD_CONCERT['ENTER_PLACE']; ?> :
		</td>
		<td>
			<input name="concert_place" style="width: 200px;" value="<?php echo $concert_place; ?>">
		</td>
	</tr>
	<tr>
		<td width="25%" valign="top">
			<?php echo $MOD_CONCERT['ENTER_CLUB']; ?> :
		</td>
		<td>
			<input name="concert_club" style="width: 200px;" value="<?php echo $concert_club; ?>">
		</td>
	</tr>
    <tr>
		<td width="25%" valign="top">
			<?php echo $MOD_CONCERT['ENTER_TIME']; ?> :
		</td>
		<td>
			<input name="concert_time" style="width: 200px;" value="<?php echo $concert_time; ?>">
		</td>
	</tr>
    <tr>
		<td width="25%" valign="top">
			<?php echo $MOD_CONCERT['ENTER_PRICE']; ?> :
		</td>
		<td>
			<input name="concert_price" style="width: 200px;" value="<?php echo $concert_price; ?>">
		</td>
	</tr>
</table>

<table cellpadding="2" cellspacing="0" border="0" width="100%">	
	<tr>
		<td>
		<?php
			$content = stripslashes(htmlspecialchars($fetch_content['concert_desc']));
			if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
				function show_wysiwyg_editor($name,$id,$content,$width,$height) {
					echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
				}
			} else {
				$id_list=array("concert_desc");
				require(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
			}
			show_wysiwyg_editor("concert_desc","concert_desc",$content,"100%","400px");
		?>
		</td>
	</tr>
</table>

<?php // call setDays (to be sure that the date items are correct!) ?>
<script type="text/javascript" language="JavaScript">
<!--
  setDays();
// -->
</script>

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