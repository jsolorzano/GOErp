<?php

class ModelsEstadisticas extends CI_Model
{
    //put your code here}
    private $table = NULL;
    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
        $this->table= 'auditoria';
    }
    
    // Método público para listar las ventas generales
    public function obtenerVentas($rango,$fecha)
    {
		$estados = array(2, 4);
		$this->db->where_in('estado', $estados);
		$this->db->like('fecha_emision', $fecha);
        $query = $this->db->get('facturas');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Método público para listar productos de facturas específicas agrupados por producto
    public function obtenerVentasEsp($list_cods)
    {
		$query = "";
		$sql = "";
		
		// La consulta se hace dependiendo de los parámetros de búsqueda
		$sql = "SELECT producto_servicio AS name, SUM(cantidad) AS y FROM facturas_ps WHERE codfactura IN(".$list_cods.") GROUP BY producto_servicio";
		//~ $sql = "SELECT producto_servicio, SUM(cantidad) AS cant, SUM(importe) AS imp FROM facturas_ps WHERE codfactura IN(".$list_cods.") GROUP BY producto_servicio";
		//~ echo $sql;
		$query = $this->db->query($sql);
        
        if ($query->num_rows() > 0)
			return $query->result();
        else
            echo '0';
    }
    
}
