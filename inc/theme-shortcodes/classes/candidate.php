<?php
/************************************/
/* Ajax handler for Saving Candidate Profile   */
/************************************/
add_action( 'wp_ajax_candidate_profile_action', 'nokri_candidate_profile' );
function nokri_candidate_profile()
{
	global $nokri;
	$taxonomy = 'job_category';
	$user_id = get_current_user_id();
	echo nokri_demo_mode();
	/*Setting profile option*/
	$profile_setting_option = isset($nokri['user_profile_setting_option']) ? $nokri['user_profile_setting_option']  : false;
	// Getting values From Param
	$params = array();
    parse_str( stripslashes( $_POST['candidate_data']), $params);
	wp_update_user( array( 'ID' => $user_id, 'display_name' => $params['cand_name'] ) ); 
	$cand_phone 		= $params['cand_phone'];
	$cand_headline 		= $params['cand_headline'];
	$cand_dob      		= $params['cand_dob'];
	$cand_gender      	= $params['cand_gender'];
	$cand_last_edu      = $params['cand_last_edu'];
	$cand_type          = $params['cand_type']; 
	$cand_level         = $params['cand_level'];
	$cand_experience    = $params['cand_experience'];
	$cand_intro         = $params['cand_intro'];
	$cand_profile       = $params['cand_profile'];
	$cand_skill         = $params['cand_skills'];
	$cand_skill_values  = $params['cand_skills_values'];
	$cand_video         = $params['cand_video'];
	$cand_fb            = $params['cand_fb'];
	$cand_twiter        = $params['cand_twiter'];
	$cand_linked        = $params['cand_linked'];
	$cand_google        = $params['cand_google'];
	$cand_address       = $params['cand_address'];
	$cand_map_lat       = $params['cand_map_lat'];
	$cand_map_long      = $params['cand_map_long'];
	$cand_pro_url       = $params['profile_url'];
	$cand_intro_vid     = $params['cand_intro_video'];
	
	/* Getting Educational Details & Updating Values  */
        $cand_education      = ($params['cand_education']);
		$edu = array();
		$arr2 = array();
		$canData = nokri_convert_to_array($cand_education );
		$countNum = ( $canData['count'] == 0 ) ? 0 : $canData['count']-1;
		for($i=0; $i <= $countNum; $i++)
		{
			$arr = $canData['arr'];
			if($arr['degree_name'][$i] != "" ){
			$arr2['degree_name'] 		= sanitize_textarea_field($arr['degree_name'][$i]);
			$arr2['degree_institute'] 	= sanitize_textarea_field($arr['degree_institute'][$i]);
			$arr2['degree_start']		= sanitize_textarea_field($arr['degree_start'][$i]);
			$arr2['degree_end'] 		= sanitize_textarea_field($arr['degree_end'][$i]);
			$arr2['degree_percent'] 	= sanitize_textarea_field($arr['degree_percent'][$i]);
			$arr2['degree_grade'] 		= sanitize_textarea_field($arr['degree_grade'][$i]);
			$arr2['degree_detail'] 		= sanitize_textarea_field($arr['degree_detail'][$i]);
			$edu[] = $arr2;
			}
		}
		
		/* Getting Professional Details & Updating Values  */
		$cand_profession      = ($params['cand_profession']);
		$arrp = $profession = array();	
		$proData = nokri_convert_to_array($cand_profession );	
		
		
			
				
		$countNum = ( $proData['count'] == 0 ) ? 0 : $proData['count']-1;
		
	
		for($i=0; $i <= $countNum; $i++)
		{
			$arr = $proData['arr'];
			if($arr['project_organization'][$i] != "" ){
				$arrp['project_name'] 		= isset($arr['project_name'][$i]) ? $arr['project_name'][$i] : 1;
				$arrp['project_start'] 	    = isset($arr['project_start'][$i]) ? $arr['project_start'][$i] : '';
				if($arrp['project_name'] == 1)
				{
					$arrp['project_end'] = "";
					
				}
				else
				{
					$arrp['project_end']		= isset($arr['project_end'][$i]) ? sanitize_text_field($arr['project_end'][$i]) : '';
				}
				$arrp['project_role'] 		= isset($arr['project_role'][$i]) ? sanitize_text_field($arr['project_role'][$i]) : '';
				$arrp['project_organization'] = isset($arr['project_organization'][$i]) ? sanitize_text_field($arr['project_organization'][$i]) : '';
				$arrp['project_desc'] 		= isset($arr['project_desc'][$i]) ? sanitize_textarea_field($arr['project_desc'][$i]) : '';
				$profession[] = $arrp;
			}
			
		}
		
		
		if ( count($profession ) > 0)
		{
			update_user_meta( $user_id, '_cand_profession', $profession);
		}
		
		
		/* Getting Certifications & Updating Values  */
		$cand_certifications      = ($params['cand_certifications']);
		$arrc = $certifications = array();	
		$proData = nokri_convert_to_array($cand_certifications );			
		$countNum = ( $proData['count'] == 0 ) ? 0 : $proData['count']-1;
		for($i=0; $i <= $countNum; $i++)
		{
			$arr = $proData['arr'];
			if($arr['certification_name'][$i] != ""){
			$arrc['certification_name']      = sanitize_text_field($arr['certification_name'][$i]);
			$arrc['certification_start']     = sanitize_text_field($arr['certification_start'][$i]);
			$arrc['certification_end']		 = sanitize_text_field($arr['certification_end'][$i]);
			$arrc['certification_duration']  = sanitize_text_field($arr['certification_duration'][$i]);
			$arrc['certification_institute'] = sanitize_text_field($arr['certification_institute'][$i]);
			$arrc['certification_desc'] 	 = sanitize_textarea_field($arr['certification_desc'][$i]);
			$certifications[] = $arrc;
			}
		}
		
		if ($certifications != '')
		{
			update_user_meta( $user_id, '_cand_certifications', $certifications);
		}
		
    

   /* Updating Values In User Meta Of Current Candidate */
   if ($cand_phone != '')
	{
		update_user_meta( $user_id, '_sb_contact', sanitize_text_field($cand_phone));
	}
   if ($cand_headline != '')
	{
		update_user_meta( $user_id, '_user_headline', sanitize_text_field($cand_headline));
	}
	if ($cand_dob != '')
	{
		update_user_meta( $user_id, '_cand_dob', sanitize_text_field($cand_dob));
	} 
	if ($cand_gender != '')
	{
		update_user_meta( $user_id, '_cand_gender', sanitize_text_field($cand_gender));
	} 
	if ($cand_last_edu != '')
	{
		update_user_meta( $user_id, '_cand_last_edu', sanitize_text_field($cand_last_edu));
	}
	if ($cand_type != '')
	{
		update_user_meta( $user_id, '_cand_type', sanitize_text_field($cand_type));
	}
	if ($cand_level != '')
	{
		update_user_meta( $user_id, '_cand_level', sanitize_text_field($cand_level));  
	}
	if ($cand_experience != '')
	{
		update_user_meta( $user_id, '_cand_experience', sanitize_text_field($cand_experience));  
	}
	if ($edu != '')
	{
		update_user_meta( $user_id, '_cand_education', $edu);
	}
	update_user_meta( $user_id, '_cand_intro', sanitize_textarea_field($cand_intro));
	/*If allowed */
	if($profile_setting_option)
	{
		update_user_meta( $user_id, '_user_profile_status', sanitize_text_field($cand_profile));
	}
	else
	{
		update_user_meta( $user_id, '_user_profile_status', sanitize_text_field('pub'));
	}
	
	
	update_user_meta( $user_id, '_cand_skills', ($cand_skill));
	
	if(!empty($cand_skill))
	{
		if(empty($cand_skill_values))
		{
			echo 6;
			die();
		}
		
		
		$cand_skill_valuesss	=	explode(',', $cand_skill_values );
		$cand_skill_sanatize = array();
		if( isset($cand_skill_valuesss) && !empty($cand_skill_valuesss) &&  count($cand_skill_valuesss) > 0 )
		{
			foreach($cand_skill_valuesss as $key => $csv )
			{
				$cand_skill_sanatize[] = sanitize_text_field($csv);
			}
		}
		update_user_meta( $user_id, '_cand_skills_values', ($cand_skill_sanatize)); 
	}
	
	
	update_user_meta( $user_id, '_cand_video',($cand_video));
	update_user_meta( $user_id, '_cand_fb', sanitize_text_field($cand_fb));
	update_user_meta( $user_id, '_cand_twiter', sanitize_text_field($cand_twiter));
	update_user_meta( $user_id, '_cand_linked', sanitize_text_field($cand_linked));
	update_user_meta( $user_id, '_cand_google', sanitize_text_field($cand_google));
	if ($cand_address != '')
	{
		update_user_meta( $user_id, '_cand_address', sanitize_text_field($cand_address));
	}
	if ($cand_map_lat != '')
	{
		update_user_meta( $user_id, '_cand_map_lat', sanitize_text_field($cand_map_lat));
	}
	if ($cand_map_long != '')
	{
		update_user_meta( $user_id, '_cand_map_long', sanitize_text_field($cand_map_long));
	}
	
	/*countries*/
	$cand_location =	array();
	if( $params['cand_country'] != "" )        {  $cand_location[]	=	$params['cand_country'];	 }
	if( $params['cand_country_states'] != "" ) {  $cand_location[]	=	$params['cand_country_states'];}
	if( $params['cand_country_cities'] != "" ) {  $cand_location[]	=	$params['cand_country_cities']; }
	if( $params['cand_country_towns'] != "" )  {  $cand_location[]	=	$params['cand_country_towns']; }
	update_user_meta( $user_id, '_cand_custom_location', sanitize_text_field($cand_location));
	
	/*Validating youtube url*/
	$rx = '~
							  ^(?:https?://)?                           # Optional protocol
							   (?:www[.])?                              # Optional sub-domain
							   (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
							   ([^&]{11})                               # Video id of 11 characters as capture group 1
								~x';
								$valid = preg_match($rx, $cand_intro_vid, $matches);
								$cand_video = $matches[1];
	if($cand_intro_vid != '')
	{
		if($valid)
		{
			update_user_meta( $user_id, '_cand_intro_vid', sanitize_text_field($cand_intro_vid));
		}
		else
		{
			echo 5;
			die();
		}
	}
	
	
	echo 1;
	die();

}


/************************************/
/* Ajax handler for Candidate Proifle Picture   */
/************************************/ 
add_action('wp_ajax_candidate_dp', 'nokri_candidate_dp');
if( ! function_exists( 'nokri_candidate_dp' ) )
{
function nokri_candidate_dp(){
global $nokri;
$user_id = get_current_user_id();

/*demo check */
$is_demo =  nokri_demo_mode();
if($is_demo)
{ 
	echo '2';
	die(); 
}

  /* img upload */
 $condition_img = 7;
 $img_count = count((array) explode( ',',$_POST["image_gallery"] )); 
 if(!empty($_FILES["candidate_dp"])){

 require_once ABSPATH . 'wp-admin/includes/image.php';
 require_once ABSPATH . 'wp-admin/includes/file.php';
 require_once ABSPATH . 'wp-admin/includes/media.php';
  
   
 $files = $_FILES["candidate_dp"];
 $attachment_ids=array();
 $attachment_idss='';

 if($img_count>=1){
 $imgcount=$img_count;
 }else{
 $imgcount=1;
 }
  

 $ul_con='';

 foreach ($files['name'] as $key => $value) {            
   if ($files['name'][$key]) { 
    $file = array( 
     'name' => $files['name'][$key],
     'type' => $files['type'][$key], 
     'tmp_name' => $files['tmp_name'][$key], 
     'error' => $files['error'][$key],
     'size' => $files['size'][$key]
    ); 
	
    $_FILES = array ("candidate_dp" => $file); 
	
// Allow certain file formats
$imageFileType	=	end( explode('.', $file['name'] ) );
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo '0|' . esc_html__( "Sorry, only JPG, JPEG, PNG & GIF files are allowed.", 'nokri' );
	die();
}
 
    $size_arr	    =	explode( '-', $nokri['sb_upload_profile_pic_size'] );
	$display_size	=	$size_arr[1];
	$actual_size	=	$size_arr[0];
	 // Check file size
	if ($file['size'] > $actual_size) {
		echo '0|' . esc_html__( "Max allowed image size is"." ".$display_size, 'nokri' );
		die();
	}
    
    
    foreach ($_FILES as $file => $array) {              
      
      if($imgcount>=$condition_img){ break; } 
     $attach_id = media_handle_upload( $file, $post_id );
      $attachment_ids[] = $attach_id; 

      $image_link = wp_get_attachment_image_src( $attach_id, 'nokri-user-profile' );
      
    }
    if($imgcount>$condition_img){ break; } 
    $imgcount++;
   } 
  }

  
 } 
/*img upload */
$attachment_idss = array_filter( $attachment_ids  );
$attachment_idss =  implode( ',', $attachment_idss );  


$arr = array();
$arr['attachment_idss'] = $attachment_idss;
$arr['ul_con'] =$ul_con; 

$uid	=	$user_id;
if($attach_id != '')
{
	update_user_meta($uid, '_cand_dp', sanitize_text_field($attach_id));
}
echo '1|' . $image_link[0];
 die();

}
}


/************************************/
/* Ajax handler for Del Portfolio */
/************************************/

add_action('wp_ajax_delete_ad_image', 'nokri_delete_ad_image');
if ( ! function_exists( 'nokri_delete_ad_image' ) ) {
function nokri_delete_ad_image()
{
	$user_crnt_id = get_current_user_id();
	if( $user_crnt_id == "" )
		die();
		/*demo check */
		$is_demo =  nokri_demo_mode();
		if($is_demo)
		{ 
			echo '2';
			die(); 
		}
	    $attachmentid	=	trim($_POST['img']);
		wp_delete_attachment( $attachmentid, true );
		if( get_user_meta( $user_crnt_id, '_cand_portfolio', true ) != "" )
		 {
			$ids	=    get_user_meta( $user_crnt_id, '_cand_portfolio', true );
			$res	=	 str_replace($attachmentid, "", $ids);
			$res	=	 str_replace(',,', ",", $res);
			$img_ids= trim($res,',');
			update_user_meta( $user_crnt_id, '_cand_portfolio', sanitize_text_field($img_ids) );
		 }	
		 echo "1"; 
		die();
}  
}

/************************************/
/* Ajax handler for Adding Portfolio */
/************************************/

add_action('wp_ajax_nokri_upload_portfolio', 'nokri_upload_portfolio');

if ( ! function_exists( 'nokri_upload_portfolio' ) ) {
function nokri_upload_portfolio(){
global $nokri;
	$user_id	     =	get_current_user_id();
	/*demo check */
	$is_demo =  nokri_demo_mode();
	if($is_demo)
	{ 
		echo '0|' . esc_html__( "Edit in demo user not allowed", 'nokri' );
		die(); 
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	
	$size_arr	    =	explode( '-', $nokri['sb_upload_size'] );
	$display_size	=	$size_arr[1];
	$actual_size	=	$size_arr[0];
	
	// Allow certain file formats
	$imageFileType	   =	strtolower(end( explode('.', $_FILES['my_file_upload']['name'] ) ));
	if($imageFileType !=    "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" )
	{
		echo '0|' . esc_html__( "Sorry, only JPG, JPEG, PNG & GIF files are allowed.", 'nokri' );
		die();
	}
	 
	 // Check file size
	if ($_FILES['my_file_upload']['size'] > $actual_size) 
	{
		echo '0|' . esc_html__( "Max allowd image size is", 'nokri' ) . " " . $display_size;
		die();
	}
	
	
	
	// Check max image limit
     $user_portfolio	 =	get_user_meta( $user_id, '_cand_portfolio', true );
     if( $user_portfolio != "" )
     {
      $media =  explode( ',', $user_portfolio );
      if( count($media) >= $nokri['sb_upload_limit'] )
      {
       echo '0|' . esc_html__( "You can not upload more than ", 'nokri' ) . " " . $nokri['sb_upload_limit']." ".esc_html__( "images ", 'nokri' );
       die();
      }
     }

	
	$attachment_id  =   media_handle_upload( 'my_file_upload', 0 );
	
	if(!is_wp_error( $attachment_id ))
	{
		
		$user_portfolio	 =	get_user_meta( $user_id, '_cand_portfolio', true );
		if( $user_portfolio != "" )
		{
			$updated_portfolio	=	$user_portfolio . ',' . $attachment_id;
		}
		else
		{
			$updated_portfolio	=	$attachment_id;
		}
		
		update_user_meta( $user_id, '_cand_portfolio', sanitize_text_field($updated_portfolio) );
	}
	else
	{
		echo '0|' . esc_html__( "Some thing went wrong", 'nokri' );
		die();
	}
	
	echo($attachment_id);
	die();
    
}}




/************************************/
/* Ajax handler for Getting Portfolio */
/************************************/



add_action('wp_ajax_get_uploaded_portfolio_images', 'nokri_get_uploaded_portfolio_images');
if ( ! function_exists( 'nokri_get_uploaded_portfolio_images' ) ) {
function nokri_get_uploaded_portfolio_images()
{
	$ids	=	get_user_meta ( get_current_user_id(), '_cand_portfolio', true );
	
	if( !$ids ) return '';
	
	$ids_array	=	explode( ',', $ids );
	
	$result	=	array();
	foreach( $ids_array as $m )
	{
		$obj	=	array();
		$obj['name'] = get_the_guid($m);
		$obj['size'] = filesize( get_attached_file( $m ) );
		$obj['id'] = $m;
		$result[] = $obj;	
	}
	header('Content-type: text/json');
	header('Content-type: application/json');
	echo json_encode($result);
	die();
}
}



/************************************/
/* Ajax handler for Adding Resume */
/************************************/

add_action('wp_ajax_cand_resume', 'nokri_cand_resume');

if ( ! function_exists( 'nokri_cand_resume' ) ) {
function nokri_cand_resume(){
global $nokri;
	
	 $user_id	    =	 get_current_user_id();
	
	
	
	/*demo check */
	$is_demo =  nokri_demo_mode();
	
	if($is_demo)
	{ 
		echo '0|' . esc_html__( "Edit in demo user not allowed", 'nokri' );
		die(); 
	}
	
	
	
	
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	
	$size_arr	    =	explode( '-', $nokri['sb_upload_resume_size'] );
	$display_size	=	$size_arr[1];
	$actual_size	=	$size_arr[0];
	
	// Allow certain file formats
	$imageFileType	=	strtolower(end( explode('.', $_FILES['my_cv_upload']['name'] ) ));
	
	if($imageFileType != "doc" && $imageFileType != "docx"
	&& $imageFileType != "pdf" )
	{
		echo '0|' . esc_html__( "Sorry, doc, docx &  pdf are allowed.", 'nokri' );
		die();
	}
	 
	 // Check file size
	if ($_FILES['my_cv_upload']['size'] > $actual_size) 
	{
		echo '0|' . esc_html__( "Max allowd image size is", 'nokri' ) . " " . $display_size;
		die();
	}
	
	
	// Check max resume limit
     $user_resume    =	 get_user_meta( $user_id, '_cand_resume', true );
     if( $user_resume != "" )
     {
      $media =  explode( ',', $user_resume );
      if( count($media) >= $nokri['sb_upload_resume_limit'] )
      {
       echo '0|' . esc_html__( "You can not upload more than ", 'nokri' ) . " " . $nokri['sb_upload_resume_limit']." ".esc_html__( "resumes ", 'nokri' );
       die();
      }
     }
	
	
	$attachment_id  =    media_handle_upload( 'my_cv_upload', 0 );
	
	if( is_wp_error($attachment_id) )
	{
		echo '0|' . esc_html__( "File is empty.", 'nokri' );
		die();
	}

	
   
	$user_resume    =	 get_user_meta( $user_id, '_cand_resume', true );
	
	if( $user_resume != "" )
	{
		$updated_resume	=	$user_resume . ',' . $attachment_id;
	}
	else
	{
		$updated_resume	=	$attachment_id;
	}
	if ( is_numeric( $attachment_id ) )
	 {
			update_user_meta( $user_id, '_cand_resume', $updated_resume );
	 }
	
	echo($attachment_id);
	die();
    
}}




/************************************/
/* Ajax handler for Del Resume */
/************************************/

add_action('wp_ajax_delete_cand_resume', 'nokri_delete_cand_resume');
if ( ! function_exists( 'nokri_delete_cand_resume' ) ) {
function nokri_delete_cand_resume()
{
	$user_crnt_id = get_current_user_id();
	if( $user_crnt_id == "" )
		die();
	/*demo check */
	$is_demo =  nokri_demo_mode();
	if($is_demo)
	{ 
		echo '0';
		die(); 
	}	
	$attachmentid	=	trim($_POST['resume']);
	if( get_user_meta( $user_crnt_id, '_cand_resume', true ) != "" )
	 {
	  $ids = get_user_meta( $user_crnt_id, '_cand_resume', true );
	  $res =  str_replace($attachmentid, "", $ids);
	  $res =  str_replace(',,', ",", $res);
	  $img_ids= trim($res,',');
	  update_user_meta( $user_crnt_id, '_cand_resume', $img_ids );
	 }	
		
	wp_delete_attachment( $attachmentid, true );
	echo "1";
	die();
}
}



/************************************/
/* Ajax handler for Getting Resume */
/************************************/



add_action('wp_ajax_get_uploaded_cand_resume', 'nokri_get_uploaded_cand_resume');  
if ( ! function_exists( 'nokri_get_uploaded_cand_resume' ) ) {
function nokri_get_uploaded_cand_resume()
{
	$result	=	array();
	$ids	=	get_user_meta ( get_current_user_id(), '_cand_resume', true );
	
	if( $ids != "" )
	{
	$ids_array	=	explode( ',', $ids );
	$cv_icon = '';
		foreach( $ids_array as $m )
		{
			$obj	   =	array();
			$array     =    explode('.', get_attached_file( $m ));
			$extension =    end($array);
			if ($extension == 'pdf' && $extension != '') 
			{
				$cv_icon = trailingslashit( get_template_directory_uri () ).'images/logo-adobe-pdf.jpg';
			}
			else if ($extension == 'doc' && $extension != '') 
			{
				$cv_icon = trailingslashit( get_template_directory_uri () ).'images/DOC.png';
			}
			else if ($extension == 'docx' && $extension != '') 
			{
				$cv_icon = trailingslashit( get_template_directory_uri () ).'images/docx.png';
			}
			$obj['display_name'] = basename( get_attached_file( $m ) );
			$obj['name'] = $cv_icon;
			$obj['size'] = filesize( get_attached_file( $m ) );
			$obj['id'] = $m;
			$result[] = $obj;	
		}
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result);
	}
	else
	{
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result);
	}
	die();
}
}


