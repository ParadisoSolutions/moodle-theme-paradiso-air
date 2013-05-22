<?php
include $CFG->dirroot.'/lib/mdl/moodle_database.php';

Global $DB;

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidepre = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-pre', $OUTPUT));
$hassidepost = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-post', $OUTPUT));
$haslogininfo = (empty($PAGE->layout_options['nologininfo']));

$showsidepre = ($hassidepre && !$PAGE->blocks->region_completely_docked('side-pre', $OUTPUT));
$showsidepost = ($hassidepost && !$PAGE->blocks->region_completely_docked('side-post', $OUTPUT));

$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

$bodyclasses = array();
if ($showsidepre && !$showsidepost) {
    $bodyclasses[] = 'side-pre-only';
} else if ($showsidepost && !$showsidepre) {
    $bodyclasses[] = 'side-post-only';
} else if (!$showsidepost && !$showsidepre) {
    $bodyclasses[] = 'content-only';
}
if ($hascustommenu) {
    $bodyclasses[] = 'has_custom_menu';
}

/*$sql_grp1 = $DB->get_record_sql('SELECT data FROM {user_info_data} WHERE fieldid = ? AND userid = ?', array(3, $USER->id));
if(is_object($sql_grp1)) {
	$sql = $DB->get_record_sql('SELECT filename FROM {groups_logo} WHERE dept_name = ?', array($sql_grp1->data));
	if(is_object($sql)) {
		$banner_change = $sql->filename;
	}	
}
else
{
	$sql2 = $DB->get_record_sql('SELECT filename FROM {groups_logo} WHERE dept_name Like ?', array('Default'));				   
	if(is_object($sql2))	{
		$banner_change = $sql2->filename;
	}	
}*/					   
echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
  <?php echo $OUTPUT->standard_head_html() ?>
</head>
<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">
  <?php //echo $OUTPUT->standard_top_of_body_html() ?>
<div id="page">
<?php if ($hasheading || $hasnavbar) { ?>
    <div id="page-header">
       <img class="logo" src="<?php echo $OUTPUT->pix_url('logo','theme') ?>">
        <?php if ($hasheading) { ?>
        <h1 class="headermain"><?php //echo $PAGE->heading ?></h1>
        <div class="headermenu"><?php
            if ($haslogininfo) {
                echo $OUTPUT->login_info();
            }
            if (!empty($PAGE->layout_options['langmenu'])) {
                echo $OUTPUT->lang_menu();
            }
            echo $PAGE->headingmenu
        ?></div><?php } ?>
<center><div class="banner" <?php if($banner_change){ ?>  style="margin-top: 25px;
text-align: center;
/*background-image: url(<?php //echo $CFG->wwwroot.'/groupimage/logo/'.$banner_change;?>);*/
height: 107px;
width: 800px;"
<?php } ?>
>
</div></center>
        <?php if ($hascustommenu) { ?>
        <div id="custommenu"><?php echo $custommenu; ?></div>
        <?php } ?>
        <?php if ($hasnavbar) { ?>
            <div class="navbar clearfix">
                <div class="breadcrumb"><?php echo $OUTPUT->navbar(); ?></div>
                <div class="navbutton"> <?php echo $PAGE->button; ?></div>
            </div>
        <?php } ?>
    </div>
<?php } ?>
<!-- END OF HEADER -->

    <div id="page-content">
        <div id="region-main-box">
            <div id="region-post-box">

                <div id="region-main-wrap">
                    <div id="region-main">
                        <div class="region-content">
                            <?php echo $OUTPUT->main_content() ?>
                        </div>
                    </div>
                </div>

                <?php if ($hassidepre) { ?>
                <div id="region-pre" class="block-region">
                    <div class="region-content">
                        <?php echo $OUTPUT->blocks_for_region('side-pre') ?>
                    </div>
                </div>
                <?php } ?>

                <?php if ($hassidepost) { ?>
                <div id="region-post" class="block-region">
                    <div class="region-content">
                        <?php echo $OUTPUT->blocks_for_region('side-post') ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

<!-- START OF FOOTER -->
    <?php if ($hasfooter) { ?>
    <div id="page-footer" class="clearfix">
        <p class="helplink"><?php echo page_doc_link(get_string('moodledocslink')) ?></p>
    </div>
    <?php } ?>
</div>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
