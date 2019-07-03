<?php
global $nokri;
$user_info = wp_get_current_user();
$user_crnt_id = $user_info->ID;
$mapType = nokri_mapType();
if($mapType == 'google_map')
{
	wp_enqueue_script( 'google-map-callback',false);
}
$ad_mapLocation  ='';
$ad_mapLocation  =  get_user_meta($user_crnt_id, '_emp_map_location', true);
$headline        =  get_user_meta($user_crnt_id, '_user_headline', true);
$ad_map_lat    	 =  get_user_meta($user_crnt_id, '_emp_map_lat', true);
$ad_map_long	 =  get_user_meta($user_crnt_id, '_emp_map_long', true);
$emp_profile	 = get_user_meta($user_crnt_id, '_user_profile_status', true);
if(get_user_meta($user_crnt_id, '_emp_map_lat', true) == '')
{
	$ad_map_lat = $nokri['sb_default_lat'];
}
if(get_user_meta($user_crnt_id, '_emp_map_long', true) == '')
{
	$ad_map_long = $nokri['sb_default_long'];
}

nokri_load_search_countries(1);
/* Getting All Jobs */
$terms = get_terms(array( 'taxonomy' => 'job_category', 'hide_empty' => false, 'parent' => 0, ));

/* Getting Company Search Selected radio Btn */
$comp_search = get_user_meta($user_crnt_id, '_emp_search', true);
/*Setting profile option*/
$profile_setting_option = isset($nokri['user_profile_setting_option']) ? $nokri['user_profile_setting_option']  : false;
/*Is map show*/
$is_lat_long = isset($nokri['allow_lat_lon']) ? $nokri['allow_lat_lon']  : false;
/*Is account del option*/
$is_acount_del = isset($nokri['user_profile_delete_option']) ? $nokri['user_profile_delete_option']  : false;
/* For job Location level text */
$job_country_level_heading = ( isset($nokri['job_country_level_heading']) && $nokri['job_country_level_heading'] != ""  ) ? $nokri['job_country_level_heading'] : '';

$job_country_level_1 = ( isset($nokri['job_country_level_1']) && $nokri['job_country_level_1'] != ""  ) ? $nokri['job_country_level_1'] : '';

$job_country_level_2 = ( isset($nokri['job_country_level_2']) && $nokri['job_country_level_2'] != ""  ) ? $nokri['job_country_level_2'] : '';

$job_country_level_3 = ( isset($nokri['job_country_level_3']) && $nokri['job_country_level_3'] != ""  ) ? $nokri['job_country_level_3'] : '';

$job_country_level_4 = ( isset($nokri['job_country_level_4']) && $nokri['job_country_level_4'] != ""  ) ? $nokri['job_country_level_4'] : '';
/* Make location selected on update ad */
$cand_custom_loc    = get_user_meta($user_crnt_id, '_emp_custom_location', true);
$levelz	            =	count((array) $cand_custom_loc);
$ad_countries	=	nokri_get_cats('ad_location' , 0 );
$country_html	=	'';
foreach( $ad_countries as $ad_country )
{
	$selected	=	'';
	if(isset($cand_custom_loc[0]))
	{
		if( $levelz > 0 && $ad_country->term_id == $cand_custom_loc[0])
		{
			$selected	=	'selected="selected"';
		}
	}
	$country_html	.=	'<option value="'.$ad_country->term_id.'" '.$selected.'>' . $ad_country->name .  '</option>';
}
$country_states = '';
if( $levelz >= 2 )
{

	$ad_states	=	nokri_get_cats('ad_location' , $cand_custom_loc[0] );
	$country_states	=	'';
	foreach( $ad_states as $ad_state )
	{
		$selected	=	'';
		if( $levelz > 0 && $ad_state->term_id == $cand_custom_loc[1] )
		{
			$selected	=	'selected="selected"';
		}
		$country_states	.=	'<option value="'.$ad_state->term_id.'" '.$selected.'>' . $ad_state->name .  '</option>';
		
	}
	
}
$country_cities	= '';
if( $levelz >= 3 )
{
	$ad_country_cities	=	nokri_get_cats('ad_location' , $cand_custom_loc[1] );
	$country_cities	=	'';
	foreach( $ad_country_cities as $ad_city )
	{
		$selected	=	'';
		if( $levelz > 0 && $ad_city->term_id ==  $cand_custom_loc[2] )
		{
			$selected	=	'selected="selected"';
		}
		$country_cities	.=	'<option value="'.$ad_city->term_id.'" '.$selected.'>' . $ad_city->name .  '</option>';
		
	}
	
}
$country_towns = '';
if( $levelz >= 4 )
{
	$ad_country_town	=	nokri_get_cats('ad_location' , $cand_custom_loc[2] );
	
	$country_towns	=	'';
	foreach( $ad_country_town as $ad_town )
	{
		$selected	=	'';
		if( $levelz > 0 && $ad_town->term_id == $cand_custom_loc[3] )
		{
			$selected	=	'selected="selected"';
		}
		$country_towns	.=	'<option value="'.$ad_town->term_id.'" '.$selected.'>' . $ad_town->name .  '</option>';
		
	}
	
}
?>

