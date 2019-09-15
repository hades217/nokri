<?php
global $nokri;
$author_id = get_query_var( 'author' );
$author = get_user_by( 'ID', $author_id );
$current_user_id 	  = get_current_user_id();
{
/* package Page */
$package_page = '';
if((isset($nokri['package_page'])) && $nokri['package_page']  != '' )
{
 	$package_page =  ($nokri['package_page']);
}
/* Candidate Resume */ 
$cand_skills = $skill_tags =  $portfolio_html = '';
global $nokri;
$user_crnt_id      =  get_query_var( 'author' );
$registered        =  $author->user_registered;
/* Getting User Skills Tags */
$cand_skills   = $cand_skills_values = array();
$cand_skills_values	= get_user_meta($user_crnt_id, '_cand_skills_values', true);
$cand_skills	= get_user_meta($user_crnt_id, '_cand_skills', true);
if( isset($cand_skills) && !empty($cand_skills) &&  count($cand_skills) > 0 )
{
	foreach($cand_skills as $key => $csv )
	{
		$term = get_term_by( 'id', $csv , 'job_skills' );		
		if($term)
		{
			$skill_lavel = 100;
		
			if( isset($cand_skills_values) && is_array($cand_skills_values))
			{
				if(array_key_exists($key,$cand_skills_values))
				{
					$skill_lavel = $cand_skills_values[$key];
				}
			}			
			$array_skills[] = array("name" => $term->name, "value" => $skill_lavel);	
		}
	}
}
$skills_bar = '';
if(isset($array_skills) && !empty($array_skills))
{
	foreach( $array_skills  as $r )
	{
		
		$skills_bar .= '<div class="bar-wrapper">
									<span class="progress-text">'.$r["name"].'</span>
								  <div class="progress">
									<div class="progress-bar" role="progressbar" aria-valuenow="'.$r["value"].'" aria-valuemin="0" aria-valuemax="100" > <span  class="popOver" data-toggle="tooltip" data-placement="top" title="'.$r["value"].'%"> </span> </div>
								  </div>
								</div>'; 
	}
}
/* Getting Candidate Dp */
$image_link[0] =  get_template_directory_uri(). '/images/candidate-dp.jpg';
if( isset( $nokri['nokri_user_dp']['url'] ) && $nokri['nokri_user_dp']['url'] != "" )
{
   	$image_link = array($nokri['nokri_user_dp']['url']);	
}
if( get_user_meta($user_crnt_id, '_cand_dp', true ) != "" )
{
	$attach_id =	get_user_meta($user_crnt_id, '_cand_dp', true );
	$image_link = wp_get_attachment_image_src( $attach_id, '' );
}
/* Getting Candidate Portfolio */
if( get_user_meta( $user_crnt_id, '_cand_portfolio', true ) != "" )
{	
	$port = get_user_meta( $user_crnt_id, '_cand_portfolio', true );
	$portfolios = explode(',', $port);
	foreach($portfolios as $portfolio)
	{	
			$portfolio_image_sm = wp_get_attachment_image_src( $portfolio, 'nokri_job_hundred' );
			$portfolio_image_lg = wp_get_attachment_image_src( $portfolio, 'nokri_cand_large' );
			$portfolio_html .= '<li><a class="portfolio-gallery" data-fancybox="gallery" href="'.esc_url($portfolio_image_lg[0]).'"><img src="'.esc_url($portfolio_image_sm[0]).'" alt= "'.esc_html__( 'portfolio image', 'nokri' ).'"></a></li>';
	}
 }	
$cand_dob 		=  $remaining_searches = '';
$cand_dob 		= get_user_meta($user_crnt_id, '_cand_dob', true);
$cand_headline  = get_user_meta($user_crnt_id, '_user_headline', true);
$cand_address   = get_user_meta($user_crnt_id, '_cand_address', true);
$cand_fb        = get_user_meta($user_crnt_id, '_cand_fb', true);
$cand_twiter    = get_user_meta($user_crnt_id, '_cand_twiter', true);
$cand_google    = get_user_meta($user_crnt_id, '_cand_google', true);
$cand_linked    = get_user_meta($user_crnt_id, '_cand_linked', true);
$cand_phone     = get_user_meta($user_crnt_id, '_sb_contact', true);
$cand_introd    = get_user_meta($user_crnt_id, '_cand_intro', true);
$cand_intro     = get_user_meta($user_crnt_id, '_cand_intro_vid', true); 
$cand_video	    = get_user_meta($user_crnt_id, '_cand_video', true);
$cand_profile_status	= get_user_meta($user_crnt_id, '_user_profile_status', true); 
if($cand_profile_status == 'priv' & $author_id != $current_user_id) { $image_link[0] =  get_template_directory_uri(). '/images/candidate-dp.jpg'; };
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
/*Profile picture*/
if($cand_profile_status == 'priv'  & $author_id != $current_user_id && $is_applied == false) { $image_link[0] =  get_template_directory_uri(). '/images/candidate-dp.jpg'; };
/*Check search mode*/
if( isset( $nokri['cand_search_mode'] ) && $nokri['cand_search_mode'] == "2" && !$is_applied )
{
	/* Candidate View Resume Logic*/
	$is_search =  nokri_is_cand_search_allowed();
	if($is_search == false && $current_user_id != $author_id  )
	{
		echo '<script>jQuery(document).ready(function($) { toastr.error("'.__( "Please purchase resume package", 'nokri' ).'", "", {timeOut: 6500,"closeButton": true, "positionClass": "toast-top-right"}); });</script>';
		echo nokri_redirect( get_the_permalink($package_page) );
	}
}
/*Social links hide/show*/
$social_links = isset($nokri['user_contact_social']) ? $nokri['user_contact_social']  : true;
/* Advertisment */
$cand_advertisment = isset($nokri['cand_advertisment']) ? $nokri['cand_advertisment']  : '';
?>
<section class="n-breadcrumb-big" <?php echo nokri_section_bg_url(); ?>>
         <div class="container">
            <div class="row">
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="breadcrumb-text">
                     <h2><?php echo esc_html($author->display_name); ?></h2>
                     <ul>
                        <li><?php echo esc_html__('Home', 'nokri' ); ?></li>
                        <li><?php echo nokri_breadcrumb(); ?></li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </section>
<section class="n-candidate-detail">
         <div class="container">
            <div class="row">
             <?php if($cand_profile_status == 'pub' || $author_id == $current_user_id || current_user_can('administrator')) { 
			 	$resumes_viewed = get_user_meta($current_user_id, '_sb_cand_viewed_resumes',true);
				if( isset( $nokri['cand_search_mode'] ) && $nokri['cand_search_mode'] == "2")
				{
						$remaining_searches = get_user_meta($current_user_id, '_sb_cand_search_value', true);
						$unlimited_searches = false;
						if($remaining_searches == '-1')
						{
							$unlimited_searches = true;
						}
					 if(!$is_applied && !$unlimited_searches && !current_user_can('administrator') && $author_id != $current_user_id)
					{
						$resumes_viewed_array =  (explode(",",$resumes_viewed));
						if (!in_array($author_id, $resumes_viewed_array))
						  {
							 $author_id = $author_id;
							if($resumes_viewed != '')
							{
								$author_id = $resumes_viewed.','.$author_id;
							}
							update_user_meta($current_user_id, '_sb_cand_viewed_resumes', $author_id);
							if($remaining_searches != '0')
							{
								update_user_meta($current_user_id, '_sb_cand_search_value', (int)$remaining_searches - 1);
							}
						  }
					}
				}
			 ?>
               <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 col-lg-push-9 col-md-push-8">
                  <aside class="n-single-sidebar">
                     <div class="n-single-job-company">
                        <div class="dingle-job-company-meta">
                         <?php if($social_links || $author_id == $current_user_id && $cand_profile_status == 'pub')  { ?>
                           <ul class="social-links">
                           	<?php if($cand_fb) { ?>
                              <li><a href="<?php echo esc_url($cand_fb); ?>"><img src="<?php echo get_template_directory_uri();?>/images/icons/006-facebook.png" class="img-responsive" alt="<?php echo esc_attr__( 'icon', 'nokri' ); ?>"></a></li>
                              <?php } if($cand_google) { ?>
                              <li>
                                 <a href="<?php echo esc_url($cand_google); ?>">
                                 <img src="<?php echo get_template_directory_uri();?>/images/icons/003-google-plus.png" class="img-responsive" alt="<?php echo esc_attr__( 'icon', 'nokri' ); ?>">
                                 </a>
                              </li>
                                <?php } if($cand_twiter) { ?>
                              <li>
                                 <a href="<?php echo esc_url($cand_twiter); ?>">
                                 <img src="<?php echo get_template_directory_uri();?>/images/icons/004-twitter.png" class="img-responsive" alt="<?php echo esc_attr__( 'icon', 'nokri' ); ?>">
                                 </a>
                              </li>
                              <?php } if($cand_linked) { ?>
                              <li>
                                 <a href="<?php echo esc_url($cand_linked); ?>">
                                 <img src="<?php echo get_template_directory_uri();?>/images/icons/005-linkedin.png" class="img-responsive" alt="<?php echo esc_attr__( 'icon', 'nokri' ); ?>">
                                 </a>
                              </li>
                               <?php } ?>
                           </ul>
                           <?php } ?>
                           <div class="contact-img">
                              <img src="<?php echo esc_url($image_link[0]); ?>" class="img-responsive" alt="<?php echo esc_attr__('image', 'nokri' ); ?>">
                           </div>
                           <div class="contact-caption">
                              <h4><?php echo esc_html($author->display_name); ?></h4>
                              <?php if($cand_headline) { ?>
                              <p><?php echo   esc_html($cand_headline); ?></p>
                              <?php } ?>
                           </div>
                           <button type="submit" class="btn n-btn-custom btn-block saving_resume" data-cand-id= <?php echo esc_attr($author_id);   ?>><i class="fa fa-heart"></i><?php echo esc_html__( 'Save Resume', 'nokri' ); ?></button> 
                           
                        </div>
                     </div>
                     <div class="n-candidate-info">
                        <h4> <?php echo esc_html__('Candidate Detail', 'nokri' ); ?></h4>
                        <ul>
                         <?php if($cand_dob) { ?>
                           <li>
                              <img src="<?php echo get_template_directory_uri();?>/images/icons/resume-detail-icons/017-birthday.png" alt="<?php echo esc_attr__('icon', 'nokri' ); ?>"> 
                              <div class="resume-detail-meta"><small><?php echo esc_html__('Date of Birth:', 'nokri' ); ?></small> <strong><?php echo date_i18n(get_option('date_format'), strtotime($cand_dob)); ?></strong></div>
                           </li>
                           <?php } if($registered) { ?>
                           <li>
                              <img src="<?php echo get_template_directory_uri();?>/images/icons/resume-detail-icons/011-calendar.png" alt="<?php echo esc_attr__('icon', 'nokri' ); ?>"> 
                              <div class="resume-detail-meta"><small><?php echo esc_html__('Member Since:', 'nokri' ); ?></small> <strong><?php echo date_i18n(get_option('date_format'), strtotime($registered)); ?></strong></div>
                           </li>
                           <?php } if($cand_phone && $is_public || $author_id == $current_user_id) { ?>
                           <li>
                              <img src="<?php echo get_template_directory_uri();?>/images/icons/resume-detail-icons/009-phone-receiver.png" alt="<?php echo esc_attr__('icon', 'nokri' ); ?>"> 
                              <div class="resume-detail-meta"><small><?php echo esc_html__('Cell No:', 'nokri' ); ?></small><strong><?php echo  esc_html($cand_phone); ?></strong></div>
                           </li>
                           <?php } if( $is_public || $author_id == $current_user_id ) {   ?>
                           <li>
                              <img src="<?php echo get_template_directory_uri();?>/images/icons/resume-detail-icons/008-email.png" alt="<?php echo esc_attr__('icon', 'nokri' ); ?>"> 
                              <div class="resume-detail-meta"><small><?php echo esc_html__('Email Address:', 'nokri' ); ?></small><strong><?php echo  esc_html($author->user_email); ?></strong></div>
                           </li>
                           <?php } if($cand_address) { ?>
                           <li>
                              <img src="<?php echo get_template_directory_uri();?>/images/icons/resume-detail-icons/007-placeholder.png" alt="<?php echo esc_attr__('icon', 'nokri' ); ?>">
                              <div class="resume-detail-meta"> <small><?php echo esc_html__('Address:', 'nokri' ); ?></small><strong><?php echo  esc_html($cand_address); ?></strong></div>   
                           </li>
                           <?php } ?>
                           
                           
                        </ul>
                     </div>
                     <?php  if( $is_public_contact) {   ?>
                     <div class="n-candidate-contact-form">
                        <h4><?php echo esc_html__('Contact Now', 'nokri' ); ?></h4>
                        <form id="contact_form_email" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                       <input type="text" name="contact_name" data-parsley-required="true" data-parsley-error-message="<?php echo esc_html__( 'Please enter name', 'nokri' ); ?>" class="form-control" placeholder="<?php echo esc_html__( 'Full Name', 'nokri' ); ?>">
                                    </div>
                                    <div class="form-group">
                                       <input type="email" name="contact_email" class="form-control" data-parsley-required="true" data-parsley-error-message="<?php echo esc_html__( 'Please enter email', 'nokri' ); ?>"  placeholder="<?php echo esc_html__( 'Email address', 'nokri' ); ?>">
                                    </div>
                                    <div class="form-group">
                                       <input type="text" class="form-control" data-parsley-required="true" data-parsley-error-message="<?php echo esc_html__( 'Please enter subject', 'nokri' ); ?>" name="contact_subject"   placeholder=" <?php echo esc_html__( 'Subject', 'nokri' ); ?>">
                                    </div>
                                    <input type="hidden" name="receiver_id" value="<?php echo esc_attr($author_id); ?>" />
                                    <div class="form-group">
                                       <textarea name="contact_message" class="form-control"  rows="5"></textarea>
                                    </div>
                                    <button type="submit" class="btn n-btn-flat btn-mid btn-block contact_me"><?php echo esc_html__( 'Message', 'nokri' ); ?></button>
                                 </form>
                     </div>
            <?php } ?>
              <?php if(isset($cand_advertisment) && $cand_advertisment != '') { ?>
             <div class="n-candidate-info">
              <h4 class="widget-heading"><?php echo esc_html__( 'Advertisement', 'nokri' ); ?></h4>
              <?php echo ($cand_advertisment); ?>
               </div>
               <?php } ?>
                      
                  </aside>
               </div>
               <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12 col-lg-pull-3 col-md-pull-4">
                  <div class="n-candidate-meta">
                     <h4><?php echo esc_html__( 'Candidate Detail', 'nokri' ); ?></h4>
                     <?php if($cand_introd != '') { ?>
                     <p><?php  echo ($cand_introd); ?></p>
                     <?php } if(!empty($skills_bar)) { ?>
                     <div class="n-skills resume-3-detail">
                        <h4> <?php echo esc_html__( 'Skills:', 'nokri' ); ?></h4> 
                        <div class="n-skills-tags resume-3-box">
                           <?php echo($skills_bar);?>
                        </div>
                        <?php } ?>
                     </div>
                     <?php
                     $cand_education = get_user_meta($user_crnt_id, '_cand_education', true); 
                     if ( $cand_education  && $cand_education[0]['degree_name'] != '' ) {  ?>
                     <div class="timeline-box">
                        <h4> <?php echo esc_html__( 'Education:', 'nokri' ); ?></h4>
                        <ul class="education">
                            <?php
               foreach($cand_education as $edu) { 
		       $degre_name		= (isset($edu['degree_name']))       ?  '<div class="lead">'.esc_html($edu['degree_name']).'</div>' :   '';
			   $degre_strt		= (isset($edu['degree_start']))      ?  $edu['degree_start'] :   '';
			   $degre_insti	    = (isset($edu['degree_institute']))  ?  '<div class="type ">'.esc_html($edu['degree_institute']).'</div>'   :   '';
			  $degre_details	  = (isset($edu['degree_detail']))   ? '<p class="info">'.$edu['degree_detail'].'</p>'   :   '';
						?>
                        <li>
                            <span></span>
                               <div>
                                  <div class="date"><?php echo esc_html($degre_strt)." "; 
                                  		if($edu['degree_end'] != '') { echo '-'." ".esc_html($edu['degree_end']); } ?>
                                  </div>
                                   <?php echo "".($degre_name).($degre_insti).($degre_details);?>
                               </div>
                        </li>
                      <?php } ?>
                        </ul>
                     </div>
                     <?php } 
                        $cand_profession	= get_user_meta($user_crnt_id, '_cand_profession', true);
                        if ( $cand_profession  && $cand_profession[0]['project_organization'] != '') { ?>
                     <div class="timeline-box">
                        <h4><?php echo esc_html__( 'Work Experience :', 'nokri' ); ?> </h4>
                        <ul class="education">
                          <?php 
                                 foreach($cand_profession as $profession) {
									 $project_end = $profession['project_end'];
									 if($profession['project_end'] == '')
									 {
										$project_end =  esc_html__( 'Currently Working', 'nokri' );
									 }
$project_role	= (isset($profession['project_role'])) ? '<div class="lead">'.esc_html($profession['project_role']).'</div>' :   '';
$project_org	= (isset($profession['project_organization']))? '<div class="type ">'.$profession['project_organization'].'</div>' :   '';
$project_strt	= (isset($profession['project_start']))  ? esc_html($profession['project_start'])   :   '';
$project_detail	= (isset($profession['project_desc']))  ? '<div class="info">'.$profession['project_desc'].'</div>'   :   '';				
									  ?>
                                   <li><span></span>
                                        <div>
                                      	<div class="date"><?php echo esc_html($project_strt)." "; 
                                      if($project_end != '') { echo '-'." ".($project_end); } ?></div>
                                       <?php echo "".($project_role).($project_org).($project_detail);?>
                                        </div>
                                    </li>
                                    <?php } ?>
                        </ul>
                     </div>
                      <?php } 
                        $cand_certifications	=  get_user_meta($user_crnt_id, '_cand_certifications', true);
                        if ( $cand_certifications  && $cand_certifications[0]['certification_name'] != '') { ?>
                     <div class="timeline-box cetificates">
                        <h4><?php echo esc_html__( 'Awards and Certificates  :', 'nokri' ); ?></h4>
                        <ul class="education">
                           <?php 
							 foreach($cand_certifications as $certification) { 
							 if ($certification['certification_name'] != '') {
								 
	$certi_name	= (isset($certification['certification_name'])) ? '<div class="lead">'.esc_html($certification['certification_name']).'</div>' :   '';
	$certi_durat = (isset($certification['certification_duration']))  ? '<span>'.esc_html($certification['certification_duration']).'</span>'   :   '';
	$certi_inst	= (isset($certification['certification_institute']))? '<div class="type ">'.$certification['certification_institute'].$certi_durat.'</div>' :   '';
	$certi_strt	= (isset($certification['certification_start']))  ? esc_html($certification['certification_start'])   :   '';
	$certi_detail	= (isset($certification['certification_desc']))  ? '<div class="info">'.$certification['certification_desc'].'</div>'   :   '';
							 ?>
								<li><span></span>
									<div>
					  <div class="date"><?php echo ($certi_strt)." "; 
					  if($certification['certification_end'] != '') { echo '-'." ".esc_html($certification['certification_end']); } ?></div>
					   <?php echo "".($certi_name).($certi_inst).($certi_detail);?> 
									</div>
								</li>
								<?php }  } ?>
                        </ul>
                     </div>
                     <?php  } if ($portfolio_html) { ?>
                     <div class="timeline-box">
                        <h4><?php echo esc_html__( 'Portfolio :', 'nokri' ); ?>  </h4>
                        <div class="n-my-portfolio">
                           <ul>
                             <?php echo "".$portfolio_html; ?>
                           </ul>
                        </div>
                     </div>
                     <?php }  if(!empty($cand_video)) {  
					 	$rx = '~
							  ^(?:https?://)?                           # Optional protocol
							   (?:www[.])?                              # Optional sub-domain
							   (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
							   ([^&]{11})                               # Video id of 11 characters as capture group 1
								~x';
								$valid = preg_match($rx, $cand_video, $matches);
								$cand_video = $matches[1];
					  ?>
                      <div class="timeline-box n-video-portfolio">
                        <h4><?php echo  esc_html__( 'Portfolio video', 'nokri' ); ?> </h4>
                        <div class="n-my-portfolio ">
                             <iframe width="750" height="380" src="https://www.youtube.com/embed/<?php echo "".($cand_video); ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        </div>
                     </div>
                     <?php } ?>
                  </div>
               </div>
                <?php  } else { ?>
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                	<div class="locked-profile alert alert-danger fade in" role="alert">
                      <i class="la la-lock"></i><?php echo "".( $user_private_txt ); ?>
                   </div>
                </div>
               <?php } ?>
            </div>
         </div>
      </section>
<?php  }