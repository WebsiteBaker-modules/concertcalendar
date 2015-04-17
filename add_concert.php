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

if (!isset($_GET['docopy'])) {
	// Insert new row into database
	$date = date('Y-m-d');
	$database->query("INSERT INTO `".TABLE_PREFIX."mod_concert_dates` (`page_id`, `section_id`, `concert_date`) VALUES ('$page_id','$section_id','$date')");
	
	//get id
	$concert_id = $database->get_one("SELECT LAST_INSERT_ID()");
	
	// Say that a new record has been added, then redirect to modify page
	if($database->is_error()) {
		$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id);
	} else {
		$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/concert/change_concert.php?page_id='.$page_id.'&section_id='.$section_id.'&concert_id='.$concert_id);
	}
} else {
	$newdate = mysql_real_escape_string($_GET['newdate']);
	$database->query("INSERT INTO `".TABLE_PREFIX."mod_concert_dates` (page_id, section_id, concert_date, concert_name, concert_desc, concert_place, concert_club, concert_price, concert_time) SELECT page_id, section_id, concert_date, concert_name, concert_desc, concert_place, concert_club, concert_price, concert_time FROM `".TABLE_PREFIX."mod_concert_dates` WHERE concert_id=".intval($_GET['concert_id']));
	//get id
	$concert_id = $database->get_one("SELECT LAST_INSERT_ID()");
	$database->query("UPDATE `".TABLE_PREFIX."mod_concert_dates` SET concert_date = '$newdate' WHERE concert_id = $concert_id");
	
	// Say that a new record has been added, then redirect to modify page
	if($database->is_error()) {
		$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id);
	} else {
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id.'&section_id='.$section_id);
	}
	
}

// Print admin footer
$admin->print_footer();