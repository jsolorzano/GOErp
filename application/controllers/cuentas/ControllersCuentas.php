<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersCuentas extends CI_Controller {

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
        $this->load->model('cuentas/ModelsCuentas');
        $this->load->model('bancos/ModelsBancos');
        $this->load->model('busquedas_ajax/ModelsBusqueda');  // Librería de consultas extra
    }

    function index() {
		$data['list_bancos'] = $this->ModelsBancos->obtenerBancos();  // Bancos en general 
        $data['listar'] = $this->ModelsCuentas->obtenerCuentas();
        $this->load->view('cuentas/lista', $data);
    }

    function registrar() {
        $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('cuentas');
        $data['list_bancos'] = $this->ModelsCuentas->obtenerBancosActivos();  // Sólo bancos activos
        $this->load->view('cuentas/registrar', $data);
    }

    public function guardar() {
		
		// Preparamos el siguiente id para evitar errores luegos de cargar manuales
		$ultimo_id = $this->ModelsBusqueda->count_all_table('cuentas');
		
		// Armamos la data a registrar
        $data_cuenta = array(
			'id' => $ultimo_id+1,
			'codigo' => $this->input->post('codigo'),
			'cod_banco' => $this->input->post('cod_banco'),
			'cuenta' => $this->input->post('cuenta'),
			'tipo' => $this->input->post('tipo'),
			'monto_inicial' => $this->input->post('monto_inicial'),
			'monto_total' => $this->input->post('monto_total'),
			'fecha_create' => date('Y-m-d'),
			'estatus' => $this->input->post('estatus'),
			'user_create_id' => $this->session->userdata['logged_in']['id'],
			//~ 'fecha_update' => date('Y-m-d'),
        );

        $result = $this->ModelsCuentas->insertarCuenta($data_cuenta);

        if ($result) {

            $param = array(
                'tabla' => 'cuentas',
                'codigo' => $this->input->post('codigo'),
                'accion' => 'Nueva cuenta',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('cuentas/ControllersCuentas');
        }
    }

    function editar() {
        $data['id'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsCuentas->obtenerCuenta($data['id']);
        //~ print_r($data['editar']);
        $data['list_bancos'] = $this->ModelsCuentas->obtenerBancosActivos();  // Sólo bancos activos
        $this->load->view('cuentas/editar', $data);
    }

    function eliminar($id) {
        $result = $this->ModelsCuentas->eliminarCuenta($id);
        $param = array(
                'tabla' => 'cuentas',
                'codigo' => $id,
                'accion' => 'Eliminar cuenta',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        if ($result) {

            
            redirect('cuentas/ControllersCuentas');
        }
    }

    function actualizar() {
		
		// Armamos la data a actualizar
        $data_cuenta = array(
			'id' => $this->input->post('id'),
			'codigo' => $this->input->post('codigo'),
			'cod_banco' => $this->input->post('cod_banco'),
			'cuenta' => $this->input->post('cuenta'),
			'tipo' => $this->input->post('tipo'),
			'monto_inicial' => $this->input->post('monto_inicial'),
			'monto_total' => $this->input->post('monto_total'),
			//~ 'fecha_create' => date('Y-m-d'),
			'fecha_update' => date('Y-m-d'),
			//~ 'estatus' => $this->input->post('estatus'),
			//~ 'user_create_id' => $this->session->userdata['logged_in']['id'],
        );
        
        $result = $this->ModelsCuentas->actualizarCuenta($data_cuenta);

        if ($result) {

            $param = array(
                'tabla' => 'cuentas',
                'codigo' => $this->input->post('codigo'),
                'accion' => 'Editar cuenta',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('cuentas/ControllersCuentas');
        }
    }
	
	// Método para consultar los datos de una cuenta por su nombre
    function consultar() {
        $result = $this->ModelsCuentas->existe_cuenta('cuentas', 'cuenta', 'cod_banco', $this->input->post('cuenta'), $this->input->post('cod_banco'));
    }
    
    // Método para activar/desactivar una cuenta
    function activar_desactivar($id)
    {
		//~ echo "Id: ".$id;
		
		$accion = $this->input->post('accion');
		$estatus = 1;
		
		if ($accion == 'desactivar'){
			$estatus = 0;
		}
		
		// Armamos la data a actualizar
        $data_cuenta = array(
			'id' => $id,
			'estatus' => $estatus,
			//~ 'fecha_update' => date('Y-m-d'),
        );
        
		// Actualizamos la cuenta con los datos armados
		$result = $this->ModelsCuentas->actualizarCuenta($data_cuenta);
		
		// Guardado en el módulo de auditoría
		if ($result) {
            $param = array(
                'tabla' => 'cuentas',
                'codigo' => $cod,
                'accion' => $accion.' cuenta',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        }
        
    }

}