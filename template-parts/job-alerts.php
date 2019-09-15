<?php
/* Make cats selected on update Job*/
$cats_html	=	'';
$ad_cats	=	nokri_get_cats('job_category' , 0 );
if ( ! empty( $ad_cats ) && ! is_wp_error( $ad_cats ) )
{
	foreach( $ad_cats as $ad_cat )
	{
		$cats_html	.=	'<option value="'.$ad_cat->term_id.'" >' . $ad_cat->name .  '</option>';
	}
}
$today =  date("Y/m/d");
?>
<div class="cp-loader"></div>
<div class="modal fade resume-action-modal" id="job-alert-subscribtion">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<form method="post" id="alert_job_form" class="alert-job-modal-popup">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><?php echo esc_html__('Want to subscribe job alerts?','nokri'); ?></h4>
				</div>
				<div class="modal-body">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="form-group">
							<label>
								<?php echo __( 'Alert Name', 'nokri' ); ?><span class="color-red">*</span>
							</label>
							<input placeholder="<?php echo  __( 'Enter alert name','nokri' ); ?>" class="form-control" type="text" data-parsley-required="true" data-parsley-error-message="<?php echo __( 'Please enter alert name', 'nokri' ); ?>" data-parsley-trigger="change" name="alert_name">
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="form-group">
							<label>
								<?php echo __( 'Your Email', 'nokri' ); ?><span class="color-red">*</span>
							</label>
							<input placeholder="<?php echo  __( 'Enter your email address','nokri' ); ?>" class="form-control" type="email" data-parsley-type="email" data-parsley-required="true" data-parsley-error-message="<?php echo __( 'Please enter your valid email', 'nokri' ); ?>" data-parsley-trigger="change" name="alert_email">
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="form-group">
							<label>
								<?php echo __( 'Select email frequency', 'nokri' ); ?><span class="color-red">*</span>
							</label>
							<select class="select-generat" data-allow-clear="true" data-parsley-required="true" data-parsley-error-message "=Select your resume to apply" name="alert_frequency">
								<option value=""><?php echo __( 'Select an option', 'nokri' ); ?></option>
                                <option value="1"><?php echo __( 'Daily', 'nokri' ); ?></option>
                                <option value="7"><?php echo __( 'Weekly', 'nokri' ); ?></option>
                                <option value="15"><?php echo __( 'Fortnightly', 'nokri' ); ?></option>
                                <option value="30"><?php echo __( 'Monthly', 'nokri' ); ?></option>
                                <option value="12"><?php echo __( 'Yearly', 'nokri' ); ?></option>
							</select>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<div class="form-group">
							<label>
								<?php echo __( 'Select category', 'nokri' ); ?><span class="color-red">*</span>
							</label>
							<select class="select-generat" data-allow-clear="true" data-parsley-required="true" data-parsley-error-message "=Select your resume to apply" name="alert_category">
								<option value=""><?php echo __( 'Select an option', 'nokri' ); ?></option>
                                <?php echo ($cats_html); ?>
							</select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" name="submit" class="btn n-btn-flat btn-mid btn-block" id="job_alerts">
							<?php echo esc_html__( 'Submit', 'nokri' ); ?>
						</button>
					</div>
				</div>
                <input type="hidden" name="alert_start" value="<?php echo($today); ?>" />
			</form>
		</div>
	</div>
</div>