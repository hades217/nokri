<?php
/* Time Ago Function*/
if( ! function_exists( 'nokri_time_ago' ) )
{
	function nokri_time_ago()
	 {
		return human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ).' '.esc_html__('ago','nokri');
	 }
}

if ( ! function_exists( 'nokri_get_echoVal' ) )
{
	function nokri_get_echoVal($value = '')
	{
		return $value;
	}
}


if( ! function_exists( 'nokri_getBGStyle' ) )
{
 function nokri_getBGStyle($optname = '')
{
  if( $optname == '' ) return '';
  global $nokri;
  $bg_size  = '' ; $bg_attachment = '' ; $bg_repeat = ''; $bg_position = '';
  $bgarea = $nokri["$optname"];  
  $bg_img = '\\s\\t\\y\\l\\e="';
  
        if ( isset( $bgarea['background-color'] ) && $bgarea['background-color']  != "" ) 
  { 
   $bg_img .=  ' background: ' .$bgarea['background-color']. ';';  
  }
    if ( isset( $bgarea['background-image'] ) && $bgarea['background-image'] != "" ) 
    {
    $bg_size = $bgarea['background-size'];
    $bg_attachment = $bgarea['background-attachment'];
    $bg_repeat = $bgarea['background-repeat'];
    $bg_position = $bgarea['background-position'];
    $bg_img .=   ' background: url('.$bgarea['background-image'].'); ';
    $bg_img .=   ' background-repeat: '.$bg_repeat.';';
    $bg_img .=   ' background-size: '.$bg_size.'; ';
    $bg_img .=   ' background-position: '.$bg_position.'; ';
    $bg_img .=   ' background-attachment: '.$bg_attachment.'; ';
        } 
     $bg_img .=   '"'; 
  return str_replace('\\',"",$bg_img);
}
}



if( ! function_exists( 'nokri_valid_json' ) )
{
	function nokri_valid_json($json){
		
		return is_array(json_decode($json,true)) ? true : false;
	
	}
}
/* Employer Dashboard */
if( ! function_exists( 'nokri_employer_dashboard' ) )
{
	function nokri_employer_dashboard($heading = '', $key = '', $des = '' )
	{
		global $nokri;
		$current_user = wp_get_current_user();
		$user_id = get_current_user_id();
		$type	= $detail =	'';
		if ($key == 'email')
		{
			$type .= '<dt>'.$heading.'</dt><dd>'.$current_user->user_email.'</dd>';
		}
		if (get_user_meta($user_id, $key, true) != '' && $des != 'yes')
		{
			
			$response = get_user_meta($user_id, $key, true);
			$t = nokri_valid_json($response);	
				$value = '';
			if( $t )
			{
				$response = json_decode($response,true);
				
				foreach( $response as $r )
				{
					$term = get_term( $r, 'job_category' );
					$value .= $term->name .', ';
				}
				$value = rtrim($value, ", ");
			}
			else
			{
				$value  = $response;
			}
			$type .= '<dt>'.$heading.'</dt><dd>'.$value.'</dd>';
		}
		else if ($des == 'yes')
	    {
			$type .= '<div class="heading-inner"><p class="title">'.$heading.'</p></div><p>'.get_user_meta($user_id, $key, true).'</p>';	
	    }
		return $type; 
	}
	
	
	
}
/* Employer Social Icons */
if( ! function_exists( 'nokri_employer_dashboard_socail_links' ) )
{
	function nokri_employer_dashboard_socail_links( )
	{
		global $nokri;
		$current_user = wp_get_current_user();
		$user_id = get_current_user_id();
		$icons = '';
		if (get_user_meta( $user_id, '_emp_fb', true) != '') 
		{
			$icons .=  '<li><a href="'.get_user_meta( $user_id, '_emp_fb', true).'" class="icoFacebook" title="Facebook"><i class="fa fa-facebook"></i></a></li>';
		}
		if(get_user_meta( $user_id, '_emp_twitter', true) != '')
		{
			$icons .='<li><a href="'.get_user_meta( $user_id, '_emp_twitter', true).'" class="icoTwitter" title="Twitter"><i class="fa fa-twitter"></i></a></li>';
		}
		if(get_user_meta( $user_id, '_emp_linked', true) != '')
		{
			$icons .= '<li><a href="'.get_user_meta( $user_id, '_emp_linked', true).'" class="icoLinkedin" title="Linkedin"><i class="fa fa-linkedin"></i></a></li>';
		}
		if(get_user_meta( $user_id, '_emp_google', true) != '')
		{
			$icons .= '<li><a href="'.get_user_meta( $user_id, '_emp_google', true).'" class="icoGoogle" title="Google +"><i class="fa fa-google-plus"></i></a></li>';
		}	
		return '<div class="resume-social">
				<ul class="social-network social-circle onwhite">
					'.$icons .'
				</ul>
             </div>';
	}
	
	
	
	
}

/* Employer Side Navigation Pages */
if( ! function_exists( 'nokri_employer_dashboard_side_links' ) )
{
function nokri_employer_dashboard_side_links()
	{
		global $nokri;
		$emp_side_edit = '';
 		if((isset($nokri['sb_emp_edit_profile'])) && $nokri['sb_emp_edit_profile']  != '' )
    	{
	 		$emp_side_edit =  ($nokri['sb_emp_edit_profile']);
 		}
		return '<li class="active"><a href="'.get_the_permalink($emp_side_edit).'"><i class="fa fa-user"></i>'.esc_html__('Edit Profile','nokri').'</a></li>';
	}
}


/* Employer Getting Simple Jobs */
if( ! function_exists( 'nokri_simple_jobs' ) )
{
function nokri_simple_jobs($is_expire = '')
	{
	$user_id = get_current_user_id();
	$args = array(
				'taxonomy'      =>  'job_class',
				'order'         =>  'ASC',
				'hide_empty'    =>  false,
				'hierarchical'  =>  false,
				'parent'        =>  0,
				);
				$job_terms      = get_terms( $args );
				/* Getting Simple Job Class Value */
				$simple_job_id = '';
				foreach( $job_terms as $job_term )
				{
					if($is_expire)
					{
					 	 $meta_name      =  'package_job_class_'.$job_term->term_id;
					 	$job_class	     =	 update_user_meta( $user_id, $meta_name, '' );
					}
					else
					{
						if (get_term_meta($job_term->term_id, 'emp_class_check', true) == '1') 
						{
							$simple_job_id = $job_term->term_id ;
							break;
						}
					}
					
				}
					return $simple_job_id;
	}
}


/* Employer Package Expired Notification*/
if( ! function_exists( 'nokri_employer_package_expire_notify' ) )
{
	function nokri_employer_package_expire_notify()
		{
			$user_id                =  get_current_user_id();
			$pkg_message            =  '';
			$job_class_free   		=  nokri_simple_jobs();
			$regular_jobs     		=  get_user_meta($user_id, 'package_job_class_'.$job_class_free,true);
			if($regular_jobs == '')
			{
				$pkg_message    = 're';
			}
			$expiry_date        =  get_user_meta($user_id, '_sb_expire_ads',true);
			$today			    =  date("Y-m-d");
			$expiry_date_string =  strtotime($expiry_date);
			$today_string 		=  strtotime($today);
			if($today_string > $expiry_date_string)
			{
				$pkg_message  = 'pe';
			}
			if($expiry_date == '')
			{
				$pkg_message  = 'np';
			}
			
			return $pkg_message;
		}
}

/* Candidate Package Expired Notification*/
if( ! function_exists( 'nokri_candidate_package_expire_notify' ) )
{
	function nokri_candidate_package_expire_notify()
	{
		$user_id                =  get_current_user_id();
		$pkg_message            =  '';
		$applied_jobs     		=  get_user_meta($user_id, '_candidate_applied_jobs',true);
		if($applied_jobs == '' || $applied_jobs == '0')
		{
			$pkg_message    = 'ae';
		}
		$expiry_date        =  get_user_meta($user_id, '_sb_expire_ads',true);
		$today			    =  date("Y-m-d");
		$expiry_date_string =  strtotime($expiry_date);
		$today_string 		=  strtotime($today);
		if($today_string > $expiry_date_string)
		{
			$pkg_message  = 'pe';
			update_user_meta($user_id, '_candidate_applied_jobs','0');
			update_user_meta($user_id, '_candidate_feature_profile','');
		}
		if($expiry_date == '')
		{
			$pkg_message  = 'np';
		}
		return $pkg_message;
	}
}


if ( ! function_exists( 'nokri_randomString' ) ) {
function nokri_randomString($length = 50) {
 $str = "";
 $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
 $max = count((array) $characters) - 1;
 for ($i = 0; $i < $length; $i++) {
  $rand = mt_rand(0, $max);
  $str .= $characters[$rand];
 }
 return $str;
}
}




/* Load Search Countries */
if ( ! function_exists( 'nokri_load_search_countries' ) ) {
function nokri_load_search_countries($action_on_complete = '')
{
	global $nokri;
	$stricts = '';
	if( isset( $nokri['sb_location_allowed'] ) && !$nokri['sb_location_allowed'] && isset ($nokri['sb_list_allowed_country'] ) )
	{
		$stricts = "componentRestrictions: {country: ". json_encode( $nokri['sb_list_allowed_country'] ) . "}";
	} 
	
	
echo "<script>
	   function nokri_location() {
      var input = document.getElementById('sb_user_address');
	  var action_on_complete	=	'".$action_on_complete."';
	  var options = { ".$stricts."
 };
 		
      var autocomplete = new google.maps.places.Autocomplete(input, options);
	  if( action_on_complete )
	  {
	   new google.maps.event.addListener(autocomplete, 'place_changed', function() {
	  // document.getElementById('sb_loading').style.display	= 'block';
    var place = autocomplete.getPlace();
	document.getElementById('ad_map_lat').value = place.geometry.location.lat();
	document.getElementById('ad_map_long').value = place.geometry.location.lng();
	var markers = [
        {
            'title': '',
            'lat': place.geometry.location.lat(),
            'lng': place.geometry.location.lng(),
        },
    ];
	
	my_g_map(markers);
	//document.getElementById('sb_loading').style.display	= 'none';
});
	   }

   }
   
   </script>";
	
}
}

