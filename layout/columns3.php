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

// Get the HTML for the settings bits.
$html = theme_solent2017_get_html_for_settings($OUTPUT, $PAGE);

$regionmainbox = 'span9 desktop-first-column';
$regionmain = 'span8 pull-right';
$sidepre = 'span4 desktop-first-column';
$sidepost = 'span3 pull-right';
$current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


$this->page->requires->js_call_amd('theme_solent2017/border', 'init', array());

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<header role="banner" class="navbar navbar-fixed-top<?php echo $html->navbarclass ?> moodle-has-zindex">
    <nav role="navigation" class="navbar-inner">
        <div class="container-fluid">
			<div id="brand_container">
					<a class="brand fullname" href="<?php echo $CFG->wwwroot;?>"><?php echo					
						format_string($SITE->fullname, true, array('context' => context_course::instance(SITEID)));
					?></a>
					
					<a class="brand shortname" href="<?php echo $CFG->wwwroot;?>"><?php 
						echo "Solent<br /><span id='online'>Online</span><br />Learning"
					?></a>
				</div>
            <?php echo $OUTPUT->navbar_button(); ?>
			<?php echo $OUTPUT->ssu_user_menu(); ?>
			<?php echo $OUTPUT->navbar_plugin_output(); ?>  
			<?php if(isloggedin() && !isguestuser()){
					echo "	<div class='nav-collapse collapse'>
								<div id='searchwrap' class='nav'>
									<form id='coursesearch' action='/course/search.php' method='get'>
										<input type='text' id='coursesearchbox_custom' name='search' placeholder='Search for pages...'>
										<input id='coursesearchgo' type='submit'>
									</form>
								</div>
							</div>";
				} ?>
			<?php echo $OUTPUT->search_box(); ?>
                      
            <div class="nav-collapse collapse">
                <?php echo $OUTPUT->custom_menu(); ?>
                <ul class="nav pull-right">
                    <li><?php echo $OUTPUT->page_heading_menu(); ?></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="page-wrap">
<?php
if((!isloggedin() || isguestuser()) && $current_url == $CFG->wwwroot.'/'){
	echo "<div id='logged_out'>";
}
?>

<?php 
global $COURSE;
//if($COURSE->format == 'onetopic' && $PAGE->pagelayout == 'course'){
if($PAGE->pagelayout == 'course'){
	echo $OUTPUT->full_header_ssu();
	echo $OUTPUT->breadcrumbs_ssu();
}else{
	echo $OUTPUT->full_header();
}
?>
	<div id="page" class="container-fluid">
		<?php echo solent_number_of_sections();	?>
		<div id="page-content" class="row-fluid">
			<div id="region-main-box" class="<?php echo $regionmainbox; ?>">
				<div class="row-fluid">
<?php							
if((!isloggedin() || isguestuser()) && $current_url == $CFG->wwwroot.'/'){
	echo "<div id='content_hide'>";
}
?>	
					<section id="region-main" class="<?php echo $regionmain; ?>">
						<?php
						// If in course or unit pages categories add the course title elements
						global $DB;
						if(substr($_SERVER['REQUEST_URI'], 0, 20) == '/course/view.php?id='){
							 include($CFG->dirroot.'/local/course_title_elements.php');
						}						
						echo $OUTPUT->main_content();
						?>
					</section>
<?php	
if((!isloggedin() || isguestuser()) && $current_url == $CFG->wwwroot.'/'){
	echo "</div>"; // end content_hide
}
?>
					<?php echo $OUTPUT->blocks('side-pre', $sidepre); ?>
				</div>
			</div>
			<?php echo $OUTPUT->blocks('side-post', $sidepost); ?>
		</div>
	</div>
<?php	
if((!isloggedin() || isguestuser()) && $current_url == $CFG->wwwroot.'/'){
	echo "</div>"; //end logged out
}
?>	
</div><!-- end wrapper-->	
	<footer class="site-footer" id="page-footer">		
		<?php include($CFG->dirroot.'/theme/solent2017/layout/footer.php'); ?>
	</footer>
		<?php echo $OUTPUT->standard_end_of_body_html() ?>
</div>
</body>
</html>
