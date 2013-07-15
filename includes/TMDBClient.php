<?php
	// Simple TMDB (The Movie Database) REST Client (limited to the functions needed)
	// Author: Daniel Garcia
	// API docs: http://docs.themoviedb.apiary.io/

	class TMDBClient extends RESTClient {
		public $config;

		public function __construct($api_root_url, $api_key) {
			parent::__construct($api_root_url, $api_key);
			// Get some config parameters
			$this->getConfiguration();
			$this->config = $this->response;
		}
		public function getConfiguration() {
			$service = 'configuration';
			$this->GET($service);
		}

		public function searchPerson($query) {
			$service = 'search/person';
			$query = "query=".urlencode($query);
			$this->GET($service, $query);
		}

		public function getMoviesByPerson($person_id) {
			$service = 'person/'.urlencode($person_id).'/credits';
			$this->GET($service);
		}
	}

	// Auxiliar function to sort movies by release date
	function release_date_cmp($a, $b) {
		return strcmp($b->release_date, $a->release_date);
	}

?>