<form id="sb-emp-profile" method="post">
  <div class="main-body">
    <div class="dashboard-edit-profile">
      <h4 class="dashboard-heading"><?php echo esc_html__('Basic Information','nokri'); ?></h4>
      <div class="cp-loader"></div>
      
      <!-- Basic Informations -->
      <div class="dashboard-social-links">
        <div class="col-md-6 col-xs-12 col-sm-6">
          <div class="form-group">
            <label class=""><?php echo esc_html__('Company Name*','nokri'); ?></label>
            <input type="text" value="<?php echo esc_attr($user_info->display_name); ?>" data-parsley-required="true" data-parsley-error-message="<?php echo esc_html__( 'This field is required.', 'nokri' ); ?>" name="emp_name" class="form-control">
          </div>
        </div>
        <div class="col-md-6 col-xs-12 col-sm-6">
          <div class="form-group">
            <label class=""><?php echo esc_html__('Headline:','nokri'); ?></label>
            <input type="text" value="<?php echo get_user_meta($user_crnt_id, '_user_headline', true); ?>" name="emp_headline" class="form-control">
          </div>
        </div>
        <div class="col-md-6 col-xs-12 col-sm-6">
          <div class="form-group">
            <label class=""><?php echo esc_html__('Email*','nokri'); ?></label>
            <input type="text" disabled placeholder="<?php echo  $user_info->user_email; ?>"  name="emp_email" class="form-control">
          </div>
        </div>
        <div class="col-md-6 col-xs-12 col-sm-6">
          <div class="form-group">
            <label class=""><?php echo esc_html__('Phone','nokri'); ?> </label>
            <input type="text" name="sb_reg_contact" data-parsley-error-message="<?php echo esc_html__('Should be in digits with out space','nokri'); ?>" data-parsley-error-message="<?php echo esc_html__('Should be in digits','nokri'); ?>" data-parsley-type="digits"  required placeholder="<?php echo esc_html__('Company Phone','nokri'); ?>" value="<?php echo get_user_meta($user_crnt_id, '_sb_contact', true); ?>" class="form-control">
            
            
          </div>
        </div>
        <div class="col-md-6 col-xs-12 col-sm-6">
          <div class="form-group">
            <label class=""><?php echo esc_html__('Website','nokri'); ?></label>
            <input type="text" data-parsley-error-message="<?php echo esc_html__('Should be in url','nokri'); ?>" data-parsley-type="url"   placeholder="<?php echo esc_html__('Company Web Url','nokri'); ?>" value="<?php echo get_user_meta($user_crnt_id, '_emp_web', true); ?>" name="emp_web" class="form-control">
          </div>
        </div>
        <div class="col-md-6 col-xs-12 col-sm-6">
          <div class="form-group">
            <label class=""><?php echo esc_html__('Profile Image:','nokri'); ?></label>
            <input id="input-b1" name="my_file_upload[]" type="file" class="file form-control sb_files-data" data-show-preview="false" data-allowed-file-extensions='["jpg", "png", "jpeg"]' data-show-upload="false">
            
          </div>
        </div>
        <?php if($profile_setting_option) { ?>
        <div class="col-md-12 col-xs-12 col-sm-12">
                <div class="form-group">
                    <label class=""><?php echo esc_html__( 'Set your profile', 'nokri' ); ?></label>
                    <select  class="select-generat form-control" name="emp_profile">
                        <option value="pub" <?php if ( $emp_profile == 'pub') { echo "selected"; } ; ?>><?php echo esc_html__( 'Public', 'nokri' ); ?></option>
                        <option value="priv" <?php if ( $emp_profile == 'priv') { echo "selected"; } ; ?>><?php echo esc_html__( 'Private', 'nokri' ); ?></option>
                    </select>
                </div>
            </div>
            <?php } ?>
        <div class="col-md-12 col-xs-12 col-sm-12">
          <div class="form-group">
            <label class=""><?php echo esc_html__('About yourself','nokri'); ?></label>
            <textarea  name="emp_intro" class="form-control rich_textarea" id=""  cols="30" rows="10"><?php echo  nokri_candidate_user_meta('_emp_intro'); ?></textarea>
          </div>
        </div>
      </div>
      <!-- End Basic Informations --> 
      
      <!-- Company Specialization -->
      <div class="row">
        <div class="col-md-12 col-xs-12 col-sm-12">
          <h4 class="dashboard-heading"><?php echo esc_html__('Company Details','nokri'); ?></h4>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12">
          <div class="form-group">
            <label class=""><?php echo esc_html__('Company Specialization','nokri'); ?></label>
            <select class="select-generat form-control" name="emp_cat[]" id="change_term" multiple="multiple">
              <?php echo nokri_candidate_skills('job_skills','_emp_skills'); ?>
            </select>
          </div>
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12">
          <div class="form-group">
            <label><?php echo esc_html__('No. of Employees','nokri'); ?></label>
            <input type="text" placeholder="<?php echo esc_html__('Enter number of employes','nokri'); ?>" value="<?php echo get_user_meta($user_crnt_id, '_emp_nos', true); ?>"  name="emp_nos" class="form-control">
          </div>
        </div>
        <div class="col-md-6 col-xs-12 col-sm-6">
          <div class="form-group">
            <label class=""><?php echo esc_html__('Company Established In','nokri'); ?></label>
            <input type="text" value="<?php echo get_user_meta($user_crnt_id, '_emp_est', true); ?>" name="emp_est" class="datepicker-here-canidate form-control"  />
          </div>
        </div>
        <!--End Company Specialization --> 
        <!-- Company Soical Links -->
        <div class="col-md-12 col-xs-12 col-sm-12">
          <div class="dashboard-social-links">
            <div class="col-md-12 col-xs-12 col-sm-12">
              <h4 class="dashboard-heading"><?php echo esc_html__('Company Social Links','nokri'); ?></h4>
            </div>
            <div class="col-md-6 col-sm-12">
              <div class="form-group">
                <label><?php echo esc_html__('Facebook','nokri'); ?></label>
                <input type="text" placeholder="<?php echo esc_html__('Profile URL','nokri'); ?>" value="<?php echo get_user_meta($user_crnt_id, '_emp_fb', true); ?>" name="emp_fb" class="form-control">
              </div>
            </div>
            <div class="col-md-6 col-sm-12">
              <div class="form-group">
                <label><?php echo esc_html__('Twitter','nokri'); ?></label>
                <input type="text" placeholder="<?php echo esc_html__('Profile URL','nokri'); ?>" value="<?php echo get_user_meta($user_crnt_id, '_emp_twitter', true); ?>" name="emp_twitter" class="form-control">
              </div>
            </div>
            <div class="col-md-6 col-sm-12">
              <div class="form-group">
                <label><?php echo esc_html__('LinkedIn','nokri'); ?></label>
                <input type="text" placeholder="<?php echo esc_html__('Profile URL','nokri'); ?>" value="<?php echo get_user_meta($user_crnt_id, '_emp_linked', true); ?>" name="emp_linked" class="form-control">
              </div>
            </div>
            <div class="col-md-6 col-sm-12">
              <div class="form-group">
                <label><?php echo esc_html__('Instagram','nokri'); ?></label>
                <input type="text" placeholder="<?php echo esc_html__('Profile URL','nokri'); ?>" value="<?php echo get_user_meta($user_crnt_id, '_emp_google', true); ?>" name="emp_google" class="form-control">
              </div>
            </div>
          </div>
        </div>
        <!--End Company Soical Links --> 
        
        <!-- Company Locations-->
        <input type="hidden" id="is_profile_edit" value="1" />
        <?php if($is_lat_long) { ?>
        <div class="col-md-12 col-xs-12 col-sm-12">
          <div class="row">
            <div class="dashboard-location">
             <div class="col-md-12 col-xs-12 col-sm-12">
             <h4 class="dashboard-heading"><?php echo $job_country_level_heading; ?></h4>
             </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                  <label class="control-label"><?php echo esc_html__( 'Set your location', 'nokri' ); ?></label>
                  <input class="form-control" name="sb_user_address" id="sb_user_address" value="<?php echo esc_attr($ad_mapLocation); ?>">
                  <?php if($mapType == 'google_map') { ?>
                      <a href="javascript:void(0);" id="your_current_location" title="<?php echo esc_html__('You Current Location', 'nokri' ); ?>"><i class="fa fa-crosshairs"></i></a>
                      <?php } ?>
                </div>
              </div>
              <div class="col-md-12 col-sm-12">
                <div class="form-group">
                  <div id="dvMap" style="width:100%; height: 300px"></div>
                </div>
              </div>
              <div class="col-md-6 col-sm-12">
                <div class="form-group">
                  <label class="control-label"><?php echo esc_html__( 'Latitude', 'nokri' ); ?></label>
                  <input class="form-control" type="text" name="ad_map_lat" id="ad_map_lat" value="<?php echo esc_attr($ad_map_lat); ?>">
                </div>
              </div>
              <div class="col-md-6 col-sm-12">
                <div class="form-group">
                  <label class="control-label"><?php echo esc_html__( 'Longitude', 'nokri' ); ?></label>
                  <input class="form-control" name="ad_map_long" id="ad_map_long" value="<?php echo esc_attr($ad_map_long); ?>" type="text">
                </div>
              </div>
            </div>
          </div>
        </div>
         <?php } ?>
         
         <div class="col-md-12 col-xs-12 col-sm-12">
          <div class="row">
            <div class="dashboard-location">
             <!--job country -->
             <div class="col-md-6 col-sm-6 col-xs-12">
           <div class="form-group">
            <label><?php echo esc_html($job_country_level_1 ); ?></label>
              <select class="js-example-basic-single" data-allow-clear="true" data-placeholder="<?php echo esc_html__( 'Select Your Country', 'nokri' ); ?>" id="ad_country" name="cand_country">
                 
                  <?php echo "".($country_html);?>
              </select>
             
            </div>
           </div>
             <!--job state -->
             <div class="col-md-6 col-sm-6 col-xs-12" id="ad_country_sub_div" <?php if($country_states == "" ){echo 'style="display: none;"';}?>>
           <div class="form-group" >
           <label><?php echo esc_html($job_country_level_2 ); ?></label>
              <select class="js-example-basic-single" data-allow-clear="true" data-placeholder="<?php echo esc_html__( 'Select State', 'nokri' ); ?>" id="ad_country_states" name="cand_country_states">
                 
                  <?php echo "".($country_states);?>
              </select>
           </div></div>
             <!--job city -->
             <div class="col-md-6 col-sm-6 col-xs-12" id="ad_country_sub_sub_div" <?php if($country_cities == "" ){echo 'style="display: none;"';}?>>
           <div class="form-group">
           <label><?php echo esc_html($job_country_level_3 ); ?></label>
              <select class="js-example-basic-single" data-allow-clear="true" data-placeholder="<?php echo esc_html__( 'Select State', 'nokri' ); ?>" id="ad_country_cities" name="cand_country_cities">
                
                  <?php echo "".($country_cities);?>
              </select>
           </div>
           </div>
            <!--job town -->
             <div class="col-md-6 col-sm-6 col-xs-12" id="ad_country_sub_sub_sub_div" <?php if($country_towns == "" ){echo 'style="display: none;"';}?>>
           <div class="form-group">
            <label><?php echo esc_html($job_country_level_4 ); ?></label>
              <select class="js-example-basic-single" data-allow-clear="true" data-placeholder="<?php echo esc_html__( 'Select State', 'nokri' ); ?>" id="ad_country_towns" name="cand_country_towns">
                
                  <?php echo "".($country_towns);?>
              </select>
           </div>
           </div>
            </div>
          </div>
        </div>
         
         
        <!-- Company Locations-->
        <div class="col-md-12 col-xs-12 col-sm-12">
          <input type="submit" id="emp_save" value="<?php echo esc_html__( 'Save Profile', 'nokri' ); ?>" class="btn n-btn-flat">
          <button class="btn n-btn-flat" type="button" id="emp_proc" disabled><?php echo  esc_html__( 'Processing...','nokri' ); ?></button>
		   <button class="btn n-btn-flat" type="button" id="emp_redir" disabled><?php echo  esc_html__( 'Redirecting...','nokri' ); ?></button>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- update password-->