/* Getting All Countries */
if ( ! function_exists( 'nokri_get_all_countries' ) ) {	 
function nokri_get_all_countries()
{
	$args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'title',
	'order'            => 'ASC',
	'post_type'        => '_sb_country',
	'post_status'      => 'publish',
	);
	$countries = get_posts( $args );
	$res	=	array();
	foreach( $countries as $country )
	{
		$res[$country->post_excerpt] = $country->post_title;
	}
	return $res;
}
}



if ( ! function_exists( 'nokri_make_link' ) ) {	 
function nokri_make_link( $url, $text )
{
	return wp_kses( "<a href='". esc_url ( $url )."' target='_blank'>", nokri_required_tags() )  . $text . wp_kses( '</a>', nokri_required_tags() );	
}
}

if ( ! function_exists( 'nokri_required_tags' ) ) {	
function nokri_required_tags()
{
        return $allowed_tags = array(
            'div'           => nokri_required_attributes(),
            'span'          => nokri_required_attributes(),
            'p'             => nokri_required_attributes(),
            'a'             => array_merge( nokri_required_attributes(), array(
                'href' => array(),
                'target' => array('_blank', '_top'),
            ) ),
            'u'             =>  nokri_required_attributes(),
            'br'             =>  nokri_required_attributes(),
            'i'             =>  nokri_required_attributes(),
            'q'             =>  nokri_required_attributes(),
            'b'             =>  nokri_required_attributes(),
            'ul'            => nokri_required_attributes(),
            'ol'            => nokri_required_attributes(),
            'li'            => nokri_required_attributes(),
            'br'            => nokri_required_attributes(),
            'hr'            => nokri_required_attributes(),
            'strong'        => nokri_required_attributes(),
            'blockquote'    => nokri_required_attributes(),
            'del'           => nokri_required_attributes(),
            'strike'        => nokri_required_attributes(),
            'em'            => nokri_required_attributes(),
            'code'          => nokri_required_attributes(),
            'style'          => nokri_required_attributes(),
            'script'          => nokri_required_attributes(),
            'img'          => nokri_required_attributes(),
        );
}
}

if ( ! function_exists( 'nokri_required_attributes' ) ) {	
 function nokri_required_attributes()
{
        return $default_attribs = array(
            'id' => array(),
            'src' => array(),
            'href' => array(),
            'target' => array(),
            'class' => array(),
            'title' => array(),
            'type' => array(),
            'style' => array(),
            'data' => array(),
            'role' => array(),
            'aria-haspopup' => array(),
            'aria-expanded' => array(),
            'data-toggle' => array(),
            'data-hover' => array(),
            'data-animations' => array(),
            'data-mce-id' => array(),
            'data-mce-style' => array(),
            'data-mce-bogus' => array(),
            'data-href' => array(),
            'data-tabs' => array(),
            'data-small-header' => array(),
            'data-adapt-container-width' => array(),
            'data-height' => array(),
            'data-hide-cover' => array(),
            'data-show-facepile' => array(),
        );
}
}


/* Getting Selected Taxonomies Name */
if ( ! function_exists( 'nokri_job_post_taxonomies' ) ) {	
 function nokri_job_post_taxonomies($taxonomy_name = '', $value = '')
 {
 		$taxonomies = get_terms($taxonomy_name, array('hide_empty' => false , 'orderby'=> 'id', 'order' => 'ASC' ,  'parent'   => 0  )); 
		$option = '';
		if( count((array)  $taxonomies ) > 0 )
		{ 
			foreach( $taxonomies as $taxonomy)
			{	
				$selected = ( $value == $taxonomy->term_id ) ? 'selected="selected"' : '';
				$option .='<option value="'.esc_attr($taxonomy->term_id).'" '.$selected.'>'.esc_html($taxonomy->name).'</option>';
			}
		}
		return $option;		
 }
}
/**********************************************/
/* Getting Checkbox Taxonomies From Widget   */
/**********************************************/
if ( ! function_exists( 'nokri_job_search_taxonomies_checkboxes' ) ) {	
 function nokri_job_search_taxonomies_checkboxes($taxonomy_name = '', $instance )
 {
	 $max_record	=	'';
	 $max_record	=   $instance['no_of_records']  ? $instance['no_of_records'] : 3;
	 $is_open	    =	$instance['is_open'] == 'open' ? 'in' : '';
	 $is_show	    =	true;
	 if( isset($_GET[$taxonomy_name] ) && $_GET[$taxonomy_name] != "" )
	 {
		$is_show	=	false;
		$is_open	=	'in';	 
	 }
	 
	 	global $nokri;
 		$taxonomies = get_terms($taxonomy_name, array('hide_empty' => false , 'orderby'=> 'id', 'order' => 'ASC' ,  'parent'   => 0  )); 
		$option = '';
		$more_html	=	'';
		if( count((array)  $taxonomies ) > 0 )
		{ 
			$count	=	1;
			$showed=	true;
			foreach( $taxonomies as $taxonomy)
			{	
			
				/* Skipping Free Job Class */
				 $emp_class_check     	= get_term_meta($taxonomy->term_id, 'emp_class_check', true);
				if( $emp_class_check  == 1  )
				{
					continue;
				}
				$selected	=	'';
				if( isset( $_GET[$taxonomy_name] ) && $_GET[$taxonomy_name] != "" &&  $_GET[$taxonomy_name] == $taxonomy->term_id  ) 
				{
					$selected = 'checked = "checked"';
				}
				
				$toggle	=	'';
				if(  $max_record > 0 && $count >  $max_record && $is_show )
				{
					$toggle	=	'hide_nexts hide_nexts-' . $taxonomy_name;
				}
				if( $max_record < $count && $is_show  && $showed )
				{
					 $showed	=	false;
					 $more_html	=	'<li class="show-more"><small><a href="javascript:void(0);" class="show_next" data-tax-id="'.esc_attr($taxonomy_name).'">'.__('Show more','nokri').'</a></small><li>';	
				}	
				
				$option .='<li class="'.esc_attr( $toggle ) .'"><input class="input-icheck-search change_select" value = "'.esc_attr($taxonomy->term_id).'" name = "'.$taxonomy_name.'" type="radio" '.esc_attr( $selected ).' ><label>'.esc_html($taxonomy->name).'</label></li>';
				$count++;
			}
			$option	.=	$more_html;
		}
		
		$cls	=	$is_open == 'in' ? 'active' : '';
		
		return  '<div class="panel panel-default">
                                    <div class="panel-heading '.esc_attr($cls).'" role="tab" >
                                      <h4 class="panel-title">
                                        <a class="" role="button" data-toggle="collapse" href="#collapse-'.esc_attr($taxonomy_name).'" >												'.$instance['title'].'	
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapse-'.esc_attr($taxonomy_name).'" class="panel-collapse collapse '.esc_attr($is_open).'">
                                      <div class="panel-body">
									  <form  method="get" action="'.get_the_permalink( $nokri['sb_search_page'] ).'">
                                        <ul class="list">
                                          '.$option.'
                                       </ul>
									   '.nokri_search_params( $taxonomy_name ).'
									  </form>
                                      </div>
                                    </div>
                                  </div>';	
 }
}







/* ========================= */
/*   Get All employess Function   */
/* ========================= */

if ( ! function_exists( 'nokri_top_employers_lists' ) )
 {
	function nokri_top_employers_lists( )
	 {
		 /* WP User Query */
		$args 			= 	array (
		'order' 		=> 	'DESC',
		'meta_query' 	=> 	array(
		array(
		'key'     		=> 	'_sb_reg_type',
		'value'   		=> 	"1",
		'compare' 		=> 	'='
			),
		)
	);
	$user_query   = new WP_User_Query($args);	
	$authors      = $user_query->get_results();
	$count_res    = count($authors);
	$employers_array = array();
	if (!empty($authors))
	{
		if( count((array)  $authors ) > 0 && $authors != "" )
		{
			foreach( $authors as $author )
			{
				$employers_array[$author->ID]	=	$author->display_name;
			}
		}
	}
		return $employers_array;
	}
}


