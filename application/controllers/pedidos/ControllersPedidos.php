<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersPedidos extends CI_Controller
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
        $this->load->model('pedidos/ModelsPedidos');
        $this->load->model('productos/ModelsProductos');
        $this->load->model('cuentas/ModelsCuentas');
        $this->load->model('bancos/ModelsBancos');
        $this->load->model('movimientos/ModelsMovimientos');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        
    }

    function index()
    {
		$data['cuentas'] = $this->ModelsCuentas->obtenerCuentas();
		$data['bancos'] = $this->ModelsBancos->obtenerBancos();
        $data['listar'] = $this->ModelsPedidos->obtenerPedidos();
        //~ $data['list_iva'] = $this->ModelsImpuesto->obtenerImpuestos();
        //~ $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('pedidos');
        //~ $data['cod_pedido'] = '';
        //~ if ($data['ultimo_id'] > 0){
			//~ $data['cod_pedido'] = $this->ModelsPedidos->obtenerpedido($data['ultimo_id']);
		//~ }
        $this->load->view('pedidos/lista.php', $data);
    }
    function pedido()
    {

        $data['listar'] = $this->ModelsPedidos->obtenerProveedores();
        $data['hora']= time();
        $data['productos'] = $this->ModelsProductos->obtenerProductos();
        $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('pedidos');
        $data['cod_pedido'] = '';
        if ($data['ultimo_id'] > 0){
			$data['cod_pedido'] = $this->ModelsPedidos->obtenerPedido($data['ultimo_id']);
		}
        $this->load->view('pedidos/pedido', $data);
    }

    public function guardar()
    {	
		$ultimo_id = $this->ModelsBusqueda->count_all_table('pedidos');
		// Preparamos los datos generales del pedido
		$data_pedido = array(
			'id' => $ultimo_id + 1,
			'codpedido' => trim($this->input->post('codpedido')),
			//~ 'pre_cod_pedido' => trim($this->input->post('pre_cod_pedido')),
			'codproveedor' => $this->input->post('codproveedor'),
			'proveedor' => $this->input->post('proveedor'),
			'observaciones' => $this->input->post('observaciones'),
			'estado' => 1,
			'fecha_emision' => date("Y-m-d"),
			'hora_emision' => date("h:i:s a"),
			'num_control' => (string)rand(),
        );
        
		// Guardamos los datos generales del pedido
		$result = $this->ModelsPedidos->insertar($data_pedido);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'pedidos',
                'codigo' => trim($this->input->post('codpedido')),
                'accion' => 'Nuevo pedido',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('presupuesto/ControllersPresupuesto');
        }
        
        // Guardamos los productos asociados a la pedido
        $data_pedido_ps = $this->input->post('data');
        
        foreach ($data_pedido_ps as $campos){
            //~ print_r($campos);
            //Construcción del correlativo para el nuevo registro
            $ultimo_id = $this->ModelsBusqueda->count_all_table('pedidos_ps');
            //~ echo $ultimo_id;
            $correlativo_ps = str_pad($ultimo_id+1, 8, "0", STR_PAD_LEFT);  //Rellenamos con ceros a la izquierda
            //~ echo $correlativo_ps;
            
            // Registro del nuevo producto
            $data_f_ps = array(
				'id' => $ultimo_id+1,
				'codpedidops' => $correlativo_ps,
				'codpedido' => $campos['cod_pedido'],
				'tipo' => 1,
				'cod_producto_servicio' => $campos['id'],
				'producto_servicio' => $campos['id_servicio'],
				'cantidad' => $campos['cantidad'],
            );
            
			// Guardamos los datos de los productos de la pedido
            $result = $this->ModelsPedidos->insertar_ps($data_f_ps);
        }
		//~ echo json_encode($data_pedido_ps);
		//~ 

    }
    
    
    function editar()
    {
        $data['codpedido'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsBusqueda->obtenerRegistro('pedidos', 'codpedido', $data['codpedido']);  // Datos de la pedido
        $data['listar'] = $this->ModelsPedidos->obtenerProveedores();  // Lista de proveedores
        $data['hora']= time();  //Hora actual
        $data['productos'] = $this->ModelsProductos->obtenerProductos();
        $data['listar_ps'] = $this->ModelsPedidos->obtenerProductosServicios($data['codpedido']);  // Lista de productos
        
        $this->load->view('pedidos/editar', $data);
    }


    function actualizar()
    
    {
        // Preparamos los datos generales de la pedido
		$regs_eliminar = $this->input->post('codigos_des');  // Productos a desvincular de la pedido
		
		$data_pedido = array(
			'codpedido' => trim($this->input->post('codpedido')),
			//~ 'pre_cod_pedido' => trim($this->input->post('pre_cod_pedido')),
			'codproveedor' => $this->input->post('codproveedor'),
			'proveedor' => $this->input->post('proveedor'),
			'observaciones' => $this->input->post('observaciones'),
			'estado' => 1,
        );
        
		// Actualizamos los datos generales de la pedido
		$result = $this->ModelsPedidos->actualizarPedido($data_pedido);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'pedidos',
                'codigo' => trim($this->input->post('codpedido')),
                'accion' => 'Editar pedido',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('presupuesto/ControllersPresupuesto');
        }
        
        $data_pedido_ps = $this->input->post('data');
        
        //~ print_r($data_pedido_ps);
        
        // Verificamos si hay productos para asociar (registrar en pedidos_ps)
        foreach ($data_pedido_ps as $campos){
			
			// Primero validamos si el producto ya tiene código, si es así entonces es que ya está asociado
			if ($campos['cod_f_ps'] == ""){
				echo "producto no existente";
				//Construcción del correlativo para el nuevo registro
				$ultimo_id = $this->ModelsBusqueda->count_all_table('pedidos_ps');
				//~ echo $ultimo_id;
				$correlativo_ps = str_pad($ultimo_id+1, 8, "0", STR_PAD_LEFT);  // Rellenamos con ceros a la izquierda hasta completar 8 dígitos
				//~ echo $correlativo_ps;
				
				// Registro del nuevo producto
				$data_f_ps = array(
					'id' => $ultimo_id+1,
					'codpedidops' => $correlativo_ps,
					'codpedido' => $campos['cod_pedido'],
					'tipo' => 1,
					'cod_producto_servicio' => $campos['cod_ps'],
					'producto_servicio' => $campos['nom_ps'],
					'cantidad' => $campos['cantidad'],
				);
				
				// Guardamos los datos de los nuevos productos de la pedido
				$result = $this->ModelsPedidos->insertar_ps($data_f_ps);
				
			}else{
				echo "producto existente";
			}
        }
        
        // Verificamos si hay productos para eliminar
        if($regs_eliminar != ''){
			$regs_eliminar = explode(",",$regs_eliminar);
			
			// Desvinculamos (eliminamos de la tabla pedidos_ps)
			foreach ($regs_eliminar as $reg){
				//~ echo "Código: ".$reg;
				
				// Eliminamos la asociación de la tabla pedidos_ps
				$result = $this->ModelsPedidos->eliminarProductoServicio($reg);
			}
		}
        
    }
    
    // Método para anular o activar una pedido
    function anular($cod)
    {
		$accion = $this->input->post('accion');
		$estado = 1;
		
		if ($accion == 'anular'){
			$estado = 3;
		}
		
		// Armamos la data a actualizar
        $data_pedido = array(
			'codpedido' => $cod,
			'estado' => $estado,
			'motivo_anulacion' => $this->input->post('motivo'),
        );
        
		// Actualizamos la pedido con los datos armados
		$result = $this->ModelsPedidos->actualizarpedido($data_pedido);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'pedidos',
                'codigo' => $cod,
                'accion' => 'Anular pedido',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('presupuesto/ControllersPresupuesto');
        }
		
    }
    
    
    // Método para aprobar o activar un pedido
    function aprobar($cod)
    {
		$accion = $this->input->post('accion');
		$estado = 1;
		
		if ($accion == 'aprobar'){
			$estado = 2;
		}
		
		// Armamos la data a actualizar
        $data_pedido = array(
			'codpedido' => $cod,
			'estado' => $estado,
        );
        
		// Actualizamos el pedido con los datos armados
		$result = $this->ModelsPedidos->actualizarPedido($data_pedido);
		
		// Guardado en el módulo de auditoría y cuentas
		if ($result) {
			// Guardado en el módulo de auditoría
            $param = array(
                'tabla' => 'pedidos',
                'codigo' => $cod,
                'accion' => 'aprobar pedido',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
        }
		
    }
    
    // Método para generar las pedidos en pdf
    function pdf_pedido($cod)
    {
        $data['pedido'] = $this->ModelsPedidos->obtenerPedidoCod($cod);  // Datos generales de la pedido
        $data['proveedor'] = $this->ModelsPedidos->obtenerProveedorCod($data['pedido']->codproveedor);  // Datos del proveedor
		
        //~ print_r($data['pedido']);
        //~ echo $data['pedido']->codpedido;
        $data['productos'] = $this->ModelsPedidos->obtenerProductosServicios($data['pedido']->codpedido);  // Productos asociados a la pedido
        //~ foreach ($data['productos_servicios'] as $campos){
			//~ print_r($campos);
		//~ }
        
        $this->load->view('pedidos/pdf/reporte_pedido', $data);
    }
}
