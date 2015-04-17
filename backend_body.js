
if (typeof WB_URL != 'undefined') {
  // Include jscalendar files:
  $.insert(WB_URL + '/include/jscalendar/calendar-system.css');
  $.insert(WB_URL + '/include/jscalendar/calendar.js');
  $.insert(WB_URL + '/include/jscalendar/lang/calendar-de.js');     
  $.insert(WB_URL + '/include/jscalendar/calendar-setup.js');   
};

$(document).ready(function () {
  var linkid = "";
	function doCopy() {
		if ($('#copydate').val() != "") {
			chlink = $('#changelink_' + linkid).attr('href');
			chlink = chlink.replace(/change_concert/,'add_concert') + "&docopy=1&newdate=" + $('#copydate').val();
			window.calendar.hide();
			window.location = chlink;
		}
		window.calendar.hide();
	}
	
	$('[id^="trigger"]').click(function(){
		calanchor = "anchor_" + this.id;
		linkid = this.id;
		Calendar.setup(
			{
				inputField  : "copydate",
				ifFormat    : "%Y-%m-%d",
				button      : calanchor,
				firstDay    : 1,
				date        : now,
				range       : [1970, 2037],
				step        : 1,
				onClose			: doCopy
			}
		);
		$('#'+calanchor).click();
	});
});
  