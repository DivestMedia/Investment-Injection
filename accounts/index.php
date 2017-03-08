<?php
include( ABSPATH . 'wp-admin/includes/image.php' );
global $current_user, $wp_roles, $wpdb, $wp; 
$current_user = get_userdata($current_user->ID);

$current_url = home_url(add_query_arg(array(),$wp->request));
if(!$current_user->ID){
	wp_redirect(site_url('/accounts/login'));
}


if ( 'POST' == $_SERVER['REQUEST_METHOD']) {
	if(!empty($_POST['action'])&&!strcasecmp($_POST['action'],'update_profile')&&!empty($_POST['first_name'])&&!empty($_POST['last_name'])&&!empty($_POST['biography'])){
		$user_id = $current_user->ID;
		update_user_meta( $user_id, 'first_name', esc_attr($_POST['first_name']) );
		update_user_meta( $user_id, 'last_name', esc_attr($_POST['last_name']) );
		update_user_meta( $user_id, 'biography', esc_attr($_POST['biography']) );
		if(!empty($_POST['location']))
			update_user_meta( $user_id, 'location', esc_attr($_POST['location']) );
		if(!empty($_POST['websites']))
			update_user_meta( $user_id, 'websites', esc_attr($_POST['websites']) );

		
	}elseif(!empty($_POST['action'])&&!strcasecmp($_POST['action'],'update_account')&&!empty($_POST['email'])){
		$info['ID'] = $current_user->ID;
		if(strcasecmp($current_user->user_email, $_POST['email'])){
			$info['user_email'] = sanitize_email( $_POST['email'] );
		}

		if(!empty($_POST['old_password'])&&!empty($_POST['new_password'])&&!empty($_POST['c_new_password'])){
			if(!strcasecmp($_POST['new_password'],$_POST['c_new_password'])){
				if(wp_check_password($_POST['old_password'],$current_user->user_pass,$current_user->ID)){
					$info['user_pass'] = $_POST['new_password'];
				}
			}
		}
		$result = wp_update_user($info);
		$account_error = [];
		if ( is_wp_error( $result ) ) {
			if(!empty($result->errors['existing_user_email'][0]))
				array_push($account_error, $result->errors['existing_user_email'][0]);
		}else{
			update_user_meta( $current_user->ID, 'is_verified', 0 );
			$current_user = get_userdata($info['ID']);
		}
	}elseif(!empty($_FILES['photo'])&&!empty($_POST['action'])&&!strcasecmp($_POST['action'],'update_photo')){

		$filename = basename($_FILES["photo"]["name"]);
		$upload_dir = wp_upload_dir();

		if(wp_mkdir_p($upload_dir['path']))     $target_path = $upload_dir['path'];
		else                                    $target_path = $upload_dir['basedir'];

		$target_file = $target_path . '/' . $filename;
		$target_file_url = $upload_dir['baseurl'] . '/' . $filename;

		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			$uploadOk = 0;
		}

		// Check file size
		if ($_FILES["photo"]["size"] > 500000) {
			$uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			// if everything is ok, try to upload file
		} else {

			if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
				$image = wp_get_image_editor($target_file);
				if ( ! is_wp_error( $image ) ) {
					$file = $target_file;

					// copy($imageUrl, $file);

					// Create attachment
					$wp_filetype = wp_check_filetype(basename($file), null);
					$attachment = array(
						'guid' => $upload_dir['url'] .
						DIRECTORY_SEPARATOR . basename($file),
						'post_mime_type' => $wp_filetype['type'],
						'post_title' => preg_replace('/\.[^.]+$/', '', basename($file)),
						'post_content' => '',
						'post_status' => 'inherit'
					);
					$attach_id = wp_insert_attachment($attachment, $file);
					$attach_data = wp_generate_attachment_metadata($attach_id, $file);
					wp_update_attachment_metadata($attach_id, $attach_data);

					// Attach avatar to user
					delete_metadata('post', null, '_wp_attachment_wp_user_avatar',$current_user->ID, true);
					update_user_meta($current_user->ID, '_wp_attachment_wp_user_avatar', $attach_id);
					update_user_meta($current_user->ID,
					$wpdb->get_blog_prefix(get_current_blog_id()) . 'user_avatar', $attach_id);

				}

			}
		}
	}
	$_POST = array();
}
get_header();
?>
<section id="section-account">
	<div class="container">
		<div class="row">
			<div class="col-md-3 col-sm-4">
				<div class="thumbnail text-center">
					
					<?php

					$profileid = get_user_meta($current_user->ID,'_wp_attachment_wp_user_avatar',true);
					if(!empty($profileid)){
						$imgsrc = wp_get_attachment_image_src((int)$profileid,'ratio-image-crop');
						?>
						<div class="container-pp-update">
							<div class="change_profile_image"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span></div>
							<img src="<?=$imgsrc[0]?>" width="245" height="245" alt="" class="avatar avatar-245 wp-user-avatar wp-user-avatar-245 photo avatar-default">
						</div>
						<?php
					}else{
						?>
						<div class="container-pp-update">
						<div class="change_profile_image"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span></div>
						<?=get_avatar( $current_user->data->ID, 245 )?>
						</div>
						<?php
					}
					?>
					<h2 class="size-18 margin-top-10 margin-bottom-0"><?=$current_user->data->display_name?></h2>
					<h3 class="size-11 margin-top-0 margin-bottom-10 text-muted"><?=strtoupper($current_user->roles[0])?></h3>
					<?php

					if(!empty($current_user->roles)){
						$issubscriber = array_intersect($current_user->roles, [
							'free-account',
							'premium-account',
							'premium-unpaid-account',
							'regular-account',
							'regular-unpaid-account',
						]);

						if(count($issubscriber)){
							$issubscriber = array_values($issubscriber)[0];
							echo '<h3 class="size-11 margin-top-0 margin-bottom-10 text-muted">' . $wp_roles->roles[$issubscriber]['name'] . '</h3>';
						}
					}

					?>
					<form id="form_profilephoto" action="<?=$current_url?>" method="post" enctype="multipart/form-data">
						<input type="hidden" name="action" value="update_photo" />
						<input type="file" class="form-control profile_photo" name="photo" />
					</form>

				</div>
			</div>
			<div class="col-md-9 col-sm-8"> 
				<ul class="nav nav-tabs nav-top-border">
					<li class="active"><a href="#account" data-toggle="tab">Account</a></li>
					<li><a href="#profile" data-toggle="tab">Profile</a></li>
					<li><a href="#payment-methods" data-toggle="tab">Payment methods</a></li>
					<li><a href="#following" data-toggle="tab">Following</a></li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane fade in active" id="account">
						<form action="<?=$current_url?>" method="post" id="form-account">
							<fieldset>
								<!-- required [php action request] -->
								<input type="hidden" name="action" value="update_account" />
								<div class="row margin-bottom-10">
									<div class="form-group">
										<div class="col-md-12">
											<?php
												if(!empty($account_error)){
													foreach ($account_error as $err) {
											?>
														<div class="alert alert-danger alert-dismissible" role="alert">
  															<button type="button" class="close" data-dismiss="alert" aria-label="Close">
  																<span aria-hidden="true">&times;</span>
  															</button>
  															<?=$err?>
  														</div>
											<?php
													}
												}else{
											?>

											<?php
												}
											?>
											
										</div>
										<div class="col-md-6 col-sm-6">
											<label>Email</label>
											<input type="email" name="email" value="<?=$current_user->user_email?>" class="form-control margin-bottom-10" required="required">
											<?php
												$is_verified = get_user_meta($current_user->ID,'is_verified',true);
												if(!$is_verified){
											?>
													<small class="label label-danger"><span class="glyphicon glyphicon-warning-sign"></span> Account not verified.</small><small> <a href="#">Re-send verification email</a></small>
											<?php
												}else{
											?>
													<small class="label label-success"><span class="glyphicon glyphicon-ok"></span> Account verified.</small>
											<?php		
												}
											?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="form-group">
										<div class="col-md-6 col-sm-6">
											
											<div class="margin-top-10 container-changepassword">
												<label>Old Password</label>
												<input type="password" name="old_password" value="" class="form-control">
											</div>
											<div class="margin-top-10 container-changepassword">
												<label>New Password</label>
												<input type="password" name="new_password" value="" class="form-control">
											</div>
											<div class="margin-top-10 container-changepassword">
												<label>Confirm Password</label>
												<input type="password" name="c_new_password" value="" class="form-control">
											</div>
										</div>
									</div>
								</div>
							</fieldset>
							<div class="row">
								<div class="col-md-12">
									<button type="button" class="btn btn-info btn-changepassword">Change Password</button>
									<button type="submit" class="btn btn-primary">Save Changes</button>
								</div>
							</div>
						</form>
					</div>
					<div class="tab-pane fade" id="profile">
						<!-- 
							.validate 				- very important, to activate validation plugin

							data-success="Sent! Thank you!" 	- used by toastr to print the message
							data-toastr-position="top-right"	- toastr message position:
								.top-right
								.top-left
								.bottom-right
								.bottom-left


							NOTE: Add .required class for required fields.
							This form example already used on Careers page: page-careers.html
						 -->
						<form action="<?=$current_url?>" method="post" enctype="multipart/form-data" data-success="Sent! Thank you!" data-toastr-position="top-right">
							<fieldset>
								<!-- required [php action request] -->
								<input type="hidden" name="action" value="update_profile" />
								<div class="row">
									<div class="form-group">
										<div class="col-md-6 col-sm-6">
											<label>First Name *</label>
											<input type="text" name="first_name" value="<?=get_user_meta($current_user->ID,'first_name',true);?>" class="form-control required" required="required">
										</div>
										<div class="col-md-6 col-sm-6">
											<label>Last Name *</label>
											<input type="text" name="last_name" value="<?=get_user_meta($current_user->ID,'last_name',true);?>" class="form-control required" required="required">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="form-group">
										<div class="col-md-12 col-sm-12">
											<label>Biography *</label>
											<textarea name="biography" rows="4" class="form-control required" required="required"><?=get_user_meta($current_user->ID,'biography',true);?></textarea>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="form-group">
										<div class="col-md-6 col-sm-6">
											<label>
												Location
												<small class="text-muted">- optional</small>
											</label>
											<input type="text" name="location" value="<?=get_user_meta($current_user->ID,'location',true);?>" class="form-control inp_location">
										</div>
										<div class="col-md-6 col-sm-6">
											<label>
												Websites
												<small class="text-muted">- optional</small>
											</label>
											<div class="container-website">
											<input type="text" name="website" placeholder="http://" class="form-control">
											<?php
												$websites = get_user_meta($current_user->ID,'websites',true);
											?>
											<input type="hidden" name="websites" class="form-control" value="<?=!empty($websites)?$websites:''?>">
											
											<button type="button" class="btn btn-primary btn_add_website"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
											</div>
											<div class="added_websites">
												<?php
													if(!empty($websites)){
														$websites = explode('|', $websites);
														foreach ($websites as $website) {
												?>
													<span class="label label-success"><span><?=$website?></span><span class="glyphicon glyphicon-remove-sign remove_website" aria-hidden="true"></span></span>
												<?php
														}
													}
												?>
											</div>
										</div>
									</div>
								</div>
								
							</fieldset>

							<div class="row">
								<div class="col-md-12">
									<button type="submit" class="btn btn-primary">Save Changes</button>
								</div>
							</div>

						</form>
					</div>
					<div class="tab-pane fade" id="payment-methods">
						<h4>Manage payment options</h4>
						<div class="row">
							<div class="col-sm-8">
								<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove."</p>
							</div>
							<div class="col-sm-4">
								<button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Add Payment Option</button>
							</div>
						</div>
						
					</div>
					<div class="tab-pane fade" id="following">
						<div class="row">

							<div class="col-md-4">

								<div class="box-image text-center">
									<a class="image-hover lightbox" href="<?=get_template_directory_uri()?>/assets/images/demo/1200x800/15-min.jpg" data-plugin-options='{"type":"image"}'>
										<img class="img-responsive" src="<?=get_template_directory_uri()?>/assets/images/demo/600x400/15-min.jpg" alt="" />
									</a>
									<a class="box-image-title" href="#">
										<h2>1000 Trees</h2>
									</a>
									<p class="font-lato weight-300">Each of us needs 10 trees to breath. Our goal is to plant 1000 of them and even more with your support.</p>
									<div class="text-left margin-left-15 margin-right-15 margin-top-10 margin-bottom-10">
										<small><i class="fa fa-map-marker"></i> Pyrénées, France</small>
										<div class="progress progress-xs margin-bottom-0">
											<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%; min-width: 2em;">
												<span class="sr-only">60% Complete</span>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-5"><small>60% funded</small></div>
											<div class="col-xs-7"><small>10 days to go</small></div>
											<div class="col-xs-12">$45,051 <small>pledged</small></div>
										</div>
									</div>
								</div>

							</div>

							<div class="col-md-4">

								<div class="box-image text-center">
									<a class="image-hover" href="#">
										<img class="img-responsive" src="<?=get_template_directory_uri()?>/assets/images/demo/600x400/20-min.jpg" alt="" />
									</a>

									<a class="box-image-title" href="#">
										<h2>Superfood Protein: The World's Healthiest Protein Powder</h2>
									</a>
									<p class="font-lato weight-300">A 100% Vegan and Plant-Based Protein Powder, made from a natural blend of Chia, Rice, Quinoa, Pea and Hemp. Become a Plant Warrior.</p>
									<div class="text-left margin-left-15 margin-right-15 margin-top-10 margin-bottom-10">
										<small><i class="fa fa-map-marker"></i>  Turin, Italy</small>
										<div class="progress progress-xs margin-bottom-0">
											<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%; min-width: 2em;">
												<span class="sr-only">20% Complete</span>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-5"><small>20% funded</small></div>
											<div class="col-xs-7"><small>3 months to go</small></div>
											<div class="col-xs-12">€15,969 <small>pledged</small></div>
										</div>
									</div>
								</div>

							</div>

							<div class="col-md-4">

								<div class="box-image text-center">
									<a class="image-hover lightbox" href="http://vimeo.com/56624256" href="" data-plugin-options='{"type":"iframe"}'>
										<img class="img-responsive" src="<?=get_template_directory_uri()?>/assets/images/demo/600x400/25-min.jpg" alt="" />
									</a>

									<a class="box-image-title" href="#">
										<h2>Blackmagic Micro Cinema Camera Remote</h2>
									</a>
									<p class="font-lato weight-300">A remote control that makes hand held and gymbal filming with the BlackMagic Micro Cinema & Studio Cameras a breeze.</p>
									<div class="text-left margin-left-15 margin-right-15 margin-top-10 margin-bottom-10">
										<small><i class="fa fa-map-marker"></i> Sherbrooke, Canada</small>
										<div class="progress progress-xs margin-bottom-0">
											<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%; min-width: 2em;">
												<span class="sr-only">90% Complete</span>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-5"><small>90% funded</small></div>
											<div class="col-xs-7"><small>7 days to go</small></div>
											<div class="col-xs-12">CA$ 1,152 <small>pledged</small></div>
										</div>
									</div>
								</div>

							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?

get_footer();
