<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<?php
global $nokri;
/* Linkedin response after logged in */
//include( 'template-parts/linkedin-access.php' );
?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<?php global $nokri; 
if( isset( $nokri['banners_code'] ) && $nokri['banners_code'] != '')
{
	echo ($nokri['banners_code']);
}
/* Search Page */
$search_page_layout = '';
if((isset($nokri['search_page_layout'])) && $nokri['search_page_layout']  != '' )
{
 	$search_page_layout =  ($nokri['search_page_layout']);
}
?>
 <link rel="profile" href="http://gmpg.org/xfn/11">
 <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
 <?php
 if ( ! ( function_exists( 'has_site_icon' ) && has_site_icon() ) ) { ?>
<link rel="shortcut icon" href="
<?php echo get_template_directory_uri(); ?>/images/favicon.ico" type="image/x-icon">
 <?php  } 
 wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
global $nokri;
/* header style  */
$header_style = '';
if((isset($nokri['main_header_style'])) && $nokri['main_header_style']  != '' )
{
	 $header_style =  ($nokri['main_header_style']);
}
 if((isset($nokri['loader_img_switch'])) && $nokri['loader_img_switch']  == 1 ) { 
$loader_text = ( isset($nokri['loader_text']) && $nokri['loader_text'] != ""  ) ? $nokri['loader_text'] : '';
/* Profile Pic  */
$preldr_link[0] =  get_template_directory_uri(). '/images/loader.gif';
if( isset( $nokri['loader_img']['url'] ) && $nokri['loader_img']['url'] != "" )
{
	$preldr_link = array($nokri['loader_img']['url']);	
}
?>
<div id="spinner">
    <div class="spinner-img"><img alt="<?php echo esc_html__( 'Preloader', 'nokri' ); ?>" src="<?php echo  esc_url($preldr_link[0]); ?>"/>
      <h2><?php echo ($loader_text ); ?></h2>
    </div>
  </div>
<?php
 }
$is_map = false;
if(wp_basename(get_page_template()) == 'page-search.php' &&  $search_page_layout == '3')
{
	$is_map = true;
}
if(basename(get_page_template()) == 'page-dashboard.php' || $is_map)
{
echo '<div class="navbar-fixed-top">'; 
if ($header_style == '1') {
?>
<div class="top-bar">
         <div class="container">
            <div class="row">
            	<?php if((isset($nokri['social_switch'])) && $nokri['social_switch']  == 1 ) { ?>
               <div class="col-lg-7 col-md-5 col-sm-5 col-xs-12">
                      <?php echo nokri_top_bar_social_sorter(); ?>
               </div>
               <?php } if((isset($nokri['contact_switch'])) && $nokri['contact_switch']  == 1 ) { ?>
               <div class="col-lg-5 col-md-7 col-sm-7 col-xs-12">
                  <div class="header-info">
                    <?php echo nokri_top_bar_sorter(); ?>
                  </div>
               </div>
               <?php } ?>
            </div>
         </div>
      </div> 
 <?php
}
} 
 if((isset($nokri['header_top_bar'])) && $nokri['header_top_bar']  == 1  && $header_style != '1')
{ 
?>
  <div class="top-bar">
         <div class="container">
            <div class="row">
            	<?php if((isset($nokri['social_switch'])) && $nokri['social_switch']  == 1 ) { ?>
               <div class="col-lg-7 col-md-5 col-sm-5 col-xs-12">
                      <?php echo nokri_top_bar_social_sorter(); ?>
               </div>
               <?php } if((isset($nokri['contact_switch'])) && $nokri['contact_switch']  == 1 ) { ?>
               <div class="col-lg-5 col-md-7 col-sm-7 col-xs-12">
                  <div class="header-info">
                    <?php echo nokri_top_bar_sorter(); ?>
                  </div>
               </div>
               <?php } ?>
            </div>
         </div>
      </div>
<?php }  
if($header_style == '2')
{
	get_template_part( 'template-parts/headers/header', '2' );
}
else
{
	$header = '1';
	get_template_part( 'template-parts/headers/header', $header );
}