/************************/
/* Employer sidebar html  */
/************************/
if ( ! function_exists( 'nokri_top_employers_search' ) ) {	
 function nokri_top_employers_search()
 {
	 global $nokri;
	 $employers = array();
	 if(isset($nokri['multi_company_select']) && $nokri['multi_company_select'] != '' )
	 {
		$employers = $nokri['multi_company_select'];
	 }
	
	$employers_array = array();
	
	if( count((array)  $employers ) > 0 && $employers != "" )
		{
			foreach( $employers as $key => $value )
			{
				$employers_array[]	=	$value;
			}
		}
	/* WP User Query */
		$args 			= 	array (
		'order' 		=> 	'DESC',
		'include'       => $employers_array,
	);
	$user_query = new WP_User_Query($args);	
	$authors = $user_query->get_results();
	$required_user_html = '';
	if (!empty($authors))
	{
		foreach ($authors as $author)
		{
			$user_id   = $author->ID;
			$user_name = $author->display_name;
			/* Profile Pic  */
			$image_dp_link[0] =  get_template_directory_uri(). '/images/candidate-dp.jpg';
			if( isset( $nokri['nokri_user_dp']['url'] ) && $nokri['nokri_user_dp']['url'] != "" )
			{
				$image_dp_link = array($nokri['nokri_user_dp']['url']);	
			}
			if(get_user_meta($user_id, '_sb_user_pic', true ) != '')
			{
				$attach_dp_id     =  get_user_meta($user_id, '_sb_user_pic', true );
				$image_dp_link    =  wp_get_attachment_image_src( $attach_dp_id, '' );
			}
			$user_post_count = count_user_posts( $user_id , 'job_post' );
			$user_post_count_html = '<span class="job-openings">'.$user_post_count." ".esc_html__( 'Openings', 'nokri' ).'</span>';
			$required_user_html .= '<a href="'.esc_url(get_author_posts_url($user_id)).'">
                                    <div class="company-list-box">
                                        <span class="company-list-img">
                                            <img src="'.esc_url($image_dp_link[0]).'" class="img-responsive" alt="'.esc_attr__( 'Image', 'nokri' ).'">
                                        </span>
                                        <div class="company-list-box-detail">
                                            <h5>'.$user_name.'</h5>
                                           <p>'.  get_user_meta($user_id,'_user_headline', true ).'</p>
                                           '.$user_post_count_html.'
                                        </div>
                                    </div>
                                </a>';
		}
	}
		  if(isset($nokri['multi_company_select']) && $nokri['multi_company_select'] != '' )
		 {
			return $required_user_html;
		 }
 	}
}




/* Getting  Taxonomies Name  From Widget  */
if ( ! function_exists( 'nokri_job_search_taxonomies' ) ) {	
 function nokri_job_search_taxonomies($taxonomy_name = '', $value = '')
 {
	 	global $nokri;
 		$taxonomies = get_terms($taxonomy_name, array('hide_empty' => false , 'orderby'=> 'id', 'order' => 'ASC' ,  'parent'   => 0  )); 
		$option = '';
		if( count((array)  $taxonomies ) > 0 )
		{ 
			
			foreach( $taxonomies as $taxonomy)
			{	
				$selected	=	'';
				if( isset( $_GET[$taxonomy_name] ) && $_GET[$taxonomy_name] != "" &&  $_GET[$taxonomy_name] == $taxonomy->term_id  ) 
				{
					$selected = 'selected = "selected"';
				}
				$option .='<option value="'.esc_attr($taxonomy->term_id).'" '.$selected.'>'.esc_html($taxonomy->name).'</option>';
			}
		}
		
		
		return  '<form  method="get" action="'.get_the_permalink( $nokri['sb_search_page'] ).'">   
				<div class="categories-module">
				 <div class="form-group">
				<select  class="select-category form-control change_select" name ="'.$taxonomy_name.'">
				<option label="'.esc_html__('Select Option','nokri').'"></option>
				'.$option.'
				</select>
				 </div>
				</div>'.nokri_search_params( $taxonomy_name ).'</form>';	
 }
}



// Job search params

if ( ! function_exists( 'nokri_search_params' ) ) {
function nokri_search_params( $index, $second = '')
{
	$param	=	$_SERVER['QUERY_STRING'];
	$res	=	'';
	if( isset( $param ) )
	{
		parse_str($_SERVER['QUERY_STRING'], $vars);
		foreach( $vars as $key => $val )
		{
			if ( $key == $index )
				continue;
			if($second != "" )
			{
				if ( $key == $second )
					continue;	
			}
			if(isset($vars['custom']) && count((array) $vars['custom']) > 0 && 'custom' == $key)
			{
				foreach($vars['custom'] as $ckey => $cval )
				{
					$name = "custom[$ckey]";
					if ( $name == $index ) {continue;}
					$res .= '<input type="hidden" name="'.esc_attr($name).'" value="'. esc_attr( $cval ) .'" />';
				}
			}
			else{
			
		  	$res .= '<input type="hidden" name="'.esc_attr( $key ). '" value="'. esc_attr( $val ) .'" />';
			}
		}
	}
	return $res;
}
}



/* Getting Taxonomies Name On Single Page */
if ( ! function_exists( 'nokri_job_post_single_taxonomies' ) ) {	
 function nokri_job_post_single_taxonomies($taxonomy_name = '', $value = '')
 {
 		$taxonomies_single = get_term_by('id', $value, $taxonomy_name); 

		if ($taxonomies_single) 
		{
			return $taxonomies_single->name;	
		}
 }
}



/* Getting Taxonomies Name And Colour */
if ( ! function_exists( 'nokri_job_search_taxonomy' ) ) {	
 function nokri_job_search_taxonomy( $id = '')
 {
		 global $nokri;
		 $ids = wp_get_post_terms( $id, 'job_type' );
		 if((array)$ids && isset($ids[0]->term_id) && $ids[0]->term_id != "" )
		 {
			 foreach($ids as $idz )
			 {
				$term_vals =  get_term_meta($idz->term_id);
				$color_bg  =  get_term_meta( $idz->term_id, '_job_type_term_color', true );
				$color     =  get_term_meta( $idz->term_id, '_job_type_term_color_bg', true );
				$style_color = '';
				if($color_bg != "" )
				{
					$style_color = 'background-color: '.$color.';  color: '.$color_bg.';';
				}
				
				return '<a href="'.get_the_permalink($nokri['sb_search_page']).'?job_type='.$idz->term_id.'" class="label part-time"  style= "'.($style_color).'">'.esc_html($idz->name).'</a>';				 
			  }
		 }
 }
}

/* Getting Taxonomies Name And Colour */
if ( ! function_exists( 'nokri_job_class_taxonomy_colour' ) ) {	
 function nokri_job_class_taxonomy_colour( $id = '')
 {
		 global $nokri;
		 $ids = wp_get_post_terms( $id, 'job_class' );
		 if((array)$ids && isset($ids[0]->term_id) && $ids[0]->term_id != "" )
		 {
			 foreach($ids as $idz )
			 {
				$term_vals = get_term_meta($idz->term_id);
				$color     =  get_term_meta( $idz->term_id, '_job_class_term_color', true );
				$style_color = '';
				if($color != "" )
				{
					$style_color = 'style=" color: '.$color.' !important; border: 1px solid '.$color.' !important;"';
				}
				return '<a href="'.get_the_permalink($nokri['sb_search_page']).'?job_type='.$idz->term_id.'" class="mata-detail part" '.$style_color.'>'.$idz->name .'</a>';			 
			  }
		 }
 }
}






/* Getting All Sub Level Categories */

if ( ! function_exists( 'nokri_get_ad_cats' ) )
 {
	function nokri_get_ad_cats( $id , $by = 'name' )
	{
		$post_categories 	= wp_get_object_terms( $id,  'job_category', array('orderby' => 'term_group') );
		$cats 				= array();
		foreach($post_categories as $c)
		{
			$cat 	= get_category( $c );
			$cats[] = array( 'name' => $cat->name, 'id' => $cat->term_id );
		}
		return $cats;
	}
}



/* Terms Child */

add_action('wp_ajax_get_cats', 'nokri_term_child');
add_action('wp_ajax_nopriv_get_cats', 'nokri_term_child');
if( ! function_exists( 'nokri_term_child' ) ) {
function nokri_term_child()
	{
	$cat_id = ($_POST['cat_id']);
	$taxonomy = 'job_category';
	$terms = get_terms(array(
			'taxonomy' => $taxonomy,
			'hide_empty' => false,
			'parent' => $cat_id,
		));
			$taxo = '';
			
			if(!empty($terms))
			{
				$taxo .= '<option value="0">'.esc_html__('Select Option','nokri').'</option>';
			}
			foreach ( $terms as $term )
			 {
				$taxo .= '<option value="'.$term->term_id.'">'.$term->name.'</option>';
			 }	
			 
			 
			echo "".$taxo;
	
	 die();
	}
}




// Get sub cats
add_action('wp_ajax_sb_get_sub_cat_search', 'nokri_get_sub_cats_search');
add_action( 'wp_ajax_nopriv_sb_get_sub_cat_search', 'nokri_get_sub_cats_search' );
if ( ! function_exists( 'nokri_get_sub_cats_search' ) ) {
	function nokri_get_sub_cats_search()
	{
		global $nokri;
		$heading = '';
		if(isset($nokri['cat_level_2']) && $nokri['cat_level_2'] !="")
		{
			$heading = $nokri['cat_level_2'];
		}
		$cat_id	   =	 $_POST['cat_id'];
		$ad_cats	=	nokri_get_cats('job_category' , $cat_id );
		$res	=	'';
		if( count((array)  $ad_cats ) > 0 )
		{
			$res	.=	'<label>'.$heading.'</label>';
			$res	.= '<select class="questions-category form-control"  id="cats_response">';
			$res	.=	'<option label="'.esc_html__('Select Option','nokri').'"></option>';
			foreach( $ad_cats as $ad_cat )
			{
				$res .= '<option value='.esc_attr( $ad_cat->term_id ).'>'.esc_html( $ad_cat->name ).'</option>';
			}
			$res	.= '</select>';
			echo($res);
		}
		die();
	}
}




