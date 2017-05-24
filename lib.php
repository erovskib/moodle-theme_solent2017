<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Moodle's solent2017 theme, an example of how to make a Bootstrap theme
 *
 * DO NOT MODIFY THIS THEME!
 * COPY IT FIRST, THEN RENAME THE COPY AND MODIFY IT INSTEAD.
 *
 * For full information about creating Moodle themes, see:
 * http://docs.moodle.org/dev/Themes_2.0
 *
 * @package   theme_solent2017
 * @copyright 2013 Moodle, moodle.org
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Parses CSS before it is cached.
 *
 * This function can make alterations and replace patterns within the CSS.
 *
 * @param string $css The CSS
 * @param theme_config $theme The theme config object.
 * @return string The parsed CSS The parsed CSS.
 */
function theme_solent2017_process_css($css, $theme) {
    global $OUTPUT;

    // Set the background image for the logo.
    $logo = $OUTPUT->get_logo_url(null, 75);
    $css = theme_solent2017_set_logo($css, $logo);

    // Set custom CSS.
    if (!empty($theme->settings->customcss)) {
        $customcss = $theme->settings->customcss;
    } else {
        $customcss = null;
    }
    $css = theme_solent2017_set_customcss($css, $customcss);

    return $css;
}

/**
 * Adds the logo to CSS.
 *
 * @param string $css The CSS.
 * @param string $logo The URL of the logo.
 * @return string The parsed CSS
 */
