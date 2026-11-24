<?php

		header("Content-type:application/json");

		$ch = curl_init("http://pre-api.localdigitalkit.com/getProvinces");

		curl_setopt($ch, CURLOPT_URL,"http://pre-api.localdigitalkit.com/getProvinces");

		curl_setopt($ch, CURLOPT_POST, false);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'username: stdUser',
			'password: m05Ke5zPir'
		));

		$response = curl_exec($ch);

		curl_close($ch);


		echo  '{"items": [{"provinces":'.$response."}]}";

?>