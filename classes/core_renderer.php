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

require_once($CFG->dirroot . '/theme/bootstrapbase/renderers.php');

/**
 * solent2017 core renderers.
 *
 * @package    theme_solent2017
 * @copyright  2015 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_solent2017_core_renderer extends theme_bootstrapbase_core_renderer {

    /**
     * Either returns the parent version of the header bar, or a version with the logo replacing the header.
     *
     * @since Moodle 2.9
     * @param array $headerinfo An array of header information, dependant on what type of header is being displayed. The following
     *                          array example is user specific.
     *                          heading => Override the page heading.
     *                          user => User object.
     *                          usercontext => user context.
     * @param int $headinglevel What level the 'h' tag will be.
     * @return string HTML for the header bar.
     */
    public function context_header($headerinfo = null, $headinglevel = 1) {

        if ($this->should_render_logo($headinglevel)) {
            return html_writer::tag('div', '', array('class' => 'logo'));
        }
        return parent::context_header($headerinfo, $headinglevel);
    }

    /**
     * Determines if we should render the logo.
     *
     * @param int $headinglevel What level the 'h' tag will be.
     * @return bool Should the logo be rendered.
     */
    protected function should_render_logo($headinglevel = 1) {
        global $PAGE;

        // Only render the logo if we're on the front page or login page
        // and the theme has a logo.
        $logo = $this->get_logo_url();
        if ($headinglevel == 1 && !empty($logo)) {
            if ($PAGE->pagelayout == 'frontpage' || $PAGE->pagelayout == 'login') {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the navigation bar home reference.
     *
     * The small logo is only rendered on pages where the logo is not displayed.
     *
     * @param bool $returnlink Whether to wrap the icon and the site name in links or not
     * @return string The site name, the small logo or both depending on the theme settings.
     */
    public function navbar_home($returnlink = true) {
        global $CFG;

        $imageurl = $this->get_compact_logo_url(null, 35);
        if ($this->should_render_logo() || empty($imageurl)) {
            // If there is no small logo we always show the site name.
            return $this->get_home_ref($returnlink);
        }
        $image = html_writer::img($imageurl, get_string('sitelogo', 'theme_' . $this->page->theme->name),
            array('class' => 'small-logo'));

        if ($returnlink) {
            $logocontainer = html_writer::link(new moodle_url('/'), $image,
                array('class' => 'small-logo-container', 'title' => get_string('home')));
        } else {
            $logocontainer = html_writer::tag('span', $image, array('class' => 'small-logo-container'));
        }

        // Sitename setting defaults to true.
        if (!isset($this->page->theme->settings->sitename) || !empty($this->page->theme->settings->sitename)) {
            return $logocontainer . $this->get_home_ref($returnlink);
        }

        return $logocontainer;
    }

    /**
     * Returns a reference to the site home.
     *
     * It can be either a link or a span.
     *
     * @param bool $returnlink
     * @return string
     */
    protected function get_home_ref($returnlink = true) {
        global $CFG, $SITE;

        $sitename = format_string($SITE->shortname, true, array('context' => context_course::instance(SITEID)));

        if ($returnlink) {
            return html_writer::link(new moodle_url('/'), $sitename, array('class' => 'brand', 'title' => get_string('home')));
        }

        return html_writer::tag('span', $sitename, array('class' => 'brand'));
    }

    /**
     * Return the theme logo URL, else the site's logo URL, if any.
     *
     * Note that maximum sizes are not applied to the theme logo.
     *
     * @param int $maxwidth The maximum width, or null when the maximum width does not matter.
     * @param int $maxheight The maximum height, or null when the maximum height does not matter.
     * @return moodle_url|false
     */
    public function get_logo_url($maxwidth = null, $maxheight = 100) {
        if (!empty($this->page->theme->settings->logo)) {
            return $this->page->theme->setting_file_url('logo', 'logo');
        }
        return parent::get_logo_url($maxwidth, $maxheight);
    }

    /**
     * Return the theme's compact logo URL, else the site's compact logo URL, if any.
     *
     * Note that maximum sizes are not applied to the theme logo.
     *
     * @param int $maxwidth The maximum width, or null when the maximum width does not matter.
     * @param int $maxheight The maximum height, or null when the maximum height does not matter.
     * @return moodle_url|false
     */
    public function get_compact_logo_url($maxwidth = 100, $maxheight = 100) {
        if (!empty($this->page->theme->settings->smalllogo)) {
            return $this->page->theme->setting_file_url('smalllogo', 'smalllogo');
        }
        return parent::get_compact_logo_url($maxwidth, $maxheight);
    }

// SSU_AMEND START - SSU_USER_MENU
 //}
	
	public function ssu_user_menu($user = null, $withlinks = null) {
        global $USER, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');
        if (is_null($user)) {
            $user = $USER;
        }
        // Note: this behaviour is intended to match that of core_renderer::login_info,
        // but should not be considered to be good practice; layout options are
        // intended to be theme-specific. Please don't copy this snippet anywhere else.
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }
        // Add a class for when $withlinks is false.
        $usermenuclasses = 'usermenu';
        if (!$withlinks) {
            $usermenuclasses .= ' withoutlinks';
        }
        $returnstr = "";
        // If during initial install, return the empty return string.
        if (during_initial_install()) {
            return $returnstr;
        }
        $loginpage = $this->is_login_page();
        $loginurl = get_login_url();
        // If not logged in, show the typical not-logged-in string.
        if (!isloggedin()) {
            $returnstr = get_string('loggedinnot', 'moodle');
            if (!$loginpage) {
                $returnstr .= " (<a href=\"$loginurl\">" . get_string('login') . '</a>)';
            }
            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );
        }

        // If logged in as a guest user, show a string to that effect.
        if (isguestuser()) {
            $returnstr = get_string('loggedinasguest');
            if (!$loginpage && $withlinks) {
                $returnstr .= " (<a href=\"$loginurl\">".get_string('login').'</a>)';
            }
            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );
        }
        // Get some navigation opts.
        $opts = user_get_user_navigation_info($user, $this->page);

// START Amended part of original user_menu function
		if(($USER->department == 'academic') || ($USER->department == 'management') || ($USER->department == 'support') || (is_siteadmin())){	
				//create new links
				$enrol = new stdClass();
				$enrol->itemtype = 'link';
				$enrol->url = new moodle_url('/local/enrolstaff/enrolstaff.php');
				$enrol->title = "Enrolment self-service";
				$enrol->titleidentifier = 'profile,admin';
				$enrol->pix = "i/course";
								
				//get the array number of the logout link
				$noelements = count($opts->navitems) -1;
				//get the logout array element
				$logout = $opts->navitems[$noelements];
				//remove the logout element
				array_splice($opts->navitems, $noelements);
				//add elements back in new order
				array_push($opts->navitems, $enrol);
				array_push($opts->navitems, $logout);	
		}		
// END amended part of original user_menu function

        $avatarclasses = "avatars";
        $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
        $usertextcontents = $opts->metadata['userfullname'];
        // Other user.
        if (!empty($opts->metadata['asotheruser'])) {
            $avatarcontents .= html_writer::span(
                $opts->metadata['realuseravatar'],
                'avatar realuser'
            );
            $usertextcontents = $opts->metadata['realuserfullname'];
            $usertextcontents .= html_writer::tag(
                'span',
                get_string(
                    'loggedinas',
                    'moodle',
                    html_writer::span(
                        $opts->metadata['userfullname'],
                        'value'
                    )
                ),
                array('class' => 'meta viewingas')
            );
        }
        // Role.
        if (!empty($opts->metadata['asotherrole'])) {
            $role = core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['rolename'],
                'meta role role-' . $role
            );
        }
        // User login failures.
        if (!empty($opts->metadata['userloginfail'])) {
            $usertextcontents .= html_writer::span(
                $opts->metadata['userloginfail'],
                'meta loginfailures'
            );
        }
        // MNet.
        if (!empty($opts->metadata['asmnetuser'])) {
            $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['mnetidprovidername'],
                'meta mnet mnet-' . $mnet
            );
        }
        $returnstr .= html_writer::span(
            html_writer::span($usertextcontents, 'usertext') .
            html_writer::span($avatarcontents, $avatarclasses),
            'userbutton'
        );
        // Create a divider (well, a filler).
        $divider = new action_menu_filler();
        $divider->primary = false;
        $am = new action_menu();
        $am->initialise_js($this->page);
        $am->set_menu_trigger(
            $returnstr
        );
        $am->set_alignment(action_menu::TR, action_menu::BR);
        $am->set_nowrap_on_items();
        if ($withlinks) {
            $navitemcount = count($opts->navitems);
            $idx = 0;
            foreach ($opts->navitems as $key => $value) {
                switch ($value->itemtype) {
                    case 'divider':
                        // If the nav item is a divider, add one and skip link processing.
                        $am->add($divider);
                        break;
                    case 'invalid':
                        // Silently skip invalid entries (should we post a notification?).
                        break;
                    case 'link':
                        // Process this as a link item.
                        $pix = null;
                        if (isset($value->pix) && !empty($value->pix)) {
                            $pix = new pix_icon($value->pix, $value->title, null, array('class' => 'iconsmall'));
                        } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                            $value->title = html_writer::img(
                                $value->imgsrc,
                                $value->title,
                                array('class' => 'iconsmall')
                            ) . $value->title;
                        }
                        $al = new action_menu_link_secondary(
                            $value->url,
                            $pix,
                            $value->title,
                            array('class' => 'icon')
                        );
                        if (!empty($value->titleidentifier)) {
                            $al->attributes['data-title'] = $value->titleidentifier;
                        }
                        $am->add($al);
                        break;
                }
                $idx++;
				
                // Add dividers after the first item and before the last item.
                if ($idx == 1 || $idx == $navitemcount - 1) {
                    $am->add($divider);
                }
            }
        }
        return html_writer::div(
            $this->render($am),
            $usermenuclasses
        );
    }	
	
	public function custom_menu($custommenuitems = '') {
        global $CFG, $DB, $USER;
        if (empty($custommenuitems) && !empty($CFG->custommenuitems)) {
            $custommenuitems = $CFG->custommenuitems;
        }
		
		if(isloggedin() && !isguestuser()){			
			
			// $search = 	"
					// <form id='coursesearch' action='/course/search.php' method='get'><input type='text' id='coursesearchbox' name='search'><input id='coursesearchgo' type='submit'></form>
					// ";			
			
			$value = get_user_preferences('course_overview_current_course_sortorder');
			
			if($value){
				$courses = $DB->get_records_sql('SELECT * FROM {course} WHERE id IN (' . $value . ') ORDER BY FIND_IN_SET(id, \'' . $value . '\') LIMIT 20');
			}else{
				$courses = enrol_get_current_courses();
				$courses = array_chunk($courses, 19);
				$courses = reset($courses);
			}		

			$bookmarks = $DB->get_records_sql('SELECT * FROM {mybookmarks} WHERE user = ? ORDER BY sort_order ASC', array($USER->id));		
			$mycourses = "Current Pages|
			"; //this MUST be on a new line otherwise the menu messes up
			
			$mycourses .= "	-My Bookmarks|
							--Bookmark this page|/local/mybookmarks/addbookmark.php
							--Manage my bookmarks|/local/mybookmarks/manage.php
			";
			
			foreach($bookmarks as $k=>$v){
				$mycourses .= "--" . $v->bookmark_name . "|" . $v->url ." 
				"; //this MUST be on a new line otherwise the menu messes up
			}
			
			if($courses){
				foreach($courses as $k=>$v){
					$mycourses .= "-" . $v->fullname . "|" . $CFG->wwwroot . "/course/view.php?id=" . $v->id . " 
					"; //this MUST be on a new line otherwise the menu messes up
				}		
			}	
					
			//$custommenuitems =  $search . $mycourses . $custommenuitems;
			$custommenuitems =  $mycourses . $custommenuitems;
		}			
					
        $custommenu = new custom_menu($custommenuitems, current_language());
		
        return $this->render_custom_menu($custommenu);
    }
	
	 /**
     * This code renders the navbar button to control the display of the custom menu
     * on smaller screens.
     *
     * Do not display the button if the menu is empty.
     *
     * @return string HTML fragment
     */
    protected function navbar_button() {
        global $CFG;
        if (empty($CFG->custommenuitems) && $this->lang_menu() == '') {
            return '';
        }              
		
		$iconbar1 = html_writer::tag('span', '', array('class' => 'icon-bar top-bar'));
        $iconbar2 = html_writer::tag('span', '', array('class' => 'icon-bar middle-bar'));
        $iconbar3 = html_writer::tag('span', '', array('class' => 'icon-bar bottom-bar'));      
        
		$button = html_writer::tag('a', $iconbar1 . "\n" . $iconbar2. "\n" . $iconbar3, array(
            'class'       => 'btn btn-navbar collapsed',
            'data-toggle' => 'collapse',
            'data-target' => '.nav-collapse'
        ));
        return $button;
    }
	
	 /*
     * This renders the navbar.
     * Uses bootstrap compatible html.
     */
    public function navbar() {
        $items = $this->page->navbar->get_items();
        if (empty($items)) {
            return '';
        }

        $breadcrumbs = array();
        foreach ($items as $item) {			
            $item->hideicon = true;					
			if($item->type == 20){
				$text = substr($item->text, 0, strpos($item->text, "_"));				
				if($text){
					$item->text = $text;				
				}
				$breadcrumbs[] = $this->render($item);
			}
			
			$schools = array(156, 158, 157, 161, 201, 159, 160, 193, 200, 202, 203, 77);
			if($item->type == 10){
				if(in_array($item->key, $schools)){
					$breadcrumbs[] = $this->render($item);
				}
			}
			
			$types = array(0, 30, 40, 50, 60, 70, 71, 80);
			if(in_array($item->type, $types) ){
				$breadcrumbs[] = $this->render($item);
			}
        }
	
        $divider = '<span class="divider">'.get_separator().'</span>';
        $list_items = '<li>'.join(" $divider</li><li>", $breadcrumbs).'</li>';	
        $title = '<span class="accesshide" id="navbar-label">'.get_string('pagepath').'</span>';
        return $title . '<nav aria-labelledby="navbar-label"><ul class="breadcrumb">' .
                $list_items . '</ul></nav>';
    }
	
	public function full_header_ssu() {
		global $COURSE, $DB;
		$opt = $DB->get_record('theme_header', array('course' => $COURSE->id), '*');
		if($opt){
			$opt = $opt->opt;
		}else{
			$record = new stdclass;
			$record->id = null;
			$record->course = $COURSE->id;
			$record->opt = 1;
			$DB->insert_record('theme_header', $record, $returnid=true);
			$opt = 1;
		}
		
		// $html = html_writer::start_tag('header', array('id' => 'page-header', 'class' => 'clearfix'));
        // $html .= $this->context_header();
        // $html .= html_writer::start_div('clearfix', array('id' => 'page-navbar'));
        // $html .= html_writer::tag('div', $this->navbar(), array('class' => 'breadcrumb-nav'));
        // $html .= html_writer::div($this->page_heading_button(), 'breadcrumb-button');
        // $html .= html_writer::end_div();
        // $html .= html_writer::tag('div', $this->course_header(), array('id' => 'course-header'));
        // $html .= html_writer::end_tag('header');
        // return $html;


		$html = html_writer::start_tag('header', array('id'=>'page-header-unit', 'class'=>'clearfix'));
        $html .= $this->context_header();
		if($COURSE->id == 18863 || $COURSE->id == 22854){
			$html .= html_writer::start_div('clearfix', array('id'=>'page-navbar-unit', 'class'=>'tts'));
		}else{
			$html .= html_writer::start_div('clearfix', array('id'=>'page-navbar-unit', 'class'=>'opt'.$opt));
		}
		$html .= html_writer::start_div('unit_title_container');
		$coursenamearray = explode("(Start", $COURSE->fullname, 2);
		$coursename = $coursenamearray[0];
		$html .= html_writer::start_div('unit_title') . $coursename . html_writer::end_div();
        $html .= html_writer::end_div();
        $html .= html_writer::end_div();
        $html .= html_writer::end_tag('header');
        return $html; 
    }

	public function breadcrumbs_ssu() {
		$html = html_writer::start_tag('header', array('id' => 'page-header-crumbs', 'class' => 'clearfix'));
        $html .= $this->context_header();
        $html .= html_writer::start_div('clearfix', array('id' => 'page-navbar'));
        $html .= html_writer::tag('div', $this->navbar(), array('class' => 'breadcrumb-nav'));
		$html .= html_writer::div($this->page_heading_button(), 'breadcrumb-button');
        $html .= html_writer::end_tag('header');
        return $html;
	}

// SSU_AMEND END	
}