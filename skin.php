<?php if (!defined('PmWiki')) exit();
/* PmWiki Colorimetry skin
 *
 * Examples at: http://pmwiki.com/Cookbook/Colorimetry and http://solidgone.org/Skins/
 * Copyright (c)2016 David Gilbert
 * This work is licensed under a Creative Commons Attribution-Share Alike 4.0 International License.
 * Please retain the links in the footer.
 * http://creativecommons.org/licenses/by-sa/4.0/
 */
global $FmtPV;
$FmtPV['$SkinName'] = '"Colorimetry"';
$FmtPV['$SkinVersion'] = '"1.2.2"';

global $PageLogoUrl, $PageLogoUrlHeight, $PageLogoUrlWidth, $HTMLStylesFmt ,$SkinTheme;
if (!empty($PageLogoUrl)) {
	dg_SetLogoHeightWidth(15, 16);
	$HTMLStylesFmt['colorimetry'] .=
		'#logo .sitetitle a{height:' .$PageLogoUrlHeight .'; background: url(' .$PageLogoUrl .') left 16px no-repeat} '.
		'#logo .sitetitle a, #logo .sitetag{padding-left: ' .$PageLogoUrlWidth .'} ';
}
global $SkinWidth,$SkinSidebarWidth,$SkinWidthUnit;
SDV($SkinWidth,850);
SDV($SkinSidebarWidth,195);  #good percentage width is 25
SDV($SkinWidthUnit,'px');  #only use 'px' or '%'
$HTMLStylesFmt['colorimetry'] .=	'#header, #page, #footer-inner { width: '.$SkinWidth.$SkinWidthUnit.'; } '.
	'#content { width: '.($SkinWidthUnit=='px'?($SkinWidth-$SkinSidebarWidth-55) :(100-$SkinSidebarWidth-5)) .$SkinWidthUnit.'; } '.
	'#sidebar { width: '.$SkinSidebarWidth .$SkinWidthUnit.'; } ';

$SkinColor = dg_SetSkinColor('green', array('green','orange','blue','purple','yellow','pink'));

# ----------------------------------------
# - Standard Skin Setup
# ----------------------------------------
$FmtPV['$WikiTitle'] = '$GLOBALS["WikiTitle"]';
$FmtPV['$WikiTag'] = '$GLOBALS["WikiTag"]';

# Move any (:noleft:) or SetTmplDisplay('PageLeftFmt', 0); directives to variables for access in jScript.
$FmtPV['$LeftColumn'] = "\$GLOBALS['TmplDisplay']['PageLeftFmt']";
Markup_e('noleft', 'directives',  '/\\(:noleft:\\)/i', "SetTmplDisplay('PageLeftFmt',0)");
$FmtPV['$RightColumn'] = "\$GLOBALS['TmplDisplay']['PageRightFmt']";
Markup_e('noright', 'directives',  '/\\(:noright:\\)/i', "SetTmplDisplay('PageRightFmt',0)");
$FmtPV['$ActionBar'] = "\$GLOBALS['TmplDisplay']['PageActionFmt']";
Markup_e('noaction', 'directives',  '/\\(:noaction:\\)/i', "SetTmplDisplay('PageActionFmt',0)");
$FmtPV['$TabsBar'] = "\$GLOBALS['TmplDisplay']['PageTabsFmt']";
Markup_e('notabs', 'directives',  '/\\(:notabs:\\)/i', "SetTmplDisplay('PageTabsFmt',0)");
$FmtPV['$SearchBar'] = "\$GLOBALS['TmplDisplay']['PageSearchFmt']";
Markup_e('nosearch', 'directives',  '/\\(:nosearch:\\)/i', "SetTmplDisplay('PageSearchFmt',0)");
$FmtPV['$TitleGroup'] = "\$GLOBALS['TmplDisplay']['PageTitleGroupFmt']";
Markup_e('notitlegroup', 'directives',  '/\\(:notitlegroup:\\)/i', "SetTmplDisplay('PageTitleGroupFmt',0)");
Markup_e('notitle', 'directives',  '/\\(:notitle:\\)/i', "dg_NoTitle()");
Markup('fieldset', 'inline', '/\\(:fieldset:\\)/i', "<fieldset>");
Markup('fieldsetend', 'inline', '/\\(:fieldsetend:\\)/i', "</fieldset>");

# Override pmwiki styles otherwise they will override styles declared in css
global $HTMLStylesFmt;
$HTMLStylesFmt['pmwiki'] = '';

# Add a custom page storage location
global $WikiLibDirs;
$PageStorePath = dirname(__FILE__)."/wikilib.d/{\$FullName}";
$where = count($WikiLibDirs);
if ($where>1) $where--;
array_splice($WikiLibDirs, $where, 0, array(new PageStore($PageStorePath)));

# ----------------------------------------
# - Standard Skin Functions
# ----------------------------------------
function dg_SetSkinColor($default, $valid_colors){
global $SkinColor, $ValidSkinColors, $_GET;
	if ( !is_array($ValidSkinColors) ) $ValidSkinColors = array();
	$ValidSkinColors = array_merge($ValidSkinColors, $valid_colors);
	if ( isset($_GET['color']) && in_array($_GET['color'], $ValidSkinColors) )
		$SkinColor = $_GET['color'];
	elseif ( !in_array($SkinColor, $ValidSkinColors) )
		$SkinColor = $default;
	return $SkinColor;
}
function dg_PoweredBy(){
	print ('<a href="http://pmwiki.com/'.($GLOBALS['bi_BlogIt_Enabled']?'Cookbook/BlogIt">BlogIt':'">PmWiki').'</a>');
}
# Determine logo height and width
function dg_SetLogoHeightWidth ($wPad, $hPad=0){
global $PageLogoUrl, $PageLogoUrlHeight, $PageLogoUrlWidth;
	if (!isset($PageLogoUrlWidth) || !isset($PageLogoUrlHeight)){
		$size = @getimagesize($PageLogoUrl);
		if (!isset($PageLogoUrlWidth))  SDV($PageLogoUrlWidth, ($size ?$size[0]+$wPad :0) .'px');
		if (!isset($PageLogoUrlHeight))  SDV($PageLogoUrlHeight, ($size ?$size[1]+$hPad :0) .'px');
	}
}
function dg_NoTitle(){
	SetTmplDisplay('PageTitleGroupFmt',0);SetTmplDisplay('PageTitleFmt',0);
}
