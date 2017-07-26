<?php
abstract class LT_ControllerUsersBase extends SB_Controller
{
	public function task_recover_pwd_now()
	{
		$email = SB_Request::getString('email');
		if( empty($email) )
		{
			SB_MessagesStack::AddMessage(__('You must enter your email', 'users'), 'error');
			return false;
		}
		$dbh = SB_Factory::getDbh();
		$email = $dbh->EscapeString($email);
		$query = "SELECT * FROM users WHERE `email` = '$email' LIMIT 1";
		if( !$dbh->Query($query) )
		{
			SB_MessagesStack::AddMessage(__('The email has not belongs to any registered user.', 'users'), 'error');
			return false;
		}
		$com 	= parse_url(BASEURL);
		$user 	= new SB_User($dbh->FetchRow()->user_id);
		$hash 	= md5($user->email . $user->pwd . SITE_TITLE . time());
		sb_update_user_meta($user->user_id, '_recover_pwd_hash', $hash);
		$link 		= SB_Route::_('index.php?mod=users&view=recover_pwd&hash='.$hash);
		$message 	= sprintf(__("Hello %s %s<br/><br/>", 'users'), $user->first_name, $user->last_name);
		$message 	.= __("In order to recover your password, please follow the next link.<br/><br/>", 'users');
		$message 	.= sprintf("<a href=\"%s\">%s</a><br/><br/>", $link, __('Recover password', 'users'));
		$message 	.= __("If you does not requested your password, please ignore this email.<br/><br/>", 'users');
		$message 	.= sprintf("%s<br/>", SITE_TITLE);
		$subject 	= __('Password Recovery - ', 'users') . ' ' . SITE_TITLE;
		$headers 	= /*implode("\r\n", */array(
				'Content-type: text/html',
				sprintf("From: \"%s\" <no-reply@%s>",  SITE_TITLE, $com['host'])
		);
		$subject = SB_Module::do_action('users_recover_pwd_email_subject', $subject, $user);
		$message = SB_Module::do_action('users_recover_pwd_email_body', $message, $user, $link);
		$headers = SB_Module::do_action('users_recover_pwd_email_header', $headers, $user);
		/*
		sb_include_module_helper('forms');
		$mail = LT_HelperForms::GetMailerInstance();
		$mail->Subject = $subject;
		$mail->Body = $message;
		$mail->isHTML(true);
		$mail->addAddress($user->email);
		$mail->send();
		*/
		lt_mail($user->email, $subject, $message, $headers);
		SB_MessagesStack::AddMessage(__('Revise su email y siga las intrucciones para recuperar su contrase&ntilde;a.'), 'success');
		sb_redirect(SB_Route::_('index.php?mod=users&view=recover_pwd'));
	}
	public function task_recover_pwd()
	{
		$recover_form = false;
		if( $hash = SB_Request::getString('hash') )
		{
			$dbh = SB_Factory::getDbh();
			$query = "SELECT user_id from user_meta WHERE meta_key = '_recover_pwd_hash' AND meta_value = '$hash' LIMIT 1";
			if( $dbh->Query($query) )
			{
				//var_dump($query);
				$recover_form = true;
				sb_set_view_var('hash', $hash);
			}
		}
		sb_set_view_var('recover_form', $recover_form);
	}
	public function task_upload_image()
	{
		require_once INCLUDE_DIR . SB_DS . 'qqFileUploader.php';
		$uh = new qqFileUploader();
		$uh->allowedExtensions = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
		// Specify max file size in bytes.
		//$uh->sizeLimit = 10 * 1024 * 1024; //10MB
		// Specify the input name set in the javascript.
		$uh->inputName = 'qqfile';
		// If you want to use resume feature for uploader, specify the folder to save parts.
		$uh->chunksFolder = 'chunks';
		$res = $uh->handleUpload(TEMP_DIR);
		$file_path = TEMP_DIR . SB_DS . $uh->getUploadName();
		sb_include('class.image.php');
		$img = new SB_Image($file_path);
		//if( $img->getWidth() > 150 || $img->getHeight() > 150)
		{
			$img->resizeImage(150, 150);
			$img->save($file_path);
		}
		$res['uploadName'] = $uh->getUploadName();
		$res['image_url'] = BASEURL . '/temp/' . $res['uploadName'];
		if( $user_id = SB_Request::getInt('user_id') )
		{
			//sb_update_user_meta($user_id, '', $meta_value);
		}
		if( sb_is_user_logged_in() && SB_Request::getInt('update') )
		{
			$user_id = sb_get_current_user()->user_id;
			$image_file	= $res['uploadName'];
			$user_dir	= UPLOADS_DIR . SB_DS . sb_build_slug(sb_get_current_user()->email);
			if( $old_image = sb_get_user_meta($user_id, '_image') )
			{
				file_exists($user_dir . SB_DS . $old_image) && @unlink($user_dir . SB_DS . $old_image);
			}
			
			@rename(TEMP_DIR . SB_DS . $image_file, $user_dir . SB_DS . $image_file);
			sb_update_user_meta($user_id, '_image', $image_file);
			$res['image_url'] = UPLOADS_URL . '/' . basename($user_dir) . '/' . $res['uploadName'];
			$res['user_dir']	= $user_dir; 
		}
		die(json_encode($res));
	}
}