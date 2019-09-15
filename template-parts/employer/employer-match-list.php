<?php 
$user_id  = get_current_user_id();
global $nokri;
$c_name	=	'';
if( isset($_GET['c_name']) )
{
	if( isset($_GET['c_name']) && $_GET['c_name'] != "" )
	{
		$c_name	  =	$_GET['c_name'];
	}
}
$c_order	=	'DESC';
if( isset($_GET['c_order']) )
{
	if( isset($_GET['c_order']) && $_GET['c_order'] != "" )
	{
		$c_order	  =	$_GET['c_order'];
	}
}
$job_id	=	'';
if( isset($_GET['id']) )
{
	if( isset($_GET['id']) && $_GET['id'] != "" )
	{
		$job_id	  =	$_GET['id'];
	}
}

/* Getting saved resumes id's */
$resumes_array = nokri_jobs_matches_candidates($job_id);
// total no of User to display
$limit   	= isset( $nokri['user_pagination'] ) ?    $nokri['user_pagination']     :   5;
$page    	= (get_query_var('page')) ? get_query_var('page') : 1;
$offset  	= ($page * $limit) - $limit;
$user_query = new WP_User_Query(
 array( 
 //'include'  =>  $resumes_array,
 'search'   => "".esc_attr( $c_name )."*",
 'number'	=>  $limit,
 'orderby'  => 'meta_value_num',
 'meta_key' => '_cand_skills_sum', 
 'offset'	=>  $offset,
 'order'    =>  $c_order,
 'meta_query' => array(
 'relation'  => 'AND',
			array(
            'key'     => '_sb_reg_type',
            'value'   => '0',
            'compare' => '='
            ),
			array(
				'key'     => '_cand_skills_sum',
				'value'   => array( 0, 1000000000 ),
				'type'    => 'numeric',
				'compare' => 'BETWEEN'
			),
			),
  )  );
$cand_followers = 	$user_query->get_results();
$pages_number   = 	ceil($user_query->get_total()/$limit);
$company_id     = 	$li  =  $follower_id =  '';
?>
<div class="cp-loader"></div>
<div class="dashboard-job-filters">
    <div class="row">
    <form method="GET" id="emp_matched_resumes_form">
    <input type="hidden" name="tab-data" value="match-list" >
    <input type="hidden" name="id" value="<?php echo  esc_attr($job_id); ?>" >
        <div class="col-md-6 col-xs-12 col-sm-6">
            <div class="form-group">
                <label class=""><?php echo  esc_html__( 'Name', 'nokri' ); ?></label>
                <input type="text" value="<?php echo esc_html($c_name); ?>" <?php if ( $order == 'ASC') { echo "selected"; } ; ?> class="form-control" name="c_name" placeholder="<?php echo  esc_html__( 'Keyword or Name', 'nokri' ); ?>">
                <a href="javascript:void(0);" class="a-btn emp_matched_resumes_form"><i class="ti-search"></i></a>
            </div>
        </div>
        <div class="col-md-6 col-xs-12 col-sm-6">
            <div class="form-group">
                <label class=""><?php echo  esc_html__( 'Sort by', 'nokri' ); ?> </label>
                <select class="js-example-basic-single emp_matched_resumes_form" name="c_order">
                    <option value=""><?php echo  esc_html__( 'Select an option', 'nokri' ); ?></option>
                    <option value="ASC" <?php if ( $c_order == 'ASC') { echo "selected"; } ; ?>><?php echo  esc_html__( 'ASC', 'nokri' ); ?></option>
                    <option value="DESC" <?php if ( $c_order == 'DESC') { echo "selected"; } ; ?>><?php echo  esc_html__( 'DESC', 'nokri' ); ?></option>
                </select>
            </div>
        </div>
        </form>
    </div>
</div>
<div class="main-body">
<div class="dashboard-job-stats followers">
    <h4><?php echo  esc_html__( 'Matched Candidates', 'nokri' ); ?></h4>
    <div class="dashboard-posted-jobs">
        <div class="posted-job-list resume-on-jobs header-title">
            <ul class="list-inline">
                <li class="posted-job-title"> <?php echo  esc_html__( 'Candidate Name', 'nokri' ); ?></li>
                <li class="posted-job-expiration"> <?php echo  esc_html__('Registered', 'nokri' ); ?></li>
                <li class="posted-job-action"><?php echo  esc_html__( 'Resumes', 'nokri' ); ?></li>
            </ul>
        </div>
<?php
if ( isset ($cand_followers) && count($cand_followers) > 0)
{
	$count = 1;
	foreach ( $cand_followers as $follower ) 
	 {
				 $follower_id   = $follower->ID;
				 /* Getting Followers Profile Photo */
				 $dp            =   nokri_get_user_profile_pic($follower_id,'_cand_dp');
				 $registered    =   date_i18n(get_option('date_format'), strtotime($follower->user_registered));
				 $headline_html = '';
				 if(get_user_meta($follower_id, '_user_headline', true ) != '')
				 {
					 $headline_html = '<p>'.get_user_meta($follower_id, '_user_headline', true ).'</p>';
				 }
	            $job_skills     =   wp_get_post_terms($job_id, 'job_skills', array("fields" => "ids"));
				$cand_skills	= 	get_user_meta($follower_id, '_cand_skills', true);
				if (is_array($cand_skills) && is_array($job_skills))
				{
					$final_array = array_intersect($cand_skills, $job_skills);
					if (count($final_array) > 0) 
					{
					 $li .= '<div class="posted-job-list resume-on-jobs" id="company-box-'.esc_attr($follower_id).'">
								<ul class="list-inline">
									<li class="posted-job-title">
										<div class="posted-job-title-img">
											<a href="javascript:void(0)">
												<img src="'.esc_url($dp).'" class="img-responsive" alt="'.esc_html__( 'Image', 'nokri' ).'">
											</a>
										</div>
										<div class="posted-job-title-meta">	<a href="'.esc_url(get_author_posts_url($follower_id)).'">'.$follower->display_name.'</a>
											'.$headline_html.'</div>
									</li>
									<li class="posted-job-expiration">'.$registered.'</li>
									<li class="posted-job-action"><a href="'.esc_url(get_author_posts_url($follower_id)).'" class="btn btn-custom"> '.esc_html__( 'View Resume', 'nokri' ).' </a>
									</li>
								</ul>
							</div>';
							$count++;
					}
				}
 	}
 echo "".$li ; ?>  
 </div>
        <div class="pagination-box clearfix">
        <ul class="pagination">
            <?php echo nokri_user_pagination($pages_number,$page);?> 
        </ul>
        </div>
    </div>
</div>
<?php } else { ?>
<div class="dashboard-posted-jobs">
    <div class="notification-box">
        <div class="notification-box-icon"><span class="ti-info-alt"></span></div>
        <h4><?php echo esc_html__( 'No matched resume found', 'nokri' ); ?></h4>
    </div>
</div>
<?php }