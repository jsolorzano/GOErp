<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersEstado extends CI_Controller
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
        $this->load->model('busquedas_ajax/ModelsBusqueda');  // Librería de consultas extra
        $this->load->model('auditoria/ModelsAuditoria');
    }

    function index()
    {
        $data['listar'] = $this->ModelsEstado->obtenerEstados();
        $this->load->view('topologia/estado/lista', $data);
    }

    function registrar()
    {
		$data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('estados');
        $this->load->view('topologia/estado/registrar', $data);
    }

    public function guardar()
    {

        $result = $this->ModelsEstado->insertar($this->input->post());
        
        if ($result) {
			// Guardamos en la bitacora
			$param = array(
                'tabla' => 'estados',
                'codigo' => $this->input->post('cod_estado'),
                'accion' => 'Nuevo Estado',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('topologia/ControllersEstado');
        }
    }

    function editar()
    {
        $data['id']     = $this->uri->segment(4);
        $data['editar'] = $this->ModelsEstado->obtenerEstado($data['id']);
        $this->load->view('topologia/estado/editar', $data);
    }

    function eliminar($id)
    {
        $result = $this->ModelsEstado->eliminarEstado($id);
        if ($result) {
			// Guardamos en la bitacora
			$param = array(
                'tabla' => 'estados',
                'codigo' => $this->input->post('cod_estado'),
                'accion' => 'Eliminar Estado',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('topologia/ControllersEstado');
        }
    }

    function actualizar()
    {
        $result = $this->ModelsEstado->actualizarEstado($this->input->post());
        if ($result) {
			// Guardamos en la bitacora
			$param = array(
                'tabla' => 'estados',
                'codigo' => (string)$this->input->post('cod_estado'),
                'accion' => 'Editar Estado',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('topologia/ControllersEstado');
        }
    }

}
