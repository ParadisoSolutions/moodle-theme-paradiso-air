<?php
include $CFG->dirroot.'/lib/mdl/moodle_database.php';

global $DB, $USER;

$hassidepre = $PAGE->blocks->region_has_content('side-pre', $OUTPUT);
$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);
$showsidepre = $hassidepre && !$PAGE->blocks->region_completely_docked('side-pre', $OUTPUT);
$showsidepost = $hassidepost && !$PAGE->blocks->region_completely_docked('side-post', $OUTPUT);

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
$sql_grp1 = $DB->get_record_sql('SELECT data FROM {user_info_data} WHERE fieldid = ? AND userid = ?', array(3, $USER->id));
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
}
echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <meta name="description" content="<?php p(strip_tags(format_text($SITE->summary, FORMAT_HTML))) ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
</head>
<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<div id="page">
  
    <div id="page-header" class="clearfix">
  <img class="logo" src="<?php echo $OUTPUT->pix_url('logo','theme') ?>">
        <div class="headermenu"><?php
            echo $OUTPUT->login_info();
            echo $OUTPUT->lang_menu();
            echo $PAGE->headingmenu;
        ?>
        </div>
	<h1 class="headermain"><?php //echo $PAGE->heading ?></h1>
    <?php
	// TODO: Use Moodle Data API to handle this query (we can't use php mysql functions to interact with DB). HC 2013-04-23.
        $gcon = mysql_connect($CFG->dbhost,$CFG->dbuser,$CFG->dbpass);
        mysql_select_db($CFG->dbname);        
        $sql_logo = "SELECT gm.*, gl.* FROM ".$CFG->prefix."groups_members gm, ".$CFG->prefix."groups_logo gl  WHERE gm.groupid = gl.group_id 
         and gm.userid = ".$USER->id;
        $qry_logo = mysql_query($sql_logo);
        $no_logo  = mysql_num_rows($qry_logo);
        $rslt_logo = mysql_fetch_array($qry_logo);
        if($no_logo>0){
        $logo_style = "style=\"background-image:url('". new moodle_url('/groupimage/logo/').$rslt_logo['filename']." ');\"";
        }else {$logo_style="";}
    ?>
    
	<center><div class="banner" <?php if($banner_change){ ?>style="margin-top: 25px;
text-align: center;
background-image: url(<?php echo $CFG->wwwroot.'/groupimage/logo/'.$banner_change;?>);
height: 107px;
width: 800px;"
<?php } ?></div></center>
        <?php if ($hascustommenu) { ?>
        <div id="custommenu"><?php echo $custommenu; ?></div>
         <?php } ?>
    </div>
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
    <div id="page-footer">
        <p class="helplink">
        <?php echo page_doc_link(get_string('moodledocslink')) ?>
        </p>
    </div>
</div>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
