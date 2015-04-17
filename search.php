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

function concert_search($func_vars) {
	extract($func_vars, EXTR_PREFIX_ALL, 'func');

	// how many lines of excerpt we want to have at most
	$max_excerpt_num = $func_default_max_excerpt;
	$divider = ".";
	$result = false;
	
	// fetch all concert-items from this section
	$table = TABLE_PREFIX."mod_concert_dates";
	$query = $func_database->query("
		SELECT concert_date, concert_desc, concert_name
		FROM $table
		WHERE section_id='$func_section_id'
		ORDER BY concert_date DESC
	");

	if($query->numRows() > 0) {
		while($res = $query->fetchRow()) {
			// the target have to be e.g. &monthno=5&year=2007
			list($year, $month, $day) = explode("-", $res['concert_date']);
			// $year==2000 means _every_ year
			if($year==2000) {
				$year=date("Y");
			}
			//convert date-format
			$timestamp = strtotime("$year-$month-$day");
			if($timestamp>0) { // just to be sure
				$date = date(DATE_FORMAT, $timestamp);
			} else {
				$date = $res['concert_date'];
			}
			$mod_vars = array(
				'page_link' => $func_page_link,
				'page_link_target' => "&monthno=".$month."&year=".$year,
				'page_title' => $func_page_title,
				'page_description' => "{$res['concert_desc']} ($date)", // use concert-title and date as description
				'page_modified_when' => $func_page_modified_when,
				'page_modified_by' => $func_page_modified_by,
				'text' => $res['concert_name'].$divider.$res['concert_desc'].$divider.$res['concert_date'].$divider,
				'max_excerpt_num' => $max_excerpt_num
			);
			if(print_excerpt2($mod_vars, $func_vars)) {
				$result = true;
			}
		}
	}
	return $result;
}

?>