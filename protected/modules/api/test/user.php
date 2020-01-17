<?php
return [ 
		'user' => [ 
				
				'login' => [ 
						'LoginForm[username]' => 'harman',
						'LoginForm[password]' => 'admin',
						'LoginForm[device_token]' => '12131313',
						'LoginForm[device_type]' => '1' 
				
				],
				'signup' => [ 
						'User[first_name]' => 'Test String',
						'User[last_name]' => 'Test String',
						'User[email]' => 'Trand' . rand ( 0, 499 ) . 'est@' . rand ( 0, 499 ) . 'String.com',
						'User[password]' => 'Test String',
						'User[confirm_password]' => 'Test String',
						'User[contact_no]' => 'Test String',
						'User[device_token]' => '123434445',
						'User[device_type]' => '1' 
				
				],
				'change-password' => [
						'User[oldPassword]' => 'Test String',
						'User[newPassword]' => 'Test String',
						'User[confirm_password]' => 'Test String' 
				
				],
				'profile' => [ 
						'User[first_name]' => 'Test String',
						'User[last_name]' => 'Test String',
						'User[contact_no]' => 'Test String',
						'User[address]' => 'Test String',
						'User[profile_file]' => '',
						'User[date_of_birth]' => 'Test String',
						'User[country]' => 'Test String',
						'UserProfile[education_qualification]' => 'Test String',
						'UserProfile[document_file]' => '',
						'UserProfile[experience]' => 'Test String',
						'UserProfile[skills]' => 'Test String',
						'Bank[bank_name]' => 'Test String',
						'Bank[account_no]' => 'Test String' 
				
				],
				
				'customer-profile-update' => [ 
						
						'User[first_name]' => 'Test String',
						'User[last_name]' => 'Test String',
						'User[contact_no]' => 'Test String',
						'User[profile_file]' => '' 
				],
				
				'instagram' => [ 
						"User[email]" => "",
						"User[userId]" => "",
						"User[provider]" => "",
						"User[full_name]" => "",
						// "User[image_url]"=>'',
						"User[device_token]" => '',
						"User[device_type]" => '' 
				],
				
				'step1' => [ 
						'User[is_service]' => 1,
						'User[address]' => 'Test String',
						'User[town]' => 'Test String',
						'User[city]' => 'Test String',
						'User[country]' => 'Test String',
						'User[zipcode]' => 'Test String',
						'User[contact_no]' => 'Test String',
						'User[date_of_birth]' => 'Test String' 
				
				],
				
				'add-card' => [ 
						'User[token]' => '4242424242424242',
						'User[security_check_no]' => '4242424242424242',
						'User[is_service]' => 2,
				
				],
				
				'step2' => [ 
						
						"WorkerSkill" => '[{"hourly_rate":"76","experience":"12","description":"fjjd","sub_category_id":"6","delivery_time":"6"},
                                           {"hourly_rate":"1","experience":"12","description":"ghh","sub_category_id":"7",,"delivery_time":"6"},
                                           {"hourly_rate":"76","experience":"12","description":"jfg","sub_category_id":"7",,"delivery_time":"6"}]' 
				
				],
				'step3' => [ 
						'User[profile_file]' => '',
						'UserProfile[document_file]' => '' 
				
				],
				'step4' => [ 
						'Bank[account_no]' => '234567',
						'Bank[routing_no]' => '123456' 
				
				] ,
				'address-update?id=&type=0' => [
						'User[address]' => '',
				
				] ,
				'add-worker-skill' => [
						'WorkerSkill[hourly_rate]' => '234567',
						'WorkerSkill[experience]' => '123456',
						'WorkerSkill[description]' => '234567',
						'WorkerSkill[sub_category_id]' => '123456',
						'WorkerSkill[category_id]' => '234567',
						'WorkerSkill[delivery_time]' => '123456',
						
				
				]
		] 

];
?>
