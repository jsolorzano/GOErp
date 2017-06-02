<?php

class ModelsAuditoria extends CI_Model
{
    //put your code here}
    private $table = NULL;
    public function __construct()
    {
        @parent::__construct();
        $this->load->database();
        $this->table= 'auditoria';
    }
    
    // Módulo auditoría
    public function add($param)
    {

        $result = $this->db->insert("auditoria", $param);
        return $result;
    }
    
    // Método público para listar las auditorías generales
    public function obtenerAuditorias()
    {
		$query = $this->db->order_by("id", "desc");
        $query = $this->db->get('auditoria');                        

        if ($query->num_rows() > 0)
            return $query->result();
        else
            return $query->result();
    }
    
    // Método público para listar auditorías específicas
    public function obtenerAuditoriasEsp($usuario,$accion,$desde,$hasta)
    {
		$query = "";
		$sql = "";
		$accion2 = "";
		
		if($accion != 'xxx'){
			$accion2 = "accion LIKE '%".$accion."%' and ";
		}else{
			$accion2 = "";
		}
		
		// La consulta se hace dependiendo de los parámetros de búsqueda
		if($usuario != "xxx" and $desde == "xxx" and $hasta == "xxx"){
			//~ $this->db->where('usuario', $usuario);
			$sql = "SELECT * FROM auditoria WHERE ".$accion2."usuario=".$usuario." ORDER BY id DESC";
		}else if($usuario != "xxx" and $desde != "xxx" and $hasta != "xxx"){
			$sql = "SELECT * FROM auditoria WHERE ".$accion2."usuario=".$usuario." AND fecha BETWEEN '".$desde."' AND '".$hasta."' ORDER BY id DESC";
		}else if($usuario == "xxx" and $desde != "xxx" and $hasta != "xxx"){
			$sql = "SELECT * FROM auditoria WHERE ".$accion2."fecha BETWEEN '".$desde."' AND '".$hasta."' ORDER BY id DESC";
		}else if($usuario == "xxx" and $desde == "xxx" and $hasta == "xxx"){
			$sql = "SELECT * FROM auditoria WHERE accion LIKE '%".$accion."%' ORDER BY id DESC";
		}
		
		//~ echo $sql;
		$query = $this->db->query($sql);
        //~ echo $this->db->last_query();
        
        if ($query->num_rows() > 0)
			return $query->result();
        else
            echo '0';
    }
    
}
