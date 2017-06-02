<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ModelsProductos extends CI_Model
{

    //put your code here


    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
    }

	// Método público para obtener una lista general de los productos
    public function obtenerProductos()
    {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('producto');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Método público para obtener una lista de los productos para servicios
    public function obtenerProductosServicios()
    {
		$query = $this->db->where('tipoproducto !=', 'TPV');                                                                        
        $query = $this->db->get('producto');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Método público para obtener una lista de los productos para terminales
    public function obtenerProductosTerminales()
    {
		$query = $this->db->where('tipoproducto =', 'TPV');                                                                                      
        $query = $this->db->get('producto');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Método público para obtener una lista de los productos para terminales 
    public function obtenerProductosTerminales2()
    {
		$sql = "select p.codigo, SUM(dt.existencia) AS sub_t from producto p inner join detalle_terminal dt ";
        $sql .= "ON p.codigo=dt.cod_producto group by p.codigo";
                                                                                              
        $query = $this->db->query($sql);                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Método público para obtener una lista filtrada de los productos que tienen existencia
    public function obtenerProductosExistencia()
    {
		$query = $this->db->where('existencia > 0');                                                                                      
        $query = $this->db->get('producto');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }

    // Metodo publico, forma de insertar los datos
    public function insertar($datos)
    {
        $result = $this->db->where('codigo =', $datos['codigo']);
        $result = $this->db->get('producto');

        if ($result->num_rows() > 0) {
            #echo "CORRECTO";
            echo '1';
        } else {
            $result = $this->db->insert("producto", $datos);
            return $result;
        }
        
    }
    
    
    public function obtenerProducto($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('producto');
        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
	// Metodo publico, forma de actualizar los datos de un producto
    public function actualizarProducto($datos)
    {
        
        $result = $this->db->where('id', $datos['id']);
        $result = $this->db->update('producto', $datos);
        return $result;
    }
    

    public function eliminarProducto($id)
    {
          // Primero buscamos si existe un cliente asociado a la factura que queremos eliminar
	$result = $this->db->where('cod_producto_servicio =', $id);
        $result = $this->db->get('facturas_ps');
        
        if ($result->num_rows() > 0) {
            echo 'existe',$result->num_rows();
        } else {
			// Procedemos a borrar el tipo de servicio si no está asociado a un servicio
             $result = $this->db->delete('producto', array('codigo'=>$id));
 
            return $result;
        }

    }
    


}
