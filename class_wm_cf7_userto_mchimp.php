<?php
namespace WM_USRTOMC_ns;

class WM_USRTOMC{
	public static $suf = __CLASS__;
	
	public function __construct(){
		self::wm_cf7_start();
    }
	
	
	public static function wm_cf7_start(){
		add_filter('wpcf7_mail_sent', [self::$suf, 'wm_cf7_mail_sent'], 10, 3);
		//add_filter('wpcf7_before_send_mail', [self::$suf, 'wm_cf7_mail_sent'], 10, 3);
    }
	
	
	//===CF7 HOOK===
	public static function wm_cf7_mail_sent($contact_form){
		$wpcf7      = \WPCF7_ContactForm::get_current();
        $submission = \WPCF7_Submission::get_instance();
        $data       = $submission->get_posted_data();

		if(isset($data['wm_mchp_cf7_audience_id'])){
			$yn = true;
			
			if(isset($data['usr_mailing_yn'][0])){
				if(strlen($data['usr_mailing_yn'][0])>0){
					$yn = true;
				}else{
					$yn = false;
				}
			}
			
			if($yn){
				$audience_id = $data['wm_mchp_cf7_audience_id'];
				$fname  = (isset($data['usr_fname']) ?$data['usr_fname'] :'');
				$lname  = (isset($data['usr_lname']) ?$data['usr_lname'] :'');
				$email  = (isset($data['usr_email']) ?$data['usr_email'] :'');
				self::wm_create_subscriber($fname, $lname, $email, $audience_id);
			}
		}
		
		return $contact_form;
	}
	

	//===add user to mailchimp Audience===
	static function wm_create_subscriber($fname='', $lname='', $email='', $audience_id=''){
		$fname       = sanitize_user(substr($fname, 0, 100));
		$lname       = sanitize_user(substr($lname, 0, 100));
		$email       = sanitize_email(substr($email, 0, 100));
		$audience_id = sanitize_text_field(substr($audience_id, 0, 100));
		
		if(!is_email($email)){
			$email = '';
		}
		
		$json = [
			'email_address' => $email,
			'status'        => 'subscribed', //"subscribed","unsubscribed","cleaned","pending"
			'merge_fields'  => [
					'FNAME'  => $fname,
					'LNAME'	 => $lname
				]
		];
		
		$response = wp_remote_post('https://'.self::wm_datacenter().'.api.mailchimp.com/3.0/lists/'.$audience_id.'/members/'.md5(strtolower($email)), array(
				'method'      => 'PUT',
				'data_format' => 'body',
				'headers'     => [
									'Content-Type'  => 'application/json; charset=utf-8',
									'Authorization' => 'Basic '.base64_encode('user:'.self::wm_get_key())
								],
				'body'        => json_encode( $json ),
			)
		);
		$response['body'] = json_decode( $response['body'] );
		
		return ( isset( $response['body']->id ) ?$response['body']->id :false );
	}

	
	//===create Audience===
	static function wm_create_audience($name, $email, $city, $state, $zip, $country, $from_name, $subject, $language, $permission_reminder){
		$name                = substr(sanitize_user($name),                      0, 100);
		$email               = substr(sanitize_email($email),                    0, 100);
		$city                = substr(sanitize_text_field($city),                0, 100);
		$state               = substr(sanitize_text_field($state),               0, 100);
		$zip                 = substr(sanitize_text_field($zip),                 0, 100);
		$country             = substr(sanitize_text_field($country),             0, 100);
		$from_name           = substr(sanitize_user($from_name),                 0, 100);
		$subject             = substr(sanitize_text_field($subject),             0, 300);
		$language            = substr(sanitize_text_field($language),            0, 2);
		$permission_reminder = substr(sanitize_text_field($permission_reminder), 0, 500);
		
		if(!is_email($email)){
			$email = '';
		}
		
		$mailchimp_key = self::wm_get_key();
		$json = [
			'name'    =>$name,
			'contact' =>'',
			'contact' =>[
				'company'  =>'',
				'address1' =>'',
				'address2' =>'',
				'city'     =>$city,
				'state'    =>$state,
				'zip'      =>$zip,
				'country'  =>$country,
				'phone'    =>'',
			],
			'permission_reminder' =>$permission_reminder,
			'use_archive_bar'     =>false,
			'campaign_defaults'   =>[
				'from_name'       =>$from_name,
				'from_email'      =>$email,
				'subject'         =>$subject,
				'language'        =>$language,
			],
			'notify_on_subscribe'    =>'',
			'notify_on_unsubscribe'  =>'',
			'email_type_option'      =>false,
			'visibility'             =>'pub',
			'double_optin'           =>false,
			'marketing_permissions'  =>false,
		];
		
		$args = [
			'headers'     => [
				'Authorization' => 'Basic '.base64_encode('user:'.self::wm_get_key()),
				'Content-Type'  => 'application/json; charset=utf-8',
			],
			'body'        => json_encode($json),
			'method'      => 'POST',
			'data_format' => 'body',
		];      
		$res = wp_remote_post('https://'.self::wm_datacenter().'.api.mailchimp.com/3.0/lists/', $args);
		return json_decode(wp_remote_retrieve_body($res));
	}
	
	
	public static function wm_list_audience(){
		$args = [
			'headers'     => [
				'Authorization' => 'Basic '.base64_encode('user:'.self::wm_get_key()),
				'Content-Type'  => 'application/json; charset=utf-8',
			],
			'body'        => '',
			'method'      => 'GET',
			'data_format' => 'body',
		];      
		$res = wp_remote_post('https://'.self::wm_datacenter().'.api.mailchimp.com/3.0/lists/', $args);
		return json_decode(wp_remote_retrieve_body($res));
	}
	
	
	public static function wm_get_key(){
		return get_option('mailchimp_key');
	}
	
	
	public static function wm_update_key( &$mailchimp_key='' ){
		$mailchimp_key = substr( $mailchimp_key, 0, 100 );
		$mailchimp_key = stripslashes( $mailchimp_key );
		//$mailchimp_key = sanitize_key( $mailchimp_key );
		$mailchimp_key = sanitize_text_field( $mailchimp_key );
		update_option( 'mailchimp_key', $mailchimp_key, false );
	}
	
	
	public static function wm_datacenter(){
		$mailchimp_key = self::wm_get_key();
		return substr($mailchimp_key, strpos($mailchimp_key, '-')+1);
	}
}