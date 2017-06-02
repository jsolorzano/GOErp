<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersParroquia extends CI_Controller
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
        $this->load->model('topologia/ModelsEstado');
        $this->load->model('topologia/ModelsMunicipio');
        $this->load->model('topologia/ModelsParroquia');
        $this->load->model('busquedas_ajax/ModelsBusqueda');  // Librería de consultas extra
        $this->load->model('auditoria/ModelsAuditoria');
    }

    function index()
    {
        $data['listar']      = $this->ModelsParroquia->obtenerParroquias();
        $data['list_estado'] = $this->ModelsEstado->obtenerEstados();
        $data['list_mun']    = $this->ModelsMunicipio->obtenerMunicipios();
        $this->load->view('topologia/parroquia/lista', $data);
    }

    function registrar()
    {
		$data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('parroquias');
        $data['list_estado'] = $this->ModelsEstado->obtenerEstados();
        $data['list_mun']    = $this->ModelsMunicipio->obtenerMunicipios();
        $this->load->view('topologia/parroquia/registrar', $data);
    }

    public function guardar()
    {

        $result = $this->ModelsParroquia->insertar($this->input->post());
        if ($result) {
			// Guardamos en la bitacora
			$param = array(
                'tabla' => 'parroquias',
                'codigo' => $this->input->post('cod_parroquia'),
                'accion' => 'Nueva Parroquia',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('topologia/ControllersParroquia');
        }
    }

    function editar()
    {
        $data['id']          = $this->uri->segment(4);
        $data['editar']      = $this->ModelsParroquia->obtenerParroquia($data['id']);
        $data['list_estado'] = $this->ModelsEstado->obtenerEstados();
        $data['list_mun']    = $this->ModelsMunicipio->obtenerMunicipios();
        $this->load->view('topologia/parroquia/editar', $data);
    }

    function eliminar($id)
    {
        $result = $this->ModelsParroquia->eliminarParroquia($id);
        if ($result) {
			// Guardamos en la bitacora
			$param = array(
                'tabla' => 'parroquias',
                'codigo' => $this->input->post('cod_parroquia'),
                'accion' => 'Eliminar Parroquia',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('topologia/ControllersParroquia');
        }
    }

    function actualizar()
    {
        $result = $this->ModelsParroquia->actualizarParroquia($this->input->post());
        if ($result) {
			// Guardamos en la bitacora
			$param = array(
                'tabla' => 'parroquias',
                'codigo' => (string)$this->input->post('cod_parroquia'),
                'accion' => 'Editar Parroquia',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('topologia/ControllersParroquia');
        }
    }

}
