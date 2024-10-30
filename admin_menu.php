<?php
$tab=0;
if(count($_GET)==1 || isset($_GET['wm_mchp_audiences'])){
	$tab=1;
}elseif(isset($_GET['wm_mchp_addaudience'])){
	$tab=2;
}elseif(isset($_GET['wm_mchp_audienceadusr'])){
	$tab=3;
}elseif(isset($_GET['wm_mchp_cf7'])){
	$tab=4;
}elseif(isset($_GET['wm_mchp_credapi'])){
	$tab=5;
}


echo'<table class="form-table">
		<tr>
			<td align="center"><a href="'.admin_url().'options-general.php?page='.esc_url( \WM_USRTOMC_ns\WM_USRTOMC::$suf ).'&wm_mchp_audiences'.'"     ><b>'.__('Audience list', 'contact-form-user-to-mailchimp-audience').'</b></a></td>
			<td align="center"><a href="'.admin_url().'options-general.php?page='.esc_url( \WM_USRTOMC_ns\WM_USRTOMC::$suf ).'&wm_mchp_addaudience'.'"   ><b>'.__('Add new Audience', 'contact-form-user-to-mailchimp-audience').'</b></a></td>
			<td align="center"><a href="'.admin_url().'options-general.php?page='.esc_url( \WM_USRTOMC_ns\WM_USRTOMC::$suf ).'&wm_mchp_audienceadusr'.'" ><b>'.__('Add new Subscriber to Audience', 'contact-form-user-to-mailchimp-audience').'</b></a></td>
			<td align="center"><a href="'.admin_url().'options-general.php?page='.esc_url( \WM_USRTOMC_ns\WM_USRTOMC::$suf ).'&wm_mchp_cf7'.'"           ><b>'.__('CF7 Integration', 'contact-form-user-to-mailchimp-audience').'</b></a></td>
			<td align="center"><a href="'.admin_url().'options-general.php?page='.esc_url( \WM_USRTOMC_ns\WM_USRTOMC::$suf ).'&wm_mchp_credapi'.'"       ><b>'.__('Credentials to API MailChimp', 'contact-form-user-to-mailchimp-audience').'</b></a></td>
		<tr>
	</table>
	<div class="wrap">';


if($tab==1){
	$res = \WM_USRTOMC_ns\WM_USRTOMC::wm_list_audience();
	echo'<h1>'.esc_html( __('Audience list', 'contact-form-user-to-mailchimp-audience') ).'</h1>
		<table class="wp-list-table widefat fixed striped table-view-list">
			<thead>
				<tr>
					<th align="center"><b>'.esc_html( __('Name', 'contact-form-user-to-mailchimp-audience') ).'</b></th>
					<th align="center"><b>'.esc_html( __('Count', 'contact-form-user-to-mailchimp-audience') ).'</b></th>
					<th align="center"><b>'.esc_html( __('id', 'contact-form-user-to-mailchimp-audience') ).'</b></th>
					<th align="center"><b>'.esc_html( __('Subject', 'contact-form-user-to-mailchimp-audience') ).'</b></th>
					<th align="center"><b>'.esc_html( __('Perm. reminder', 'contact-form-user-to-mailchimp-audience') ).'</b></th>
					<th align="center"><b>'.esc_html( __('CF7 Field', 'contact-form-user-to-mailchimp-audience') ).'</b></th>
				<tr>
			</thead>
			<tbody>';
			if(isset($res->lists) && is_array($res->lists)){
				foreach($res->lists as $resi){
					echo'<tr>
							<td class="title column-title has-row-actions column-primary page-title">'.esc_html( $resi->name ).'</td>
							<td class="title column-title has-row-actions column-primary page-title">'.esc_html( $resi->stats->member_count ).'</td>
							<td class="title column-title has-row-actions column-primary page-title">'.esc_html( $resi->id ).'</td>
							<td class="title column-title has-row-actions column-primary page-title">'.esc_html( $resi->campaign_defaults->subject ).'</td>
							<td class="title column-title has-row-actions column-primary page-title">'.esc_html( $resi->permission_reminder ).'</td>
							<td class="title column-title has-row-actions column-primary page-title">[hidden wm_mchp_cf7_audience_id default:"'.esc_html( $resi->id ).'"]</td>
						</tr>';
				}
			}
	echo'</tbody>
		</table>';
}


