<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersUnidadMedida extends CI_Controller
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
        $this->load->model('unidad_medida/ModelsUnidadMedida');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        $this->load->model('auditoria/ModelsAuditoria');
        
    }

    function index()
    {
        $data['listar'] = $this->ModelsUnidadMedida->obtenerUnidadMedidas();
        $this->load->view('unidad_medida/lista', $data);
    }

    function registrar()
    {
        $data['detalles_lista']   = $this->ModelsBusqueda->count_all_table('unidad_medida');
        $this->load->view('unidad_medida/registrar', $data);
    }

    public function guardar()
    {

        $result = $this->ModelsUnidadMedida->insertarUnidadMedida($this->input->post());
        if ($result) {
            
            $param = array(
                'tabla' => 'Unidad de Medida',
                'codigo' => $this->input->post('cod_unidad'),
                'accion' => 'Nuevo Unidad de Medida',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('unidad_medida/ControllersUnidadMedida');
        }
    }

    function editar()
    {
        $data['id']     = $this->uri->segment(4);
        $data['editar'] = $this->ModelsUnidadMedida->obtenerUnidadMedida($data['id']);
        $this->load->view('unidad_medida/editar', $data);
    }

    function eliminar($id)
    {
        $result = $this->ModelsUnidadMedida->eliminarUnidadMedida($id);
        $param = array(
                'tabla' => 'Unidad de Medida',
                'codigo' => $id,
                'accion' => 'Eliminar Unidad de Medida',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        if ($result) {
            
            
            redirect('unidad_medida/ControllersUnidadMedida');
        }
    }

    function actualizar()
    {
        $result = $this->ModelsUnidadMedida->actualizarUnidadMedida($this->input->post());
        if ($result) {
            
            $param = array(
                'tabla' => 'Unidad de Medida',
                'codigo' => $this->input->post('cod_unidad'),
                'accion' => 'Editar Unidad de Medida',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('unidad_medida/ControllersUnidadMedida');
        }
    }

}
