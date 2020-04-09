<?php require trailingslashit( get_template_directory () ) . "/template-parts/layouts/job-style/job-informations.php"; ?>


<section class="n-single-job">
         <div class="container">
            <div class="row">
               <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
               <?php if ( get_post_status() == 'pending') { ?>
                  <div class="alert alert-info alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong><?php echo esc_html__('Information ! ', 'nokri' ); ?></strong><?php echo esc_html__('Your job is awaiting for admin approval', 'nokri' ); ?> 
</div>
                  <?php } ?>
                <?php echo "".($job_badge_ul); ?>
                  <div class="n-single-title">
                     <h4><?php the_title(); ?></h4>
                     <ul>
                        <li><i class="fa fa-folder-open"></i> <?php echo $project; ?></li>
                        <?php if(!empty($job_type)) { ?>
                        <li> <i class="fa fa-hand-o-right"></i><?php echo nokri_job_post_single_taxonomies('job_type', $job_type); ?></li><?php } ?>
                        <li> <i class="fa fa-clock-o"></i><?php echo nokri_time_ago(); ?></li>
                        <?php if(isset($nokri['allow_job_countries']) && $nokri['allow_job_countries']!= 'hide'){ ?>
                        <li> <i class="fa fa-map-marker"></i><?php echo $countries_last; ?></li>
                        <?php } ?>
                     </ul>
                  </div>
                  <?php echo ($advert_up); ?>
                  <div class="n-single-meta">
                     <ul class="n-single-meta-detail">
                     <?php if(!empty($job_salary)) { ?>
                        <li>
                           <small><?php echo esc_html__('Salary', 'nokri' ); ?></small>
                           <strong><?php echo nokri_job_post_single_taxonomies('job_currency', $job_currency). " ".nokri_job_post_single_taxonomies('job_salary', $job_salary)." ".'/'. " ".nokri_job_post_single_taxonomies('job_salary_type', $job_salary_type); ?></strong>
                        </li>
                        <?php } if(!empty($job_shift)) { ?>
                        <li>
                           <small><?php echo nokri_feilds_label('shift_txt',esc_html__( 'Shift', 'nokri' )); ?></small>
                           <strong><?php echo nokri_job_post_single_taxonomies('job_shift', $job_shift); ?></strong>
                        </li>
                        <?php } if(!empty($job_qualifications)) {  ?>
                        <li>
                           <small><?php echo nokri_feilds_label('quali_txt',esc_html__( 'Job Qualifications', 'nokri' )); ?></small>
                           <strong><?php echo nokri_job_post_single_taxonomies('job_qualifications', $job_qualifications); ?></strong>
                        </li>
                        <?php } if(!empty($job_vacancy)) { ?>
                        <li>
                           <small><?php echo nokri_feilds_label('vacancy_txt',esc_html__( 'Number of vacancies', 'nokri' )); ?></small>
                           <strong><?php echo esc_html($job_vacancy)." ".($opening_text); ?></strong>
                        </li>
                        <?php } if(!empty($job_experience)) { ?>
                        <li>
                           <small><?php echo nokri_feilds_label('experience_txt',esc_html__( 'Job experience', 'nokri' )); ?></small>
                           <strong><?php echo nokri_job_post_single_taxonomies('job_experience', $job_experience); ?></strong>
                        </li>
                        <?php } if(!empty($job_level)) { ?>
                        <li>
                           <small><?php echo nokri_feilds_label('level_txt',esc_html__( 'Job level', 'nokri' )); ?></small>
                           <strong><?php echo nokri_job_post_single_taxonomies('job_level', $job_level); ?></strong>
                        </li>
                        <?php } 
						/* Dynamic feilds*/
						if(function_exists('nokriCustomFieldsHTML'))
						{
							echo nokriCustomFieldsHTML($job_id);
						}
						?>
                     </ul>
                  </div>
                  <div class="n-single-detail">
                     <h4><?php echo esc_html__('Job Description', 'nokri' ); ?></h4>
                     <?php   the_content();?>
                  </div>
                  <?php if($job_alerts) { ?>
                  <div class="jobs-alert-box">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                           <span><?php echo esc_html($job_alerts_title); ?></span>
                           <p><?php echo esc_html($job_alerts_tagline); ?></p>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <a href="javascript:void(0)" class="btn n-btn-flat job_alert"><?php echo esc_html($job_alerts_btn); ?></a>
                        </div>
                     </div>
                  <?php } ?>
                  <div class="n-skills">
                     <h5><i class="fa fa-tags"></i><?php echo nokri_feilds_label('skills_txt',esc_html__( 'Job skills', 'nokri' )); ?></h5>
                     <div class="n-skills-tags">
                        <?php echo "".rtrim($skill_tags,','); ?>
                     </div>
                  </div>
                  <?php echo ($advert_down); 
				   // check if plugin addtoany actiavted then load that otherwise builtin function
					if ( in_array( 'add-to-any/add-to-any.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
					{
						 echo do_shortcode('[addtoany]');
					}
				  ?>
                  <?php
			   if((isset($nokri['relateds_jobs_switch'])) && $nokri['relateds_jobs_switch']  == '1' )
				{
			   		echo get_template_part( 'template-parts/layouts/job-style/related','jobs');
				}
			    ?>
               </div>
               <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                  <aside class="n-single-sidebar">
                     <div class="app-deadline">
                        <div class="short-detail-icon">
                           <i class="ti-timer"></i>
                        </div>
                        <div class="short-detail-meta">
                           <small> <?php echo esc_html__('Deadline', 'nokri' ); ?></small>
                           <strong><?php echo esc_html($job_deadline); ?></strong>
                        </div>
                        <span class="ab-iocn"><i class="ti-alarm-clock"></i></span>
                     </div>
                     <div class="apply-buttons">
                     <?php
					 /* Author Check */
				  	if ($user_id == $post_author_id || current_user_can('administrator') ) {   
					$edit_url    =   esc_url(nokri_set_url_param(get_the_permalink( $nokri['sb_post_ad_page'] ),'id',esc_attr( $job_id )));
					?>
                     <a href="<?php echo $edit_url;  ?>" class="btn n-btn-flat btn-mid btn-clear"><?php echo esc_html__('Edit Job', 'nokri' ); ?></a>
                     <?php } else {  ?>
                     <?php if($post_apply_status == 'active') {
						 if($job_apply_with == 'exter') { ?>
                        <a href="JavaScript:Void(0)" class="btn n-btn-flat btn-mid btn-clear external_apply" data-job-id="<?php echo esc_attr( $job_id );?>" data-job-exter="<?php echo esc_url( $job_apply_url );?>"><?php echo esc_html__('Apply now', 'nokri' ); ?></a>
                         <?php } else if ($job_apply_with == 'mail') { ?>
                        <a href="#" class="btn n-btn-flat btn-mid btn-clear apply_job" data-job-id="<?php echo esc_attr( $job_id );?>" data-toggle="modal" data-target="#myModal"><?php echo esc_html__('Apply now', 'nokri' ); ?></a>
                        <?php } else  { ?>
                         <a href="javascript:void(0)" class="btn n-btn-flat btn-mid btn-clear apply_job" data-job-id="<?php echo esc_attr( $job_id );?>" data-author-id="<?php echo esc_attr( $post_author_id );?>" data-toggle="modal" data-target="#myModal" id="applying_job"><?php echo esc_html__('Apply now', 'nokri' ); ?></a>
                         <?php }
						/* Enable/disable linkedin apply */
						if((isset($nokri['cand_linkedin_apply'])) && $nokri['cand_linkedin_apply']  == 1 )
							{ 
						echo '<div class="divider">
                           <small>'.esc_html__('Or', 'nokri' ).'</small>	
                           <hr>
                        </div>';
						/* Linkedin key*/
						$linkedin_api_key = '';
						if((isset($nokri['linkedin_api_key'])) && $nokri['linkedin_api_key']  != '' && (isset($nokri['linkedin_api_secret'])) && $nokri['linkedin_api_secret']  != '' && (isset($nokri['redirect_uri'])) && $nokri['redirect_uri']  != '' )
						{
							$linkedin_api_key =  ($nokri['linkedin_api_key']);
							$linkedin_secret_key =  ($nokri['linkedin_api_secret']);
							$redirect_uri =  ($nokri['redirect_uri']);
							$state = 'not_logged_in-' . $job_id;
							if(is_user_logged_in())
							{
								$state = 'logged_in-' . $job_id;
							}
						echo '<a href="https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id='.$linkedin_api_key.'&redirect_uri='.$redirect_uri.'&state='.esc_attr($state).'&scope=r_emailaddress r_basicprofile" class="btn-linkedIn btn-block"><i class="ti-linkedin"></i> <span>'.esc_html__( 'Apply With LinkedIn', 'nokri' ).'</span></a>'; 
						}
					}
					 } else { ?><a href="javascript:void(0)" class="btn n-btn-flat btn-mid btn-clear"><?php echo esc_html__('Job Expired', 'nokri' ); ?></a> <?php } } ?>
                     
                     <a class="btn btn-block n-btn-custom-two save_job" href="javascript:void(0)" data-value=<?php echo esc_attr($job_id);?>><?php echo esc_html__('Save This Job', 'nokri' ); ?> </a>
                     
                     <?php if($is_email_job) { ?>
                     
                     <a class="btn n-btn-custom btn-block email_this_job" href="javascript:void(0)" data-job-id=<?php echo esc_attr($job_id);?>><?php echo esc_html__('Email This Job', 'nokri' ); ?> </a>
                     </div>
                     <?php } if(!empty($job_attachments)) { ?>
                     <div class="n-single-job-attach">
                          <h4 class="widget-heading"><?php echo  esc_html__( 'Job attachments', 'nokri' ); ?></h4>
                        <ul class="job-attach"><?php echo "".($job_attachments); ?></ul>
                     </div>
                     <?php } if($is_public) { ?>
                     <div class="n-single-job-company">
                        <div class="dingle-job-company-meta">
                        <?php if($emp_fb != '' || $emp_google != '' || $emp_twitter != '' || $emp_linkedin != '' ) { ?>
                           <ul class="social-links list-inline">
                     		<?php if($emp_fb) { ?>
                        <li> <a href="<?php echo esc_url($emp_fb); ?>"><img src="<?php echo get_template_directory_uri();?>/images/icons/006-facebook.png" alt="<?php echo esc_attr__( 'icon', 'nokri' ); ?>"></a></li>
						<?php } if($emp_google) { ?>
                        <li> <a href="<?php echo esc_url($emp_google); ?>"><img src="<?php echo get_template_directory_uri();?>/images/icons/003-google-plus.png" alt="<?php echo esc_attr__( 'icon', 'nokri' ); ?>"></a></li>
                        <?php } if($emp_twitter) { ?>
                        <li> <a href="<?php echo esc_url($emp_twitter); ?>"><img src="<?php echo get_template_directory_uri();?>/images/icons/004-twitter.png" alt="<?php echo esc_attr__( 'icon', 'nokri' ); ?>"></a></li>
                        <?php } if($emp_linkedin) { ?>
                        <li> <a href="<?php echo esc_url($emp_linkedin); ?>"><img src="<?php echo get_template_directory_uri();?>/images/icons/005-linkedin.png" alt="<?php echo esc_attr__( 'icon', 'nokri' ); ?>"></a></li>
                        <?php }  ?>
                     		</ul>
                            <?php } ?>
                           <div class="contact-img">
                              <img src="<?php echo esc_url($image_link[0]); ?>" class="img-responsive img-circle" alt="<?php echo esc_attr__('company profile image', 'nokri' ); ?>">
                           </div>
                           <div class="contact-caption">
                              <h4><?php echo esc_html($company_name); ?></h4>
                              <a href="<?php echo esc_url($web); ?>" target="_blank" ><?php echo esc_html__('Visit Website', 'nokri' ); ?></a>
                           </div>
                        </div>
                        <a class="view-profile" href="<?php echo esc_url(get_author_posts_url($post_author_id));?>"><?php echo esc_html__('Visit Profile', 'nokri' ); ?></a>
                     </div>
                     <?php }  if($is_lat_long) { ?>
                     <div class="n-single-job-company">
                    	<div class="dingle-job-company-meta">
                        <div id="itemMap" style="height:300px;" ></div>
                   		 <input type="hidden" id="lat" value="<?php echo esc_attr($ad_map_lat); ?>" />
                    	<input type="hidden" id="lon" value="<?php echo esc_attr($ad_map_long); ?>" />
                    	</div>
                    </div>
                    <?php } if((isset($nokri['single_job_tags'])) && $nokri['single_job_tags']  == 1 && !empty($tags_html) ) { ?>
                     <div class="n-job-tags">
                         <h3><?php echo nokri_feilds_label('tags_txt',esc_html__( 'Job tags', 'nokri' )); ?></h3>
                         <ul class="job-tag-list">
                            <?php echo "".($tags_html); ?>
                         </ul>
                     </div>
                     <?php } ?>
                  </aside>
               </div>
            </div>
         </div>
 </section>
 <div class="modal fade resume-action-modal" id="myModal-linkedin_url">
    <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
          <form method="post" id="submit_linkedin_url" class="apply-job-modal-popup">
            <div class="modal-header">
              <h4 class="modal-title"><?php echo  esc_html__( 'Want to apply for this job', 'nokri' ); ?></h4>
            </div>
            <div class="modal-body">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                  <label><?php echo  esc_html__( 'Enter your linkedin profile url', 'nokri' ); ?><span class="color-red">*</span></label>
                  <input placeholder="<?php echo  esc_html__( 'Enter your linkedin profile url', 'nokri' ); ?>" class="form-control" type="text" name="linkedin_url"  data-parsley-required="true" data-parsley-error-message="<?php echo  esc_html__( 'Enter your linkedin profile url', 'nokri' ); ?>">
               </div>
           </div>
            <div class="modal-footer">
             <button type="submit" name="submit"  class="btn n-btn-flat btn-mid btn-block submit_linkedin_url">
					<?php echo  esc_html__( 'Apply Now', 'nokri' ); ?>
                </button>
             </div>
          </div>
          <input type="hidden" value="<?php echo esc_attr($job_id); ?>"  name="apply_job_id" />
          </form>
        </div>
    </div>
</div>
<?php
if(isset($_GET['src']) && $_GET['src'] == 'lkn')
{
	echo "<script type='text/javascript'>
	jQuery(window).load(function(){
		jQuery('#myModal-linkedin_url').modal({backdrop: 'static', keyboard: false});
	jQuery('#myModal-linkedin_url').modal('show');
	});
	</script>";
} 
if($single_job_schema) { ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "JobPosting",
  "title": "<?php the_title(); ?>",
  "description": "<?php echo wp_strip_all_tags(get_the_content()); ?>",
  "hiringOrganization" : {
    "@type": "Organization",
    "name": "<?php echo esc_html($company_name); ?>",
    "sameAs": "<?php echo esc_url($web); ?>"
  },
  "employmentType": "<?php echo nokri_job_post_single_taxonomies('job_type', $job_type); ?>",
  "datePosted": "<?php echo get_the_date( 'Y-m-d'); ?>",
  "validThrough": "<?php echo esc_html($job_deadline); ?>",
  "jobLocation": {
    "@type": "Place",
    "address": {
      "@type": "PostalAddress",
      "addressCountry": "<?php echo $countries_last; ?>"
    }
  },
  "baseSalary": {
    "@type": "MonetaryAmount",
    "currency": "<?php echo nokri_job_post_single_taxonomies('job_currency', $job_currency); ?>",
    "value": {
      "@type": "QuantitativeValue",
      "value": "<?php echo nokri_job_post_single_taxonomies('job_salary', $job_salary); ?>",
      "unitText": "<?php echo nokri_job_post_single_taxonomies('job_salary_type', $job_salary_type); ?>"
    }
  },
  "qualifications": "<?php echo  nokri_job_post_single_taxonomies('job_qualifications',$job_qualifications); ?>",
  "experienceRequirements": "<?php echo nokri_job_post_single_taxonomies('job_experience', $job_experience); ?>"
}
</script>
<?php }