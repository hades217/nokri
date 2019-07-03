<?php 
global $nokri;
$rtl_class = '';
if(is_rtl())
 {
 	$rtl_class = "flip";
 }
$cand_status = '';
if( isset( $_GET['id'] ) )
 {
     $job_id = $_GET['id'];
 }
$user_info =   wp_get_current_user();
$user_id   =   get_current_user_id();
/* Getting Applied Status */
$apllied_actions =  nokri_canidate_apply_status();
$options         =  '';
foreach( $apllied_actions as $val => $key )
  {
	  $options .= '<option value="'.esc_attr($val).'">'.esc_html($key).'</option>';
  }


$cand_msg = esc_html__( 'No candidate sorted with this status', 'nokri' );  
//if($job_id == '')
//{
	$cand_msg = esc_html__( 'No candidate apply yet', 'nokri' );
//}
  
  
/* Query For Getting All Resumes Against Job */
$status_wise	=	false;
$c_status	    =	'';
$extra	        =	" AND meta_key like '_job_applied_resume_%'";
if( isset($_POST['c_status']) )
{
	
	if( isset($_POST['c_status']) && $_POST['c_status'] != "" )
	{
		$c_status	  =	$_POST['c_status'];
		$extra	      =	" AND meta_key LIKE '_job_applied_status_%' AND meta_value = '$c_status'";
		$status_wise  =	true;
	}
}
$c_name	=	'';
if( isset($_POST['c_name']) )
{
	if( isset($_POST['c_name']) && $_POST['c_name'] != "" )
	{
		$c_name	  =	$_POST['c_name'];
	}
}
 $applier	=	array();
global $wpdb;
$query	            =	"SELECT * FROM $wpdb->postmeta WHERE post_id = '$job_id' $extra";
$applier_resumes    =   $wpdb->get_results( $query );