/************************************/
/* Ajax handler for Candidate Aplly Job Athentication */
/************************************/

add_action( 'wp_ajax_nopriv_aplly_job', 'nokri_aplly_job' );
add_action('wp_ajax_aplly_job', 'nokri_aplly_job');
if ( ! function_exists( 'nokri_aplly_job' ) ) {
function nokri_aplly_job()
{
	global $nokri;
	
	/*demo check */
	$is_demo =  nokri_demo_mode();
	if($is_demo)
	{ 
		echo '5';
		die(); 
	}
	/* Is without login */
	$is_without_login = isset($nokri['apply_without_login']) ? $nokri['apply_without_login']  : false;
	
	if($is_without_login && !is_user_logged_in())
	{
	$job_id      =   ($_POST['apply_job_id']);
	$author_id   =   ($_POST['apply_author_id']);
	echo '<div class="cp-loader"></div>
          <div class="modal fade resume-action-modal" id="myModal-job">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
              <form method="post" id="submit_cv_form1" class="apply-job-modal-popup">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">'. esc_html__( "Want to apply for this job?","nokri").'</h4>
                </div>
                <div class="modal-body">
				
				
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
					  <label>'.__( 'Your Name','nokri' ). '<span class="color-red">*</span></label>
					  <input placeholder="'. __( 'Enter your name','nokri' ).'" class="form-control" type="text" name="sb_reg_name"  data-parsley-required="true" data-parsley-error-message="'. __( 'Please enter your name', 'nokri' ) .'">
				   </div>
			   </div>
			   
			  <div class="col-md-6 col-sm-6 col-xs-12">
			   <div class="form-group">
				  <label>'. __( 'Your Email','nokri' ).'<span class="color-red">*</span></label>
				  <input placeholder="'.  __( 'Enter your email address','nokri' ).'" class="form-control" type="email" data-parsley-type="email" data-parsley-required="true"  data-parsley-error-message="'. __( 'Please enter your valid email', 'nokri' ) .'" data-parsley-trigger="change" name="sb_reg_email">
			   </div>
		 </div>
				
				
				<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
			    <label>'. __( 'Upload Resume','nokri' ).'<span class="color-red">*</span></label>
				
				
				
				<input id="input-b2" name="input-b2" name="my_file_upload[]" type="file" class="file sb_files-data-doc" data-show-preview="false" data-show-upload="false" data-parsley-required="true"  data-parsley-error-message="'. __( 'Upload resume', 'nokri' ) .'" data-msg-placeholder="'. esc_html__( "Click to upload","nokri").'" >
				
				<input type="hidden" id="_sb_company_doc" value="" name="cand_apply_resume" />
				</div></div>
					
					<div class="col-md-12 col-sm-12 col-xs-12">
                	<div class="form-group">
					<label>'.esc_html__('Write cover letter (optional)','nokri').'</label>
                        <textarea name="cand_cover_letter" rows="6" class="form-control" placeholder="'. esc_html__( "Write cover letter","nokri").'" ></textarea>
                    </div></div>
					
					
					<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
                  <button type="submit" name="submit" class="btn n-btn-flat btn-mid btn-block" id="submit_cv_form_btn">'.esc_html__( 'Apply now', 'nokri' ).'</button>
                </div> </div>
					
				<div class="modal-footer">
				 </div>
				
				
				
				<input type="hidden" name="current_job"   id="current_job" value="'.esc_attr($job_id).'" />
				<input type="hidden" name="current_author" id="current_author" value="'.esc_attr($author_id).'" />
              </form>
              </div>
              
            </div>
        </div>';
				die();
	}
	
	
	
	
	/* Dashboard Page */
	$dashboard_id = '';
	if((isset($nokri['sb_dashboard_page'])) && $nokri['sb_dashboard_page']  != '' )
	{
	   $dashboard_id =  ($nokri['sb_dashboard_page']);
	}
	/* Signin Page */
	$signin_page = '';
	if((isset($nokri['sb_sign_in_page'])) && $nokri['sb_sign_in_page']  != '' )
	{
	   $signin_page =  ($nokri['sb_sign_in_page']);
	}
	$job_id      =   ($_POST['apply_job_id']);
	$author_id   =   ($_POST['apply_author_id']);
	$user_id     =   get_current_user_id();
	if($user_id == '')
	{
		echo '2';
		echo nokri_redirect(get_the_permalink($signin_page));
		exit;
	}
	nokri_check_user_activity();
	nokri_check_user_type();
	/* Getting Candidate Resume */
	if( get_user_meta( $user_id, '_cand_resume', true ) != "" )
	{	
		$resume = get_user_meta( $user_id, '_cand_resume', true );
		$resumes = explode(',', $resume);
		$resume_options = '';
		foreach($resumes as $resum)
		{ 
			$resume_options .=   '<option value="'.$resum.'">'.basename( get_attached_file( $resum ) ).'</option>  ';
		}
		
		$select_resumes = '<div class="form-group">
					<label>'.esc_html__('Select your resume to apply','nokri').'</label>
					<select class="select-generat" data-allow-clear="true" data-parsley-required="true" data-parsley-error-message="'. esc_html__('Select your resume to apply','nokri').'" name="cand_apply_resume">
                            <option value="">'.esc_html__( 'Select your resume', 'nokri' ).'</option>
                            '.$resume_options.'
                        </select>
                    </div>
                    <div class="form-group">
					<label>'.esc_html__('Write cover letter (optional)','nokri').'</label>
                        <textarea name="cand_cover_letter" rows="6" class="form-control" placeholder="'. esc_html__( "Write cover letter","nokri").'" ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" name="submit" class="btn n-btn-flat btn-mid btn-block" id="submit_cv_form_btn">'.esc_html__( 'Apply now', 'nokri' ).'</button>
                </div>
                <input type="hidden" name="current_job"   id="current_job" value="'.esc_attr($job_id).'" />
				<input type="hidden" name="current_author" id="current_author" value="'.esc_attr($author_id).'" />';
	}
	else
	{
		$select_resumes =  '<h4>'.esc_html__('You have not uploaded resume','nokri').'</h4>
		<a class="btn n-btn-flat" href="'.get_the_permalink($dashboard_id).'?candidate-page=edit-profile#add-resume" target="_blank">'.esc_html__('Upload Now','nokri').'</a>';
	}
	$resume_exist  = get_post_meta( $job_id, '_job_applied_status_'.$user_id,true);
	
	if ($resume_exist == '')
	{
		echo '<div class="cp-loader"></div>
<div class="modal fade resume-action-modal" id="myModal-job">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
              <form method="post" id="submit_cv_form1" class="apply-job-modal-popup">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">'. esc_html__( "Want to apply for this job?","nokri").'</h4>
                </div>
                <div class="modal-body">
                	'.$select_resumes.'
              </form>
              </div>
              
            </div>
        </div>';
	}
	else 
	{
		echo '4';
		
	}
	die();
}
}


