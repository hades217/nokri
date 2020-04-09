<?php
 /* Template Name: Page Search */ 
/**
 * The template for displaying Pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#page-search
 *
 * @package nokri
 */
get_header();
global $nokri;


/* Getting Title From Query String */ 
$title	=	'';
if( isset( $_GET['job-title'] ) && $_GET['job-title'] != "" )
{
	$title	=	$_GET['job-title'];
}
/*RTL check*/ 
$rtl_class = '';
if(is_rtl())
{
	$rtl_class = "flip";
}
/* search section bg */ 
$search_bg_url = '';
 if ( isset( $nokri['search_bg_img'] ) )
{
	$search_bg_url = nokri_getBGStyle('search_bg_img');
}
 /* Getting All Taxonomy From Query String */
 $taxonomies	=	array('job_type','ad_title', 'cat_id','job_category','job_tags','job_qualifications','job_level','job_salary','job_currency','job_skills','job_experience','job_currency','job_shift','job_class','job-location');
foreach( $taxonomies as $tax )
{
	$$tax = '';
	if( isset( $_GET[$tax] ) && $_GET[$tax] != ""  )
		{
			$$tax	=	array(
			array('taxonomy' => $tax,'field'    => 'term_id','terms'   => $_GET[$tax]),);	
		}
}
$category	=	'';
if( isset( $_GET['cat-id'] ) && $_GET['cat-id'] != ""  )
{
	$category	=	array(
		array(
		'taxonomy' => 'job_category',
		'field'    => 'term_id',
		'terms'    => $_GET['cat-id'],
		),
	);	
}	

$location	=	'';
if( isset( $_GET['job-location'] ) && $_GET['job-location'] != ""  )
{
	$location	=	array(
		array(
		'taxonomy' => 'ad_location',
		'field'    => 'term_id',
		'terms'    => $_GET['job-location'],
		),
	);	
}

$location_keyword	=	'';
if( isset( $_GET['loc_keyword'] ) && $_GET['loc_keyword'] != ""  )
{
	$location_keyword	=	array(
		array(
		'taxonomy'     => 'ad_location',
		'field'        => 'name',
		'terms'        => $_GET['loc_keyword'],
		 'operator'    => 'LIKE'
		),
	);	
}

/* Custom feilds search satrts */
$custom_search = array();
if( isset( $_GET['custom'] ) )
{
	foreach($_GET['custom'] as $key => $val)
	{
		if( is_array($val) )
		{
			$arr = array();
			$metaKey = '_nokri_tpl_field_'.$key;
			foreach ($val as $v)
			{ 
				 $custom_search[] = array(
				  'key'     => $metaKey,
				  'value'   => $v,
				  'compare' => 'LIKE',
				 ); 
			}
		}
		else
		{
			if(trim( $val ) == "0" ) { continue; }
			$val =  stripslashes_deep($val);
			$metaKey = '_nokri_tpl_field_'.$key;
			$custom_search[] = array(
			 'key'     => $metaKey,
			 'value'   => $val,
			 'compare' => 'LIKE',
			);     
		}
	}
}


/* Custom feilds search ends */

/* Radius search starts */
$lat_lng_meta_query = array();
if(isset( $_GET['radius_lat']) && isset( $_GET['radius_long']))
{
	$latitude  = $_GET['radius_lat'];
	$longitude = $_GET['radius_long'];
}
if(!empty($latitude) && !empty($longitude))
{
	$distance = '30';
	if(!empty($_GET['distance']) && !empty($_GET['distance']))
	{
		$distance = $_GET['distance'];
	}
	
	$data_array  =   array("latitude" => $latitude, "longitude" => $longitude, "distance" => $distance );
	$type_lat	 =	 "'DECIMAL'";
	$type_lon	 =	 "'DECIMAL'";
	$lats_longs  =   nokri_radius_search_theme($data_array, false);
	
	if(!empty($lats_longs) && count((array)$lats_longs) > 0 )
	{
		if( $latitude > 0 )
		{
				$lat_lng_meta_query[] = array(
				  'key' => '_job_lat',
				  'value' => array($lats_longs['lat']['min'], $lats_longs['lat']['max']),
				  'compare' => 'BETWEEN',
				  );
		}
		else
		{
			$lat_lng_meta_query[] = array(
			  'key' => '_job_lat',
			  'value' => array($lats_longs['lat']['max'], $lats_longs['lat']['min']),
			  'compare' => 'BETWEEN',
			 );
		 }
		if( $longitude > 0 )
		{
				$lat_lng_meta_query[] = array(
				  'key' => '_job_long',
				  'value' => array($lats_longs['long']['min'], $lats_longs['long']['max']),
				  'compare' => 'BETWEEN',
				  );
		}
		else
		{
			$lat_lng_meta_query[] = array(
			  'key' => '_job_long',
			  'value' => array($lats_longs['long']['max'], $lats_longs['long']['min']),
			  'compare' => 'BETWEEN',
			 );
		 }

	}
}
/* Radius search ends */