// Get sub cats Version
add_action('wp_ajax_sb_get_sub_sub_cat_search', 'nokri_get_sub_sub_cats_search');
add_action( 'wp_ajax_nopriv_sb_get_sub_sub_cat_search', 'nokri_get_sub_sub_cats_search' );
if ( ! function_exists( 'nokri_get_sub_sub_cats_search' ) ) {
	function nokri_get_sub_sub_cats_search()
	{
		global $nokri;
		$heading = '';
		if(isset($nokri['cat_level_3']) && $nokri['cat_level_3'] !="")
		{
			$heading = $nokri['cat_level_3'];
		}
		$cat_id	=	$_POST['cat_id'];
		$ad_cats	=	nokri_get_cats('job_category' , $cat_id );
		$res	=	'';
		if( count((array)  $ad_cats ) > 0 )
		{
			$res	.=	'<label>'.$heading.'</label>';
			$res	.= '<select class="search-select form-control"  id="select_version">';
			$res	.=	'<option label="'.esc_html__('Select Option','nokri').'"></option>';
			foreach( $ad_cats as $ad_cat )
			{
				$res .= '<option value='.esc_attr( $ad_cat->term_id ).'>'.esc_html( $ad_cat->name ).'</option>';
			}
			$res	.= '</select>';
			echo($res);
		}
		die();
	}
}



// Get sub cats Version 4th Level
add_action('wp_ajax_sb_get_sub_sub_sub_cat_search', 'nokri_get_sub_sub_sub_cats_forth_search');
add_action( 'wp_ajax_nopriv_sb_get_sub_sub_sub_cat_search', 'nokri_get_sub_sub_sub_cats_forth_search' );
if ( ! function_exists( 'nokri_get_sub_sub_sub_cats_forth_search' ) ) {
	function nokri_get_sub_sub_sub_cats_forth_search()
	{
		global $nokri;
		$heading = '';
		if(isset($nokri['cat_level_4']) && $nokri['cat_level_4'] !="")
		{
			$heading = $nokri['cat_level_4'];
		}
		$cat_id	=	$_POST['cat_id'];
		$ad_cats	=	nokri_get_cats('job_category' , $cat_id );
		$res	=	'';
		if( count((array)  $ad_cats ) > 0 )
		{
			$res	.=	'<label>'.$heading.'</label>';
			$res	.= '<select class="search-select form-control"  id="select_forth">';
			$res	.=	'<option label="'.esc_html__('Select Option','nokri').'"></option>';
			foreach( $ad_cats as $ad_cat )
			{
				$res .= '<option value='.esc_attr( $ad_cat->term_id ).'>'.esc_html( $ad_cat->name ).'</option>';
			}
			$res	.= '</select>';
			echo($res);
		}
		die();
	}
}






// Get Countries
add_action('wp_ajax_get_countries_search', 'nokri_get_countries_search');
add_action( 'wp_ajax_nopriv_get_countries_search', 'nokri_get_countries_search' );
if ( ! function_exists( 'nokri_get_countries_search' ) ) {
	function nokri_get_countries_search()
	{
		global $nokri;
		$heading = '';
		if(isset($nokri['cat_level_2']) && $nokri['cat_level_2'] !="")
		{
			$heading = $nokri['cat_level_2'];
		}
		$country_id	       =	 $_POST['country_id'];
		$job_countries	   =	 nokri_get_cats('ad_location' , $country_id );
		$res	=	'';
		if( count((array)  $job_countries ) > 0 )
		{
			$res	.=	'<label>'.$heading.'</label>';
			$res	.= '<select class="questions-category form-control"  id="countries_response">';
			$res	.=	'<option label="'.esc_html__('Select Option','nokri').'"></option>';
			foreach( $job_countries as $job_country )
			{
				$res .= '<option value='.esc_attr( $job_country->term_id ).'>'.esc_html( $job_country->name ).'</option>';
			}
			$res	.= '</select>';
			echo($res);
		}
		die();
	}
}



// Get States


add_action('wp_ajax_get_states_search', 'nokri_get_states_search');
add_action( 'wp_ajax_nopriv_get_states_search', 'nokri_get_states_search' );
if ( ! function_exists( 'nokri_get_states_search' ) ) {
	function nokri_get_states_search()
	{
		global $nokri;
		$heading = '';
		if(isset($nokri['cat_level_2']) && $nokri['cat_level_2'] !="")
		{
			$heading = $nokri['cat_level_2'];
		}
		$country_id	       =	 $_POST['country_id'];
		$job_countries	   =	 nokri_get_cats('ad_location' , $country_id );
		$res	=	'';
		if( count((array)  $job_countries ) > 0 )
		{
			$res	.=	'<label>'.$heading.'</label>';
			$res	.= '<select class="questions-category form-control"  id="state_response">';
			$res	.=	'<option label="'.esc_html__('Select Option','nokri').'"></option>';
			foreach( $job_countries as $job_country )
			{
				$res .= '<option value='.esc_attr( $job_country->term_id ).'>'.esc_html( $job_country->name ).'</option>';
			}
			$res	.= '</select>';
			echo($res);
		}
		die();
	}
}



// Get Cities


add_action('wp_ajax_get_cities_search', 'nokri_get_cities_search');
add_action( 'wp_ajax_nopriv_get_cities_search', 'nokri_get_cities_search' );
if ( ! function_exists( 'nokri_get_cities_search' ) ) {
	function nokri_get_cities_search()
	{
		global $nokri;
		$heading = '';
		if(isset($nokri['cat_level_2']) && $nokri['cat_level_2'] !="")
		{
			$heading = $nokri['cat_level_2'];
		}
		$country_id	       =	 $_POST['country_id'];
		$job_countries	   =	 nokri_get_cats('ad_location' , $country_id );
		$res	=	'';
		if( count((array)  $job_countries ) > 0 )
		{
			$res	.=	'<label>'.$heading.'</label>';
			$res	.= '<select class="questions-category form-control"  id="cities_response">';
			$res	.=	'<option label="'.esc_html__('Select Option','nokri').'"></option>';
			foreach( $job_countries as $job_country )
			{
				$res .= '<option value='.esc_attr( $job_country->term_id ).'>'.esc_html( $job_country->name ).'</option>';
			}
			$res	.= '</select>';
			echo($res);
		}
		die();
	}
}





/* Getting Job Post Countries */

if ( ! function_exists( 'nokri_get_job_country' ) ) {
function nokri_get_job_country( $id , $by = 'name' )
{
	$post_countries = wp_get_object_terms( $id,  array('ad_location'), array('orderby' => 'term_group') );
	$countries = array();
	foreach($post_countries as $country)
	{
		$related_result = get_category( $country );
		$countries[] = array( 'name' => $related_result->name, 'id' => $related_result->term_id );
	}
	return $countries;
}
}



/* Getting Job Post Country City States */

if ( ! function_exists( 'nokri_display_adLocation' ) ) {  
function nokri_display_adLocation( $pid )
{
 global $nokri;
 $ad_country = '';
 $type = ''; 
 $type = $nokri['cat_and_location'];
 $ad_country = wp_get_object_terms( $pid,  array('ad_location'), array('orderby' => 'term_group') );
 $all_locations = array();
 foreach($ad_country as $ad_count)
 {
  $country_ads = get_term( $ad_count);
  $item = array(
   'term_id' => $country_ads->term_id, 
   'location' => $country_ads->name
   );
  $all_locations[] = $item;
 }
 $location_html = '';
  if(count((array)  $all_locations ) > 0 )
  {
   $limit = count((array)  $all_locations ) - 1;
   for( $i = $limit; $i>=0; $i-- )
   {
    if($type == 'search')
    {
     
    $location_html .= '<a href="'.get_the_permalink($nokri['sb_search_page']).'?country_id='.$all_locations[$i]['term_id'].'">'.esc_html( $all_locations[$i]['location'] ).'</a>, ';
    }
    else
    {
     $location_html .= '<a href="'.get_term_link( $all_locations[$i]['term_id'] ).'">'.esc_html( $all_locations[$i]['location'] ).'</a>, '; 
    }
   }
   
  }
  return rtrim($location_html, ', ');
 
}
}










/* Getting Job Class For Badges */ 

if ( ! function_exists( 'nokri_job_class_badg' ) ) {
function nokri_job_class_badg( $job_id = '' )
	{
		$term_ids	=	array();
		$job_class_badges = get_terms( array( 'taxonomy' => 'job_class', 'hide_empty' => false, ) );
		foreach( $job_class_badges as $job_class_badge )
		{
			$term_id = $job_class_badge->term_id;
	 		$job_class_post_meta = get_post_meta($job_id, 'package_job_class_'.$term_id, true);
			if( $job_class_post_meta == $term_id )
	 			$term_ids[$job_class_badge->name]	=	$job_class_post_meta;
		}
		return $term_ids;
	}
}


/******************************************/		
/* Calling Funtion Job Class For Badges */
/******************************************/

if ( ! function_exists( 'nokri_premium_job_class_badges' ) ) 
{	
	 function nokri_premium_job_class_badges($job_id = '')
	 {
		 	global $nokri;
		 	$job_badge_ul       = '';
			$single_job_badges  = wp_get_post_terms($job_id, 'job_class', array("fields" => "ids"));
			$featured_html =  $premium_val  =  $premium_class = $job_badge_text =  $job_badge_ul= '';
			if( count((array)  $single_job_badges ) > 0) 
			{	
				$premium_class  = 'featured-box';
				foreach( $single_job_badges as $job_badge => $val )
					{
						
						$term_vals =   get_term_meta($val);
						$terms     =   get_term_by( 'id', $val, 'job_class' ); 
						$bg_color  =   get_term_meta( $val, '_job_class_term_color_bg', true );
						$color     =   get_term_meta( $val, '_job_class_term_color', true );
						$style_li  =   $style_anch ='';
						if($color != "" )
						{
							$style_li   = 'style=" background-color: '.$bg_color.' !important;"';
							$style_anch = 'style="color: '.$color.' !important;"';
						}
						
						$premium_val     =  get_post_meta( $job_id, 'package_job_class_'.$val, true );
						$featured_html   = ' <div class="features-star-2"><i class="fa fa-star"></i></div>';
						$job_badge_text .= '<li '.$style_li.'><a href="'.get_the_permalink($nokri['sb_search_page']).'?job_class='.$val.'" class="job-class-tags-anchor" '.$style_anch.'>'.esc_html(ucfirst($terms->name)).'</a></li>';
					}
					
					return	$job_badge_ul =  '<ul class="featured-badge-list">'. "".($job_badge_text).'</ul>';
			 }
	 }
}