<div class="main-body change-password">
  <div class="dashboard-edit-profile">
    <h4 class="dashboard-heading"><?php echo esc_html__('Change Password','nokri'); ?></h4>
    <form id="change_password" method="post" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-12 col-xs-12 col-sm-12">
          <div class="row">
            <div class="dashboard-location">
              <div class="col-md-6 col-xs-12 col-sm-6">
                <div class="form-group">
                  <label><?php echo esc_html__('Old Password','nokri'); ?></label>
                  <input type="password" class="form-control" name="old_password" placeholder="<?php echo esc_html__('Enter old password','nokri'); ?>">
                </div>
              </div>
              <div class="col-md-6 col-xs-12 col-sm-6">
                <div class="form-group">
                  <label><?php echo esc_html__('New Password','nokri'); ?></label>
                  <input type="password" name="new_password" class="form-control" placeholder="<?php echo esc_html__('Enter new password','nokri'); ?>">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12">
        <?php if($is_acount_del) { ?>
          <input type="button" value="<?php echo esc_html__('Delete account?','nokri'); ?>" class="btn btn-custom del_acount">
          <?php } ?>
          <input type="submit" value="<?php echo esc_html__('Update password','nokri'); ?>" class="btn n-btn-flat change_password">
        </div>
      </div>
    </form>
  </div>
