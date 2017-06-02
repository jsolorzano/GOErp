<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsSaldos extends CI_Model
{

    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }
}
