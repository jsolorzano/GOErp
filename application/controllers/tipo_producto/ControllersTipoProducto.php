<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersTipoProducto extends CI_Controller {

    public function __construct() {

        @@parent::__construct();

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
        $this->load->model('tipo_producto/ModelsTipoProducto');
        $this->load->model('busquedas_ajax/ModelsBusqueda');  // Librería de consultas extra
    }

    function index() {
        $data['listar'] = $this->ModelsTipoProducto->obtenerTiposProductos();
        $this->load->view('tipo_producto/lista', $data);
    }

    function registrar() {
        $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('tipo_producto');
        $this->load->view('tipo_producto/registrar', $data);
    }

    public function guardar() {

        $result = $this->ModelsTipoProducto->insertarTipoProducto($this->input->post());

        if ($result) {

            $param = array(
                'tabla' => 'tipo_producto',
                'codigo' => $this->input->post('cod_tipo'),
                'accion' => 'Nuevo Tipo de cliente',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('tipo_producto/ControllersTipoProducto');
        }
    }

    function editar() {
        $data['id'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsTipoProducto->obtenerTipoProducto($data['id']);
        $this->load->view('tipo_producto/editar', $data);
    }

    function eliminar($id) {
        $result = $this->ModelsTipoProducto->eliminarTipoProducto($id);
        
        if ($result) {
			$param = array(
                'tabla' => 'tipo_producto',
                'codigo' => $id,
                'accion' => 'Eliminar Tipo de producto',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        }
    }

    function actualizar() {
        $result = $this->ModelsTipoProducto->actualizarTipoProducto($this->input->post());

        if ($result) {

            $param = array(
                'tabla' => 'tipo_producto',
                'codigo' => $this->input->post('cod_tipo'),
                'accion' => 'Editar Tipo de cliente',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('tipo_producto/ControllersTipoProducto');
        }
    }

    function consultar() {
        $result = $this->ModelsBusqueda->existe_registro('tipo_producto', 'tipo_producto', $this->input->post('tipo_producto'));
    }

}
