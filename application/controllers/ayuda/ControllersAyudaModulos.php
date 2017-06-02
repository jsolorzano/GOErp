<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersAyudaModulos extends CI_Controller
{

    public function __construct()
    {

        @parent::__construct();

// Load form helper library
        $this->load->helper('form');

        $this->load->helper(array('url'));

// Load form validation library
        $this->load->library('form_validation');

// Load session library
        $this->load->library('session');

// Load database
        $this->load->model('auditoria/ModelsAuditoria');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        
    }
	
	// Método para visualizar información de ayuda del módulo de facturación
    function ayuda_facturacion()
    {
        $this->load->view('ayuda/ayuda_facturacion.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_presupuesto()
    {
        $this->load->view('ayuda/ayuda_presupuesto.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_ajustes()
    {
        $this->load->view('ayuda/ayuda_ajustes.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_pedidos()
    {
        $this->load->view('ayuda/ayuda_pedidos.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_productos()
    {
        $this->load->view('ayuda/ayuda_productos.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_clientes()
    {
        $this->load->view('ayuda/ayuda_clientes.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_proveedores()
    {
        $this->load->view('ayuda/ayuda_proveedores.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_bancos()
    {
        $this->load->view('ayuda/ayuda_bancos.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_estadisticas()
    {
        $this->load->view('ayuda/ayuda_estadisticas.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_saldos()
    {
        $this->load->view('ayuda/ayuda_saldos.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_movimientos()
    {
        $this->load->view('ayuda/ayuda_movimientos.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_cuentas()
    {
        $this->load->view('ayuda/ayuda_cuentas.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_libro()
    {
        $this->load->view('ayuda/ayuda_libro.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_inventario()
    {
        $this->load->view('ayuda/ayuda_inventario.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_auditoria()
    {
        $this->load->view('ayuda/ayuda_auditoria.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_usuarios()
    {
        $this->load->view('ayuda/ayuda_usuarios.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_facturas_almacen()
    {
        $this->load->view('ayuda/ayuda_facturas_almacen.php');
    }
    
    // Método para visualizar información de ayuda del módulo de facturación
    function ayuda_pedidos_almacen()
    {
        $this->load->view('ayuda/ayuda_pedidos_almacen.php');
    }
    
}
