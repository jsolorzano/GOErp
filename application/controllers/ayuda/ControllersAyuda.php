<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersAyuda extends CI_Controller
{

    public function __construct()
    {

        @parent::__construct();

// Load form helper library
        $this->load->helper('form');

        $this->load->helper(array('url'));

        $this->load->view('base');

// Load form validation library
        $this->load->library('form_validation');

// Load session library
        $this->load->library('session');

// Load database
        $this->load->model('auditoria/ModelsAuditoria');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        
    }

    function index()
    {
        $this->load->view('ayuda/ayuda.php');
    }
    
}