</div>
<?php
if($mapType == 'leafletjs_map')
{
	echo $lat_lon_script = '<script type="text/javascript">
	var mymap = L.map(\'dvMap\').setView(['.$ad_map_lat.', '.$ad_map_long.'], 13);
		L.tileLayer(\'https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png\', {
			maxZoom: 18,
			attribution: \'\'
		}).addTo(mymap);
		var markerz = L.marker(['.$ad_map_lat.', '.$ad_map_long.'],{draggable: true}).addTo(mymap);
		var searchControl 	=	new L.Control.Search({
			url: \'//nominatim.openstreetmap.org/search?format=json&q={s}\',
			jsonpParam: \'json_callback\',
			propertyName: \'display_name\',
			propertyLoc: [\'lat\',\'lon\'],
			marker: markerz,
			autoCollapse: true,
			autoType: true,
			minLength: 2,
		});
		searchControl.on(\'search:locationfound\', function(obj) {
			
			var lt	=	obj.latlng + \'\';
			var res = lt.split( "LatLng(" );
			res = res[1].split( ")" );
			res = res[0].split( "," );
			document.getElementById(\'ad_map_lat\').value = res[0];
			document.getElementById(\'ad_map_long\').value = res[1];
		});
		mymap.addControl( searchControl );
		
		markerz.on(\'dragend\', function (e) {
		  document.getElementById(\'ad_map_lat\').value = markerz.getLatLng().lat;
		  document.getElementById(\'ad_map_long\').value = markerz.getLatLng().lng;
		});
	</script>';
}
 ?>