/******************************************/		
/* Getting Selected Skills For Candidate */
/******************************************/
if ( ! function_exists( 'nokri_candidate_skills' ) ) {	
 function nokri_candidate_skills($taxonomy_name = '',$meta_key = '')
 {
 $cand_skills    = array();
 $user_info     = wp_get_current_user();
 $user_crnt_id  = $user_info->ID;
 $cand_skills	= get_user_meta($user_crnt_id, $meta_key, true);
 $taxonomies    = get_terms($taxonomy_name, array('hide_empty' => false , 'orderby'=> 'id', 'order' => 'ASC' ,  'parent'   => 0  )); $option = '';

		if( count((array)  $taxonomies ) > 0 )
		{ 
			foreach( $taxonomies as $taxonomy)
			{
				    $selected  = '';
					if(count((array)  $cand_skills ) > 0)
					{
						if (in_array( $taxonomy->term_id, $cand_skills ))
							{
								$selected = 'selected="selected"';
							}
					}
				
				$option .='<option value="'.esc_attr($taxonomy->term_id).'" '.$selected.'>'.esc_html($taxonomy->name).'</option>';
			}
		}

		return $option;
 }
}		



/******************************************/		
/* Getting Job Selected Skills For Job */
/******************************************/
if ( ! function_exists( 'nokri_job_selected_skills' ) )
 {	
	 function nokri_job_selected_skills($taxonomy_name = '',$meta_key = '',$job_skills  = '')
	 {
		 $taxonomies    =  get_terms($taxonomy_name, array('hide_empty' => false , 'orderby'=> 'id', 'order' => 'ASC' ,  'parent'   => 0  )); 
		 $option        =  '';
		 if(empty($job_skills))
		 {
		 	$job_skills    =  array();
		 }
		 
				if( count((array)  $taxonomies ) > 0 )
				{ 
					foreach( $taxonomies as $taxonomy)
					{
						if (in_array( $taxonomy->term_id, $job_skills ))
							{
								$selected = 'selected="selected"';
							}
						else
							{
								$selected  = '';
							}
						$option .='<option value="'.esc_attr($taxonomy->term_id).'" '.$selected.'>'.esc_html($taxonomy->name).'</option>';
					}
				}
				
				return $option;
	 }
}	

/*************************/
/* Candidates grid layouts*/
/*************************/ 
if( ! function_exists( 'nokri_candidates_get_grid_layouts' ) )
{
	function nokri_candidates_get_grid_layouts( $cand_id = '' , $layout = '')
	{
					$cand_data      = get_userdata($cand_id);
					/* Profile Pic  */
					$image_dp_link  =  nokri_get_user_profile_pic($cand_id,'_cand_dp');
					/* Getting Employer Skills  */
					$skill_tags     = nokri_get_candidates_skills($cand_id);
					$cand_headline  = get_user_meta($cand_id, '_user_headline', true);
					$emp_address    = get_user_meta($cand_id, '_cand_address', true);
					$adress_html = '';
					if($emp_address)
					{
						$adress_html = '<i class="fa fa-map-marker"></i><p>'.$emp_address.'</p>';
					}
					
					if($layout == 1)
					{
						return $layout = '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						   <div class="n-featured-single">
							  <div class="n-featured-candidates-single-top">
								 <div class="n-candidate-title">
									<h4><a href="'. esc_url(get_author_posts_url($cand_id)).'">'. esc_html( $cand_data->display_name ).'</a></h4>
									<p>'.esc_html($cand_headline).'</p>
								 </div>
								 <div class="n-canididate-avatar">
								   <a href="'. esc_url(get_author_posts_url($cand_id)).'"><img src="'. esc_url($image_dp_link).'" class="img-responsive" alt="'. esc_attr__( "logo", 'nokri' ).'"></a>
								 </div>
								 <div class="n-candidate-location">
								   '.($adress_html).'
								 </div>
								 <div class="n-candidate-skills">
								  '. "".$skill_tags.'
								 </div>
							  </div>
							  <div class="n-candidates-single-bottom">
								 <a href="'.esc_url(get_author_posts_url($cand_id)).'">'. esc_html__('View Profile','nokri').'</a>
							  </div>
						   </div>
						</div>';
					}
					else
					{
						return $layout = '<div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
							<div class="candidates-grid">
								<div class="candidate-meta-box">
									<a href="'. esc_url(get_author_posts_url($cand_id)).'"><img src="'. esc_url($image_dp_link).'" class="img-responsive" alt="'. esc_attr__( "logo", 'nokri' ).'"></a>
									<div class="candidate-meta">
										<div class="cand-name">
											<a href="'. esc_url(get_author_posts_url($cand_id)).'">'. esc_html( $cand_data->display_name ).'</a>
										</div>
										<p>'.esc_html($cand_headline).'</p>
									</div>
								</div>
								<div class="cand-skills">
									'. "".$skill_tags.'
								</div>
								<div class="cand-btns">
									<ul>
										<li><a href="'.esc_url(get_author_posts_url($cand_id)).'">'. esc_html__('View Profile','nokri').'</a></li>
										<li class="cand-fav saving_resume"><a href="javascript:void(0)" data-cand-id="'.esc_attr($cand_id).'"><i class="fa fa-heart-o"></i></a></li>
									</ul>
								</div>
							</div>
						</div>';
						
					}
	}
}


/******************************************/		
/*    Getting User Meta Of Candidate     */
/******************************************/		
if ( ! function_exists( 'nokri_candidate_user_meta' ) ) {	
 function nokri_candidate_user_meta($meta_key = '')
   {
	 $user_info = $user_crnt_id = $candidate_meta_value = '';
	 $user_info = wp_get_current_user();
	 $user_crnt_id = $user_info->ID;
	 if(get_user_meta($user_crnt_id, $meta_key, true) != '') 
		{
			$candidate_meta_value = get_user_meta($user_crnt_id, $meta_key, true);	
		}
	return $candidate_meta_value;
   }
}
		
		
		
		
/******************************************/		
/*    Getting User Meta Of Candidate     */
/******************************************/		
if ( ! function_exists( 'nokri_fields_validation' ) ) {	
 function nokri_fields_validation($message = '')
   {	
		$validation_msg = 'data-parsley-error-message='.$message.''; 
		return $validation_msg;
   }
}

/******************************************/		
/*    Getting Post Counts    */
/******************************************/

if ( ! function_exists( 'nokri_get_jobs_count' ) )
{  
 function nokri_get_jobs_count( $user_id,$status)
 {
  global $wpdb;
  $listing_count = $wpdb->get_var( "SELECT COUNT(*) AS total FROM  $wpdb->posts WHERE post_type = 'job_post' AND post_status = '$status' AND post_author = '$user_id'" );
  return number_format($listing_count);
 }
}

/******************************************/		
/*    Getting Resumes  Counts On Job    */
/******************************************/

if ( ! function_exists( 'nokri_get_resume_count' ) )
{  
 function nokri_get_resume_count( $job_id)
 {
	 
	global $wpdb;
	$query = $wpdb->get_results("SELECT meta_id FROM $wpdb->postmeta WHERE (meta_key LIKE '_job_applied_resume_%' AND post_id = '".$job_id."')");
	return count((array) $query);
 }
}


/******************************************/		
/*    Replace Ul in pagination   */
/******************************************/
if ( ! function_exists( 'nokri_strip_single_tag' ) )
{ 
	function nokri_strip_single_tag($str,$tag){
	
		$str=preg_replace('/<'.$tag.'[^>]*>/i', '', $str);
	
		$str=preg_replace('/<\/'.$tag.'>/i', '', $str);
	
		return $str;
	}
}

/******************************************/		
/*    WP Query  pagination   */
/******************************************/
if ( ! function_exists( 'nokri_job_pagination' ) )
{
	function nokri_job_pagination($max_num_pages ) {
	 $big = 999999999; // need an unlikely integer
	 $pages = paginate_links( array(
	   'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	   'format' => '?paged=%#%',
	   'current' => max( 1, get_query_var('paged') ),
	   'total' => $max_num_pages->max_num_pages,
	   'type'  => 'array',
	   'prev_next'   => true,
	   'prev_text'    => __('<< Prev', 'nokri'),
	   'next_text'    => __('Next >>', 'nokri'),
	  )
	 );
	
	 if( is_array( $pages ) ) {
	  $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
	
	  $pagination = '<ul class="pagination">';
	  foreach ( $pages as $page ) {
	   $pagination .= "<li>$page</li>";
	  }
	  $pagination .= '</ul>';
	  return $pagination;
	 }
	}
}

/******************************************/		
/*    WP User Query  pagination   */
/******************************************/


