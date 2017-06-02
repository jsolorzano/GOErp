<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersAlmacen extends CI_Controller
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
        $this->load->model('almacen/ModelsAlmacen');
        $this->load->model('factura/ModelsFacturar');
        $this->load->model('pedidos/ModelsPedidos');
        $this->load->model('productos/ModelsProductos');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        
    }

    function index()
    {
        $this->load->view('index.php', $data);
    }
    
    // Método para listar las facturas aprobados
    function lista_facturas()
    {
        $data['listar_facturas'] = $this->ModelsAlmacen->obtenerFacturas();
        $this->load->view('almacen/lista.php', $data);
    }
    
    // Método para listar los pedidos aprobados
    function lista_pedidos()
    {
        $data['listar_pedidos'] = $this->ModelsAlmacen->obtenerPedidos();
        $this->load->view('almacen/lista_pedidos.php', $data);
    }
    
    // Método para entregar una factura
    function entregar($cod)
    {
		//~ echo "Código: ".$cod;
		//~ echo "Acción: ".$this->input->post('accion');
		//~ echo "condicion pago: ". $this->input->post('condicion');
		
		$accion = $this->input->post('accion');
		$estado = 1;
		
		if ($accion == 'entregar'){
			$estado = 4;
		}
		
		$verificar = $this->ModelsAlmacen->verificarFactura($this->input->post('num_control'), $cod);
		
		//~ print_r($verificar);
		
		if($verificar == 0){
			echo 0;
		}else{
			// Obtenemos los datos del usuario logueado
			$datos_usuario = $this->ModelsBusqueda->obtenerRegistro('usuarios', 'id', $this->session->userdata['logged_in']['id']);
			$usuario_entrega = "".$datos_usuario->first_name ." ". $datos_usuario->last_name ." (".$datos_usuario->username .")";
			
			// Armamos la data a actualizar
			$data_factura = array(
				'codfactura' => $cod,
				'estado' => $estado,
				'firma_almacen' => $usuario_entrega,
				'fecha_entrega' => date('Y-m-d'),
				'hora_entrega' => date("h:i:s a"),
			);
			
			// Actualizamos la factura con los datos armados
			$result = $this->ModelsFacturar->actualizarFactura($data_factura);
			
			// Guardado en el módulo de auditoría
			if ($result) {

				$param = array(
					'tabla' => 'facturas',
					'codigo' => $cod,
					'accion' => 'Entregar Factura',
					'fecha' => date('Y-m-d'),
					'hora' => date("h:i:s a"),
					'usuario' => $this->session->userdata['logged_in']['id'],
				);
				$this->ModelsAuditoria->add($param);
				//~ redirect('presupuesto/ControllersPresupuesto');
				
				// PROCESO DE DESCUENTO EN EL STOCK DE LOS PRODUCTOS
				// Consultamos los productos asociados a la factura
				$productos_servicios = $this->ModelsFacturar->obtenerProductosServicios($cod);  // Productos asociados a la factura
				
				//~ print_r($productos_servicios);
				
				// Recorremos los productos
				foreach ($productos_servicios as $campos){
					
					// Primero validamos si el registro es un producto
					if ($campos->tipo == 1){
						//~ echo "El registro es un producto";
						//~ echo "Código del producto: ".$campos->cod_producto_servicio;
						
						// Obtenemos la existencia y el stock requerido actuales del producto
						$datos_producto = $this->ModelsBusqueda->obtenerRegistro('producto', 'codigo', $campos->cod_producto_servicio);
						
						// Preparamos la nueva existencia y stock requerido para el producto
						$id_produc = $datos_producto->id;
						$nueva_existencia = (int)$datos_producto->existencia-(int)$campos->cantidad;
						$nuevo_stock_req = (int)$datos_producto->stock_max-(int)$nueva_existencia;
						
						if ($nueva_existencia < 0){
							$nueva_existencia = 0;
						}
						
						if ($nuevo_stock_req < 0){
							$nuevo_stock_req = 0;
						}
						
						// Asignamos la nueva existencia y stock requerido para el producto
						$data_producto = array(
							'id' => $id_produc,
							'existencia' => $nueva_existencia,
							'stock_req' => $nuevo_stock_req,
						);
						
						// Actualizamos el stock del producto
						$result = $this->ModelsProductos->actualizarProducto($data_producto);
					}
				}
			}
		}
		
    }
    
    // Método para ingresar un pedido
    function ingresar($cod)
    {
		//~ echo "Código: ".$cod;
		//~ echo "Acción: ".$this->input->post('accion');
		//~ echo "condicion pago: ". $this->input->post('condicion');
		
		$accion = $this->input->post('accion');
		$estado = 1;
		
		if ($accion == 'ingresar'){
			$estado = 4;
		}
		
		$verificar = $this->ModelsAlmacen->verificarPedido($this->input->post('num_control'), $cod);
		
		//~ print_r($verificar);
		
		if($verificar == 0){
			echo 0;
		}else{
			// Obtenemos los datos del usuario logueado
			$datos_usuario = $this->ModelsBusqueda->obtenerRegistro('usuarios', 'id', $this->session->userdata['logged_in']['id']);
			$usuario_ingresa = "".$datos_usuario->first_name ." ". $datos_usuario->last_name ." (".$datos_usuario->username .")";
			
			// Armamos la data a actualizar
			$data_pedido = array(
				'codpedido' => $cod,
				'estado' => $estado,
				'firma_almacen' => $usuario_ingresa,
				'fecha_ingreso' => date('Y-m-d'),
				'hora_ingreso' => date("h:i:s a"),
			);
			
			// Actualizamos el pedido con los datos armados
			$result = $this->ModelsPedidos->actualizarPedido($data_pedido);
			
			// Guardado en el módulo de auditoría
			if ($result) {

				$param = array(
					'tabla' => 'pedidos',
					'codigo' => $cod,
					'accion' => 'Ingresar Pedido',
					'fecha' => date('Y-m-d'),
					'hora' => date("h:i:s a"),
					'usuario' => $this->session->userdata['logged_in']['id'],
				);
				$this->ModelsAuditoria->add($param);
				
				// PROCESO DE ADICIÓN EN EL STOCK DE LOS PRODUCTOS
				// Consultamos los productos asociados al pedido
				$productos_servicios = $this->ModelsPedidos->obtenerProductosServicios($cod);  // Productos asociados al pedido
				
				//~ print_r($productos_servicios);
				
				// Recorremos los productos
				foreach ($productos_servicios as $campos){
					
					// Primero validamos si el registro es un producto
					if ($campos->tipo == 1){
						//~ echo "El registro es un producto";
						//~ echo "Código del producto: ".$campos->cod_producto_servicio;
						
						// Obtenemos la existencia y el stock requerido actuales del producto
						$datos_producto = $this->ModelsBusqueda->obtenerRegistro('producto', 'codigo', $campos->cod_producto_servicio);
						
						// Preparamos la nueva existencia y stock requerido para el producto
						$id_produc = $datos_producto->id;
						$nueva_existencia = (int)$datos_producto->existencia+(int)$campos->cantidad;
						$nuevo_stock_req = (int)$datos_producto->stock_max-(int)$nueva_existencia;
						
						if ($nueva_existencia < 0){
							$nueva_existencia = 0;
						}
						
						if ($nuevo_stock_req < 0){
							$nuevo_stock_req = 0;
						}
						
						// Asignamos la nueva existencia y stock requerido para el producto
						$data_producto = array(
							'id' => $id_produc,
							'existencia' => $nueva_existencia,
							'stock_req' => $nuevo_stock_req,
						);
						
						// Actualizamos el stock del producto
						$result = $this->ModelsProductos->actualizarProducto($data_producto);
					}
				}
			}
		}
		
    }
    
    // Método para ejecutar el contador de facturas verificadas por entregar
    function contar()
    {
		$this->ModelsAlmacen->obtenerNumFacturas();
	}
	
	// Método para ejecutar el contador de pedidos aprobados por ingresar
    function contar_pedidos()
    {
		$this->ModelsAlmacen->obtenerNumPedidos();
	}
    
    // Método para la generación del reporte de la facturas en almacen
    function pdf_factura($cod)
    {
        $data['factura'] = $this->ModelsFacturar->obtenerFacturaCod($cod);  // Datos generales de la factura
        if ($data['factura']->codcliente != 'PUNTOVENTA'){
			$data['cliente'] = $this->ModelsFacturar->obtenerClienteCod($data['factura']->codcliente);  // Datos del cliente
		}else{
			$data['cliente'] = "PUNTOVENTA";  // Datos del cliente
		}
        $data['impuesto'] = $this->ModelsBusqueda->obtenerRegistro('impuesto', 'id', $data['factura']->iva);  // Datos del impuesto
        //~ print_r($data['factura']);
        //~ echo $data['factura']->codfactura;
        $data['productos_servicios'] = $this->ModelsFacturar->obtenerProductosServicios($data['factura']->codfactura);  // Productos asociados a la factura
        //~ foreach ($data['productos_servicios'] as $campos){
			//~ print_r($campos);
		//~ }
        
        $this->load->view('almacen/pdf/reporte_factura', $data);
    }
    
    // Método para la generación del reporte en pdf del pedido
    function pdf_pedido($cod)
    {
        $data['pedido'] = $this->ModelsPedidos->obtenerPedidoCod($cod);  // Datos generales del pedido
        if ($data['pedido']->codproveedor != 'PUNTOVENTA'){
			$data['proveedor'] = $this->ModelsPedidos->obtenerProveedorCod($data['pedido']->codproveedor);  // Datos del proveedor
		}else{
			$data['proveedor'] = "PUNTOVENTA";  // Datos del proveedor
		}
        //~ print_r($data['pedido']);
        //~ echo $data['pedido']->codpedido;
        $data['productos'] = $this->ModelsPedidos->obtenerProductosServicios($data['pedido']->codpedido);  // Productos asociados al pedido
        //~ foreach ($data['productos_servicios'] as $campos){
			//~ print_r($campos);
		//~ }
        
        $this->load->view('almacen/pdf/reporte_pedido', $data);
    }
}
