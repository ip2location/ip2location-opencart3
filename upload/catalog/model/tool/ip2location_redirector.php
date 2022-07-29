<?php

class ModelToolIP2LocationRedirector extends Model
{
	public function getRule($from)
	{
		$this->load->model('setting/setting');

		$from = ltrim($from, '/');

		$query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "ip2location_redirector` WHERE `status` = '1'");

		foreach ($query->rows as $row) {
			$trigerred = false;

			if ($from == $row['to'] && $row['code'] != 404) {
				continue;
			}

			switch ($row['mode']) {
				case 0:
					if ($row['from'] == $from) {
						$trigerred = true;
					}

					break;

				case 1:
					if (substr($from, 0, strlen($row['from'])) === $row['from']) {
						$trigerred = true;
					}

					break;

				case 2:
					if (preg_match('/' . $row['from'] . '/', $from)) {
						$trigerred = true;
					}

					break;
			}

			if ($trigerred) {
				$records = [
					'countryCode' => '',
					'regionName'  => '',
				];

				$settings = $this->model_setting_setting->getSetting('ip2location');

				if (!$settings) {
					return;
				}

				if ($settings['ip2location_lookup_method'] == '0') {
					require_once DIR_SYSTEM . 'library/ip2location/class.IP2Location.php';

					$geolocation = new \IP2Location\Database($settings['ip2location_database_location'], \IP2Location\Database::FILE_IO);
					$records = $geolocation->lookup($this->getIP(), \IP2Location\Database::ALL);

					unset($geolocation);
				} else {
					$ch = curl_init();
					curl_setopt_array($ch, [
						CURLOPT_HEADER         => 0,
						CURLOPT_URL            => 'http://api.ip2location.com/v2/?key=' . $settings['ip2location_api_key'] . '&package=WS3&format=json&ip=' . $this->getIP(),
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_TIMEOUT        => 10,
						CURLOPT_SSL_VERIFYPEER => 0,
					]);
					$response = curl_exec($ch);
					curl_close($ch);

					if ($json = json_decode($response)) {
						$records = [
							'countryCode' => $json->country_code,
							'regionName'  => $json->region_name,
						];
					}
				}

				$origins = json_decode($row['origins']);

				foreach ($origins as $origin) {
					if ($origin->code == '*') {
						return [
							'to'   => $row['to'],
							'code' => $row['code'],
						];
					}

					if ($origin->code == $records['countryCode']) {
						if ($origin->region == '*') {
							return [
								'to'   => $row['to'],
								'code' => $row['code'],
							];
						}

						if ($origin->region == $records['regionName']) {
							return [
								'to'   => $row['to'],
								'code' => $row['code'],
							];
						}
					}
				}
			}
		}
	}

	private function getIP()
	{
		// Get client IP
		$ip = $_SERVER['REMOTE_ADDR'];

		// Get forwarded IP
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$xip = trim(current(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])));

			if (filter_var($xip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
				$ip = $xip;
			}
		}

		if (isset($_SERVER['DEV_MODE'])) {
			$ip = '175.144.151.253';
		}

		return $ip;
	}
}