/* Passing Query String Results To Arguments */ 
$order	=	'DESC';
if( isset($_GET['order_job']) )
{
	if( isset($_GET['order_job']) && $_GET['order_job'] != "" )
	{
		 $order	  =	$_GET['order_job'];
	}
}
$featur_excluded = '';

if( isset( $nokri['premium_jobs_list_switch'] ) && $nokri['premium_jobs_list_switch'] == false   )
{
    if( isset( $nokri['premium_jobs_class'] ) && $nokri['premium_jobs_class'] != ''   ){
	
	$featur_excluded   =	array(	
		'taxonomy' => 'job_class',
		'field'    => 'term_id',
		'terms'    => $nokri['premium_jobs_class'],
		'operator' => 'NOT IN',		
	);	
}}
if ( get_query_var( 'paged' ) ) 
{
	$paged = get_query_var( 'paged' );
} 
else if ( get_query_var( 'page' ) )
{
/*This will occur if on front page.*/
$paged = get_query_var( 'page' );
} 
else 
{
	$paged = 1;
}
$args	  =	array(
'tax_query' => array($category,$job_salary,$title, $job_type,$job_category,$job_tags,$job_qualifications,$job_level,$job_skills,$job_experience,$job_currency,$job_shift,$job_class,$location,$location_keyword,$featur_excluded),
'posts_per_page' 	=> 	 get_option( 'posts_per_page' ),
 's'                    =>      $title,
'post_type' 		=> 	'job_post',
'post_status' 		=> 	'publish',
'order'				=> 	$order,
'orderby' 			=> 	'date',
'paged' 			=> 	$paged,
'meta_query' 		=> 	array(
		array(
			'key'     => '_job_status',
			'value'   => 'active',
			'compare' => '=',
		),
		$custom_search,
		$lat_lng_meta_query,
	), 
);
$args = nokri_wpml_show_all_posts_callback($args);
$results = new WP_Query( $args );

if ($results->found_posts > 0)
{
	$message = __('Available Jobs','nokri');
}
else
{
	$message = __('No Jobs Matched','nokri');
}

