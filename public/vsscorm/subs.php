<?php

/*

VS SCORM 1.2 RTE - subs.php
Rev 2009-11-30-01
Copyright (C) 2009, Addison Robson LLC

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor,
Boston, MA 02110-1301, USA.

*/

// ------------------------------------------------------------------------------------
// Database-specific code
// ------------------------------------------------------------------------------------

function dbConnect() {

	// database login details
	global $dbname;
	global $dbhost;
	global $dbuser;
	global $dbpass;

	// link
	global $mysqli;

	// connect to the database
	// $link = mysql_connect($dbhost,$dbuser,$dbpass);
	// mysql_select_db($dbname,$link);
	$mysqli = new mysqli($dbhost,$dbuser,$dbpass, $dbname);
	// $mysqli = new mysqli($dbhost,$dbuser,$dbpass, 'bakkah_lms_db3');
	if ($mysqli -> connect_errno) {
		echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
		exit();
	}
}

function readElement($VarName) {

	global $mysqli;
	global $SCOInstanceID;

	// $safeVarName = mysql_escape_string($VarName);
	$safeVarName = $mysqli -> real_escape_string($VarName);
	// $result = mysql_query("select VarValue from scormvars where ((SCOInstanceID=$SCOInstanceID) and (VarName='$safeVarName'))",$link);
	$result = $mysqli -> query("select VarValue from scormvars where ((SCOInstanceID=$SCOInstanceID) and (VarName='$safeVarName'))");
	list($value) = $result->fetch_row();

	return $value;

}

function writeElement($VarName,$VarValue) {

	global $mysqli;
	global $SCOInstanceID;

	$safeVarName = $mysqli -> real_escape_string($VarName);
	$safeVarValue = $mysqli -> real_escape_string($VarValue);
	$mysqli -> query("update scormvars set VarValue='$safeVarValue' where ((SCOInstanceID=$SCOInstanceID) and (VarName='$safeVarName'))");
	// $safeVarName = mysql_escape_string($VarName);
	// $safeVarValue = mysql_escape_string($VarValue);
	// mysql_query("update scormvars set VarValue='$safeVarValue' where ((SCOInstanceID=$SCOInstanceID) and (VarName='$safeVarName'))",$link);

	return;

}

function initializeElement($VarName,$VarValue) {

	global $mysqli;
	global $SCOInstanceID;

	$safeVarName = $mysqli -> real_escape_string($VarName);
	$safeVarValue = $mysqli -> real_escape_string($VarValue);
	$result = $mysqli -> query("select VarValue from scormvars where ((SCOInstanceID=$SCOInstanceID) and (VarName='$safeVarName'))");

	// if nothing found ...
	if (! $result -> num_rows) {
		$mysqli -> query("insert into scormvars (SCOInstanceID,VarName,VarValue) values ($SCOInstanceID,'$safeVarName','$safeVarValue')");
	}

	// // make safe for the database
	// $safeVarName = mysql_escape_string($VarName);
	// $safeVarValue = mysql_escape_string($VarValue);

	// // look for pre-existing values
	// $result = mysql_query("select VarValue from scormvars where ((SCOInstanceID=$SCOInstanceID) and (VarName='$safeVarName'))",$link);

	// // if nothing found ...
	// if (! mysql_num_rows($result)) {
	// 	mysql_query("insert into scormvars (SCOInstanceID,VarName,VarValue) values ($SCOInstanceID,'$safeVarName','$safeVarValue')",$link);
	// }
}

function initializeSCO() {

	global $mysqli;
	global $SCOInstanceID;

	// has the SCO previously been initialized?
	$result = $mysqli -> query("select count(VarName) from scormvars where (SCOInstanceID=$SCOInstanceID)");
	list($count) = $result -> fetch_row();

	// $result = mysql_query("select count(VarName) from scormvars where (SCOInstanceID=$SCOInstanceID)",$link);
	// list($count) = mysql_fetch_row($result);

	// not yet initialized - initialize all elements
	if (! $count) {

		// elements that tell the SCO which other elements are supported by this API
		initializeElement('cmi.core._children','student_id,student_name,lesson_location,credit,lesson_status,entry,score,total_time,exit,session_time');
		initializeElement('cmi.core.score._children','raw');

		// student information
		initializeElement('cmi.core.student_name',getFromLMS('cmi.core.student_name'));
		initializeElement('cmi.core.student_id',getFromLMS('cmi.core.student_id'));

		// test score
		initializeElement('cmi.core.score.raw','');
		initializeElement('adlcp:masteryscore',getFromLMS('adlcp:masteryscore'));

		// SCO launch and suspend data
		initializeElement('cmi.launch_data',getFromLMS('cmi.launch_data'));
		initializeElement('cmi.suspend_data','');

		// progress and completion tracking
		initializeElement('cmi.core.lesson_location','');
		initializeElement('cmi.core.credit','credit');
		initializeElement('cmi.core.lesson_status','not attempted');
		initializeElement('cmi.core.entry','ab-initio');
		initializeElement('cmi.core.exit','');

		// seat time
		initializeElement('cmi.core.total_time','0000:00:00');
		initializeElement('cmi.core.session_time','');

	}

	// new session so clear pre-existing session time
	writeElement('cmi.core.session_time','');

	// create the javascript code that will be used to set up the javascript cache,
	$initializeCache = "var cache = new Object();\n";

	$result = $mysqli -> query("select VarName,VarValue from scormvars where (SCOInstanceID=$SCOInstanceID)");

	// $result = mysql_query("select VarName,VarValue from scormvars where (SCOInstanceID=$SCOInstanceID)",$link);
	while (list($varname,$varvalue) = $result -> fetch_row()) {
		// make the value safe by escaping quotes and special characters
		$jvarvalue = addslashes($varvalue);

		// javascript to set the initial cache value
		$initializeCache .= "cache['$varname'] = '$jvarvalue';\n";

	}

	// return javascript for cache initialization to the calling program
	return $initializeCache;

}

// ------------------------------------------------------------------------------------
// LMS-specific code
// ------------------------------------------------------------------------------------
function setInLMS($varname,$varvalue) {
	return "OK";
}

function getFromLMS($varname) {

    global $mysqli;

    $sql = "select name->>'$.en' as name from users where id='".$_REQUEST['user_id']."'";
	$result = $mysqli -> query($sql);
    $name = '';
    while($row = $result->fetch_row()){
        $name = $row[0];
    }

	switch ($varname) {

		case 'cmi.core.student_name':
			// $varvalue = "Addison, Steve";
			$varvalue = $name;
			break;

		case 'cmi.core.student_id':
			// $varvalue = "007";
			$varvalue = $_REQUEST['user_id'];
			break;

		case 'adlcp:masteryscore':
			$varvalue = 0;
			break;

		case 'cmi.launch_data':
			$varvalue = "";
			break;

		default:
			$varvalue = '';

	}

	return $varvalue;

}

?>
