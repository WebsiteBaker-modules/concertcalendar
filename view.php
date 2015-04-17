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
//error_reporting(E_ALL);
if(!defined('WB_PATH')) die(header('Location: index.php'));

// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(WB_PATH .'/modules/concert/languages/'.LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file EN.php
	require_once(WB_PATH .'/modules/concert/languages/EN.php');
} else {
	// a module language file exists for the language defined by the user, load it
	require_once(WB_PATH .'/modules/concert/languages/'.LANGUAGE .'.php');
}

// check if frontend.css file needs to be included into the <body></body> of view.php
if((!function_exists('register_frontend_modfiles') || !defined('MOD_FRONTEND_CSS_REGISTERED')) &&  file_exists(WB_PATH .'/modules/concert/frontend.css')) {
   echo '<style type="text/css">';
   include(WB_PATH .'/modules/concert/frontend.css');
   echo "\n</style>\n";
}

// Get settings
$query = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_concert_settings WHERE section_id = '$section_id'");
if( $query->numRows() > 0) {
	$fetch_content = $query->fetchRow();
	$header_data = stripslashes($fetch_content['header_data']);
	$footer_data = stripslashes($fetch_content['footer_data']);
	$ccloop = stripslashes($fetch_content['ccloop']);
	$detailed_view = $fetch_content['detailed_view'];
	$upcoming_view = $fetch_content['upcoming_view'];
	$previous_view = $fetch_content['previous_view'];
	$previous_num = stripslashes($fetch_content['previous_num']);
	$upcoming_num = stripslashes($fetch_content['upcoming_num']);
	$dateview = $fetch_content['dateview'];
	$date_link = $fetch_content['date_link'];
	$toggle = $fetch_content['toggle'];
}

if (isset($_GET['date'])) {
	if (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $_GET['date'])) { 
        $date = $_GET["date"];
    } else {
        $date = "all";
    }
}

$today = date('Y-m-d');


// Functions--------------------
function switch_date($date, $dateview) {
	$altdate = "";
	if ($date != '') {
		list($a2year, $a2month, $a2day) = preg_split('#[/.-]#', $date);
				$ydash="-";
		$ydot=".";
			if ($dateview == 1 ) { 
				$altdate=$a2day.$ydot.$a2month.$ydot.$a2year;
			} elseif ($dateview == 2 ) { 
				$altdate=$a2month.$ydash.$a2day.$ydash.$a2year;
			} else {
				$altdate=$date;
			}
	}
	//setlocale ( LC_TIME, ['de_DE', 'deu'] );
	//return $altdate.strtoupper(strftime(', %a',strtotime($altdate)));
	//return date("l",strtotime($altdate))." ".$altdate;
	$gdays = array("SO","MO","DI","MI","DO","FR","SA");
	return $altdate.", ".$gdays[date("w",strtotime($altdate))];
}

function output($data, $dateview, $MOD_CONCERT, $date_link, $ccloop, $toggle) {
	$search = array('[PLACE]', '[CLUB]', '[TIME]', '[PRICE]', '[NAME]', '[DATE]', '[INFO]');
	$replace = array($data['concert_place'], $data['concert_club'], $data['concert_time'], $data['concert_price'], $data['concert_name'], switch_date($data['concert_date'], $dateview), $data['concert_desc']);
	$content = "";
	if ($date_link == 1) {$content .= '<i><a href="?date='.$data['concert_date'].'">' . switch_date($data['concert_date'],$dateview).'</a></i>&nbsp;&nbsp;'.$data['concert_place'];}
	else {$content .= '<i>'.switch_date($data['concert_date'], $dateview).'</i>&nbsp;&nbsp;'.$data['concert_place'];}
	$content .= "&nbsp;&nbsp;";
	$divtxt = '"cc'.$data['concert_id'].'"';
	$more = (strlen(strip_tags($data['concert_desc'])) > 0) ? '<img class="cc_arrow" id="imgcc'.$data['concert_id'].'" title="'.$MOD_CONCERT['DISPDETAILEDVIEW'].'" style="float:right;" src="'.ADMIN_URL.'/images/down_16.png" />' : '';
	if ($toggle == 1) {
		$content .= " <a class='toggle' onclick='toggle_visibility(".$divtxt.");'>".$more."&nbsp;".$data['concert_name'].'&nbsp;&nbsp;'.$data['concert_time']." Uhr</a>";
	} else {
		$content .= $data['concert_name'].'&nbsp;&nbsp;'.$data['concert_time']. "Uhr";
	}
	$content .= "<div class='desc' id='cc".$data['concert_id']."' style='display:none;'>";
	$content .= str_replace($search, $replace, $ccloop);
	$content .= "</div>\n";
	$content .= "<br />\n";
	return $content;
}



echo '<div id="concert-calendar">';
echo '<div class="header_data">';
if ($header_data != "" ) {
	echo $header_data;
}
echo '</div>';
?>
<script type="text/javascript">
<!--
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       var i = document.getElementById("img"+id);
       if(e.style.display == 'block'){
          e.style.display = 'none';
          i.src = "<?php echo ADMIN_URL; ?>/images/down_16.png";
       } else {
          e.style.display = 'block';
          i.src = "<?php echo ADMIN_URL; ?>/images/up_16.png";
       }
    }
