<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersProductos extends CI_Controller {

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
        $this->load->model('productos/ModelsProductos');
        $this->load->model('tipo_producto/ModelsTipoProducto');
        $this->load->model('proveedores/ModelsProveedores');
        $this->load->model('unidad_medida/ModelsUnidadMedida');
        $this->load->model('impuesto/ModelsImpuesto');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        $this->load->model('ganancia/ModelsGanancia');
    }

    function index() {
		$usuario = $this->session->userdata['logged_in']['tipo_usuario'];
		if($usuario == 'Comercializacion'){
			$data['listar'] = $this->ModelsProductos->obtenerProductosTerminales();  // Listado de productos para terminales
			$data['listar2'] = $this->ModelsProductos->obtenerProductosTerminales2();  // Listado de productos para terminales		
		}else if($usuario == 'Administrador'){
			$data['listar'] = $this->ModelsProductos->obtenerProductos();  // Listado general de productos
		}else{
			$data['listar'] = $this->ModelsProductos->obtenerProductosServicios();  // Listado de productos para servicios
		}
		
        $this->load->view('productos/lista', $data);
    }

    function registrar() {
        $data['detalles_lista'] = $this->ModelsBusqueda->count_all_table('producto');
        $data['list_prod'] = $this->ModelsTipoProducto->obtenerTiposProductos();
        $data['list_prov'] = $this->ModelsProveedores->obtenerProveedores();
        $data['list_um'] = $this->ModelsUnidadMedida->obtenerUnidadMedidas();
        $data['list_iva'] = $this->ModelsImpuesto->obtenerImpuestos();
        $data['ganancia'] = $this->ModelsGanancia->obtenerGanancias();
        $this->load->view('productos/registrar', $data);
    }

    public function guardar() {

        $result = $this->ModelsProductos->insertar($this->input->post());

        if ($result) {

            $param = array(
                'tabla' => 'producto',
                'codigo' => $this->input->post('codigo'),
                'accion' => 'Nuevo Producto',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //redirect('productos/ControllersProductos');
        }
    }

    function editar() {
        $data['id'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsProductos->obtenerProducto($data['id']);
        $data['list_prod'] = $this->ModelsTipoProducto->obtenerTiposProductos();
        $data['list_prov'] = $this->ModelsProveedores->obtenerProveedores();
        $data['list_um'] = $this->ModelsUnidadMedida->obtenerUnidadMedidas();
        $data['list_iva'] = $this->ModelsImpuesto->obtenerImpuestos();
        $data['ganancia'] = $this->ModelsGanancia->obtenerGanancias();
        $this->load->view('productos/editar', $data);
    }

    function eliminar($id) {
        $result = $this->ModelsProductos->eliminarProducto($id);
        
        if ($result) {
            $param = array(
                'tabla' => 'producto',
                'codigo' => $id,
                'accion' => 'Eliminar Producto',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        }
    }

    function actualizar() {


        $result = $this->ModelsProductos->actualizarProducto($this->input->post());

        if ($result) {
            $param = array(
                'tabla' => 'producto',
                'codigo' => $this->input->post('codigo'),
                'accion' => 'Editar Producto',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            redirect('proveedores/ControllersProveedores');
        }
    }

    // Método para comprar (añadir existencia) 
    function comprar($cod) {
        //~ echo "Código: ".$cod;
        //~ echo "condicion pago: ". $this->input->post('condicion');
        //Consultamos los datos pertenecientes al producto con el código especificado
        $data = $this->ModelsBusqueda->obtenerRegistro('producto', 'codigo', $cod);

        $id = $data->id;
        $existencia = (int) $this->input->post('cantidad') + (int) $data->existencia;
        if($this->input->post('n_precio') != ''){
			$precio_unitario = $this->input->post('n_precio');
		}else{
			$precio_unitario = $data->precio_unitario;
		}
        
        $ganancia = $data->ganancia;  // Porcentaje de ganancia
        $monto_ganancia = 0;
        $iva = $data->iva;
        $precio_total = $data->precio_total;
        $monto_iva = $data->monto_iva;
        $stock_req = $data->stock_req;
        $stock_max = $data->stock_max;

        // Cálculo del stock requerido
        if ($existencia >= $stock_max) {
            $stock_req = 0;
        } else {
            $stock_req = (int) $stock_max - (int) $existencia;
        }

        // Si el nuevo precio unitario es mayor al existente se calcula nuevamente el precio_total y el monto_iva
        if ($precio_unitario > $data->precio_unitario) {
            // Cálculo del nuevo precio_total
            $monto_ganancia = (float) $precio_unitario * (float) $ganancia / 100;  // Monto del porcentaje de ganancia 
            $precio_total = (float) $precio_unitario + $monto_ganancia;

            // Cálculo del monto del iva
            // Primero consultamos el valor del iva aplicado
            $data_iva = $this->ModelsBusqueda->obtenerRegistro('impuesto', 'id', $iva);
            $valor_iva = $data_iva->valor;
            $monto_iva = $precio_total * $valor_iva / 100;
        } else {
            $precio_unitario = $data->precio_unitario;
        }
		
		if ($existencia <= $stock_max) {
            // Armamos la data a actualizar
			$data_producto = array(
				'id' => $id,
				'existencia' => $existencia,
				'stock_req' => $stock_req,
				'precio_unitario' => $precio_unitario,
				'precio_total' => $precio_total,
				'monto_iva' => $monto_iva,
			);

			// Actualizamos el producto con los datos armados
			$result = $this->ModelsProductos->actualizarProducto($data_producto);
        } else {
            echo "stock superado";
        }
		
    }
    
    // Generación del reporte de inventario de productos para servicios
    function pdf_inventario_general()
    {
        $data['productos'] = $this->ModelsProductos->obtenerProductosServicios();  // Lista de productos
        
        print_r($data['productos']);
        
        $this->load->view('productos/pdf/reporte_inventario_servicios', $data);
    }
    
    // Generación de reporte de inventario de productos para terminales
    function pdf_inventario_terminales()
    {
        $data['productos'] = $this->ModelsProductos->obtenerProductosTerminales();  // Lista de productos	
        
        $this->load->view('productos/pdf/reporte_inventario_terminales', $data);
    }

}