if($tab==2){
	if(isset($_POST['wm_mchp_add_audience_submit'])){
		$_POST['wm_mchp_add_audience_name']                = sanitize_user(substr(($_POST['wm_mchp_add_audience_name']                      ?? ''), 0, 100));
		$_POST['wm_mchp_add_audience_email']               = sanitize_email(substr(($_POST['wm_mchp_add_audience_email']                    ?? ''), 0, 100));
		$_POST['wm_mchp_add_audience_from_name']           = sanitize_user(substr(($_POST['wm_mchp_add_audience_from_name']                 ?? ''), 0, 100));
		$_POST['wm_mchp_add_audience_city']                = sanitize_text_field(substr(($_POST['wm_mchp_add_audience_city']                ?? ''), 0, 100));
		$_POST['wm_mchp_add_audience_state']               = sanitize_text_field(substr(($_POST['wm_mchp_add_audience_state']               ?? ''), 0, 100));
		$_POST['wm_mchp_add_audience_zip']                 = sanitize_text_field(substr(($_POST['wm_mchp_add_audience_zip']                 ?? ''), 0, 100));
		$_POST['wm_mchp_add_audience_country']             = sanitize_text_field(substr(($_POST['wm_mchp_add_audience_country']             ?? ''), 0, 100));
		$_POST['wm_mchp_add_audience_subject']             = sanitize_text_field(substr(($_POST['wm_mchp_add_audience_subject']             ?? ''), 0, 100));
		$_POST['wm_mchp_add_audience_language']            = sanitize_text_field(substr(($_POST['wm_mchp_add_audience_language']            ?? ''), 0, 100));
		$_POST['wm_mchp_add_audience_permission_reminder'] = sanitize_text_field(substr(($_POST['wm_mchp_add_audience_permission_reminder'] ?? ''), 0, 100));
		
		\WM_USRTOMC_ns\WM_USRTOMC::wm_create_audience(  $_POST['wm_mchp_add_audience_name'],
														$_POST['wm_mchp_add_audience_email'],
														$_POST['wm_mchp_add_audience_city'],
														$_POST['wm_mchp_add_audience_state'],
														$_POST['wm_mchp_add_audience_zip'],
														$_POST['wm_mchp_add_audience_country'],
														$_POST['wm_mchp_add_audience_from_name'],
														$_POST['wm_mchp_add_audience_subject'],
														$_POST['wm_mchp_add_audience_language'],
														$_POST['wm_mchp_add_audience_permission_reminder']
													);
	}
	echo'<h1>'.esc_html( __('Add new Audience', 'contact-form-user-to-mailchimp-audience') ).'</h1>
		<form action="'.esc_url( $_SERVER['REQUEST_URI'] ).'" method="POST">
			<table>
				<tbody>
					<tr>
						<td>
							<input type="text"   name="wm_mchp_add_audience_name"                 placeholder="'.esc_attr( __('Name',                    'contact-form-user-to-mailchimp-audience') ).'">
						</td>
					</tr>
					<tr>
						<td>
							<input type="email"  name="wm_mchp_add_audience_email"                placeholder="'.esc_attr( __('E-Mail',                  'contact-form-user-to-mailchimp-audience') ).'">
						</td>
					</tr>
					<tr>
						<td>
							<input type="text"   name="wm_mchp_add_audience_city"                 placeholder="'.esc_attr( __('City',                    'contact-form-user-to-mailchimp-audience') ).'">
						</td>
					</tr>
					<tr>
						<td>
							<input type="text"   name="wm_mchp_add_audience_state"                placeholder="'.esc_attr( __('State',                   'contact-form-user-to-mailchimp-audience') ).'">
						</td>
					</tr>
					<tr>
						<td>
							<input type="text"   name="wm_mchp_add_audience_zip"                  placeholder="'.esc_attr( __('Zip',                     'contact-form-user-to-mailchimp-audience') ).'">
						</td>
					</tr>
					<tr>
						<td>
							<input type="text"   name="wm_mchp_add_audience_country"              placeholder="'.esc_attr( __('Country',                 'contact-form-user-to-mailchimp-audience') ).'">
						</td>
					</tr>
					<tr>
						<td>
							<input type="text"   name="wm_mchp_add_audience_from_name"            placeholder="'.esc_attr( __('From Name',               'contact-form-user-to-mailchimp-audience') ).'">
						</td>
					</tr>
					<tr>
						<td>
							<input type="text"   name="wm_mchp_add_audience_subject"              placeholder="'.esc_attr( __('Subject',                 'contact-form-user-to-mailchimp-audience') ).'">
						</td>
					</tr>
					<tr>
						<td>
							<input type="text"   name="wm_mchp_add_audience_language"             placeholder="'.esc_attr( __('Language (RU,EN,...)',    'contact-form-user-to-mailchimp-audience') ).'">
						</td>
					</tr>
					<tr>
						<td>
							<input type="text"   name="wm_mchp_add_audience_permission_reminder"  placeholder="'.esc_attr( __('Permis. reminder (text)', 'contact-form-user-to-mailchimp-audience') ).'" title="'.esc_attr( __('Why did you receive this letter.', 'contact-form-user-to-mailchimp-audience') ).'">
						</td>
					</tr>
					<tr>
						<td>
							<p class="submit">
								<input type="submit"  name="wm_mchp_add_audience_submit" value="'.esc_attr( __('Add', 'contact-form-user-to-mailchimp-audience') ).'" class="button button-primary">
							</p>
						</td>
					</tr>
				</tbody>
			</table>
		</form>';
}