//-->
</script>
<?php
if (isset($date) && $date == 'all') { // call for archive
?>

<!-- Display archive --> 

<a href="?">&laquo; <?php echo $MOD_CONCERT['BACK']; ?></a>
<div class="concertborder">
	<div class="concertheading">
	<?php 
		echo $MOD_CONCERT['ARCHIVE']; 
	?>
	</div>
    <div class="concert">
	<?php
		$query_dates_archive = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_concert_dates WHERE section_id = '$section_id' && concert_date < '$today' ORDER BY concert_date DESC LIMIT 200");
		if ($query_dates_archive->numRows() > 0) {
			while($result_archive = $query_dates_archive->fetchRow()) { 
				echo output($result_archive, $dateview, $MOD_CONCERT, $date_link, $ccloop, $toggle);
			}
		} else {
			echo $MOD_CONCERT['ARCHIVE_EMPTY']; 
		}
		?>
	</div>
</div>

<?php 
} else { // No call for archive
?>

<!-- Display detailed events -->

<?php if ($detailed_view == 1 ){ 
$date_link2 = true;
if (!isset($date)) {
	$query_new_date = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_concert_dates WHERE section_id = '$section_id' && concert_date >= '$today' ORDER BY concert_date ASC LIMIT 1");
	$result_new_date = $query_new_date->fetchRow();
	$date = $result_new_date['concert_date'];
	$date_link2 = false;
}
?>
<div class="concertborder">
	<div class="concertheading">
	<?php
		if ($date_link2) {
			echo $MOD_CONCERT['DETAILED_VIEW'];
		} else {
			if ($date != '') {echo $MOD_CONCERT['NEXTCONCERT'];} else {echo $MOD_CONCERT['NOTHING_ARRANGED'];}
		} 
		echo " ".switch_date($date, $dateview)." ";
		if ($date_link2) {
			echo '(<a href="?">'.$MOD_CONCERT['BACK_TO_CURRENT'].'</a>)';
		}
	?>
	</div>
    <div class="concert">
	<?php
		$query_dates = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_concert_dates WHERE section_id = '$section_id' && concert_date = '$date'");
		$i = 0;
		if ($query_dates->numRows() > 0) {
			while($result = $query_dates->fetchRow()) {
				$i++;
				echo '<b>' . $result['concert_name'] . '</b><br />';
				$search = array('[PLACE]', '[CLUB]', '[TIME]', '[PRICE]', '[NAME]', '[DATE]', '[INFO]');
				$replace = array($result['concert_place'], $result['concert_club'], $result['concert_time'], $result['concert_price'], $result['concert_name'], switch_date($result['concert_date'], $dateview), $result['concert_desc']);
				$content = str_replace($search, $replace, $ccloop);
				$content .= '<br />';
				if ($i < $query_dates->numRows()) { $content .= '<br /><br /><hr />'; } 
				$wb->preprocess($content);
				echo $content;
			}
		} else {
			echo $MOD_CONCERT['NO_ENTRY']; 
		}
	?>
	</div>
</div>
<?php } ?>

<!-- Display upcoming events -->

<?php if ($upcoming_view == 1 ){ ?>
<div class="concertborder">
	<div class="concertheading">
	<?php
		echo $MOD_CONCERT['UPCOMING_CONCERTS'];
	?>
	</div>
    <div class="concert">
	<?php
		$query_dates_upcoming = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_concert_dates WHERE section_id = '$section_id' && concert_date >= '$today' ORDER BY concert_date LIMIT $upcoming_num");
		if ($query_dates_upcoming->numRows() > 0) {
			while($result_upcoming = $query_dates_upcoming->fetchRow()) { 
				echo output($result_upcoming, $dateview, $MOD_CONCERT,$date_link, $ccloop, $toggle);
			}
		} else {
			echo $MOD_CONCERT['NO_ENTRY']; 
		}
	?>
	</div>
</div>
<?php } ?>

<!-- Display previous events -->

<?php if ($previous_view == 1 ){ ?>
<div class="concertborder">
	<div class="concertheading">
	<?php
		echo $MOD_CONCERT['PREVIOUS_CONCERTS']; 
		echo ' (<a href="?date=all">'.$MOD_CONCERT['ARCHIVE'].'</a>)';
	?>
	</div>
    <div class="concert">
	<?php
		$query_dates_previous = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_concert_dates WHERE section_id = '$section_id' && concert_date < '$today' ORDER BY concert_date DESC LIMIT $previous_num");
		if ($query_dates_previous->numRows() > 0) {
			while($result_previous = $query_dates_previous->fetchRow()) {
				echo output($result_previous, $dateview, $MOD_CONCERT, $date_link, $ccloop, $toggle);
			}
		} else {
			echo $MOD_CONCERT['NO_ENTRY']; 
		}
	?>
	</div>
</div>
<?php 
}  

} // end-else for 'no call for archive'
echo '<div class="footer_data">';
if ($footer_data != "" ) {
	echo $footer_data;
}
echo '</div>';
echo '</div>';
?>