if ( ! function_exists( 'nokri_user_pagination' ) )
{
 function nokri_user_pagination($total_records,$current_page)
 {
  // Check if a records is set.
  if ( ! isset( $total_records ) )
   return;
  if ( ! isset( $current_page ) )
   return; 
  $args = array(
   'base'         => add_query_arg('page','%#%'),
   'format'       => '?page=%#%',
   'total'        => $total_records,
   'current'      => $current_page,
   'show_all'     => false,
   'end_size'     => 1,
   'mid_size'     => 2,
   'prev_next'    => true,
   'prev_text'    => '<i class="fa fa-chevron-left" aria-hidden="true"></i>',
   'next_text'    => '<i class="fa fa-chevron-right" aria-hidden="true"></i>',
   'type'         => 'list');

   return nokri_strip_single_tag(paginate_links( $args ), 'ul');
    }

}




/* ========================= */
/*   Salary Type Values Function   */
/* ========================= */

if ( ! function_exists( 'nokri_salary_type_values' ) )
 {
	function nokri_salary_type_values($getvalue = '' )
	 {
		$salary_array = array(
			"0" 		=> __( "Per Month", 'nokri' ),
			"1" 	    => __( "Per Year", 'nokri' ),
			"2" 		=> __( "Per Hour", 'nokri' ),
		);
		
		return ( $getvalue == "" ) ? $salary_array : $salary_array["$getvalue"];
	}
}

/* ========================= */
/*   Salary Type Function   */
/* ========================= */

if ( ! function_exists( 'nokri_job_post_salary_type' ) )
{
	function nokri_job_post_salary_type($select_val = '')
	{
		global $nokri;
		$salry_type   		=   '';
		$salry_type   		=  isset($nokri['job_post_salary_type'])? $nokri['job_post_salary_type']: array();
		$salary_values 		=   '';
		if( count((array)  $salry_type ) > 0 && $salry_type != '')
		{
			foreach($salry_type as $type )
			{  
				if ( $select_val == $type )
				{
					$selected = 'selected="selected"';
				}	
				else 
				{
					$selected = '';
				}
				$salary_values .= '<option value="'.$type.'" '.$selected.' >'.nokri_salary_type_values($type ).'</option>';
			}
		}
			return $salary_values ;
	}
}

/* ========================= */
/*  User Registration   */
/* ========================= */

if( !function_exists('nokri_authorization') )
{
 	function nokri_authorization()
 	{
  		if ( !is_user_logged_in() ):
  		get_template_part( 'template-parts/registration/registration','popup' );
		get_template_part( 'template-parts/registration/login','popup' );  
  		endif;
 	}
}

function nokri_redirect_to_request( $redirect_to, $request, $user ){
    //is there a user to check?
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        //check for admins
        if ( in_array( 'administrator', $user->roles ) ) {
            // redirect them to the default place
            return $redirect_to;
        } else {
            return home_url();
        }
    } else {
        return $redirect_to;
    }
}

	add_filter('login_redirect', 'nokri_redirect_to_request', 10, 3);


/* ========================= */
/*  User loged In   */
/* ========================= */


if (! function_exists ( 'nokri_check_if_not_logged' )) 
{
	function nokri_check_if_not_logged()
	{
		 if( get_current_user_id() == "" )
		 {
		  	echo nokri_redirect( home_url( '/' ) ); 
		 }
	}
}


/* ================================== */
/*  Check User Log In Before Action   */
/* =================================== */


if (! function_exists ( 'nokri_check_user_activity' )) 
{
	function nokri_check_user_activity()
	{
		 if( get_current_user_id() == "" )
			{
				echo "2";
				die();
			}
	}
}


/* ================================== */
/*  Check User Type Activity         */
/* =================================== */


if (! function_exists ( 'nokri_check_user_type' )) 
{
	function nokri_check_user_type()
	{
		$user_id               =   get_current_user_id();
		
		 if( get_user_meta($user_id, '_sb_reg_type', true) == '1' )
			{
				echo "3";
				die();
			}
	}
}


/* ========================= */
/*  Employer Menu Sorter  */
/* ========================= */
if ( ! function_exists( 'nokri_employer_menu_sorter' ) )
{
	function nokri_employer_menu_sorter($user_id = '')
	{
		global $nokri; 
		$sorter = '';
		if(isset($nokri['employer_menu_sorter']) && $nokri['employer_menu_sorter'] !="")
		{
			$menus = $nokri['employer_menu_sorter'];
		}
			if(count((array)  $menus ) > 0)
			{
				foreach($menus as $optn => $valu)
				{
					if($optn == 'Dashboard' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'Dashboard', 'nokri' );
						$sorter .= '<li><a href="'.get_the_permalink().'"><span class="la la-tachometer"></span>'.($valu).'</a></li>';
					}
					if($optn == 'udpdate profile' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'Udpdate Profile', 'nokri' );
						$sorter .= '<li><a href="'.get_the_permalink().'?tab-data=edit-profile"><span class="la la-edit"></span>'.($label).'</a></li>';
					}
					if($optn == 'View My Profile' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'View My Profile', 'nokri' );
						$sorter .= '<li><a href="'.esc_url(get_author_posts_url($user_id)).'"><span class="la la-user"></span>'.($label).'</a></li>';
					}
					if($optn == 'My Jobs' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'My Jobs', 'nokri' );
						$sorter .= '<li>
                            <div class="profile-menu-link">
                              <span class="la la-bars"></span>'.($label).'
                            </div>
                            <ul class="submenu">
                              <li><a href="'.get_the_permalink().'?tab-data=active-jobs">'. esc_html__( ' Active Jobs', 'nokri' ).'</a></li>
                              <li><a href="'.get_the_permalink().'?tab-data=inactive-jobs">'.esc_html__( ' In-Active Jobs', 'nokri' ).'</a></li>
                              <li><a href="'.get_the_permalink().'?tab-data=pending-jobs">'.esc_html__( 'Pending Jobs', 'nokri' ).'</a></li>
                            </ul>
                          </li>';
					}
					if($optn == 'Email Templates' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'Email Templates', 'nokri' );
						$sorter .= '<li>
                            <div class="profile-menu-link">
                              <span class="fa fa-envelope-o"></span>'.($label).'<i class="fa fa-angle-down"></i>
                            </div>
                            <ul class="submenu">
                              <li><a href="'. get_the_permalink().'?tab-data=email-templates">'. esc_html__( 'Add Email Template', 'nokri' ).'</a></li>
                              <li><a href="'.get_the_permalink().'?tab-data=email-templates-list">'. esc_html__( 'Email Templates', 'nokri' ).'</a></li>
                            </ul>
                          </li>';
					}
					if($optn == 'Matched Resumes' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'Matched Resumes', 'nokri' );
						$sorter .= '<li><a href="'.get_the_permalink().'?tab-data=matched-resumes"><span class="la la-external-link"></span>'.($label).'</a></li>';
					}
					if($optn == 'Saved Resumes' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'Saved Resumes', 'nokri' );
						$sorter .= '<li><a href="'.get_the_permalink().'?tab-data=saved-resumes"><span class="la la-file-pdf-o"></span>'.($label).'</a></li>';
					}
					if($optn == 'Followers' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'Followers', 'nokri' );
						$sorter .= '<li><a href="'.get_the_permalink().'?tab-data=our-followers"><span class="fa fa-users"></span>'.($label).'</a></li>';
					}
					if($optn == 'My Package' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'My Package', 'nokri' );
						$sorter .= '<li><a href="'.get_the_permalink().'?tab-data=my-packages"><span class="la la-list-alt"></span>'.($label).'</a></li>';
					}
					if($optn == 'My Orders' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'My Orders', 'nokri' );
						$sorter .= '<li><a href="'.get_the_permalink().'?tab-data=my-orders"><span class="la la-check-circle"></span>'.($label).'</a></li>';
					}
					if($optn == 'Logout' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'Logout', 'nokri' );
						$sorter .= '<li><a href="'.wp_logout_url( home_url() ).'"><span class="la la-sign-out"></span>'.($label).'</a></li>';
					}
				}
			}
				return $sorter;
	}
}