if($tab==3){
	if(isset($_POST['wm_mchp_add_subscriber_submit'])){
		$_POST['wm_subscriber_fname'] = sanitize_user(substr(($_POST['wm_subscriber_fname']  ?? ''), 0, 100));
		$_POST['wm_subscriber_lname'] = sanitize_user(substr(($_POST['wm_subscriber_lname']  ?? ''), 0, 100));
		$_POST['wm_subscriber_email'] = sanitize_email(substr(($_POST['wm_subscriber_email'] ?? ''), 0, 100));
		$_POST['wm_audience_id']      = sanitize_text_field(substr(($_POST['wm_audience_id'] ?? ''), 0, 100));
		
		$userID = \WM_USRTOMC_ns\WM_USRTOMC::wm_create_subscriber(  $_POST['wm_subscriber_fname'],
																    $_POST['wm_subscriber_lname'],
																	$_POST['wm_subscriber_email'],
																	$_POST['wm_audience_id']
																);
	}
	echo'<h1>'.__('Add new Subscriber to Audience', 'contact-form-user-to-mailchimp-audience').'</h1>
		'.(isset($userID) && $userID>0 ?'<p>'.esc_html( __('User Added!', 'contact-form-user-to-mailchimp-audience') ).'</p>' :'').'
		<form action="'.esc_attr($_SERVER['REQUEST_URI']).'" method="POST">
			<table>
				<tbody>
					<tr>
						<td>
							<select name="wm_audience_id">';
								$res = \WM_USRTOMC_ns\WM_USRTOMC::wm_list_audience();
								if(isset($res->lists) && is_array($res->lists)){
									foreach($res->lists as $resi){
										echo'<option value="'.esc_attr( $resi->id ).'">'.esc_html( $resi->name ).'</option>';
									}
								}
						echo'</select>
						</td>
					</tr>
					<tr>
						<td><input type="text" name="wm_subscriber_fname" placeholder="'.esc_attr( __('First Name', 'contact-form-user-to-mailchimp-audience') ).'"></td>
					</tr>
					<tr>
						<td><input type="text" name="wm_subscriber_lname" placeholder="'.esc_attr( __('Last Name',  'contact-form-user-to-mailchimp-audience') ).'"></td>
					</tr>
					<tr>
						<td><input type="text" name="wm_subscriber_email" placeholder="'.esc_attr( __('E-Mail',     'contact-form-user-to-mailchimp-audience') ).'"></td>
					</tr>
					<tr>
						<td>
							<p class="submit">
								<input type="submit" name="wm_mchp_add_subscriber_submit" value="'.esc_attr( __('Add', 'contact-form-user-to-mailchimp-audience') ).'" class="button button-primary">
							</p>
						</td>
					</tr>
				</tbody>
			</table>
		</form>';
}


if($tab==4){
	echo'<div>
			<h1>'.esc_html( __('CF7 Integration', 'contact-form-user-to-mailchimp-audience') ).'</h1>
			<p>'.esc_html( __('CF7 short code, with audience id', 'contact-form-user-to-mailchimp-audience') ).': <code>[hidden wm_mchp_cf7_audience_id default:"1234567"]</code></p>
			<p>'.esc_html( __('CF7 fields can be', 'contact-form-user-to-mailchimp-audience') ).': <code>[usr_email*] [usr_fname] [usr_lname] [usr_mailing_yn]</code></p>
		</div>';
}


if($tab==5){
	if( isset($_POST['wm_mchp_acces_submit']) && isset( $_POST['mailchimp_key'] ) ){
		\WM_USRTOMC_ns\WM_USRTOMC::wm_update_key( $_POST['mailchimp_key'] );
	}
	echo'<h1>'.__('MailChimp Credentials to API', 'contact-form-user-to-mailchimp-audience').'</h1>
		<form action="'.esc_attr( $_SERVER['REQUEST_URI'] ).'" method="POST">
			<table>
				<tr>
					<td>
						<input type="text"        name="mailchimp_key"         value="'.esc_attr( \WM_USRTOMC_ns\WM_USRTOMC::wm_get_key() ).'" placeholder="'.esc_attr( __('MailChimp API KEY', 'contact-form-user-to-mailchimp-audience') ).'">
					</td>
				</tr>
				<tr>
					<td>
						<p class="submit">
							<input type="submit"  name="wm_mchp_acces_submit"  value="'.esc_attr( __('Save', 'contact-form-user-to-mailchimp-audience') ).'" class="button button-primary">
						</p>
					</td>
				</tr>
			</table>
		</form>';
}

echo'</div>'; ?>