/* Check Is Resume Exist */
if(count( $applier_resumes ) != 0)
{
	
	
		
if( count( $applier_resumes ) > 0 )
{
foreach ( $applier_resumes as $resumes ) 
 {
	

	 if( $status_wise )
	 {
		 $array_data	    =	explode( '_',  $resumes->meta_key );
		 $applier[]	        =	$array_data[4];
		 
		 
	 }
	 else
	 {
		$array_data	    =	explode( '|',  $resumes->meta_value );
		$applier[]	    =	$array_data[0];
		
	 }
 }
}
if( $status_wise &&  count( $applier ) == 0 )
{
	$applier[]	=	'@#/!';
}



/* For Pagination */
 // total no of User to display
 $limit = isset( $nokri['user_pagination'] )         ?    $nokri['user_pagination']     :   5;
	$page   =  (get_query_var('page')) ? get_query_var('page') : 1;
	$offset =  ($page * $limit) - $limit;
	$args   =  array(
			'search'    => "*".esc_attr( $c_name )."*",
			'number'	=> $limit,
			'offset'	=> $offset,
			'include'   => $applier,
			'order '	=> 'ASC',
			'search_columns' => array('display_name',),
			
		);
$user_query   =   new WP_User_Query( $args );
$authors      =   $user_query->get_results();
$pages_number =   ceil($user_query->get_total()/$limit);
$resume_table = $resume_link = '';
if ($authors)
{
	$sr_no = '1';
    foreach ($authors as $author)
    {
        // get all the user's id's
       $candidate_id      =   ($author->ID);
	   $user              =   get_userdata( $candidate_id );
	   $cand_resume       =   get_post_meta( $job_id,'_job_applied_resume_'.$candidate_id,true);
	   $cand_status       =   get_post_meta( $job_id, '_job_applied_status_'.$candidate_id,true);
	   $cand_final        =   nokri_canidate_apply_status($cand_status);
	   $cand_headline     =   get_user_meta( $candidate_id, '_user_headline',true);
	   $job_date	      =   get_post_meta( $job_id, '_job_applied_date_'.$candidate_id, true);
	   $cand_cover	      =   get_post_meta( $job_id, '_job_applied_cover_'.$candidate_id, true);
	   $cand_intro_vid    =   get_user_meta( $candidate_id, '_cand_intro_vid', true);
	   $array_data	      =	  explode( '|',  $cand_resume );
	   $attachment_id     =	  $array_data[1];
	   /*Resume status colours*/
	   if($cand_status == '0')
	   {
		   $label_class = 'default';
	   }
	   elseif($cand_status == '1')
	   {
		   $label_class = 'info';
	   }
	   elseif($cand_status == '2')
	   {
		   $label_class = 'danger';
	   }
	   elseif($cand_status == '3')
	   {
		   $label_class = 'primary';
	   }
	   elseif($cand_status == '4')
	   {
		   $label_class = 'warning';
	   }
	   elseif($cand_status == '5')
	   {
		   $label_class = 'success';
	   }
	   
	    if (is_numeric($attachment_id)) 
		{
        		$resume_link = '<a href="'.get_permalink( $attachment_id ) . '?attachment_id='. $attachment_id.'&download_file=1"">'.esc_html__( 'Download', 'nokri' ).'</a>';
		} 
		else 
		{
				$resume_link = '<a href="'.$attachment_id.'">'.esc_html__( 'View profile', 'nokri' ).'</a>';
		}
	   
	   
	   /* Getting Candidate Dp */
	   $image_dp_link[0] =  get_template_directory_uri(). '/images/candidate-dp.jpg';
	   if( isset( $nokri['nokri_user_dp']['url'] ) && $nokri['nokri_user_dp']['url'] != "" )
		{
			$image_dp_link = array($nokri['nokri_user_dp']['url']);	
		}
		if( get_user_meta($candidate_id, '_cand_dp', true ) != "" )
		{
			$attach_dp_id    =	get_user_meta($candidate_id, '_cand_dp', true );
			$image_dp_link   =  wp_get_attachment_image_src( $attach_dp_id, 'nokri_job_hundred' );
		}
		
		/* If video exisst */
		$video_pop = '';
		if($cand_intro_vid != '')
		{
			$video_pop = '<li class="tool-tip" title="'.esc_attr__('View Video','nokri').'"><a class="bla-1" href="'.esc_html($cand_intro_vid).'"><i class="la la-file-video-o"></i></a></li>';
			echo '<input type="hidden" id="is_intro_vid" value="1" />';
		}
		
		
	   $resume_table .=  '<div class="posted-job-list resume-on-jobs">
						<ul class="list-inline">
							<li class="resume-id">'.esc_html($sr_no).'</li>
							<li class="posted-job-title ">
								<div class="posted-job-title-img gt">
									<a href="'.get_author_posts_url($candidate_id).'"><img src="'.$image_dp_link[0].'" class="img-responsive img-circle" alt="'.esc_html__( 'Candidate Image', 'nokri' ).'"></a>
								</div> 
								<div class="posted-job-title-meta">
									<a href="'.get_author_posts_url($candidate_id).'">'.esc_html($author->display_name).'</a>
									<p>'.esc_html($cand_headline) .'</p>
								</div>
							</li>
							<li class="posted-job-status"> <span class="label label-'.esc_attr($label_class).'">'.esc_html($cand_final).'</span></li>
							<li class="posted-job-applicants">'.$resume_link.'</li>
							
							<li class="posted-job-expiration">'.esc_html($job_date).'</li>
							<li class="posted-job-action"> 
                                                    <ul class="list-inline">
													<li class="tool-tip" title="'.esc_attr__( 'Take Action', 'nokri' ).'"> 
								<a href="javascript:void(0)" class="label label-success candidate_resume_action" data-applierId="'.esc_attr($candidate_id).'" data-jobid="'.esc_attr($job_id).'"  data-toggle="modal" data-target="#myModal"><i class="la la-files-o"></i></a></li>
													
                                                    	<li class="tool-tip candidate_short_det" title="'.esc_attr__('Application Details','nokri').'" data-applierId="'.esc_attr($candidate_id).'" data-jobid="'.esc_attr($job_id).'" data-attachid="'.esc_attr($attachment_id).'" ><a href="" class="label label-info" data-toggle="modal" data-target="#short-detail-modal" ><i class="la la-edit"></i></a></li>
														<li class="tool-tip" title="'.esc_attr__('View Video','nokri').'"><a class="bla-1" href="'.esc_html($cand_intro_vid).'"><i class="la la-file-video-o"></i></a></li>
						</ul>
					</div>';
	   $sr_no++;
    }
}
?>
<div class="cp-loader"></div>
<div class="dashboard-job-filters">
<form method="post" id="emp_resumes_form">
    <div class="row">
        <div class="col-md-8 col-xs-12 col-sm-8">
            <div class="form-group">
                <label class=""><?php echo esc_html__( 'Name', 'nokri' ); ?></label>
                <input type="text" value="<?php echo esc_html($c_name); ?>" class="form-control" placeholder="Keyword or Name" name="c_name">
                <a href="#" class="a-btn search_resume"><i class="ti-search"></i></a>
            </div>
        </div>
        <div class="col-md-4 col-xs-12 col-sm-4">
            <div class="form-group">
                <label class=""><?php echo esc_html__( 'Filter By Status', 'nokri' ); ?></label>
                <select class="js-example-basic-single form-control resumes_filter" name="c_status">
                    <option value="as"><?php echo esc_html__( 'select an option', 'nokri' ); ?></option>
                    <option value="0" <?php if ( $c_status == '0') { echo "selected"; } ; ?>><?php echo esc_html__( 'Received', 'nokri' ); ?></option>
                    <option value="1" <?php if ( $c_status == '1') { echo "selected"; } ; ?>><?php echo esc_html__( 'In-Review', 'nokri' ); ?></option>
                    <option value="2" <?php if ( $c_status == '2') { echo "selected"; } ; ?>><?php echo esc_html__( 'Rejected', 'nokri' ); ?></option>
                    <option value="3" <?php if ( $c_status == '3') { echo "selected"; } ; ?>><?php echo esc_html__( 'Short Listed', 'nokri' ); ?></option>
                    <option value="4" <?php if ( $c_status == '4') { echo "selected"; } ; ?>><?php echo esc_html__( 'Interview', 'nokri' ); ?></option>
                    <option value="5" <?php if ( $c_status == '5') { echo "selected"; } ; ?>><?php echo esc_html__( 'Selected', 'nokri' ); ?></option>
                </select>
            </div>
        </div>
    </div>
</form>
</div>
<div class="main-body">
<div class="dashboard-job-stats">
    <h4><?php echo esc_html__( 'Applications on ', 'nokri' ).get_the_title($job_id); ?></h4>
    <div class="dashboard-posted-jobs">
        <div class="posted-job-list resume-on-jobs header-title">
            <ul class="list-inline">
                <li class="resume-id"><?php echo esc_html__( 'Id', 'nokri' ); ?></li>
                <li class="posted-job-title"><?php echo esc_html__( 'Candidate Name', 'nokri' ); ?></li>
                <li class="posted-job-status"><?php echo esc_html__( 'Status', 'nokri' ); ?></li>
                <li class="posted-job-applicants"><?php echo esc_html__( 'Resume', 'nokri' ); ?></li>
                <li class="posted-job-expiration"><?php echo esc_html__( 'Applied on', 'nokri' ); ?></li>
                <li class="posted-job-action"><?php echo esc_html__( 'Action', 'nokri' ); ?></li>
            </ul>
        </div>
<?php echo "".($resume_table); ?>
</div>
    <div class="pagination-box clearfix">
    <ul class="pagination">
       <?php echo nokri_user_pagination($pages_number,$page);?> 
    </ul>
</div>
</div>
</div>
<input type="hidden" id="action_job_id" value="<?php echo esc_attr($job_id); ?>" />
      
<?php } else { $cand_msg = esc_html__( 'No candidate found', 'nokri' ); ?>
 <div class="dashboard-job-filters">
<form method="post" id="emp_resumes_form">
    <div class="row">
        <div class="col-md-8 col-xs-12 col-sm-8">
            <div class="form-group">
                <label class=""><?php echo esc_html__( 'Name', 'nokri' ); ?></label>
                <input type="text" value="<?php echo esc_html($c_name); ?>" class="form-control" placeholder="Keyword or Name" name="c_name">
                <a href="#" class="a-btn search_resume"><i class="ti-search"></i></a>
            </div>
        </div>
        <div class="col-md-4 col-xs-12 col-sm-4">
            <div class="form-group">
                <label class=""><?php echo esc_html__( 'Filter By Status', 'nokri' ); ?></label>
                <select class="js-example-basic-single form-control resumes_filter" name="c_status">
                    <option value=""><?php echo esc_html__( 'select an option', 'nokri' ); ?></option>
                    <option value="0" <?php if ( $c_status == '0') { echo "selected"; } ; ?>><?php echo esc_html__( 'Received', 'nokri' ); ?></option>
                    <option value="1" <?php if ( $c_status == '1') { echo "selected"; } ; ?>><?php echo esc_html__( 'In-Review', 'nokri' ); ?></option>
                    <option value="2" <?php if ( $c_status == '2') { echo "selected"; } ; ?>><?php echo esc_html__( 'Rejected', 'nokri' ); ?></option>
                    <option value="3" <?php if ( $c_status == '3') { echo "selected"; } ; ?>><?php echo esc_html__( 'Short Listed', 'nokri' ); ?></option>
                    <option value="4" <?php if ( $c_status == '4') { echo "selected"; } ; ?>><?php echo esc_html__( 'Interview', 'nokri' ); ?></option>
                    <option value="5" <?php if ( $c_status == '5') { echo "selected"; } ; ?>><?php echo esc_html__( 'Selected', 'nokri' ); ?></option>
                </select>
            </div>
        </div>
    </div>
</form>
</div>
 <div class="main-body">
<div class="dashboard-posted-jobs">
<div class="notification-box">
<div class="notification-box-icon"><span class="ti-info-alt"></span></div>
<h4><?php echo esc_html($cand_msg ); ?></h4>
</div>
</div>
</div>
<?php }