<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersMunicipio extends CI_Controller
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
        $this->load->model('busquedas_ajax/ModelsBusqueda');  // Librería de consultas extra
        $this->load->model('auditoria/ModelsAuditoria');
    }

    function index()
    {
        $data['listar']      = $this->ModelsMunicipio->obtenerMunicipios();
        $data['list_estado'] = $this->ModelsEstado->obtenerEstados();
        $this->load->view('topologia/municipio/lista', $data);
    }

    function registrar()
    {
		$data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('municipios');
        $data['list_estado'] = $this->ModelsEstado->obtenerEstados();
        $this->load->view('topologia/municipio/registrar', $data);
    }

    public function guardar()
    {

        $result = $this->ModelsMunicipio->insertar($this->input->post());
        if ($result) {
			// Guardamos en la bitacora
			$param = array(
                'tabla' => 'municipios',
                'codigo' => $this->input->post('cod_municipio'),
                'accion' => 'Nuevo Muicipio',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('topologia/ControllersMunicipio');
        }
    }

    function editar()
    {
        $data['id']          = $this->uri->segment(4);
        $data['editar']      = $this->ModelsMunicipio->obtenerMunicipio($data['id']);
        $data['list_estado'] = $this->ModelsEstado->obtenerEstados();
        $this->load->view('topologia/municipio/editar', $data);
    }

    function eliminar($id)
    {
        $result = $this->ModelsMunicipio->eliminarMunicipio($id);
        if ($result) {
			// Guardamos en la bitacora
			$param = array(
                'tabla' => 'municipios',
                'codigo' => $this->input->post('cod_municipio'),
                'accion' => 'Eliminar Muicipio',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('topologia/ControllersMunicipio');
        }
    }

    function actualizar()
    {
        $result = $this->ModelsMunicipio->actualizarMunicipio($this->input->post());
        if ($result) {
			// Guardamos en la bitacora
			$param = array(
                'tabla' => 'municipios',
                'codigo' => (string)$this->input->post('cod_municipio'),
                'accion' => 'Editar Muicipio',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('topologia/ControllersMunicipio');
        }
    }

}
