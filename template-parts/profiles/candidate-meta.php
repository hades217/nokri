<?php
global $nokri;
$author_id       = get_query_var( 'author' );
$author          = get_user_by( 'ID', $author_id );
$current_user_id = get_current_user_id();
/* Candidate Resume */ 
$cand_skills   =  $skill_tags =  $portfolio_html = '';
$user_crnt_id  =  get_query_var( 'author' );
$registered    =  $author->user_registered;
/* package Page */
$package_page  = isset($nokri['package_page']) ? $nokri['package_page']  : '';
/* Getting Candidate Dp */
$image_link     =  nokri_get_user_profile_pic($user_crnt_id,'_cand_dp');
/* Getting Candidate Portfolio */
$portfolio_html = nokri_candidate_portfolio($user_crnt_id);
/* Getting Candidate user meta */
$cand_dob 		=  $remaining_searches = '';
$cand_dob 		= get_user_meta($user_crnt_id, '_cand_dob', true);
$cand_gender    = get_user_meta($user_crnt_id, '_cand_gender', true);
$cand_qualification	= get_user_meta($user_crnt_id, '_cand_qualification', true);
$cand_headline  = get_user_meta($user_crnt_id, '_user_headline', true);
$cand_address   = get_user_meta($user_crnt_id, '_cand_address', true);
$cand_fb        = get_user_meta($user_crnt_id, '_cand_fb', true);
$cand_twiter    = get_user_meta($user_crnt_id, '_cand_twiter', true);
$cand_google    = get_user_meta($user_crnt_id, '_cand_google', true);
$cand_linked    = get_user_meta($user_crnt_id, '_cand_linked', true);
$cand_phone     = get_user_meta($user_crnt_id, '_sb_contact', true);
$cand_intro_video    = get_user_meta($user_crnt_id, '_cand_intro_vid', true);
$cand_introd    = get_user_meta($user_crnt_id, '_cand_intro', true);
$cand_video	    = get_user_meta($user_crnt_id, '_cand_video', true);
$salary_type	= get_user_meta( $user_crnt_id, '_cand_salary_type', true);
$salary_range	= get_user_meta( $user_crnt_id, '_cand_salary_range', true);
$salary_curren	= get_user_meta( $user_crnt_id, '_cand_salary_curren', true);
$cand_profile_status	= get_user_meta($user_crnt_id, '_user_profile_status', true); 
/*Background*/
$bg_url = '';
$bg_url        = nokri_section_bg_url();
/*Candidate Skillsbar*/
$skills_bar   = nokri_candidate_skill_bar($user_crnt_id);
/*email/phone hide/show*/
$is_public = isset($nokri['user_phone_email']) ? $nokri['user_phone_email']  : false;
/*contact form hide/show*/
$is_public_contact = isset($nokri['user_contact_form']) ? $nokri['user_contact_form']  : false;
/*profile private txt*/
$user_private_txt = isset($nokri['user_private_txt']) ? $nokri['user_private_txt']  : '';
/*Is Applied*/
$is_applied = false;
$args = array(
	'post_type'   => 'job_post',
	'orderby'     => 'date',
	'order'       => 'ASC',
	'author' 	  => $current_user_id,
	'post_status' => array('publish'), 
	 'meta_query' => 
        array(
            'key' => '_job_status',
            'value' => 'active',
            'compare' => '='
    )
);
$query = new WP_Query( $args ); 
if ( $query->have_posts() )
{
		  while ( $query->have_posts()  )
		  { 
			$query->the_post(); 
			$job_id =  get_the_id();
			if(get_post_meta($job_id, '_job_applied_resume_'.$author_id, true))
			{
				$is_applied = true;
			}
		 }
	}
/*Check search mode*/
$search_mode =  isset($nokri['cand_search_mode']) ? $nokri['cand_search_mode']  : '1';
$is_search   =  true;

if( $search_mode == "2"  && !$is_applied)
{
	/* Candidate View Resume Logic*/
	$is_search =  nokri_is_cand_search_allowed();
	if($is_search == false && $current_user_id != $author_id  )
	{
		echo '<script>jQuery(document).ready(function($) { toastr.error("'.__( "You have not allowed", 'nokri' ).'", "", {timeOut: 6500,"closeButton": true, "positionClass": "toast-top-right"}); });</script>';
		echo nokri_redirect( get_the_permalink($package_page) );
	}
}
if($cand_profile_status == 'priv')
{
   $is_search =  false;
}
/*Profile picture*/
if($cand_profile_status == 'priv'  & $author_id != $current_user_id && $is_applied == false) 
{ 
	$image_link =  get_template_directory_uri(). '/images/candidate-dp.jpg';
}
/*Social links hide/show*/
$social_links = isset($nokri['user_contact_social']) ? $nokri['user_contact_social']  : true;
/* Advertisment */
$cand_advertisment =  isset($nokri['cand_advertisment']) ? $nokri['cand_advertisment']  : '';
/*Custom registration feilds for candidate */
$custom_feilds_cand = $registration_feilds = '';
$custom_feild_id           =  (isset($nokri['custom_registration_feilds'])) ? $nokri['custom_registration_feilds'] : '';
if(isset($custom_feild_id) && $custom_feild_id != '')
{
	$registration_feilds       =  nokri_get_custom_feilds($author_id,'Registration',$custom_feild_id,false,true);
}
/* Custom feilds for Candidate */
$custom_feild_cand  = (isset($nokri['custom_candidate_feilds'])) ? $nokri['custom_candidate_feilds'] : '';
if(isset($custom_feild_cand) && $custom_feild_cand != '')
{
	$custom_feilds_cand = nokri_get_custom_feilds($author_id,'Candidate',$custom_feild_cand,false,true);
   
    
}
/* Resume download */
$resume_id	= nokri_get_resume_publically($author_id,'');
$cand_resume_down  = (isset($nokri['cand_resume_down'])) ? $nokri['cand_resume_down'] : true;