function theme_solent2017_set_logo($css, $logo) {
    $tag = '[[setting:logo]]';
    $replacement = $logo;
    if (is_null($replacement)) {
        $replacement = '';
    }

    $css = str_replace($tag, $replacement, $css);

    return $css;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_solent2017_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM and ($filearea === 'logo' || $filearea === 'smalllogo')) {
        $theme = theme_config::load('solent2017');
        // By default, theme files must be cache-able by both browsers and proxies.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}

/**
 * Adds any custom CSS to the CSS before it is cached.
 *
 * @param string $css The original CSS.
 * @param string $customcss The custom CSS to add.
 * @return string The CSS which now contains our custom CSS.
 */
function theme_solent2017_set_customcss($css, $customcss) {
    $tag = '[[setting:customcss]]';
    $replacement = $customcss;
    if (is_null($replacement)) {
        $replacement = '';
    }

    $css = str_replace($tag, $replacement, $css);

    return $css;
}

/**
 * Returns an object containing HTML for the areas affected by settings.
 *
 * Do not add solent2017 specific logic in here, child themes should be able to
 * rely on that function just by declaring settings with similar names.
 *
 * @param renderer_base $output Pass in $OUTPUT.
 * @param moodle_page $page Pass in $PAGE.
 * @return stdClass An object with the following properties:
 *      - navbarclass A CSS class to use on the navbar. By default ''.
 *      - heading HTML to use for the heading. A logo if one is selected or the default heading.
 *      - footnote HTML to use as a footnote. By default ''.
 */
function theme_solent2017_get_html_for_settings(renderer_base $output, moodle_page $page) {
    global $CFG;
    $return = new stdClass;

    $return->navbarclass = '';
    if (!empty($page->theme->settings->invert)) {
        $return->navbarclass .= ' navbar-inverse';
    }

    // Only display the logo on the front page and login page, if one is defined.
    if (!empty($page->theme->settings->logo) &&
            ($page->pagelayout == 'frontpage' || $page->pagelayout == 'login')) {
        $return->heading = html_writer::tag('div', '', array('class' => 'logo'));
    } else {
        $return->heading = $output->page_heading();
    }

    $return->footnote = '';
    if (!empty($page->theme->settings->footnote)) {
        $return->footnote = '<div class="footnote text-center">'.format_text($page->theme->settings->footnote).'</div>';
    }

    return $return;
}

/**
 * All theme functions should start with theme_solent2017_
 * @deprecated since 2.5.1
 */
function solent2017_process_css() {
    throw new coding_exception('Please call theme_'.__FUNCTION__.' instead of '.__FUNCTION__);
}

/**
 * All theme functions should start with theme_solent2017_
 * @deprecated since 2.5.1
 */
function solent2017_set_logo() {
    throw new coding_exception('Please call theme_'.__FUNCTION__.' instead of '.__FUNCTION__);
}

/**
 * All theme functions should start with theme_solent2017_
 * @deprecated since 2.5.1
 */
function solent2017_set_customcss() {
    throw new coding_exception('Please call theme_'.__FUNCTION__.' instead of '.__FUNCTION__);
}


// SSU_AMEND START - ADD SECTIONS DROPDOWN 
function solent_number_of_sections(){
	global $CFG, $COURSE,$PAGE, $USER, $DB;
	if ($PAGE->user_is_editing()){
		$i = 1;
		$courseformatoptions = course_get_format($COURSE)->get_format_options();
		$secnum = $courseformatoptions['numsections'];
		$oncoursepage = substr($_SERVER['REQUEST_URI'] ,1,11);

		if ($oncoursepage == 'course/view'){
			echo 	'<div id="course-content" style="text-align:center;">
					<fieldset class="coursefieldset">
					<form action="'. $CFG->wwwroot .'/local/add_sections_query.php" method="post">
					<label for "secnumbers">Number of Weeks/Topics/Tabs:&nbsp; 
					<select name="secnumbers">';
			while ($i<=52) {
				   if ($i == $secnum){
					   $selected = 'selected = "selected"';
						}else{
						$selected ='';
						}
					   echo '<option value="'.$i.'"  '.$selected.'>'.$i++.' </option>';
			}
			   
			echo '  <input type="hidden" name="courseid" value="'. $COURSE->id .'"/>';
			echo '&nbsp;&nbsp;&nbsp;<input type="submit" value ="Save">
				 </select></label></form></fieldset><br />';
		}
		
		if ($COURSE->id > 1){
			//get current option
			$option = $DB->get_record('theme_header', array('course' => $COURSE->id), '*');
			$options = array(1, 2, 3, 4);
//print_r($option);	
		
			echo 	'<fieldset class="coursefieldset">
					<form action="'. $CFG->wwwroot .'/local/set_header_image.php" method="post">
					<label for "opt">Select header image:&nbsp; 
					<select name="opt">';	
					
						echo '<option value="0">Not selected</option>';
						foreach($options as $val){
							echo '<option value="' . $val . '"'; if($val == $option->opt) echo 'selected="selected"'; echo '>Option ' . $val . '</option>';
						}
					  
			   
			echo '  <input type="hidden" name="course" value="'. $COURSE->id .'"/>';
			echo '  <input type="hidden" name="id" value="'. $option->id .'"/>';
			echo '&nbsp;&nbsp;&nbsp;<input type="submit" value ="Save">
				 </select></label></form></fieldset></div>';
		}
	}
}

function solent_header_image(){
	// global $CFG, $COURSE,$PAGE, $USER;
	// if ($PAGE->user_is_editing()){
		// $i = 1;
		// //$courseformatoptions = course_get_format($COURSE)->get_format_options();
		// //$secnum = $courseformatoptions['numsections'];
		// //$oncoursepage = substr($_SERVER['REQUEST_URI'] ,1,11);

		// if ($COURSE->id > 1){
			// echo 	'<div id = "course-content" style="text-align:center;">
					// <fieldset id="coursefieldset">
					// <form action="'. $CFG->wwwroot .'/local/set_header_image.php" method="post">
					// <span style="white-space:nowrap">
					// <label for "headerimg">Select header image:&nbsp; </label>
					// <select name="headerimg">';			
					   // echo '<option value="">Not selected</option>';
					   // echo '<option value="1">Option 1</option>';
					   // echo '<option value="2">Option 2</option>';
					   // echo '<option value="3">Option 3</option>';
			   
			// echo '  <input type="hidden" name="courseid" value="'. $COURSE->id .'"/>';
			// echo '&nbsp;&nbsp;&nbsp;<input type="submit" value ="save">
				 // </select></span></form></fieldset></div>	';
		// }
	// }
}

// SSU_AMEND END

function enrol_get_current_courses($fields = NULL, $sort = 'visible DESC,sortorder ASC', $limit = 0) {
	global $DB, $USER;
	// Guest account does not have any courses
	if (isguestuser() or !isloggedin()) {
		return(array());
	}
	$basefields = array('id', 'category', 'sortorder',
						'shortname', 'fullname', 'idnumber',
						'startdate', 'visible',
						'groupmode', 'groupmodeforce', 'cacherev');
	if (empty($fields)) {
		$fields = $basefields;
	} else if (is_string($fields)) {
		// turn the fields from a string to an array
		$fields = explode(',', $fields);
		$fields = array_map('trim', $fields);
		$fields = array_unique(array_merge($basefields, $fields));
	} else if (is_array($fields)) {
		$fields = array_unique(array_merge($basefields, $fields));
	} else {
		throw new coding_exception('Invalid $fileds parameter in enrol_get_my_courses()');
	}
	if (in_array('*', $fields)) {
		$fields = array('*');
	}
	$orderby = "";
	$sort    = trim($sort);
	if (!empty($sort)) {
		$rawsorts = explode(',', $sort);
		$sorts = array();
		foreach ($rawsorts as $rawsort) {
			$rawsort = trim($rawsort);
			if (strpos($rawsort, 'c.') === 0) {
				$rawsort = substr($rawsort, 2);
			}
			$sorts[] = trim($rawsort);
		}
		$sort = 'c.'.implode(',c.', $sorts);
		$orderby = "ORDER BY $sort";
	}
	$wheres = array("c.id <> :siteid");
	$params = array('siteid'=>SITEID);
	if (isset($USER->loginascontext) and $USER->loginascontext->contextlevel == CONTEXT_COURSE) {
		// list _only_ this course - anything else is asking for trouble...
		$wheres[] = "courseid = :loginas";
		$params['loginas'] = $USER->loginascontext->instanceid;
	}
	$coursefields = 'c.' .join(',c.', $fields);
	$ccselect = ', ' . context_helper::get_preload_record_columns_sql('ctx');
	$ccjoin = "LEFT JOIN {context} ctx ON (ctx.instanceid = c.id AND ctx.contextlevel = :contextlevel)";
	$params['contextlevel'] = CONTEXT_COURSE;
	$wheres = implode(" AND ", $wheres);
	//note: we can not use DISTINCT + text fields due to Oracle and MS limitations, that is why we have the subselect there
// SSU_AMEND START - BOOKMARKS
// Edit course parent categories each year to show past courses (opposite of current block)
	$sql = "SELECT $coursefields $ccselect
			  FROM {course} c
			  JOIN (SELECT DISTINCT e.courseid
					  FROM {enrol} e
					  JOIN {user_enrolments} ue ON (ue.enrolid = e.id AND ue.userid = :userid)
					 WHERE ue.status = :active AND e.status = :enabled AND ue.timestart < :now1 AND (ue.timeend = 0 OR ue.timeend > :now2)
				   ) en ON (en.courseid = c.id)
		   $ccjoin
			  JOIN {course_categories} cc ON c.category = cc.id
			 WHERE $wheres
			 AND cc.parent IN  (23, 156, 158, 157, 161, 201, 159, 160)
		  $orderby";
// SSU_AMEND END
	$params['userid']  = $USER->id;
	$params['active']  = ENROL_USER_ACTIVE;
	$params['enabled'] = ENROL_INSTANCE_ENABLED;
	$params['now1']    = round(time(), -2); // improves db caching
	$params['now2']    = $params['now1'];
	$courses = $DB->get_records_sql($sql, $params, 0, $limit);
	// preload contexts and check visibility
	foreach ($courses as $id=>$course) {
		context_helper::preload_from_record($course);
		if (!$course->visible) {
			if (!$context = context_course::instance($id, IGNORE_MISSING)) {
				unset($courses[$id]);
				continue;
			}
			if (!has_capability('moodle/course:viewhiddencourses', $context)) {
				unset($courses[$id]);
				continue;
			}
		}
		$courses[$id] = $course;
	}
	//wow! Is that really all? :-D
	return $courses;
}