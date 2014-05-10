<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Played_model extends CI_Model {
	private $table       = 't_played';
	private $primary_key = 'played_id';
	
	public function index() {
	}
}
?>