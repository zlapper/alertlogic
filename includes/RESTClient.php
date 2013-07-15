<?php
	// Simple JSON REST Client (using curl)
	// Author: Daniel Garcia
	// Based on: http://snipplr.com/view/44760/

	class RESTClient {
		public $handle;

		public $response;
		public $code;
		public $error;

		public $api_root_url;
		public $api_key;

		//Constructor of the class
		public function __construct($api_root_url, $api_key) {
			$this->api_root_url = $api_root_url;
			$this->api_key = $api_key;
			$this->init();
		}

		private function init() {
			$headers = array(
				'Accept: application/json',
				'Content-Type: application/json',
			);
			$this->handle = curl_init();
			curl_setopt($this->handle, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($this->handle, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($this->handle, CURLOPT_SSL_VERIFYPEER, false);
		}

		private function close() {
			curl_close($this->handle);
		}

		private function exec() {
			$this->response = json_decode(curl_exec($this->handle));
			$this->code = curl_getinfo($this->handle, CURLINFO_HTTP_CODE);
			$this->error = curl_error($this->handle);
		}

		public function GET($service_name, $query_string='') {
			// $url_params = urlencode($query_string).'&api_key='.$this->api_key;
			$url_params = $query_string.'&api_key='.$this->api_key;
			curl_setopt($this->handle, CURLOPT_URL, $this->api_root_url.$service_name.'?'.$url_params);
			$this->exec();
		}

		public function POST($service_name, $data_array) {
			$data_array['api_key']=$this->api_key;
			$data = json_encode($data_array);
			curl_setopt($this->handle, CURLOPT_URL, $this->api_root_url.$service_name);
			curl_setopt($this->handle, CURLOPT_POST, true);
			curl_setopt($this->handle, CURLOPT_POSTFIELDS, $data);
			$this->exec();
		}

		public function PUT($service_name, $data_array) {
			$data_array['api_key']=$this->api_key;
			$data = json_encode($data_array);
			curl_setopt($this->handle, CURLOPT_URL, $this->api_root_url.$service_name);
			curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($this->handle, CURLOPT_POSTFIELDS, $data);
			$this->exec();
		}

		public function DELETE($service_name) {
			curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
			$this->exec();
		}
	}
?>