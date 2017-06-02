<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersEstadisticas extends CI_Controller
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
        $this->load->model('estadisticas/ModelsEstadisticas');
        //~ $this->load->model('clientes/ModelsClientes');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        $this->load->model('usuarios/Usuarios_model');
        
    }

    function index()
    {
        $this->load->view('estadisticas/estadisticas');
    }
    
    // Consulta de auditorías
    function obtenerVentas()
    {
		$rango = $this->input->post('rango');
		$fecha = $this->input->post('fecha');
		// Preparamos la fecha
		if($rango == '1'){
			$fecha = explode('/',$fecha);
			$fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
		}else if($rango == '2'){
			$fecha = explode('/',$fecha);
			$fecha = $fecha[1]."-".$fecha[0];
		}else if($rango == '3'){
			$fecha = $fecha;
		}
		// Consultamos las ventas pagadas de la fecha indicada
        $ventas = $this->ModelsEstadisticas->obtenerVentas($rango,$fecha);  // Datos de las ventas específicas
        
        if(count($ventas) > 0){
			//~ print_r($ventas);
			// Armamos una lista de códigos de facturas coincidentes
			//~ $cod_facts = array();
			$cod_facts = "";
			foreach($ventas as $venta){
				//~ array_push($cod_facts,$venta->codfactura);  // Añade nuevos elementos al arreglo
				$cod_facts .= "'".$venta->codfactura."',"; 
			}
			$cod_facts = substr($cod_facts,0,-1);
			//~ echo $cod_facts;
			$ventas_productos = $this->ModelsEstadisticas->obtenerVentasEsp($cod_facts);
			//~ print_r($ventas_productos);
			echo json_encode($ventas_productos, JSON_NUMERIC_CHECK);
		}else{
			echo "Vacio";
		}
        
    }
    
    
    // Generación de reporte de auditoría
    function pdf_estadisticas($rango,$fecha)
    {
        $data['auditoria'] = $this->ModelsAuditoria->obtenerAuditoriasEsp($usuario,$desde,$hasta);  // Datos generales de la factura
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
