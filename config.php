<?php
/**
 * Theme version info
 *
 * @package    theme
 * @subpackage paradiso_air
 * @copyright 2012 onwards Paradiso Solutions {@link http://paradisosolutions.com}
 */


$THEME->name = 'paradiso_air';
$THEME->parents = array('base');
$THEME->sheets = array(
    'core',     /** Must come first**/
    'admin',
    'blocks',
    'calendar',
    'course',
    'user',
    'dock',
    'grade',
    'message',
    'modules',
    'question',
    'css3'      /** Sets up CSS 3 + browser specific styles **/
);

$THEME->enable_dock = true;

// Remember we don't put the file extension so no .js at the end
$THEME->javascripts_footer = array('jquery-1.9.0.min', 'aascript');