/* ========================= */
/*  Candidate Menu Sorter  */
/* ========================= */
if ( ! function_exists( 'nokri_candidate_menu_sorter' ) )
{
	function nokri_candidate_menu_sorter($user_id = '')
	{
		global $nokri; 
		$sorter = '';
		if(isset($nokri['candidate_menu_sorter']) && $nokri['candidate_menu_sorter'] !="")
		{
			$menus = $nokri['candidate_menu_sorter'];
		}
		/* Is applying job package base*/
$is_apply_pkg_base = ( isset($nokri['job_apply_package_base']) && $nokri['job_apply_package_base'] != ""  ) ? $nokri['job_apply_package_base'] : false; 
/* Is job alerts*/
$job_alerts = ( isset($nokri['job_alerts_switch']) && $nokri['job_alerts_switch'] != ""  ) ? $nokri['job_alerts_switch'] : false; 
			if(count((array)  $menus ) > 0)
			{
				foreach($menus as $optn => $valu)
				{
					if($optn == 'Dashboard' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'Dashboard', 'nokri' );
						$sorter .= '<li><a href="'.get_the_permalink().'"><span class="la la-tachometer"></span>'.($label).'</a></li>';
					}
					if($optn == 'udpdate profile' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'Update Profile', 'nokri' );
						$sorter .= '<li><a href="'.get_the_permalink().'?candidate-page=edit-profile"><span class="la la-edit"></span>'.($label).'</a></li>';
					}
					if($optn == 'View My Profile' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'View My Profile', 'nokri' );
						$sorter .= '<li><a href="'.esc_url(get_author_posts_url($user_id)).'"><span class="la la-user"></span>'.($label).'</a></li>';
					}
					if($optn == 'My Resumes' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'My Resumes', 'nokri' );
						$sorter .=   '<li><a href="'.get_the_permalink().'?candidate-page=resumes-list"><span class="la la-file-pdf-o"></span>'.($label).'</a></li>';
					}
					if($optn == 'Jobs Applied' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'Jobs Applied', 'nokri' );
						$sorter .= '<li><a href="'.get_the_permalink().'?candidate-page=jobs-applied"><span class="la la-bars"></span>'.($label).'</a></li>';
					}
					if($optn == 'Saved Jobs' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'Saved Jobs', 'nokri' );
						$sorter .= '<li><a href="'.get_the_permalink().'?candidate-page=saved-jobs"><span class="la la-bookmark"></span>'.($label).'</a></li>';
					}
					if($optn == 'Followed Companies' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'Followed Companies', 'nokri' );
						$sorter .= '<li><a href="'.get_the_permalink().'?candidate-page=followed-companies"><span class="la la-external-link"></span>'.($label).'</a></li>';
					}
					if($optn == 'Job Alerts' && $valu != "" && $job_alerts )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'Job Alerts', 'nokri' );
						$sorter .= '<li><a href="'.get_the_permalink().'?candidate-page=job-alerts"><span class="la la-bell"></span>'.($label).'</a></li>';
					}
					if($optn == 'My Package' && $valu != ""  && $is_apply_pkg_base)
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'My Package', 'nokri' );
						$sorter .= '<li><a href="'.get_the_permalink().'?candidate-page=my-packages"><span class="la la-list-alt"></span>'.($label).'</a></li>';
					}
					if($optn == 'My Orders' && $valu != "" && $is_apply_pkg_base)
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'My Orders', 'nokri' );
						$sorter .= '<li><a href="'.get_the_permalink().'?candidate-page=my-orders"><span class="la la-check-circle"></span>'.($label).'</a></li>';
					}
					if($optn == 'Logout' && $valu != "" )
					{
						$label    =  isset($valu) ? $valu:  esc_html__( 'Logout', 'nokri' );
						$sorter .= '<li><a href="'.wp_logout_url( home_url() ).'"><span class="la la-sign-out"></span>'.($label).'</a></li>';
					}
				}
			}
				return $sorter;
	}
}



/* ================================== */
/*  Return Job Country                */
/* =================================== */

if ( ! function_exists( 'nokri_job_country' ) ) 
{	 
	function nokri_job_country( $pid )
		{
			global $nokri;
			$ad_country 		= 	'';
			$ad_country 		= 	wp_get_object_terms( $pid,  array('ad_location'), array('orderby' => 'term_group') );
			$all_locations 		= 	array();
			foreach($ad_country as $ad_count)
			{
				$country_ads = get_term( $ad_count);
				$item = array(
					'term_id' => $country_ads->term_id, 
					'location' => $country_ads->name
					);
				$all_locations[] = $item;
			}
			$location_html	=	'';
			if(count( $all_locations ) > 0 )
			{
				$limit = count( $all_locations ) - 1;
				for( $i = $limit; $i>=0; $i-- )
				{
					$location_html	.= '<a href="'.get_the_permalink($nokri['sb_search_page']).'?job_location='.$all_locations[$i]['term_id'].'">'.esc_html( $all_locations[$i]['location'] ).'</a>, ';
				}
				
			}
			 return rtrim($location_html, ', ');
		}
}


/* ================================== */
/*  Return Job Categories         */
/* =================================== */

if ( ! function_exists( 'nokri_job_categories_with_chlid' ) ) 
{	 
	function nokri_job_categories_with_chlid( $pid )
	{
			global $nokri;
			$post_categories = wp_get_object_terms( $pid,  array('job_category'), array('orderby' => 'term_group') );
			$cats_html	    =	'';
			foreach($post_categories as $c)
			{
				$cat = get_term( $c );
				$cats_html	.= '<a href="'.get_the_permalink($nokri['sb_search_page']).'?cat_id='.esc_attr($cat->term_id) .'">'.esc_html($cat->name ).'</a>, ';
			}
			
			 return rtrim($cats_html, ', ');
	}
}


// Get parents of custom taxonomy
if ( ! function_exists( 'nokri_get_taxonomy_parents' ) ) {	 
function nokri_get_taxonomy_parents($id, $taxonomy, $link = true, $separator = ' &raquo; ', $nicename = false, $visited = array()) 
{

$chain = '';

$parent = get_term($id, $taxonomy);

if (is_wp_error($parent))
{ 
	echo "fail";
    return $parent;
}

if ($nicename)
{
    $name = $parent->slug;
}
else
{
    $name = $parent->name;
}

if ($parent -> parent && ($parent -> parent != $parent -> term_id) && !in_array($parent -> parent, $visited)) {

    $visited[] = $parent -> parent;

    $chain .= nokri_get_taxonomy_parents($parent->parent, $taxonomy, $link, $separator, $nicename, $visited);

}
  
    return $chain;

}
}



if ( ! function_exists( 'nokri_jspopup' ) ) 
{	 
	function nokri_jspopup($msg = '', $type = '')
	{
		echo("<script>jQuery(document).ready(function($) { toastr.".$type."('".$msg."', '', {
									timeOut: 2500,
									'closeButton': true,
									'positionClass': 'toast-top-right'
								}); });</script>");
	}
}
if ( ! function_exists( 'nokri_js_redirect' ) ) 
{	 
	function nokri_js_redirect($url)
	{
		echo("<script>jQuery(document).ready(function($) { window.location = '".$url."' });</script>");
	}
}


//Allow Pending jobs to be viewed by listing/product owner
if ( ! function_exists( 'posts_for_current_author' ) ) {
function posts_for_current_author($query)
{
 if( isset( $_GET['post_type'] ) && $_GET['post_type'] == "job_post"  && isset($_GET['p']) )
 {
  $post_id = $_GET['p'];
  $post_author = get_post_field( 'post_author', $post_id );
  if ( is_user_logged_in() && get_current_user_id() == $post_author )
  {
   $query->set('post_status', array('publish','pending'));
   return $query;

  }
  else
  {
   return $query; 
  }
 }
 else
 {
  return $query; 
 }
}
}
add_filter('pre_get_posts', 'posts_for_current_author');

/* ======================================== */
/*  Return Candidate Information From Linkedin */
/* ======================================== */

if ( ! function_exists( 'nokri_linkedin_access' ) ) 
{	 
	function nokri_linkedin_access($code)
	{
		$server_output2	=	nokri_linked_handling( $code );
	    $user_data = json_decode($server_output2);
	
	     return $user_data;
	}
}
/* =============== */
/* Section bg */
/* ===============*/
if ( ! function_exists( 'nokri_section_bg_url' ) ) 
{	 
	function nokri_section_bg_url()
	{
		global $nokri;
		$section_bg_img_url = '';
		if ( isset( $nokri['section_bg_img'] ) )
		{
			$section_bg_img_url = nokri_getBGStyle('section_bg_img');
		}
		
		return $section_bg_img_url;
	}
}

/* =============== */
/* Is Page function */
/* ===============*/
if ( ! function_exists( 'nokri_is_page_check' ) ) 
{	 
	function nokri_is_page_check()
	{
		global $nokri;
		$page_id  = get_the_id();
		$is_valid = false;
		if($nokri['sb_sign_up_page'] == $page_id )
		{
			$is_valid = true;
		} 
		else if($nokri['sb_sign_in_page'] == $page_id )
		{
			$is_valid = true;
		}
		else if($nokri['contact_us'] == $page_id )
		{
			$is_valid = true;
		} 
		else if($nokri['about_us'] == $page_id )
		{
			$is_valid = false;
		} 
		else if(is_author())
		{
			$is_valid = true;
		}
		else if(is_singular('job_post') && ($nokri['main_header_style']) == '1')
		{
			$is_valid = true;
		} 
		else if(is_404())
		{
			$is_valid = true;
		}
		
		
		
		return $is_valid;
	}
}


/* =============== */
/* Maptype function */
/* ===============*/
if ( ! function_exists( 'nokri_mapType' ) )
{
 function nokri_mapType()
 {
	 global $nokri;
	 $mapType = 'google_map';
	 if( isset( $nokri['map-setings-map-type'] ) && $nokri['map-setings-map-type'] != ''  )
	 {
		$mapType = $nokri['map-setings-map-type'];
	 }
	 return $mapType;
 }
}

// get post description as per need. 
if ( ! function_exists( 'nokri_words_count' ) ) {	
	function nokri_words_count($contect = '', $limit = 180)
	{
		 $string	=	'';
		 $contents = strip_tags( strip_shortcodes( $contect ) );
		 $contents	=	nokri_removeURL( $contents );
		 $removeSpaces = str_replace(" ", "", $contents);
		 $contents	=	preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $contents);
		 if(strlen($removeSpaces) > $limit)
		 {
			 return mb_substr(str_replace("&nbsp;","",$contents), 0, $limit).'...';
		 }
		 else
		 {
			 return str_replace("&nbsp;","",$contents);
		 }
	}
}
/* =============== */
/* Demo enable function */
/* ===============*/
if ( ! function_exists( 'nokri_demo_mode' ) )
{
 function nokri_demo_mode()
 {
	 global $nokri;
	 $is_demo_mode = false;
	 if( isset( $nokri['is_demo_mode'] ) && $nokri['is_demo_mode'] == '1'  )
	 {
		$is_demo_mode = $nokri['is_demo_mode'];
		$is_demo_mode = true;
	 }
	 
	 return $is_demo_mode;
 }
}

