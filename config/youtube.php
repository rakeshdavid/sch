<?php

//Ivan Petrov
//showcase0987@gmail.com
//ghjcnjSHOW


return [

    'key' => env( 'YOUTUBE_API_KEY', NULL ),

	/**
	 * Application Name.
	 */
	'application_name' => 'Showcase',
	
	/**
	 * Client ID.
	 */
	'client_id'        => env( 'GOOGLE_CLIENT_ID', NULL ),
	
	/**
	 * Client Secret.
	 */
	'client_secret'    => env( 'GOOGLE_CLIENT_SECRET', NULL ),
	
	/**
	 * Access Type
	 */
	'access_type'      => 'offline',
	
	/**
	 * Approval Prompt
	 */
	'approval_prompt'  => 'force',//TODO::??
	
	/**
	 * Scopes.
	 */
	'scopes'           => [
		'https://www.googleapis.com/auth/youtube',
		'https://www.googleapis.com/auth/youtube.upload',
		'https://www.googleapis.com/auth/youtube.readonly',
	],
	
	/**
	 * Developer key.
	 */
	'developer_key'    => env( 'GOOGLE_DEVELOPER_KEY', NULL ),
	
	/**
	 * Route URI's
	 */
	'routes'           => [
		
		/**
		 * The prefix for the below URI's
		 */
		'prefix'             => 'youtube',
		
		/**
		 * Redirect URI
		 */
		'redirect_uri'       => 'callback',
		
		/**
		 * The autentication URI
		 */
		'authentication_uri' => 'auth',
	
	],
];
