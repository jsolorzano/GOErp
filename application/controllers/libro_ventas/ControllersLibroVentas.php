<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersLibroVentas extends CI_Controller
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
        $this->load->model('libro_ventas/ModelsLibroVentas');
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
        $data['listar'] = $this->ModelsLibroVentas->obtenerVentas();
        //~ $data['usuarios'] = $this->Usuarios_model->obtenerUsuarios();
        $this->load->view('libro_ventas/libro_ventas', $data);
    }
    
    // Consulta de ventas
    function obtenerVentas($desde,$hasta)
    {
        $data = $this->ModelsLibroVentas->obtenerVentasEsp($desde,$hasta);
        
        return $data;
    }
    
    
    // Generación de reporte de ventas
    function pdf_libroventas($desde,$hasta)
    {
		// Datos de las ventas
        $data['ventas'] = $this->ModelsLibroVentas->obtenerVentasEsp($desde,$hasta);  // Datos de las ventas específicas
        $data['clientes'] = $this->ModelsClientes->obtenerClientes();  // Lista de clientes
        //~ $data['impuesto'] = $this->ModelsBusqueda->obtenerRegistro('impuesto', 'id', $data['ventas']->iva);  // Datos del impuesto
        $data['desde'] = $desde;  // Fecha de inicio
        $data['hasta'] = $hasta;  // Fecha final
        
        // Datos de los ajustes
		// Primero generamos la lista de códigos de facturas
        $list_facturas = "";
        foreach($data['ventas'] as $factura){
			$list_facturas .= "'".$factura->codfactura."',";
		}
		$list_facturas = "(".substr($list_facturas,0,-1).")";
		echo "Lista de facturas: ".$list_facturas;
		if($list_facturas != "()"){
			$data['ajustes'] = $this->ModelsLibroVentas->obtenerAjustesEsp($list_facturas);  // Datos de los ajustes correspondientes a la lista de facturas
		}else{
			$data['ajustes'] = "";
		}
        $this->load->view('libro_ventas/pdf/reporte_libro_ventas', $data);
    }    
    
}
