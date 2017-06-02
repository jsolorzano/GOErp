<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersBancos extends CI_Controller {

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
        $this->load->model('bancos/ModelsBancos');
        $this->load->model('busquedas_ajax/ModelsBusqueda');  // Librería de consultas extra
    }

    function index() {
        $data['listar'] = $this->ModelsBancos->obtenerBancos();
        $this->load->view('bancos/lista', $data);
    }

    function registrar() {
        $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('bancos');
        $this->load->view('bancos/registrar', $data);
    }

    public function guardar() {

        $result = $this->ModelsBancos->insertarBanco($this->input->post());

        if ($result) {

            $param = array(
                'tabla' => 'bancos',
                'codigo' => $this->input->post('cod_banco'),
                'accion' => 'Nuevo Banco',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('bancos/ControllersBancos');
        }
    }

    function editar() {
        $data['id'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsBancos->obtenerBanco($data['id']);
        $this->load->view('bancos/editar', $data);
    }

    function eliminar($id) {
        $result = $this->ModelsBancos->eliminarBancos($id);
        $param = array(
                'tabla' => 'bancos',
                'codigo' => $id,
                'accion' => 'Eliminar Banco',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        if ($result) {

            
            redirect('bancos/ControllersBancos');
        }
    }

    function actualizar() {
        $result = $this->ModelsBancos->actualizarBanco($this->input->post());

        if ($result) {

            $param = array(
                'tabla' => 'bancos',
                'codigo' => $this->input->post('cod_banco'),
                'accion' => 'Editar Banco',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('bancos/ControllersBancos');
        }
    }
	
	// Método para consultar los datos de un banco por su nombre
    function consultar() {
        $result = $this->ModelsBusqueda->existe_registro('bancos', 'banco', $this->input->post('banco'));
    }
    
    // Método para activar/desactivar un banco
    function activar_desactivar($id)
    {
		//~ echo "Id: ".$id;
		
		$accion = $this->input->post('accion');
		$estatus = 1;
		
		if ($accion == 'desactivar'){
			$estatus = 0;
		}
		
		// Armamos la data a actualizar
        $data_banco = array(
			'id' => $id,
			'estatus' => $estatus,
			//~ 'fecha_update' => date('Y-m-d'),
        );
        
		// Actualizamos el banco con los datos armados
		$result = $this->ModelsBancos->actualizarBanco($data_banco);
		
		// Guardado en el módulo de auditoría
		if ($result) {
            $param = array(
                'tabla' => 'bancos',
                'codigo' => $cod,
                'accion' => $accion.' Banco',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        }
        
    }

}