/* Getting Selected Taxonomies Name */
if ( ! function_exists( 'nokri_cand_skills_values' ) ) {	
 function nokri_cand_skills_values( $skill_value = '')
 {
	
		
		 for ($i = 5; $i <= 100;)
		 {
			$array_values[] =  $i;
			$i = $i+5;
		 }
		 
		 
		 $option        =  '';
		 if(empty($skill_value))
		 {
		 	$skill_value    =  array();
		 }
		 
				if( count((array)  $array_values ) > 0 )
				{ 
					foreach( $array_values as $array_value)
					{
						if (in_array( $array_value, $skill_value ))
							{
								$selected = 'selected="selected"';
							}
							
						else
							{
								$selected  = '';
							}
						$option .='<option value="'.esc_attr($array_value).'" '.$selected.'>'.esc_html($array_value).'</option>';
					}
				}
				
				return $option;	
			
 }
}

if ( ! function_exists( 'nokri_cand_skills_values' ) ) 
{
	function nokri_user_id_exists($user)
	{
		global $wpdb;
		$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users WHERE ID = %d", $user));
		if($count == 1){ return true; }else{ return false; }
	}
}


if ( ! function_exists( 'nokri_cand_del_resume_keys' ) ) 
{
	function nokri_cand_del_resume_keys($candidate_id= '')
	{
		if(!user_id_exists($candidate_id))
	   { 
			delete_post_meta( $job_id, '_job_applied_date_'.$candidate_id);
			delete_post_meta( $job_id, '_job_applied_resume_'.$candidate_id);
			delete_post_meta( $job_id, '_job_applied_status_'.$candidate_id);
	   } 
	}
}

/* Employer Allowed Candidate search*/
if( ! function_exists( 'nokri_is_cand_search_allowed' ) )
{
	function nokri_is_cand_search_allowed()
		{
			global $nokri;
			$current_user_id 	  =  get_current_user_id();
			$expiry_date          =  get_user_meta($current_user_id, '_sb_expire_ads', true);
			$remaining_searches   =  get_user_meta($current_user_id, '_sb_cand_search_value', true);
			$today			      =  date("Y-m-d");
			$expiry_date_string   =  strtotime($expiry_date);
			$today_string 		  =  strtotime($today);
			$can_search           =  true;
			if(isset($nokri['cand_search_mode']) && $nokri['cand_search_mode'] == '2' )
			{
				if($remaining_searches != '-1' && !current_user_can('administrator'))
				{
					if($today_string > $expiry_date_string)
					{
						update_user_meta($current_user_id, '_sb_cand_viewed_resumes','');
						update_user_meta($current_user_id, '_sb_cand_search_value','0');
					}
					if($remaining_searches <= 0 || $today_string > $expiry_date_string)
					{
						$can_search = false;
					}
				}
			}
			return $can_search;
		}
}
/************************/
/* Category count function*/
/************************/
if( ! function_exists( 'nokri_get_opening_count' ) )
{
	function nokri_get_opening_count($term_id = '')
	{
		$custom_count = '';
		$query = new WP_Query( 
		array('post_type' => 'job_post','meta_query' => array(
		array('key'     => '_job_status','value'   => 'active','compare' => '=',),),
		'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'job_category',
			'field'    => 'term_id',
			'terms'    => array( $term_id ),
			'operator' => 'IN',
		),
	),																		
	));
		 
		$custom_count =  $query->found_posts;
		wp_reset_postdata();
	 	return $custom_count;
	}
}

if( ! function_exists( 'nokri_assign_free_package' ) )
{
	function nokri_assign_free_package()
	{
		global $nokri;
		$package_id = '';
		if( isset( $nokri['register_package'] ) && $nokri['register_package'] != '' )
		   {
			   $package_id = $nokri['register_package'];
		   }
		   return $package_id;
	}
}

/******************************/
/* Job post feilds operations*/
/*****************************/

if( ! function_exists( 'nokri_job_post_feilds_operations' ) )
{
	function nokri_feilds_operat($key = '', $type = 'show')
	{
		global $nokri;
		
		
		if($type == 'show')
		{
			if((!empty($nokri["$key"]) && $nokri["$key"] == 'show') || !empty($nokri["$key"]) && $nokri["$key"] == 'required') 
			{
				return true;
			}
			else
			{
				return false;	
			}
			
		}
		
		if($type == 'required')
		{			
			return (!empty($nokri["$key"]) && $nokri["$key"]== 'required') ?  ' data-parsley-required="true"' : '';	
		}
		
	}
}

/******************************/
/* Getting candidates skills*/
/*****************************/
if( ! function_exists( 'nokri_get_candidates_skills' ) )
{
	function nokri_get_candidates_skills($user_id = '')
	{
		global $nokri;
		$emp_skills     = get_user_meta($user_id, '_cand_skills', true);
		$skill_tags     = $total_skills  = '';
		if((array)$emp_skills  && $emp_skills > 0) 
		{
			$taxonomies = get_terms('job_skills', array('hide_empty' => false , 'orderby'=> 'id', 'order' => 'ASC' ,  'parent'   => 0  ));
			if(count((array) $taxonomies) > 0)
			 {
				$count = 0;
				foreach($taxonomies as $taxonomy)
					{
						 if (in_array( $taxonomy->term_id, $emp_skills ))
						 {
							 $skill_tags .=   '<a href="'.esc_url(get_the_permalink( $nokri['candidates_search_page'] )).'?cand_skills='.esc_attr($taxonomy->term_id).'">'.esc_html($taxonomy->name).'</a>';
							 if($count == 2)
							 {
								 break;
							 }
							 $count++;
						 }
					}
				}
			}
			return $skill_tags;
	}
}
/******************************/
/* Getting Last country value*/
/*****************************/
if( ! function_exists( 'nokri_get_candidates_location' ) )
{
	function nokri_get_candidates_location($user_id = '')
	{
			$job_locations  = array();
			$last_location  =  '';
			$job_locations  =  wp_get_object_terms( $pid,  array('ad_location'), array('orderby' => 'term_group') );
			if ( ! empty( $job_locations ) ) { 
				foreach($job_locations as $location)
				{
				   $last_location = '<a href="'.get_the_permalink($nokri['sb_search_page']).'?job_location='.$location->term_id.'">'.$location->name.'</a>';
				}
			}
	}
}
/******************************/
/* Job post feilds label*/
/*****************************/
if( ! function_exists( 'nokri_feilds_label' ) )
{
	function nokri_feilds_label($key = '',$default = '')
	{
		global $nokri;
		return (!empty($nokri["$key"]) && $nokri["$key"] != '') ?  $nokri["$key"] : $default;
	}
}
/* ================================== */
/*  Getting saved resumes id's   */
/* =================================== */
if (! function_exists ( 'nokri_emp_saved_resumes_ids' )) 
{
	function nokri_emp_saved_resumes_ids()
	{
		 $resumesArray     =    array();
		 $emp_id           =    get_current_user_id();
		 $resumes	       =	get_user_meta( $emp_id,'_emp_saved_resume_'.$emp_id, true );
		 $resumesArray     =    explode(',', $resumes);
		 return $resumesArray;
	}
}

/* ================================== */
/*  Getting All candidaites skills  */
/* =================================== */
if (! function_exists ( 'nokri_jobs_matches_candidates' )) 
{
	function nokri_jobs_matches_candidates($job_id = '')
	{
		/* Candidate Job Notifications */
		$job_skills     =   wp_get_post_terms($job_id, 'job_skills', array("fields" => "ids"));
		//$skillsz = array();
		$cands_meta_qry2['relation']  = 'OR';
		foreach($job_skills as $skill)
		{ 
			$cands_meta_qry2[] 	=   array( 'key'  => '_cand_skills', 'value' =>  $skill, 'compare' => 'LIKE' ); 
		}
		$cands_meta_qry 	=   array();
		$cands_meta_qry[]   =   array('key'   => '_sb_reg_type','value'      =>  '0','compare' => '=');
		$args          		=   array( 'role' => 'subscriber', 'meta_query' =>   array($cands_meta_qry, $cands_meta_qry2) );
		// Create the WP_User_Query object
		$wp_user_query =   new WP_User_Query($args);
		$users         =   $wp_user_query->get_results();
		$total_users   =   $wp_user_query->get_total();
		$notification  =   array();
		if (!empty($users))
		{
			 // Loop through results
			 foreach ($users as $user) 
			 {
				$cand_id            =   $user->ID; 
				$cand_name          =   $user->display_name;
				$cand_skills_value	= 	get_user_meta($cand_id, '_cand_skills_values', true);
				$cand_skills_sum	=   array_sum($cand_skills_value);
				update_user_meta($cand_id, '_cand_skills_sum', sanitize_text_field($cand_skills_sum));
				$notification[]     =   $cand_id; 
			}
		}
		return $notification;
	}
}
/* ================================== */
/*  Getting User profile picture  */
/* =================================== */
if (! function_exists ( 'nokri_get_user_profile_pic' )) 
{
	function nokri_get_user_profile_pic($user_id = '',$user_key = '')
	{
		global $nokri;
		$image_dp_link[0] =  get_template_directory_uri(). '/images/candidate-dp.jpg';
		if( isset( $nokri['nokri_user_dp']['url'] ) && $nokri['nokri_user_dp']['url'] != "" )
		{
			$image_dp_link = array($nokri['nokri_user_dp']['url']);	
		}
		if(get_user_meta($user_id, $user_key, true ) != '')
		{
			$attach_dp_id     =  get_user_meta($user_id, $user_key, true );
			$image_dp_link    =  wp_get_attachment_image_src( $attach_dp_id, '' );
		}
		 return $image_dp_link[0];
	}
}