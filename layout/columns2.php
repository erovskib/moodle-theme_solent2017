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
 * The two column layout.
 *
 * @package   theme_solent2017
 * @copyright 2013 Moodle, moodle.org
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Get the HTML for the settings bits.
$html = theme_solent2017_get_html_for_settings($OUTPUT, $PAGE);

$regionmain = 'span9 pull-right';
$sidepre = 'span3 desktop-first-column';

$this->page->requires->js_call_amd('theme_solent2017/border', 'init', array());

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body <?php echo $OUTPUT->body_attributes('two-column'); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<header role="banner" class="navbar navbar-fixed-top<?php echo $html->navbarclass ?> moodle-has-zindex">
    <nav role="navigation" class="navbar-inner">
        <div class="container-fluid">
			<div id="brand_container">
				<a class="brand" href="<?php echo $CFG->wwwroot;?>"><?php echo					
					format_string($SITE->fullname, true, array('context' => context_course::instance(SITEID)));
				?></a>
				<a class="brand shortname" href="<?php echo $CFG->wwwroot;?>"><?php 
					//echo format_string($SITE->shortname, true, array('context' => context_course::instance(SITEID)));
					echo "Solent<br /><span id='online'>Online</span><br />Learning"
				?></a>
			</div>
            <?php echo $OUTPUT->navbar_button(); ?>
<? // SSU_AMEND START - SSU_USER_MENU ?>
			<?php echo $OUTPUT->ssu_user_menu(); ?>
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
<? // SSU_AMEND END ?>
            <?php echo $OUTPUT->navbar_plugin_output(); ?>
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
	<div id="page" class="container-fluid">
		<?php echo $OUTPUT->full_header(); ?>
		<div id="page-content" class="row-fluid">
			<section id="region-main" class="<?php echo $regionmain; ?>">
				<?php
				echo $OUTPUT->course_content_header();
				echo $OUTPUT->main_content();
				echo $OUTPUT->course_content_footer();
				?>
			</section>
			<?php echo $OUTPUT->blocks('side-pre', $sidepre);
			?>
		</div>
	</div>
</div>
		<footer class="site-footer" id="page-footer">
			<div id="course-footer"><?php //echo $OUTPUT->course_footer(); ?></div>
			<?php include_once 'footer.php'; ?>
		</footer>

		<?php echo $OUTPUT->standard_end_of_body_html() ?>
	
</body>
</html>