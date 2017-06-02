<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersTipoProveedor extends CI_Controller {

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
        $this->load->model('tipo_proveedor/ModelsTipoProveedor');
        $this->load->model('busquedas_ajax/ModelsBusqueda');  // Librería de consultas extra
    }

    function index() {
        $data['listar'] = $this->ModelsTipoProveedor->obtenerTiposProveedores();
        $this->load->view('tipo_proveedor/lista', $data);
    }

    function registrar() {
        $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('tipo_proveedor');
        $this->load->view('tipo_proveedor/registrar', $data);
    }

    public function guardar() {

        $result = $this->ModelsTipoProveedor->insertarTipoProveedor($this->input->post());

        if ($result) {

            $param = array(
                'tabla' => 'tipo_proveedor',
                'codigo' => $this->input->post('cod_tipo'),
                'accion' => 'Nuevo Tipo de Proveedor',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('tipo_proveedor/ControllersTipoProveedor');
        }
    }

    function editar() {
        $data['id'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsTipoProveedor->obtenerTipoProveedor($data['id']);
        $this->load->view('tipo_proveedor/editar', $data);
    }

    function eliminar($cod) {
        $result = $this->ModelsTipoProveedor->eliminarTipoProveedor($cod);
        $param = array(
                'tabla' => 'tipo_proveedor',
                'codigo' => $cod,
                'accion' => 'Eliminar Tipo de Proveedor',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        if ($result) {

            
            redirect('tipo_proveedor/ControllersTipoProveedor');
        }
    }

    function actualizar() {
        $result = $this->ModelsTipoProveedor->actualizarTipoProveedor($this->input->post());

        if ($result) {

            $param = array(
                'tabla' => 'tipo_proveedor',
                'codigo' => $this->input->post('cod_tipo'),
                'accion' => 'Editar Tipo de Proveedor',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('tipo_proveedor/ControllersTipoProveedor');
        }
    }

    function consultar() {
        $result = $this->ModelsBusqueda->existe_registro('tipo_proveedor', 'tipo_proveedor', $this->input->post('tipo_proveedor'));
    }

}
