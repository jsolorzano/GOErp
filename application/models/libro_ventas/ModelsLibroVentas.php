<?php

class ModelsLibroVentas extends CI_Model
{
    //put your code here}
    private $table = NULL;
    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
        $this->table= 'facturas';
    }
    
    // Metodo publico, forma de obtener un cliente por su código
    public function obtenerClienteCod($cod)
    {
        $this->db->where('codigo', $cod);
        $query = $this->db->get('cliente');
        return $query->row();
    }
    
    // Método público para listar las ventas generales
    public function obtenerVentas()
    {                                                                                      
        $query = $this->db->get('facturas');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Método público para listar ventas específicas
    public function obtenerVentasEsp($desde,$hasta)
    {
		$query = "";
		$sql = "";
		// Formateamos las fechas
		$desde = explode('-',$desde);
		$desde = $desde[2].'-'.$desde[1].'-'.$desde[0];
		$hasta = explode('-',$hasta);
		$hasta = $hasta[2].'-'.$hasta[1].'-'.$hasta[0];
		
		// La consulta se hace dependiendo de los parámetros de búsqueda
		if($desde != "xxx" and $hasta != "xxx"){
			$sql = "SELECT * FROM facturas f ";
			$sql .= "WHERE fecha_emision BETWEEN '".$desde."' AND '".$hasta."'";
		}
		//~ echo $sql;
		$query = $this->db->query($sql);
		//~ echo $this->db->last_query();
        //~ $query = $this->db->get('facturas');  No es necesario cuando se usa db->query()
        
        if ($query->num_rows() > 0)
			return $query->result();
        else
            echo '0';
    }
    
    // Método público para listar ventas específicas
    public function obtenerAjustesEsp($list_facturas)
    {
		$query = "";
		$sql = "";
		
		$sql = "SELECT * FROM ajustes WHERE codfactura in ".$list_facturas;
        $query = $this->db->query($sql);
        echo $this->db->last_query();
        //~ $query = $this->db->get('facturas');  No es necesario cuando se usa db->query()
        
        if ($query->num_rows() > 0)
			return $query->result();
        else
            echo '0';
    }
    
}
