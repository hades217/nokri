<?php 
global $nokri; 
$user_info =   wp_get_current_user();
$user_id   =   get_current_user_id();
/* Getting All Resumes */
$cand_resume 	    = get_user_meta($user_id, '_cand_resume', true);
if ($cand_resume)   {  
$cand_resumes		=  explode(',', $cand_resume);
$resume_table       = '';
$sr_no              = '1';
foreach ( $cand_resumes as $resume ) 
 {
	 $resume_table .=  '<div class="posted-job-list resume-on-jobs">
								<ul class="list-inline">
									<li class="resume-id">'.$sr_no.'</li>
									<li class="posted-job-title">
										<div class="posted-job-title-meta">
											<a href="javascript:void(0)">'. basename( get_attached_file( $resume ) ).'</a>
										</div>
									</li>
									 <li class="posted-job-expiration"> 
										<a href="'.get_permalink( $resume ) . '?attachment_id='. $resume.'&download_file=1" class="btn btn-custom ">'. esc_html__( 'Download', 'nokri' ).'</a>
									</li>
									<li class="posted-job-action"> 
										<a  class="btn btn-custom del_my_resume" value="'.esc_attr($resume).'" id="del_my_resume">'. esc_html__( 'Delete', 'nokri' ).'</a>
									</li>
								</ul>
							</div>';
	 $sr_no++;
  }
?>
<div class="cp-loader"></div>
<div class="main-body">
    <div class="dashboard-job-stats followers">
        <h4><?php echo esc_html__( 'Your Uploaded Resumes', 'nokri' ); ?></h4>
        <div class="dashboard-posted-jobs">
            <div class="posted-job-list resume-on-jobs header-title">
                <ul class="list-inline">
                    <li class="resume-id"><?php echo esc_html__( '#', 'nokri' ); ?></li>
                    <li class="posted-job-title"><?php echo esc_html__( 'Resume Name', 'nokri' ); ?></li>
                    <li class="posted-job-expiration"><?php echo esc_html__( 'Download', 'nokri' ); ?></li>
                    <li class="posted-job-action"><?php echo esc_html__( 'Delete', 'nokri' ); ?></li>
                    
                </ul>
            </div>
          <?php echo "".$resume_table; ?>
        </div>
        <div class="pagination-box clearfix">
            <ul class="pagination">
               <?php echo nokri_blogs_pagination(); ?>
            </ul>
        </div>
    </div>
</div>
<?php } else { ?>
<div class="main-body">
<div class="dashboard-posted-jobs">
    <div class="notification-box">
        <div class="notification-box-icon"><span class="ti-info-alt"></span></div>
        <h4><?php echo esc_html__( 'You have not uploaded any resume yet', 'nokri' ); ?></h4>
    </div>
</div>
</div>
<?php }