/*******************************************/
/* Ajax handler for Candidate resume action status*/
/******************************************/

add_action( 'wp_ajax_nopriv_candidate_resume_status_action', 'candidate_resume_status_action' );
add_action('wp_ajax_candidate_resume_status_action', 'candidate_resume_status_action');
if ( ! function_exists( 'candidate_resume_status_action' ) ) {
function candidate_resume_status_action()
{
	
	global $nokri;
	$user_id            =   get_current_user_id();
	$candidate_id       =   ($_POST['candidate_id']);
	$candidate_info 	=   get_userdata($candidate_id);
	$job_id             =   ($_POST['job_id']);
	
	/* Getting Email Templates */
	$user_id   =   get_current_user_id();
	$res       =   nokri_get_resumes_list( $user_id );
	$roptions  =   '';
	if(!empty($roptions))
	{
		$roptions .= '<option value="0">'.esc_html__( 'Select an template', 'nokri' ).'</option>';
	}
	foreach( $res as $key => $val )
	  {
		 $roptions .= '<option value="'.esc_attr($key).'">'.esc_html($val['name']).'</option>';
	  }    
	/* Getting No email status */  
	$cand_status = '';
	$cand_status = nokri_canidate_apply_status();
	$coptions  =   '';
	
	foreach( $cand_status as $key => $val )
	{
		$coptions .= '<option value="'.esc_attr($key).'">'.esc_html($val).'</option>';
	}
	
    echo '<div class="modal fade resume-action-modal" id="myModalaction" role="dialog">
<div class="cp-loader"></div>
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">'. esc_html__( 'Take action on', 'nokri' )." ".$candidate_info->display_name." ". esc_html__( 'application', 'nokri' ).'</h4>
                </div>
                <div class="modal-body">
                <form method="post" id="email_template_action" class="job-form" enctype="multipart/form-data">
                  <input type="hidden" value="'. esc_attr($candidate_id).'"  name="candidiate_id" />
                   <input type="hidden" value="'. esc_attr($job_id).'"  name="job_stat_id" />
                	<div class="form-group">
                    	<div class="row">
                        	<div class="company-search-toggle">
                                <div class="col-md-9 col-xs-12 col-sm-9">
                                    <label>'. esc_html__( 'Do you want to send email as well?', 'nokri' ).'</label>
                                </div>
                                <div class="col-md-3 col-xs-12 col-sm-3">
                                    <div class="pull-right '. esc_attr($rtl_class).'">
                                      <input id="email_send_toggle"  data-on="'. esc_html__( 'YES', 'nokri' ).'" data-off="'. esc_html__( 'NO', 'nokri' ).'" data-size="small" data-toggle="toggle" type="checkbox">
                                      <input type="hidden" value="" id="is_send_email" name="is_send_email" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group no-email-status">
                        <label class="">'. esc_html__( 'Select  status', 'nokri' ).'</label>
                        <select class="js-example-basic-single form-control stat_cls" name="cand_status_val">
                           '.($coptions).'
                        </select>
                    </div>
                	<div class="form-group email-status">
                        <label class="">'.esc_html__( 'Select email template', 'nokri' ).'</label>
                        <select class="js-example-basic-single form-control template_select"  id="temp_select">
                          '.($roptions).'
                        </select>
                    </div>
                    <div id="email_temp_html"></div>
                    </form>
                </div>
                <div class="modal-footer">
                 <button type="submit" name="submit"  class="btn n-btn-flat btn-mid btn-block send_email">
					'. esc_html__( 'Send', 'nokri' ).'
                </button>
                </div>
              </div>
            </div>
        </div>';
		die();
	
}
}



