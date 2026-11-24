<?php

		//Forms Fields


		$email=$_POST['email'];
		$medio=$_POST['medio'];

		$post_data="email=".$email."&medio=".$medio;


		$domain_url = "https://" . $_SERVER['HTTP_HOST'];
		if ( $parts = parse_url( $_SERVER['HTTP_REFERER'] ) ) {
			$domain_url= $parts[ "scheme" ] . "://" . $parts[ "host" ];
		}

        header("Content-type: application/json");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Origin: *");
        header("AMP-Access-Control-Allow-Source-Origin: " . $domain_url);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"http://pre-api.localdigitalkit.com/suscribeNewsletter");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'username: stdUser',
			'password: m05Ke5zPir'
		));
		$response = curl_exec($ch);
		curl_close($ch);
		$response=json_decode($response,true);
		$response_wrap=[];
		$response_wrap['srv']=$response;
		$response_wrap['type']='error';
		$response_wrap['msg']=$response['error'];
		if($response['success']){
			$response_wrap['type']='success';
			$response_wrap['msg']='Sus datos han sido enviados';
		}

		echo json_encode($response_wrap);

?>