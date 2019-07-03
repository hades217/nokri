<?php get_header();
global $nokri;
$user_id         =  get_current_user_id();
/* Sigin Page */
$signin_page = '';
if((isset($nokri['sb_sign_in_page'])) && $nokri['sb_sign_in_page']  != '' )
{
 	$signin_page =  ($nokri['sb_sign_in_page']);
}
/* package Page */
$package_page = '';
if((isset($nokri['package_page'])) && $nokri['package_page']  != '' )
{
 	$package_page =  ($nokri['package_page']);
}
/*Candidate search mode*/
if( isset( $nokri['cand_search_mode'] ) && $nokri['cand_search_mode'] == "2" ) 
{
	if(!is_user_logged_in())
	{
		echo nokri_redirect( get_the_permalink($signin_page) );
	}
}
/* search section bg */ 
$list_bg_url = '';
 if ( isset( $nokri['candidate_list_bg_img'] ) )
{
	$list_bg_url = nokri_getBGStyle('candidate_list_bg_img');
}
/* Template Name: Candidates*/
$title	=	'';
if( isset( $_GET['cand_title'] ) && $_GET['cand_title'] != ""  )
{
	$title	=	$_GET['cand_title'];
}
$level	=	'';
if( isset( $_GET['cand_level'] ) && $_GET['cand_level'] != ""  )
{
	$level	=	$_GET['cand_level'];
}
$skills	=	'';
if( isset( $_GET['cand_skills'] ) && $_GET['cand_skills'] != ""  )
{
	$skills	=	$_GET['cand_skills'];
}

 $type_qry = '';
 if( isset( $_GET['cand_type'] ) && $_GET['cand_type'] != "" )
 {
  $type_qry =   array(
            'key' => '_cand_type',
            'value' => $_GET['cand_type'],
            'compare' => '='
        );
 }
 
 $level_qry = '';
 if( isset( $_GET['cand_level'] ) && $_GET['cand_level'] != "" )
 {
  $level_qry =   array(
            'key' => '_cand_level',
            'value' => $_GET['cand_level'],
            'compare' => '='
        );
 }
 
 $skills_qry = '';
 if( isset( $_GET['cand_skills'] ) && $_GET['cand_skills'] != "" )
 {
  $skills_qry =   array(
            'key' => '_cand_skills',
            'value' => $_GET['cand_skills'],
            'compare' => 'like'
        );
 }
 $experience_qry = '';
 if( isset( $_GET['cand_experience'] ) && $_GET['cand_experience'] != "" )
 {
  $experience_qry =   array(
            'key' => '_cand_experience',
            'value' => $_GET['cand_experience'],
            'compare' => '='
        );
 }
 $location_qry = '';
 if( isset( $_GET['job_location'] ) && $_GET['job_location'] != "" )
 {
  $location_qry =   array(
            'key' => '_cand_custom_location',
            'value' => $_GET['job_location'],
            'compare' => 'like'
        );
 }
$cands_qry =   array(
		'key' => '_sb_reg_type',
		'value' => '0',
		'compare' => '='
	);
 

 $order = 'display_name';
 $orderby = 'ASC';
 if( isset( $_GET['order'] ) && $_GET['order'] == 'name' )
 {
	$orderby =  'display_name';
	$order   =  'ASC';
 }
 elseif (isset( $_GET['order'] ) && $_GET['order'] == 'date')
 {
	 $orderby  = 'registered';
	 $order   =  'DESC';
 }
// total no of User to display
$limit  =    isset( $nokri['user_pagination'] )         ?    $nokri['user_pagination']     :   10;
$page   =   (get_query_var('page')) ? get_query_var('page') : 1;
$offset =   ($page * $limit) - $limit;
// Query args
$args = array(
   'search'         => "".esc_attr( $title )."*",
   'order' 	        => $order,
   'orderby'        => $orderby ,
   'number' 	    => $limit,
   'offset'	        => $offset,
   'role'           => 'subscriber',
   'meta_query' 	=> array(
        $type_qry,
		$cands_qry,
		$level_qry,
		$skills_qry,
		$experience_qry,
		$location_qry
   )
);
// Create the WP_User_Query object
$wp_user_query = new WP_User_Query($args);
// Get the results
$users         =  $wp_user_query->get_results();
$total_users   =  $wp_user_query->get_total();
$pages_number  =  ceil($total_users/$limit);
if($total_users > 0)
{
	$users_found = esc_html__("Candidates found", 'nokri')." ".'('.$total_users.')';
}
else
{
	$users_found = esc_html__("No candidates found", 'nokri');
}