/*******************************************/
/* Ajax handler for Candidate short details*/
/******************************************/

add_action( 'wp_ajax_nopriv_candidate_short_details', 'candidate_short_details' );
add_action('wp_ajax_candidate_short_details', 'candidate_short_details');
if ( ! function_exists( 'candidate_short_details' ) ) {
function candidate_short_details()
{
	
	global $nokri;
	$user_id            =   get_current_user_id();
	$candidate_id       =   ($_POST['candidate_id']);
	$job_id             =   ($_POST['job_id']);
	$attachment_id      =   ($_POST['attachment_id']);
	$candidate_data    	=   get_userdata($candidate_id);
    $candidate_name     =   $candidate_data->display_name; 
	$candidate_email    =   $candidate_data->user_email;
	$candidate_phone    =   get_user_meta( $candidate_id, '_sb_contact', true);
	$cand_intro_vid     =   get_user_meta( $candidate_id, '_cand_intro_vid', true);
	$phone_html = '';
	if($candidate_phone)
	{
		$phone_html = '<p><i class="la la-phone"></i>'.esc_html($candidate_phone).'</p>';
	}
	$cand_cover	        =   get_post_meta( $job_id, '_job_applied_cover_'.$candidate_id, true);
	$cover_html = '';
	if($cand_cover)
	{
		$cover_html = '<div class="n-modal-candidate-cover">
                                            <h5> '.esc_html__('Cover Letter','nokri').' </h5>
                                            <p>'.esc_html($cand_cover).'</p>
                                    </div>';
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
	/* Getting resume */
	 if (is_numeric($attachment_id)) 
	{
			$resume_link = '<a href="'.get_permalink( $attachment_id ) . '?attachment_id='. $attachment_id.'&download_file=1"" class="btn btn-default">'.esc_html__( 'Download', 'nokri' ).'</a>';
	} 
	else 
	{
			$resume_link = '<a href="'.$attachment_id.'" class="btn btn-default">'.esc_html__( 'View profile', 'nokri' ).'</a>';
	}
	
	
    echo '<div class="modal fade modal-popup" id="short-detail-modal" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
              	<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">'.esc_html__('Application Details','nokri').' </h4>
                  </div>
                <div class="modal-body">
                    <form action="">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <div class="n-modal-candidate-avatar">
                                    	<img src="'.esc_url($image_dp_link[0]).'" alt="" class="img-responsive img-circle">
                                    </div>
                                    <div class="n-modal-candidate-detail">
                                        <h4>'.esc_html($candidate_name).'</h4>
                                        <p><i class="la la-envelope-o"></i>'.esc_html($candidate_email).'</p>
										
										
                                        '.$phone_html.'
                                        '.$resume_link.'
                                    </div>
                                    '.$cover_html.'
                                  </div>
                            </div>
                        </div>
                        <a href="'.esc_url(get_author_posts_url($candidate_id)).'" class="btn n-btn-flat btn-mid btn-block"> '.esc_html__('View Complete Resume','nokri').'</a>
                    </form>
                </div>
              </div>
            </div>
        </div>';
		die();
	
}
}

/************************************/
/* Ajax handler for Candidate submitting url */
/************************************/

add_action( 'wp_ajax_nopriv_submiit_linkedin_url', 'submiit_linkedin_url' );
add_action('wp_ajax_submiit_linkedin_url', 'submiit_linkedin_url');
if ( ! function_exists( 'submiit_linkedin_url' ) ) {
function submiit_linkedin_url()
{
	// Getting values From Param
	$params = array();
    parse_str( stripslashes( $_POST['submit_linkedin_url']), $params);
	$profile_url       	    =   $params['linkedin_url'];
	$user_id                =   get_current_user_id();
	$job_id                 =   ($params['apply_job_id']);
	$applied_job_key_val    =   $user_id.'|'.$profile_url;
	update_post_meta( $job_id, '_job_applied_resume_'.$user_id,sanitize_text_field($applied_job_key_val));
	echo get_the_permalink($job_id);
	die();
}
}


/************************************/
/* Ajax handler for Candidate Aplly Job Athentication */
/************************************/
add_action( 'wp_ajax_nopriv_submit_cv_action', 'nokri_submit_cv' );
add_action('wp_ajax_submit_cv_action', 'nokri_submit_cv');
if ( ! function_exists( 'nokri_submit_cv' ) ) {
	function nokri_submit_cv() {
	global $nokri;
	$user_id = get_current_user_id();
	// Getting values From Param
	$params = array();
    parse_str( stripslashes( $_POST['submit_cv_data']), $params);
	$email = $params['sb_reg_email'];
    $exists = email_exists( $email );
    if ( $exists ) 
	{
      echo "3";
	  die();
    } 

	
	
	
	/* Without login */
	if($user_id == '')
	{
		$applied_job_id    	 =   $params['current_job'];
		$password    		 =   nokri_randomString (10);
		$user_id             =   nokri_do_register($params['sb_reg_email'],$password);
		update_user_meta($user_id, '_sb_reg_type', '0');
		echo nokri_apply_without_login_password($user_id,$password,$applied_job_id);
	} 
	$cand_resume       	 = $params['cand_apply_resume'];
	$cand_cover        	 = $params['cand_cover_letter'];
    $applied_job_id    	 = $params['current_job'];
	$applied_job_author  = $params['current_author'];
	$applied_job_key_val = $user_id.'|'.$cand_resume;
	$cand_date           = date("F j, Y");
	
	if($cand_resume == "" )
	{
		echo "2";
		die();
	}
	
	/* Email On apply to author*/
    nokri_new_candidate_apply($applied_job_id,$user_id);
    // Updating User Data In Job Meta
	if( $cand_resume != "" )
	{
		update_post_meta( $applied_job_id, '_job_applied_resume_'.$user_id,sanitize_text_field($applied_job_key_val));
	}
	if( $cand_cover != "" )
	{
		update_post_meta( $applied_job_id, '_job_applied_cover_'.$user_id,sanitize_text_field($cand_cover));
	}
		update_post_meta( $applied_job_id, '_job_applied_status_'.$user_id,0);
		update_post_meta( $applied_job_id, '_job_applied_date_'.$user_id,sanitize_text_field($cand_date));
	echo "1";
	die();
}
}

/************************************/
/*  Candidate Aplly with linkedin */
/************************************/


if ( ! function_exists( 'nokri_apply_by_linkedin' ) ) {
	function nokri_apply_by_linkedin($job_id,$user_id, $url = '') {
	$resume_exist  = get_post_meta( $job_id, '_job_applied_resume_'.$user_id,true);
	$profile_exist = get_post_meta( $job_id, '_job_applied_linked_profile'.$user_id,true);
	
	if($resume_exist != ''  || $profile_exist != '' )
	{
		return false;	
	}
	else
	{
	$applied_job_key_val = $user_id.'|'.$url;
		// Updating User Data In Job Meta
		if( $job_id != "" )
		{
			update_post_meta( $job_id, '_job_applied_resume_'.$user_id,sanitize_text_field($applied_job_key_val));
			/* Email On apply to author*/
	        nokri_new_candidate_apply($job_id,$user_id);
		}
		$cand_date	= date("F j, Y");
		update_post_meta( $job_id, '_job_applied_status_'.$user_id,0);
		update_post_meta( $job_id, '_job_applied_date_'.$user_id,$cand_date);
		return true;
	}
}
}





/************************************/
/* Ajax handler for Candidate View Application */
/************************************/

add_action('wp_ajax_view_application', 'nokri_view_application');
if ( ! function_exists( 'nokri_view_application' ) ) {
function nokri_view_application()
{
	global $nokri;
	$job_id     =   ($_POST['app_job_id']);
	$user_id    =   get_current_user_id();
	$job_cvr	=   get_post_meta($job_id, '_job_applied_cover_'.$user_id, true);
	$job_cv		=   get_post_meta($job_id, '_job_applied_resume_'.$user_id, true);
    $array_data	=	explode( '|',  $job_cv );
    $attachment_id	=	$array_data[1];
	
	
	
	 if (is_numeric($attachment_id)) 
		{
        		$resume_link = '<a href="'.get_permalink( $attachment_id ) . '?attachment_id='. $attachment_id.'&download_file=1"">'.esc_html__( 'Download', 'nokri' ).'</a>';
				
				$label = esc_html__('You Applied Against Resume', 'nokri' );
		} 
		else 
		{
				$resume_link = '<a href="'.$attachment_id.'">'.esc_html__( 'View profile', 'nokri' ).'</a>';
				
				$label = esc_html__('You Applied Against Linkedin Profile', 'nokri' );
		}
	
	$filename_only = basename( get_attached_file( $attachment_id ) );
	
	if ($job_cvr != '')
	{
		$job_cvr_html = '<div class="form-group">
                        <label class="">'.esc_html__('Your Cover Letter', 'nokri' ).'</label>
                        <textarea class="form-control rich_textarea" rows="10" name="ckeditor" >'.$job_cvr.'</textarea>
                    </div>';
	}
	
	
		echo '<div class="modal fade resume-action-modal" id="appmodel" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">'.get_the_title($job_id).'</h4>
                </div>
                <div class="modal-body">
                	<div class="form-group">
                    	<div class="row">
                        	<div class="company-search-toggle">
                                <div class="col-md-9 col-xs-12 col-sm-9">
                                    <label>'.$label.'</label>
                                </div>
                                <div class="col-md-3 col-xs-12 col-sm-3">
                                  '.$resume_link.'
                                </div>
                            </div>
                        </div>
                    </div>
                    '.$job_cvr_html.'
                </div>
                <div class="modal-footer">
                </div>
              </div>
              
            </div>
        </div>';

	
	die();
}
}



/************************************/
/* Ajax handler for Candidate Saving Job */
/************************************/
add_action('wp_ajax_save_my_job', 'nokri_save_my_job');
add_action('wp_ajax_nopriv_save_my_job', 'nokri_save_my_job');
if ( ! function_exists( 'nokri_save_my_job' ) )
{
function nokri_save_my_job() 
{
	global $nokri;
	/*demo check */
	$is_demo =  nokri_demo_mode();
	if($is_demo)
	{ 
		echo '4';
		die(); 
	}	
	nokri_check_user_activity();
	nokri_check_user_type();
	$user_id               =   get_current_user_id();
	$job_id                =   $_POST['job_id'];
	$applied_job_key_val   =   $user_id.'|'.$job_id;
	
	if( $job_id != "" && $user_id != ''  )
	{
		update_post_meta( $job_id, '_job_saved_value_'.$user_id,sanitize_text_field($applied_job_key_val));
	}
		echo "1";
		die();
	}
}

/************************************/
/* Ajax handler for Candidate Deleting Saved Job*/
/************************************/

add_action('wp_ajax_del_saved_job', 'nokri_del_saved_job');
if ( ! function_exists( 'nokri_del_saved_job' ) ) {
function nokri_del_saved_job() {
global $nokri;
$user_id  = get_current_user_id();
/*demo check */
$is_demo =  nokri_demo_mode();
if($is_demo)
{ 
	echo '2';
	die(); 
}
$job_id   = $_POST['cand_job_id'];
$applied_job_key_val = $user_id.'|'.$job_id;
if( $job_id != "" )
	{
		delete_post_meta( $job_id, '_job_saved_value_'.$user_id,$applied_job_key_val);
	}
	echo "1";
	die();
	}
}



/************************************/
/* Ajax handler for Candidate Following Company */
/************************************/
add_action( 'wp_ajax_nopriv_following_company', 'nokri_following_company' );
add_action('wp_ajax_following_company', 'nokri_following_company');
if ( ! function_exists( 'nokri_following_company' ) ) {
function nokri_following_company() {
global $nokri;
/*demo check */
$is_demo =  nokri_demo_mode();
if($is_demo)
{ 
	echo '4';
	die(); 
}
$user_id 				=   get_current_user_id();
$company_id   			=   $_POST['company_id'];
$follow_date            =   date("F j, Y");

nokri_check_user_activity();
nokri_check_user_type(); 
if( $company_id != "" )
	{
		update_user_meta( $user_id, '_cand_follow_company_'.$company_id,sanitize_text_field($company_id));
		update_user_meta( $user_id, '_cand_follow_date',sanitize_text_field($follow_date));
	}
	echo "1";
	die();
	}
}

/************************************/
/* Ajax handler for Candidate Un Following Company */
/************************************/

add_action('wp_ajax_un_following_company', 'nokri_un_following_company');
if ( ! function_exists( 'nokri_un_following_company' ) ) {
function nokri_un_following_company() {
global $nokri;
$user_id 				= get_current_user_id();
$company_id   			= $_POST['company_id'];
/*demo check */
$is_demo =  nokri_demo_mode();
if($is_demo)
{ 
	echo '2';
	die(); 
}
if( $company_id != "" )
{
	if(delete_user_meta( $user_id, '_cand_follow_company_'.$company_id))
	{
		echo "1";
		die();
	}
	else
	{
		echo "0";
		die();
	}
}
echo "0";
die();
}
}


/************************************/
/* Return Followed Companies ID's*/
/************************************/
if ( ! function_exists( 'nokri_following_company_ids' ) )
{
	function nokri_following_company_ids($user_id)
	{
		/* Query For Getting All Followed Companies */
		global $wpdb;
		$query	          =      "SELECT meta_value FROM $wpdb->usermeta WHERE user_id = '$user_id' AND meta_key like '_cand_follow_company_%'";
		$cand_followings  =      $wpdb->get_results($query);
		if(count((array) $cand_followings) > 0 )
		{
			 $ids = array();
			 foreach ( $cand_followings as $companies ) 
			 {
				  $ids[] = $companies->meta_value;
			 }
			return $ids;
		}
	}
}

/**********************/
/* Return attachment ID */
/**********************/

add_action( 'wp_ajax_nopriv_sb_upload_user_docs', 'nokri_sb_upload_user_docs' );
add_action('wp_ajax_sb_upload_user_docs', 'nokri_sb_upload_user_docs');
if ( ! function_exists( 'nokri_sb_upload_user_docs' ) ) {  
function nokri_sb_upload_user_docs(){
    
/* img upload */

 $condition_img=7;
 $img_count = count(explode( ',',$_POST["image_gallery"] )); 

 if(!empty($_FILES["my_file_upload"])){

 require_once ABSPATH . 'wp-admin/includes/image.php';
 require_once ABSPATH . 'wp-admin/includes/file.php';
 require_once ABSPATH . 'wp-admin/includes/media.php';
  
   
 $files = $_FILES["my_file_upload"];
 

   
 $attachment_ids=array();
 $attachment_idss='';

 if($img_count>=1){
 $imgcount=$img_count;
 }else{
 $imgcount=1;
 }
  

 $ul_con='';

 foreach ($files['name'] as $key => $value) {            
   if ($files['name'][$key]) { 
    $file = array( 
     'name' => $files['name'][$key],
     'type' => $files['type'][$key], 
     'tmp_name' => $files['tmp_name'][$key], 
     'error' => $files['error'][$key],
     'size' => $files['size'][$key]
    ); 
	
    $_FILES = array ("my_file_upload" => $file); 
	
// Allow certain file formats
$imageFileType	=	strtolower( end( explode('.', $file['name'] ) ) );




if($imageFileType != "pdf" && $imageFileType != "doc" && $imageFileType != "docx"
 ) {
    echo '0|' . __( "Sorry, only PDF, DOC, DOCX  files are allowed.", 'nokri' );
	die();
}
 
 // Check file size
/*if ($file['size'] > 2097152) {
    echo '0|' . __( "Max allowd image size is 2MB", 'adforest' );
    die();
}*/
    
    
    foreach ($_FILES as $file => $array) {              
      
      if($imgcount>=$condition_img){ break; } 
     $attach_id = media_handle_upload( $file, $post_id );
      $attachment_ids[] = $attach_id; 

      $image_link = wp_get_attachment_image_src( $attach_id, '' );
      
    }
    if($imgcount>$condition_img){ break; } 
    $imgcount++;
   } 
  }

  
 } 
/*img upload */
$attachment_idss = array_filter( $attachment_ids  );
$attachment_idss =  implode( ',', $attachment_idss );  


$arr = array();
$arr['attachment_idss'] = $attachment_idss;
$arr['ul_con'] =$ul_con; 

//$profile	= new adforest_profile();
//$uid	=	$profile->user_info->ID;
//update_user_meta($uid, '_sb_user_doc', $attach_id );
echo '1|' . $attach_id;
 die();

}
}