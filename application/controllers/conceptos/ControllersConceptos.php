<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersConceptos extends CI_Controller {

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
        $this->load->model('conceptos/ModelsConceptos');
        $this->load->model('busquedas_ajax/ModelsBusqueda');  // Librería de consultas extra
    }

    function index() {
        $data['listar'] = $this->ModelsConceptos->obtenerConceptos();
        $this->load->view('conceptos/lista', $data);
    }

    function registrar() {
        $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('conceptos');
        $this->load->view('conceptos/registrar', $data);
    }

    public function guardar() {
		
		// Preparamos el siguiente id para evitar errores luegos de cargar manuales
		$ultimo_id = $this->ModelsBusqueda->count_all_table('conceptos');
		
		// Armamos la data a registrar
        $data_concepto = array(
			'id' => $ultimo_id+1,
			'codigo' => $this->input->post('codigo'),
			'concepto' => $this->input->post('concepto'),
			'tipo' => $this->input->post('tipo'),
			'monto' => $this->input->post('monto'),
			'fecha_create' => date('Y-m-d'),
			'estatus' => $this->input->post('estatus'),
			'user_create_id' => $this->session->userdata['logged_in']['id'],
			//~ 'fecha_update' => date('Y-m-d'),
        );

        $result = $this->ModelsConceptos->insertarConcepto($data_concepto);

        if ($result) {

            $param = array(
                'tabla' => 'conceptos',
                'codigo' => $this->input->post('codigo'),
                'accion' => 'Nueva concepto',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('conceptos/ControllersConceptos');
        }
    }

    function editar() {
        $data['id'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsConceptos->obtenerConcepto($data['id']);
        $this->load->view('conceptos/editar', $data);
    }

    function eliminar($id) {
        $result = $this->ModelsConceptos->eliminarConceptos($id);
        $param = array(
                'tabla' => 'conceptos',
                'codigo' => $id,
                'accion' => 'Eliminar concepto',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        if ($result) {

            
            redirect('conceptos/ControllersConceptos');
        }
    }

    function actualizar() {
		
		// Armamos la data a registrar
        $data_concepto = array(
			'id' => $this->input->post('id'),
			'codigo' => $this->input->post('codigo'),
			'concepto' => $this->input->post('concepto'),
			'tipo' => $this->input->post('tipo'),
			'monto' => $this->input->post('monto'),
			//~ 'fecha_create' => date('Y-m-d'),
			'fecha_update' => date('Y-m-d'),
			//~ 'estatus' => $this->input->post('estatus'),
			//~ 'user_create_id' => $this->session->userdata['logged_in']['id'],
        );
        
        $result = $this->ModelsConceptos->actualizarConcepto($data_concepto);

        if ($result) {

            $param = array(
                'tabla' => 'conceptos',
                'codigo' => $this->input->post('codigo'),
                'accion' => 'Editar concepto',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('conceptos/ControllersConceptos');
        }
    }
	
	// Método para consultar los datos de una concepto por su nombre
    function consultar() {
        $result = $this->ModelsBusqueda->existe_registro('conceptos', 'concepto', $this->input->post('concepto'));
    }
    
    // Método para activar/desactivar una concepto
    function activar_desactivar($id)
    {
		//~ echo "Id: ".$id;
		
		$accion = $this->input->post('accion');
		$estatus = 1;
		
		if ($accion == 'desactivar'){
			$estatus = 0;
		}
		
		// Armamos la data a actualizar
        $data_concepto = array(
			'id' => $id,
			'estatus' => $estatus,
			//~ 'fecha_update' => date('Y-m-d'),
        );
        
		// Actualizamos la concepto con los datos armados
		$result = $this->ModelsConceptos->actualizarConcepto($data_concepto);
		
		// Guardado en el módulo de auditoría
		if ($result) {
            $param = array(
                'tabla' => 'conceptos',
                'codigo' => $cod,
                'accion' => $accion.' concepto',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        }
        
    }

}
