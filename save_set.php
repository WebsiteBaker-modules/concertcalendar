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
require(WB_PATH.'/modules/admin.php');

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
error_reporting(E_ALL);
// This code removes any php tags
$header_data = addslashes($_POST['ccheader']);
$footer_data = addslashes($_POST['ccfooter']);
$ccloop = addslashes($_POST['ccloop']);
$detailed_view = $_POST['detailed_view'];
$upcoming_view = $_POST['upcoming_view'];
$previous_view = $_POST['previous_view'];
$previous_num = addslashes($_POST['previous_num']);
$upcoming_num = addslashes($_POST['upcoming_num']);
$dateview = $_POST['dateview_form'];
$date_link = $_POST['date_link'];
$toggle = $_POST['toggle'];


//Write Settings to Database
$database->query("UPDATE `".TABLE_PREFIX."mod_concert_settings`
			SET	`page_id` = '$page_id',
				`header_data` = '$header_data',
				`footer_data` = '$footer_data',
				`ccloop` = '$ccloop',
				`detailed_view` = '$detailed_view',
				`previous_view` = '$previous_view',
				`upcoming_view` = '$upcoming_view',
				`previous_num` = '$previous_num',
				`upcoming_num` = '$upcoming_num',
				`dateview` = '$dateview',
				`date_link` = '$date_link',
				`toggle` = '$toggle'
			WHERE `section_id` = '$section_id'"
			);

// Check if there is a database error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), $js_back);
} else {
	$admin->print_success($MESSAGE['PAGES']['SAVED'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer()

?>