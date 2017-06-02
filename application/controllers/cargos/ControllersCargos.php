<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersCargos extends CI_Controller {

    public function __construct() {


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
        $this->load->model('cargos/ModelsCargos');
        $this->load->model('busquedas_ajax/ModelsBusqueda');  // Librería de consultas extra
    }

    function index() {
        $data['listar'] = $this->ModelsCargos->obtenerCargos();
        $this->load->view('cargos/lista', $data);
    }

    function registrar() {
        $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('cargos');
        $this->load->view('cargos/registrar', $data);
    }

    public function guardar() {

        $result = $this->ModelsCargos->insertarCargo($this->input->post());

        if ($result) {

            $param = array(
                'tabla' => 'cargos',
                'codigo' => $this->input->post('cod_cargo'),
                'accion' => 'Nuevo Cargo',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('cargos/ControllersCargos');
        }
    }

    function editar() {
        $data['id'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsCargos->obtenerCargo($data['id']);
        $this->load->view('cargos/editar', $data);
    }

    function eliminar($id) {
        $result = $this->ModelsCargos->eliminarCargos($id);
        $param = array(
                'tabla' => 'cargos',
                'codigo' => $id,
                'accion' => 'Eliminar Cargo',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        if ($result) {

            
            redirect('cargos/ControllersCargos');
        }
    }

    function actualizar() {
        $result = $this->ModelsCargos->actualizarCargo($this->input->post());

        if ($result) {

            $param = array(
                'tabla' => 'cargos',
                'codigo' => $this->input->post('cod_cargo'),
                'accion' => 'Editar Cargo',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('cargos/ControllersCargos');
        }
    }
	
	// Método para consultar los datos de un cargo por su nombre
    function consultar() {
        $result = $this->ModelsBusqueda->existe_registro('cargos', 'cargo', $this->input->post('cargo'));
    }
    
    // Método para activar/desactivar un cargo
    function activar_desactivar($id)
    {
		//~ echo "Id: ".$id;
		
		$accion = $this->input->post('accion');
		$estatus = 1;
		
		if ($accion == 'desactivar'){
			$estatus = 0;
		}
		
		// Armamos la data a actualizar
        $data_cargo = array(
			'id' => $id,
			'estatus' => $estatus,
			//~ 'fecha_update' => date('Y-m-d'),
        );
        
		// Actualizamos el cargo con los datos armados
		$result = $this->ModelsCargos->actualizarCargo($data_cargo);
		
		// Guardado en el módulo de auditoría
		if ($result) {
            $param = array(
                'tabla' => 'cargos',
                'codigo' => $cod,
                'accion' => $accion.' Cargo',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        }
        
    }

}
