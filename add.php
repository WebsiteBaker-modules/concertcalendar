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

// default header
$header_data = addslashes('<h1>Concerts</h1>');
// default footer
$footer_data = addslashes('Footer');
//default loop
$ccloop = addslashes('In [PLACE] ([CLUB]) at [TIME] for [PRICE].');


$database->query("INSERT INTO `".TABLE_PREFIX."mod_concert_settings` (`page_id`, `section_id`, `header_data`, `footer_data`, `ccloop`, `detailed_view`, `upcoming_view`, `previous_view`, `previous_num`, `upcoming_num`, `dateview`, `date_link`, `toggle`) VALUES ('$page_id','$section_id','$header_data','$footer_data','$ccloop','1','1','1','10','10','0','0','1')");

?>