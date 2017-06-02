<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControllersFacturar extends CI_Controller
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
        $this->load->model('factura/ModelsFacturar');
        $this->load->model('productos/ModelsProductos');
        $this->load->model('impuesto/ModelsImpuesto');
        $this->load->model('cuentas/ModelsCuentas');
        $this->load->model('bancos/ModelsBancos');
        $this->load->model('movimientos/ModelsMovimientos');
        $this->load->model('ajustes/ModelsAjustes');
        $this->load->model('busquedas_ajax/ModelsBusqueda');
        
    }

    function index()
    {
		$data['cuentas'] = $this->ModelsCuentas->obtenerCuentas();
		$data['bancos'] = $this->ModelsBancos->obtenerBancos();
        $data['listar'] = $this->ModelsFacturar->obtenerFacturas();
        //~ $data['list_iva'] = $this->ModelsImpuesto->obtenerImpuestos();
        //~ $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('facturas');
        //~ $data['cod_factura'] = '';
        //~ if ($data['ultimo_id'] > 0){
			//~ $data['cod_factura'] = $this->ModelsFacturar->obtenerFactura($data['ultimo_id']);
		//~ }
        $this->load->view('factura/lista.php', $data);
    }
    function factura()
    {

        $data['listar'] = $this->ModelsFacturar->obtenerClientes();
        $data['list_iva'] = $this->ModelsImpuesto->obtenerImpuestos();
        $data['hora']= time();
        $data['ultimo_id'] = $this->ModelsBusqueda->count_all_table('facturas');
        $data['cod_factura'] = '';
        if ($data['ultimo_id'] > 0){
			$data['cod_factura'] = $this->ModelsFacturar->obtenerFactura($data['ultimo_id']);
		}
        $this->load->view('factura/factura', $data);
    }

    public function guardar()
    {	
		$ultimo_id = $this->ModelsBusqueda->count_all_table('facturas');
		// Preparamos los datos generales de la factura
		$descuento = 0;
		if ($this->input->post('descuento') != ''){
			$descuento = $this->input->post('descuento');
		}
		$data_factura = array(
			'id' => $ultimo_id + 1,
			'codfactura' => trim($this->input->post('codfactura')),
			//~ 'pre_cod_factura' => trim($this->input->post('pre_cod_factura')),
			'codcliente' => $this->input->post('codcliente'),
			'cliente' => $this->input->post('cliente'),
			'base_imponible' => $this->input->post('base_imponible'),
			'monto_exento' => $this->input->post('monto_exento'),
			'monto_desc' => $this->input->post('monto_desc'),
			'monto_iva' => $this->input->post('monto_iva'),
			'iva' => $this->input->post('iva'),
			'descuento' => $descuento,
			'condicion_pago' => $this->input->post('condicion_pago'),
			'subtotal' => $this->input->post('subtotal'),
			'totalfactura' => $this->input->post('totalfactura'),
			'observaciones' => $this->input->post('observaciones'),
			'estado' => 1,
			'fecha_emision' => date("Y-m-d"),
			'hora_emision' => date("h:i:s a"),
			'nota_credito' => $this->input->post('nota_credito'),
			'nota_debito' => $this->input->post('nota_debito'),
			'ajustes' => $this->input->post('ajustes'),
			//~ 'num_cheque' => $this->input->post('num_cheque'),
			//~ 'num_recibo' => $this->input->post('num_recibo'),
			//~ 'num_transf' => $this->input->post('num_transf'),
			//~ 'num_deposito' => $this->input->post('num_deposito'),
			'num_control' => (string)rand(),
			//~ 'fecha_vencimiento' => $this->input->post('fecha_vencimiento'),
        );
        
		// Guardamos los datos generales de la factura
		$result = $this->ModelsFacturar->insertar($data_factura);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'facturas',
                'codigo' => trim($this->input->post('codfactura')),
                'accion' => 'Nueva Factura',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('presupuesto/ControllersPresupuesto');
        }
        
        // Guardamos los productos asociados a la factura
        $data_factura_ps = $this->input->post('data');
        
        foreach ($data_factura_ps as $campos){
            //~ print_r($campos);
            //Construcción del correlativo para el nuevo registro
            $ultimo_id = $this->ModelsBusqueda->count_all_table('facturas_ps');
            //~ echo $ultimo_id;
            $correlativo_ps = str_pad($ultimo_id+1, 8, "0", STR_PAD_LEFT);  //Rellenamos con ceros a la izquierda
            //~ echo $correlativo_ps;
            
            // Registro del nuevo producto
            $data_f_ps = array(
				'id' => $ultimo_id+1,
				'codfacturaps' => $correlativo_ps,
				'codfactura' => $campos['cod_factura'],
				'tipo' => 1,
				'cod_producto_servicio' => $campos['id'],
				'producto_servicio' => $campos['id_servicio'],
				'precio' => $campos['precio'],
				'monto_iva' => $campos['monto_iva'],
				'cantidad' => $campos['cantidad'],
				'importe' => $campos['importe'],
            );
            
			// Guardamos los datos de los productos de la factura
            $result = $this->ModelsFacturar->insertar_ps($data_f_ps);
        }
		//~ echo json_encode($data_factura_ps);
		//~ 

    }
    

    function editar()
    {
        $data['codfactura'] = $this->uri->segment(4);
        $data['editar'] = $this->ModelsBusqueda->obtenerRegistro('facturas', 'codfactura', $data['codfactura']);  // Datos de la factura
        $data['listar'] = $this->ModelsFacturar->obtenerClientes();  // Lista de clientes
        $data['list_iva'] = $this->ModelsImpuesto->obtenerImpuestos();  // Lista de tipos de IVA
        $data['hora']= time();  //Hora actual
        $data['listar_ps'] = $this->ModelsFacturar->obtenerProductosServicios($data['codfactura']);  // Lista de productos
        
        $this->load->view('factura/editar', $data);
    }


    function actualizar()
    
    {
        // Preparamos los datos generales de la factura
		$descuento = 0;
		if ($this->input->post('descuento') != ''){
			$descuento = $this->input->post('descuento');
		}
		
		$regs_eliminar = $this->input->post('codigos_des');  // Productos a desvincular de la factura
		
		$data_factura = array(
			'codfactura' => trim($this->input->post('codfactura')),
			//~ 'pre_cod_factura' => trim($this->input->post('pre_cod_factura')),
			'codcliente' => $this->input->post('codcliente'),
			'cliente' => $this->input->post('cliente'),
			'base_imponible' => $this->input->post('base_imponible'),
			'monto_exento' => $this->input->post('monto_exento'),
			'monto_desc' => $this->input->post('monto_desc'),
			'monto_iva' => $this->input->post('monto_iva'),
			'iva' => $this->input->post('iva'),
			'descuento' => $descuento,
			'condicion_pago' => $this->input->post('condicion_pago'),
			'subtotal' => $this->input->post('subtotal'),
			'totalfactura' => $this->input->post('totalfactura'),
			'observaciones' => $this->input->post('observaciones'),
			'estado' => 1,
			//~ 'fecha_emision' => $this->input->post('fecha_emision'),
			'nota_credito' => $this->input->post('nota_credito'),
			'nota_debito' => $this->input->post('nota_debito'),
			'ajustes' => $this->input->post('ajustes'),
			//~ 'num_cheque' => $this->input->post('num_cheque'),
			//~ 'num_recibo' => $this->input->post('num_recibo'),
			//~ 'num_transf' => $this->input->post('num_transf'),
			//~ 'num_deposito' => $this->input->post('num_deposito'),
			//~ 'hora_emision' => date("h:i:s a"),
			//~ 'fecha_vencimiento' => $this->input->post('fecha_vencimiento'),
        );
        
		// Actualizamos los datos generales de la factura
		$result = $this->ModelsFacturar->actualizarFactura($data_factura);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'facturas',
                'codigo' => trim($this->input->post('codfactura')),
                'accion' => 'Editar Factura',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('presupuesto/ControllersPresupuesto');
        }
        
        $data_factura_ps = $this->input->post('data');
        
        //~ print_r($data_factura_ps);
        
        // Verificamos si hay productos para asociar (registrar en facturas_ps)
        foreach ($data_factura_ps as $campos){
			
			// Primero validamos si el producto ya tiene código, si es así entonces es que ya está asociado
			if ($campos['cod_f_ps'] == ""){
				echo "producto no existente";
				//Construcción del correlativo para el nuevo registro
				$ultimo_id = $this->ModelsBusqueda->count_all_table('facturas_ps');
				//~ echo $ultimo_id;
				$correlativo_ps = str_pad($ultimo_id+1, 8, "0", STR_PAD_LEFT);  // Rellenamos con ceros a la izquierda hasta completar 8 dígitos
				//~ echo $correlativo_ps;
				
				// Registro del nuevo producto
				$data_f_ps = array(
					'id' => $ultimo_id+1,
					'codfacturaps' => $correlativo_ps,
					'codfactura' => $campos['cod_factura'],
					'tipo' => 1,
					'cod_producto_servicio' => $campos['cod_ps'],
					'producto_servicio' => $campos['nom_ps'],
					'precio' => $campos['precio'],
					'monto_iva' => $campos['monto_iva'],
					'cantidad' => $campos['cantidad'],
					'importe' => $campos['importe'],
				);
				
				// Guardamos los datos de los nuevos productos de la factura
				$result = $this->ModelsFacturar->insertar_ps($data_f_ps);
				
			}else{
				echo "producto existente";
			}
        }
        
        // Verificamos si hay productos para eliminar
        if($regs_eliminar != ''){
			$regs_eliminar = explode(",",$regs_eliminar);
			
			// Desvinculamos (eliminamos de la tabla facturas_ps)
			foreach ($regs_eliminar as $reg){
				//~ echo "Código: ".$reg;
				
				// Eliminamos la asociación de la tabla facturas_ps
				$result = $this->ModelsFacturar->eliminarProductoServicio($reg);
			}
		}
        
    }
    
    // Método para anular o activar una factura
    function anular($cod)
    {
		//~ echo "Código: ".$cod;
		//~ echo "Acción: ".$this->input->post('accion');
		//~ echo "Motivo: ". $this->input->post('motivo');
		
		$accion = $this->input->post('accion');
		$estado = 1;
		
		if ($accion == 'anular'){
			$estado = 3;
		}
		
		// Armamos la data a actualizar
        $data_factura = array(
			'codfactura' => $cod,
			'estado' => $estado,
			'motivo_anulacion' => $this->input->post('motivo'),
        );
        
		// Actualizamos la factura con los datos armados
		$result = $this->ModelsFacturar->actualizarFactura($data_factura);
		
		// Guardado en el módulo de auditoría
		if ($result) {

            $param = array(
                'tabla' => 'facturas',
                'codigo' => $cod,
                'accion' => 'Anular Factura',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            //~ redirect('presupuesto/ControllersPresupuesto');
        }
		
    }
    
    
    // Método para verificar o activar una factura
    function verificar($cod)
    {
		//~ echo "Código: ".$cod;
		//~ echo "Acción: ".$this->input->post('accion');
		//~ echo "condicion pago: ". $this->input->post('condicion');
		
		$accion = $this->input->post('accion');
		$estado = 1;
		$cuenta = $this->input->post('cuenta');
		$monto_factura = $this->input->post('monto_f');
		
		if ($accion == 'verificar'){
			$estado = 2;
		}
		
		// Armamos la data a actualizar
        $data_factura = array(
			'codfactura' => $cod,
			'estado' => $estado,
			'num_cheque' => $this->input->post('num_cheque'),
			'num_recibo' => $this->input->post('num_recibo'),
			'num_transf' => $this->input->post('num_transf'),
			'num_deposito' => $this->input->post('num_deposito'),
			'monto_cheque' => $this->input->post('monto_cheque'),
			'monto_recibo' => $this->input->post('monto_recibo'),
			'monto_efectivo' => $this->input->post('monto_efectivo'),
			'monto_transf' => $this->input->post('monto_transf'),
			'monto_deposito' => $this->input->post('monto_deposito'),
        );
        
		// Actualizamos la factura con los datos armados
		$result = $this->ModelsFacturar->actualizarFactura($data_factura);
		
		// Guardado en el módulo de auditoría y cuentas
		if ($result) {
			// Guardado en el módulo de auditoría
            $param = array(
                'tabla' => 'facturas',
                'codigo' => $cod,
                'accion' => 'verificar Factura',
                'fecha' => date('Y-m-d'),
                'hora' => date("h:i:s a"),
                'usuario' => $this->session->userdata['logged_in']['id'],
            );
            $this->ModelsAuditoria->add($param);
            
            // Guardado en el módulo de cuentas
            // Primero buscamos los datos de la cuenta con el código
            $busqueda_cuenta = $this->ModelsBusqueda->obtenerRegistro('cuentas','codigo',$cuenta);
            // Armamos la data para actualizar la cuenta
            $data_cuenta = array(
                'id' => $busqueda_cuenta->id,
                'monto_total' => (float)$busqueda_cuenta->monto_total+(float)$monto_factura,
            );
            // Actualizamos el nuevo monto total a la cuenta
            $update_cuenta = $this->ModelsCuentas->actualizarCuenta($data_cuenta);
            
            // Guardado en el módulo de movimientos
            // Primero construimos el correlativo para el nuevo movimiento
            $ultimo_id = $this->ModelsBusqueda->count_all_table('movimientos');
			//~ echo $ultimo_id;
			$correlativo_m = str_pad($ultimo_id+1, 8, "0", STR_PAD_LEFT);  // Rellenamos con ceros a la izquierda hasta completar 8 dígitos
			//~ echo $correlativo_m;
			// Armamos la data para registrar el movimiento
            $data_movimiento = array(
				'id' => $ultimo_id+1,
				'codigo' => $correlativo_m,
				'banco' => $busqueda_cuenta->cod_banco,
				'cuenta' => $cuenta,
				'monto' => (float)$monto_factura,
				'tipo' => '1',
				'concepto' => "Nuevo ingreso por factura $cod",
				'fecha' => date('Y-m-d'),
				'hora' => date("h:i:s a"),
				'user_create' => $this->session->userdata['logged_in']['id'],
            );
            // Guardamos los datos del movimiento
			$this->ModelsMovimientos->insertar($data_movimiento);
			
			// Actualizamos el estatus a '4' (ejecutado) a los ajustes aplicados a la factura
            $factura = $this->ModelsFacturar->obtenerFacturaCod($cod);
            $ajustes = explode("-",$factura->ajustes);
            foreach($ajustes as $ajuste){
				$data_ajuste = array('codajuste'=>$ajuste,'estado'=>4);
				$this->ModelsAjustes->actualizarAjuste($data_ajuste);
			}
        }
		
    }
    
    // Método para consultar los ajustes aplicados a una factura o más de un cliente
    function consultar_ajustes($cliente) {
		// Primero buscamos las facturas por código de cliente
        $facturas = $this->ModelsFacturar->obtenerFacturasCliente($cliente);
        //~ echo count($facturas);
        $nota_credito = 0;
        $nota_debito = 0;
        $list_ajustes = "";
        if(count($facturas) > 0){
			foreach($facturas as $factura){
				//~ echo $factura->codfactura;
				// Buscamos los ajustes de cada factura y los sumamos para obtener los totales en notas de crédito y débito
				$ajustes = $this->ModelsAjustes->obtenerAjusteFactura($factura->codfactura);
				if(count($ajustes) > 0){
					foreach($ajustes as $ajuste){
						//~ echo $ajuste->codfactura;
						//~ echo $ajuste->totalajuste;
						if($ajuste->tipo_ajuste == '1'){
							$nota_credito += $ajuste->totalajuste;
						}else if($ajuste->tipo_ajuste == '2'){
							$nota_debito += $ajuste->totalajuste;
						}
						// Hacemos una lista de los códigos de los ajustes pendientes para el cliente
						$list_ajustes .= $ajuste->codajuste."-";
					}
				}
			}
		}
		
		//~ return array('nota_credito'=>$nota_credito,'nota_debito'=>$nota_debito);
        echo ("$nota_credito;$nota_debito;".substr($list_ajustes, 0, -1));
    }
    
    // Método para generar las facturas en pdf
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
        
        $this->load->view('factura/pdf/reporte_factura', $data);
    }
}