/* Search allowed */
$is_allowed        =  nokri_is_cand_search_allowed();
if(!$is_allowed)
{
	echo nokri_redirect( get_the_permalink($package_page) );
}
?>
<section class="n-featured-candidates n-search-page"> 
     <div class="container">
        <div class="row">
           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="row">
                 <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <aside class="new-sidebar">
                       <div class="heading">
                          <h4> <?php echo esc_html__("Search Filters", 'nokri'); ?></h4> 
                          <a href="<?php  echo get_the_permalink($nokri['candidates_search_page']); ?>"><?php echo esc_html__("Clear All", 'nokri'); ?></a>
                       </div>
                       <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                         <?php echo get_sidebar( 'candidates' ); ?>
                       </div>
                    </aside>
                 </div>
                 <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="n-search-main">
                       <div class="n-bread-crumb">
                          <ol class="breadcrumb">
                             <li> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__("Home", 'nokri'); ?></a></li>
                             <li class="active"><a href="javascript:void(0);" class="active"><?php echo esc_html__("Candidate Search", 'nokri'); ?></a></li>
                          </ol>
                       </div>
                       <div class="heading-area">
                          <div class="row">
                             <div class="col-md-8 col-sm-8 col-xs-12">
                                <h4><?php echo esc_html($users_found); ?></h4>
                             </div>
                             <div class="col-md-4 col-sm-4 col-xs-12">
                                <form method="GET" id="candiate_order">
                                   <select class="js-example-basic-single form-control candidates_orders" data-allow-clear="true" data-placeholder="<?php echo esc_html__("Select option", 'nokri'); ?>" style="width: 100%" name="order">
                                      <option value="0"><?php echo esc_html__("Select order", 'nokri'); ?></option>
                                    <option value="name" <?php if(isset($_GET['order']) && $_GET['order'] == 'name') echo "selected=selected"; ?>><?php echo esc_html__("Alphabatically", 'nokri'); ?></option>
                                    <option value="date" <?php if(isset($_GET['order']) && $_GET['order'] == 'date') echo "selected=selected"; ?>><?php echo esc_html__("New registered", 'nokri'); ?></option>
                                   </select>
                                </form>
                             </div>
                          </div>
                       </div>
                       <div class="n-search-listing n-featured-jobs">
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                             <div class="row">
                                <div class="n-company-grids">
                                   <div class="row">
                                   <div class="n-featured-candidates-box clear-custom">
                                   <?php
									/* Query User results */
									if (!empty($users))
									 {
										// Loop through results
										foreach ($users as $user) {
											$cand_id  = $user->ID;
											$canddata = get_userdata($cand_id);
											/* Social links */
											$cand_fb        = get_user_meta($cand_id, '_cand_fb', true);
											$cand_twiter    = get_user_meta($cand_id, '_cand_twiter', true);
											$cand_google    = get_user_meta($cand_id, '_cand_google', true);
											$cand_linked    = get_user_meta($cand_id, '_cand_linked', true);
											/* Profile Pic  */
											$image_dp_link[0] =  get_template_directory_uri(). '/images/candidate-dp.jpg';
											if( isset( $nokri['nokri_user_dp']['url'] ) && $nokri['nokri_user_dp']['url'] != "" )
											{
												$image_dp_link = array($nokri['nokri_user_dp']['url']);	
											}
											if(get_user_meta($cand_id, '_cand_dp', true ) != '')
											{
												$attach_dp_id     =  get_user_meta($cand_id, '_cand_dp', true );
												$image_dp_link    =  wp_get_attachment_image_src( $attach_dp_id, '' );
											}
											if(empty($image_dp_link[0]))
											{
												$image_dp_link[0] =  get_template_directory_uri(). '/images/candidate-dp.jpg';
											}
											/* Getting Employer Skills  */
											$emp_skills = get_user_meta($cand_id, '_cand_skills', true);
											$skill_tags  = '';
											if((array)$emp_skills  && $emp_skills > 0) 
											  {
												$taxonomies = get_terms('job_skills', array('hide_empty' => false , 'orderby'=> 'id', 'order' => 'ASC' ,  'parent'   => 0  ));
												$total_skills = count($emp_skills) - 3;
												if(count((array) $taxonomies) > 0)
												 {
													foreach($taxonomies as $taxonomy)
														{
															 if (in_array( $taxonomy->term_id, $emp_skills ))
															 {
																 $skill_tags .=   '<a href="'.get_the_permalink( $nokri['candidates_search_page'] ).'?cand_skills='.$taxonomy->term_id.'">'.esc_html($taxonomy->name).'</a>';
															 }
														}
														if($total_skills > 1)
														{
															$skill_tags .=   '<a href="javascript:void(0)">'.$total_skills.'+</a>';
														}
													}
												}
											$cand_headline  = get_user_meta($cand_id, '_user_headline', true);
											$emp_address   = get_user_meta($cand_id, '_cand_address', true);
											$adress_html = '';
											if($emp_address)
											{
												$adress_html = '<i class="fa fa-map-marker"></i><p>'.$emp_address.'</p>';
											}
											 ?>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                           <div class="n-featured-single">
                              <div class="n-featured-candidates-single-top">
                                 <div class="n-candidate-title">
                                    <h4><a href="<?php echo esc_url(get_author_posts_url($cand_id)); ?>"><?php echo esc_html( $user->display_name );  ?></a></h4>
                                    <p><?php echo esc_html($cand_headline); ?></p>
                                 </div>
                                 <div class="n-canididate-avatar">
                                   <a href="<?php echo esc_url(get_author_posts_url($cand_id)); ?>"><img src="<?php echo esc_url($image_dp_link[0]); ?>" class="img-responsive" alt="<?php echo esc_attr__( "logo", 'nokri' ); ?>"></a>
                                 </div>
                                 <div class="n-candidate-location">
                                   <?php echo "".($adress_html); ?>
                                 </div>
                                 <div class="n-candidate-skills">
                                  <?php echo "".$skill_tags; ?>
                                 </div>
                              </div>
                              <div class="n-candidates-single-bottom">
                                 <a href="<?php echo esc_url(get_author_posts_url($cand_id)); ?>"><?php echo esc_html__('View Profile','nokri'); ?></a>
                              </div>
                           </div>
                        </div>
                        				 <?php }  } ?>
                                   </div>
                                   </div>
                                </div>
                             </div>
                          </div>
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
                             <nav aria-label="Page navigation">
                                <ul class="pagination">
                                   <?php echo  nokri_user_pagination($pages_number,$page); ?>
                                </ul>
                             </nav>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
  </section>
<?php
get_footer();