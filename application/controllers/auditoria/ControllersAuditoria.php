<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersAuditoria extends CI_Controller
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
        $this->load->model('auditoria/ModelsAuditoria');
        $this->load->model('clientes/ModelsClientes');
        $this->load->model('topologia/ModelsEstado');
        $this->load->model('topologia/ModelsMunicipio');
        $this->load->model('topologia/ModelsParroquia');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        $this->load->model('tipo_cliente/ModelsTipoCliente');
        $this->load->model('usuarios/Usuarios_model');
        
    }

    function index()
    {
        $data['listar'] = $this->ModelsAuditoria->obtenerAuditorias();
        $data['usuarios'] = $this->Usuarios_model->obtenerUsuarios();
        $this->load->view('auditoria/auditoria', $data);
    }
    
    // Consulta de auditorías
    function obtenerAuditorias($usuario,$accion,$desde,$hasta)
    {
        $data = $this->ModelsAuditoria->obtenerAuditoriasEsp($usuario,$accion,$desde,$hasta);  // Datos generales de la factura
        
        return $data;
    }
    
    
    // Generación de reporte de auditoría
    function pdf_auditoria($usuario,$accion,$desde,$hasta)
    {
        $data['auditoria'] = $this->ModelsAuditoria->obtenerAuditoriasEsp($usuario,$accion,$desde,$hasta);  // Datos generales de la factura
		if ($usuario != "xxx"){
			$data['usuario'] = $this->ModelsBusqueda->obtenerRegistro('usuarios', 'id', $usuario);  // Usuario
		}else{
			$data['usuario'] = "xxx";
		}
		$data['desde'] = $desde;  // Fecha de inicio
		$data['hasta'] = $hasta;  // Fecha final
		//~ 
		//~ $data['productos_servicios'] = $this->ModelsFacturar->obtenerProductosServicios($data['factura']->codfactura);  // Productos/Servicios asociados a la factura
		
		$this->load->view('auditoria/pdf/reporte_auditoria', $data);
		
    }    
    
}