$side_bar_emp_title = ( isset($nokri['multi_company_select_title']) && $nokri['multi_company_select_title'] != ""  ) ? '<div class="widget-heading"><span class="title">'.$nokri['multi_company_select_title'].'</span></div>' : "";
/* Premium Jobs Top Query */
$premium_jobs_class_num = ( isset($nokri['premium_jobs_class_number']) && $nokri['premium_jobs_class_number'] != ""  ) ? $nokri['premium_jobs_class_number'] : "";
$args_premium	    =	  array();
if( isset( $nokri['premium_jobs_class'] ) && $nokri['premium_jobs_class'] != ''   )
{
	$job_classes	=	'';
	$job_classes	=	array(
		array(
		'taxonomy' => 'job_class',
		'field'    => 'term_id',
		'terms'    => $nokri['premium_jobs_class'],
		'operator' => 'IN',
		),
	);	

$args_premium	    =	  array(
'tax_query'         =>    array($job_classes,$category,$location),
 's'                    =>      $title,
'posts_per_page' 	=> 	$premium_jobs_class_num,
'post_type' 		=> 	'job_post',
'post_status' 		=> 	'publish',
'orderby' 			=> 	'rand',
'meta_query' 		=> 	array(
		array(
			'key'     => '_job_status',
			'value'   => 'active',
			'compare' => '=',
		),
	), 
);
}
/* Advertisement Module */
$advert_up = $advert_down = '';
if( isset( $nokri['search_job_advert_switch']) && $nokri['search_job_advert_switch'] == "1" )
{
	/*Above joba */
	if(isset( $nokri['search_job_advert_up']) && $nokri['search_job_advert_up'] != "")
	{
		$advert_up = '<div class="n-advert-box">'.$nokri['search_job_advert_up'].'</div>';
	}
	/*Below jobs */
	if(isset( $nokri['search_job_advert_down']) && $nokri['search_job_advert_down'] != "")
	{
		$advert_down = '<div class="n-advert-box">'.$nokri['search_job_advert_down'].'</div>';
	}
	
}
/* Search page lay out */
$search_page_layout = ( isset($nokri['search_page_layout']) && $nokri['search_page_layout'] != ""  ) ? $nokri['search_page_layout'] : "";
/* Is job alerts*/
$job_alerts = ( isset($nokri['job_alerts_switch']) && $nokri['job_alerts_switch'] != ""  ) ? $nokri['job_alerts_switch'] : false;
/* Job alert title*/
$job_alerts_title = ( isset($nokri['job_alerts_title']) && $nokri['job_alerts_title'] != ""  ) ? $nokri['job_alerts_title'] : '';
/* Job alert tagline*/
$job_alerts_tagline = ( isset($nokri['job_alerts_tagline']) && $nokri['job_alerts_tagline'] != ""  ) ? $nokri['job_alerts_tagline'] : '';
/* Job alert btn*/
$job_alerts_btn = ( isset($nokri['job_alerts_btn']) && $nokri['job_alerts_btn'] != ""  ) ? $nokri['job_alerts_btn'] : '';
if($search_page_layout == 1)
{
?>
<div class="cp-loader"></div>
<section class="n-search-page" >
         <div class="container">
            <div class="row">
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="row">
                     <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">                      
                                                
                         <aside class="new-sidebar">
                             
                           <div class="heading">
                              <h4> <?php  echo esc_html__("Search Filters", "nokri"); ?></h4>
                              <a href="<?php  echo get_the_permalink($nokri['sb_search_page']); ?>"><?php  echo esc_html__("Clear All", "nokri"); ?></a>
                              <?php if(wp_is_mobile()){ ?>
                               <a role="button" class="" data-toggle="collapse" href="#accordion" aria-expanded="true" id="panel_acordian"></a>
                              <?php }?>
                           </div>                          
                           <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                             <?php get_sidebar( 'widget' ); ?>
                           </div>
                             
                        </aside>                         
                     </div>
                     <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                        <div class="n-search-main">
                           <div class="n-bread-crumb">
                              <ol class="breadcrumb">
                                 <li> <a href=""><?php  echo esc_html__("Home", "nokri"); ?></a></li>
                                 <li class="active"><a href="javascript:void(0);" class="active"><?php  echo esc_html__("Search Page", "nokri"); ?></a></li>
                              </ol>
                           </div>
                           <div class="heading-area" <?php  echo ($search_bg_url); ?>>
                              <div class="row">
                                 <div class="col-md-8 col-sm-8 col-xs-12">
                                    <h4><?php echo esc_html($message)." ".'(' .esc_html( $results->found_posts ).')'; ?></h4>
                                 </div>
                                 <div class="col-md-4 col-sm-4 col-xs-12">
                                    <form method="GET" id="job_order_search">
                                       <select class="js-example-basic-single form-control" data-allow-clear="true" data-placeholder="<?php  echo esc_html__("Select Option", "nokri"); ?>" style="width: 100%" name="order_job" id="order_job">
                                          <option value="" ><?php  echo esc_html__("Select Option", "nokri"); ?></option>
                                           <option value="ASC" <?php if ( $order == 'ASC') { echo "selected"; } ; ?>><?php  echo esc_html__("Ascending", "nokri"); ?></option>
                                        <option value="DESC" <?php if ( $order == 'DESC') { echo "selected"; } ; ?>><?php  echo esc_html__("Descending ", "nokri"); ?></option>
                                       </select>
                                       <?php echo nokri_form_lang_field_callback(true); ?> 
                                    </form>
                                 </div>
                              </div>
                           </div>
                           <?php if($job_alerts) { ?>
                           <div class="jobs-alert-box">
                           	<div class="row">
                                <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
                                   <span><?php echo esc_html($job_alerts_title); ?></span>
                                   <p><?php echo esc_html($job_alerts_tagline); ?></p>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                    <a href="javascript:void(0)" class="btn n-btn-flat job_alert"><?php echo esc_html($job_alerts_btn); ?></a>
                                </div>
                            </div>
                           </div>
                    	 <?php } ?>
                           <div class="n-search-listing n-featured-jobs-two">
                              <div class="row">
                              <?php echo ($advert_up); ?>
                                 <div class="n-features-job-two-box">
                                 <?php
								/* Regular Search Query */
								   if ( $results->have_posts() ) { 
									 $current_layout    =   $nokri['search_layout'];
									 $layouts		    =   array( 'list_1', 'list_2', 'list_3' );
									 if (in_array($current_layout, $layouts))  					
									 {		
										 require trailingslashit( get_template_directory () ) . "template-parts/layouts/job-style/search-layout-list.php";
										 echo($out);
									 }
									 else
									 {	
										 require trailingslashit( get_template_directory () ) . "template-parts/layouts/job-style/search-layout-grid.php";
										 echo($out);
									 }
										 /* Restore original Post Data */
										 wp_reset_postdata();
									 } 
								?>
                                <div class="clearfix"></div>
                          		<?php echo  ($advert_down); ?>
                               	 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                       <nav aria-label="Page navigation">
                                          <?php echo nokri_job_pagination( $results );?>
                                       </nav>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <?php  
					/* Premium Job Top Query */	
					if( isset( $nokri['premium_jobs_class_switch'] ) && $nokri['premium_jobs_class_switch'] == '1'   )
					{	
							$results_premium   = new WP_Query( $args_premium );
							 if ( $results_premium->have_posts() ) 
							 { 
							  /*Section Title */
						$section_title = (isset($nokri['premium_jobs_class_title']) && $nokri['premium_jobs_class_title'] != "") ? '<div class="heading"><h4>'.$nokri['premium_jobs_class_title'].'</h4></div>' : "";
							 ?>
                     <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <aside class="new-sidebar">
                           <?php  echo ($section_title); ?>
                           <div class="vertical-job-slider verticalCarousel">
                              <ul class="slider-1">
                              <?php
							 $current_layout    =   $nokri['search_layout'];
							 $layouts		    =   array( 'list_1', 'list_2', 'list_3' );
							 if (in_array($current_layout, $layouts))  					
							 {		
								 require trailingslashit( get_template_directory () ) . "template-parts/layouts/job-style/search-layout-premium-list.php";
								 echo($out);
							 }
							 else
							 {	
								 require trailingslashit( get_template_directory () ) . "template-parts/layouts/job-style/search-layout-premium-grid.php";
								 echo($out);
							 }
							wp_reset_postdata();
							?>
                              </ul>
                           </div>
                        </aside>
                     </div>
                     <?php } } ?>
                  </div>
               </div>
            </div>
         </div>
      </section>
<?php }  else if($search_page_layout == 2) { ?>
<section class="n-search-page" >
 <div class="container">
    <div class="row">
       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="row">
             <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">                                            
                    <aside class="new-sidebar">                       
                   <div class="heading">
                      <h4> <?php  echo esc_html__("Search Filters", "nokri"); ?></h4>
                      <a href="<?php  echo get_the_permalink($nokri['sb_search_page']); ?>"><?php  echo esc_html__("Clear All", "nokri"); ?></a>
                      <?php if(wp_is_mobile()){ ?>
                      <a role="button" class="" data-toggle="collapse" href="#accordion" aria-expanded="true" id="panel_acordian"></a>
                      <?php }?>
                   </div>   
                                    
                   <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                      <?php get_sidebar( 'widget' ); ?>    
                       
                   </div>  
                       
                </aside>                   
             </div>

             <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="n-search-main">
                   <div class="n-bread-crumb">
                      <ol class="breadcrumb">
                         <li> <a href="javascript:void(0)"><?php  echo esc_html__("Home", "nokri"); ?></a></li>
                         <li class="active"><a href="javascript:void(0);" class="active"><?php  echo esc_html__("Search Page", "nokri"); ?></a></li>
                      </ol>
                   </div>
                   <div class="heading-area">
                      <div class="row">
                         <div class="col-md-8 col-sm-8 col-xs-12">
                            <h4><?php echo esc_html($message)." ".'(' .esc_html( $results->found_posts ).')'; ?></h4>
                         </div>
                         <div class="col-md-4 col-sm-4 col-xs-12">
                            <form method="GET" id="job_order_search">
                               <select class="js-example-basic-single form-control " data-allow-clear="true" data-placeholder="<?php  echo esc_html__("Select Option", "nokri"); ?>" style="width: 100%" name="order_job" id="order_job">
                                  <option value="" ><?php  echo esc_html__("Select Option", "nokri"); ?></option>
                                   <option value="ASC" <?php if ( $order == 'ASC') { echo "selected"; } ; ?>><?php  echo esc_html__("Ascending", "nokri"); ?></option>
                                <option value="DESC" <?php if ( $order == 'DESC') { echo "selected"; } ; ?>><?php  echo esc_html__("Descending ", "nokri"); ?></option>
                               </select>
                               <?php echo nokri_form_lang_field_callback(true); ?>
                            </form>
                         </div>
                      </div>
                   </div>
                   <?php if($job_alerts) { ?>
                   <div class="jobs-alert-box">
                           	<div class="row">
                                <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
                                   <span><?php echo esc_html($job_alerts_title); ?></span>
                                   <p><?php echo esc_html($job_alerts_tagline); ?></p>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                    <a href="javascript:void(0)" class="btn n-btn-flat job_alert"><?php echo esc_html($job_alerts_btn); ?></a>
                                </div>
                            </div>
                           </div>
                   
                   
                   <?php } echo ($advert_up); ?>
                   
               
				  
				  
                  		 <?php
				   if( isset( $nokri['premium_jobs_class_switch'] ) && $nokri['premium_jobs_class_switch'] == '1'   )
						{
                                       $section_title = (isset($nokri['premium_jobs_class_title']) && $nokri['premium_jobs_class_title'] != "") ? '<h3>'.$nokri['premium_jobs_class_title'].'</h3>' : "";
                                       echo       "<div class='n-search-listing n-featured-jobs featured'>" ;                                               
							  /* Premium jobs in list style*/
							  $results_premium   = new WP_Query( $args_premium );
							  $current_layout    =   $nokri['search_layout'];
							  $layouts		    =   array( 'list_1', 'list_2', 'list_3' );
								if ( $results_premium->have_posts() )
								 { 
                                                                      echo     $section_title ;
                                                                      echo "<div class='n-featured-job-boxes'>";
									if (in_array($current_layout, $layouts))  					
											 {		
												 require trailingslashit( get_template_directory () ) . "template-parts/layouts/job-style/search-layout-premium-list2.php";
												 echo($out);
											 }
											wp_reset_postdata();
                                                                                      echo "</div>";
								}
                                                            echo "</div>";
                                                                  
						}
					?>
                    
                   
                   <div class="n-search-listing n-featured-jobs">    
                       <h3><?php echo esc_html__('Regular Jobs','nokri') ?></h3>
                      <div class="n-featured-job-boxes">
                           
                       <?php
						/* Regular Search Query */	
						 if ( $results->have_posts() ) { 
								 $current_layout    =   $nokri['search_layout'];
								 $layouts		    =   array( 'list_1', 'list_2', 'list_3' );
								 if ( $results->have_posts() )
								 {
								 if (in_array($current_layout, $layouts))  					
								 {		
									 require trailingslashit( get_template_directory () ) . "template-parts/layouts/job-style/search-layout-full.php";
									 echo($out);
								 }
								 else
								 {	
									 require trailingslashit( get_template_directory () ) . "template-parts/layouts/job-style/search-layout-grid.php";
									 echo($out);
								 }
									 /* Restore original Post Data */
									 wp_reset_postdata();
								 }
								 }
						?>
                          <div class="clearfix"></div>
                          <?php echo  ($advert_down); ?> 
                      	 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
                            <nav aria-label="Page navigation">
                               <?php echo nokri_job_pagination( $results );?>
                            </nav>
                         </div>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>
</section>
<!--footer section-->
<?php } else {
/* Getting map layout */
get_template_part( 'template-parts/layouts/job-style/search', 'map');	 
}
get_footer();