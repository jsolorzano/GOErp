<?php

//Definimos la clase del reporte
class DOC extends FPDF{
	function Header(){
		#Arial bold 15
		$this->SetFont('Arial','B',15);
		
		# ALINEACION DE LA IMAGEN EN LA CABECERA DEL DOCUMENTO
		# (CAMPO 1 = HORIZONTAL , CAMPO 2 = VERTICAL, CAMPO 3 = DIMENSION DE LA IMAGEN)
		
		//~ $this->Image('static/img/logo_aragua_peque.jpg',13,10,35);
		//~ $this->Image('static/img/002.jpg',220,10,45);
		#Calcular ancho del texto (title) y establecer posición
		#w=$this->get_string_width(title)+6
		#$this->set_x((210-w)/2)
		#Colores del marco, fondo y texto
		$this->SetDrawColor(0,80,180);
		$this->SetFillColor(28,108,198);
		$this->SetTextColor(220,50,50);
		#Grosor del marco (1 mm)
		#$this->set_line_width(1)
		#Titulo
		#$this->Cell(w,9,title,1,1,'C',1)
		#Salto de línea
		$this->Ln(20);
	}	
	
	#METODO PARA CONSTRUIR LA PAGINACION
	# Page footer
	function Footer(){
		#Posición a 1.5 cm desde abajo
		$this->SetY(28);
		#Arial italic 8
		$this->SetFont('Arial','B',8);
		#Color de texto en gris
		$this->SetTextColor(0);
		#Numero de pagina
		$this->Cell(250,10,utf8_decode('Página ').$this->PageNo(),0,0,'R') ;
	}
	
	function ChapterTitle($num,$label){
		#Arial 12
		$this->SetFont('Arial','',12);
		#Color de fondo
		$this->SetFillColor(200,220,255);
		#Titulo
		$this->Cell(0,6,"Chapter $num: $label",0,1,'L',1);
		#Salto de línea
		$this->Ln(4);
	}
	
	function ChapterBody($name){
		#Leer archivo de texto
		$txt=file($name).read();
		#Times 12
		$this->SetFont('Times','',12);
		#Emitir texto justificado
		$this->MultiCell(0,5,txt);
		#Salto de línea
		$this->Ln();
		#Mención en italic -cursiva-
		$this->SetFont('','I');
		$this->Cell(0,5,'(end of excerpt)');
	}
	
	# CONSTRUCCTOR DEL DOCUMENTO
	function print_chapter($num,$title,$name){
		$this->add_page();
		$this->chapter_title($num,$title);
		$this->chapter_body($name);
	}
	
}

# Instancia de la clase heredada L es horizontal y P es vertical
$this->pdf = new DOC($orientation = 'L', $unit = 'mm', $format = 'letter');

$this->pdf->AddPage(); # AÑADE UNA NUEVA PAGINACIÓN
#$this->pdf->SetFont('Times','',10) # TAMANO DE LA FUENTE
$this->pdf->SetFont('Arial','B',15);
$this->pdf->SetFillColor(157,188,201); # COLOR DE BOLDE DE LA CELDA
$this->pdf->SetTextColor(24,29,31); # COLOR DEL TEXTO
$this->pdf->SetMargins(15,10,10); # MARGENE DEL DOCUMENTO
#$this->pdf->ln(20) # Saldo de linea
# 10 y 50 eje x y y 200 dimencion
#$this->pdf->line(10, 40, 200, 40) Linea 


$this->pdf->SetFillColor(255,255,255);
$this->pdf->SetFont('Arial','B',8);
#~ $this->pdf->Cell(55,6,"",'',0,'L',1)
$this->pdf->SetY(15);
$this->pdf->SetX(65);
$this->pdf->Cell(20,8,utf8_decode("Dirección:"),'LT',0,'L',0);
$this->pdf->MultiCell(120,4,"Av. Ppal El Castaño - # 131 - Maracay, Aragua. Municipio Girardot. 2101.",'TR','L',0);
$this->pdf->SetY(23);
$this->pdf->SetX(65);
$this->pdf->Cell(20,4,"Rif: ",'LB',0,'L',0);
$this->pdf->Cell(120,4,"J-40162325-5",'BR',1,'L',0);

$fecha_actual = date('d / m / Y');
$this->pdf->Cell(250,5,utf8_decode("Fecha de Emisión: $fecha_actual"),'',1,'R',0);

$this->pdf->SetFont('Arial','B',10);
$this->pdf->Cell(245,5,"LIBRO DE  VENTAS",'',1,'C',1);
$this->pdf->Cell(245,5,"<Expresados en Bolivares (Bs.)>",'',1,'C',1);

$this->pdf->Ln(5);
$this->pdf->SetFont('Arial','B',10);
$this->pdf->Cell(245,5,"EJERCICIO FISCAL: 2016",'',1,'L',1);
$this->pdf->Ln(5);
$this->pdf->SetFont('Arial','',10);
$this->pdf->Cell(245,5,"Periodo del $desde hasta el $hasta",'',1,'L',1);


$this->pdf->SetY(67);
$this->pdf->SetFont('Arial','B',8);
$this->pdf->Cell(20,10,utf8_decode("Factura N°"),'LTBR',0,'C',1);
$this->pdf->MultiCell(20,5,"Fecha de Factura",1,'C',1);
$this->pdf->SetY(67);
$this->pdf->SetX(55);
$this->pdf->Cell(20,10,utf8_decode("N° Rif"),'LTBR',0,'C',1);
$this->pdf->MultiCell(55,10,utf8_decode("Nombre o razón social del Cliente"),1,'C',1);
$this->pdf->SetY(67);
$this->pdf->SetX(130);

$this->pdf->Cell(20,10,utf8_decode("N° de Control"),'LTBR',0,'C',1);
$this->pdf->MultiCell(20,5,"Total Ventas Incluye IVA",1,'C',1);
$this->pdf->SetY(67);
$this->pdf->SetX(170);
$this->pdf->MultiCell(20,5,"Ventas Exentas",1,'C',1);
$this->pdf->SetY(67);
$this->pdf->SetX(190);
$this->pdf->MultiCell(25,5,utf8_decode("Ventas Tasa o Exportación"),1,'C',1);
$this->pdf->SetY(67);
$this->pdf->SetX(215);
$this->pdf->MultiCell(20,5,"Base Imponible",1,'C',1);
$this->pdf->SetY(67);
$this->pdf->SetX(235);
$this->pdf->Cell(7,10,"%",'LTBR',0,'C',1);
$this->pdf->SetY(67);
$this->pdf->SetX(242);
$this->pdf->MultiCell(20,5,"Impuesto I.V.A.",1,'C',1);
//~ $this->pdf->SetY(67);
//~ $this->pdf->SetX(247);
//~ $this->pdf->MultiCell(15,5,"(%) I.V.A. Retenido",1,'C',1);

$i = 0;
$total_ventas = 0;
$total_bi = 0;
$total_iva = 0;
$total_exento = 0;

//~ foreach ($auditoria as $auditoria){
foreach ($ventas as $factura){
	$contador = $i+1;
	$iva = $factura->monto_iva/$factura->base_imponible*100;  // Calculamos el porcentaje del iva aplicado
	$total_ventas += $factura->totalfactura;
	$total_exento += $factura->monto_exento;
	$total_bi += $factura->base_imponible;
	$total_iva += $factura->monto_iva;
	$fecha_emision = explode('-',$factura->fecha_emision);
	$fecha_emision = $fecha_emision[2].'-'.$fecha_emision[1].'-'.$fecha_emision[0];
	$this->pdf->SetFont('Arial','',8);
	$this->pdf->Cell(20,4,"$factura->codfactura",'',0,'R',0);
	$this->pdf->Cell(20,4,"$fecha_emision",'',0,'C',1);
	$rif_cliente = "";
	$nom_cliente = "";
	if($factura->codcliente != "PUNTOVENTA"){
		foreach($clientes as $cliente){
			if($cliente->codigo == $factura->codcliente){
				$rif_cliente = $cliente->tipocliente."-".$cliente->cirif;
				$nom_cliente = $cliente->nombre;
			}
		}
	}else{
		$rif_cliente = "";
		$nom_cliente = "CLIENTE POR PUNTO DE VENTA";
	}
	$this->pdf->Cell(20,4,"$rif_cliente",'',0,'C',0);
	$this->pdf->SetFont('Arial','',7);
	$this->pdf->Cell(55,4,"$nom_cliente",'',0,'L',0);
	$this->pdf->Cell(20,4,"$factura->num_control",'',0,'R',0);
	$this->pdf->SetFont('Arial','',8);
	$this->pdf->Cell(20,4,"$factura->totalfactura",'',0,'C',0);
	$this->pdf->Cell(20,4,"$factura->monto_exento",'',0,'C',0);
	$this->pdf->Cell(25,4,"",'',0,'C',0);
	$this->pdf->Cell(20,4,"$factura->base_imponible",'',0,'C',0);
	$this->pdf->Cell(7,4,"$iva",'',0,'C',0);
	$this->pdf->Cell(20,4,"$factura->monto_iva",'',1,'C',0);
	//~ $this->pdf->Cell(15,4,"75",'',1,'C',0);
	
	$i += 1;
}

$contadorpie = $i+1;
$this->pdf->SetFont('Arial','B',8);
$this->pdf->Cell(135,4,"Totales",'LTB',0,'C',1);
$this->pdf->Cell(20,4,"$total_ventas",'TB',0,'C',0);
$this->pdf->Cell(20,4,"$total_exento",'TB',0,'C',0);
$this->pdf->Cell(25,4,"0,00",'TB',0,'C',0);
$this->pdf->Cell(20,4,"$total_bi",'TB',0,'C',0);
$this->pdf->Cell(7,4,"",'TB',0,'C',0);
$this->pdf->Cell(20,4,"$total_iva",'TB',0,'C',0);
//~ $this->pdf->Cell(15,4,"",'TBR',1,'C',0);

$j = 0;
$tipo_ajuste = "N.C.";
$total_ajustes = 0;
$total_bi_ajustes = 0;
$total_iva_ajustes = 0;
$total_exento_ajustes = 0;
$signo = "";  // Esta variable servirá para indicar el tipo de ajuste ("", positivo; "-", negativo)

$this->pdf->Ln(8);
$this->pdf->SetFont('Arial','B',8);
$this->pdf->Cell(25,4,"AJUSTES",'',1,'R',0);
$this->pdf->Ln(4);
if($ajustes != ""){
	foreach ($ajustes as $ajuste){
		$contador = $i+1;
		$iva_ajuste = $ajuste->monto_iva/$ajuste->base_imponible*100;  // Calculamos el porcentaje del iva aplicado
		if($ajuste->tipo_ajuste == '1'){
			$signo = "-";
			$total_ajustes -= $ajuste->totalajuste;
			$total_bi_ajustes -= $ajuste->base_imponible;
			$total_iva_ajustes -= $ajuste->monto_iva;
			$total_exento_ajustes -= $ajuste->monto_exento;
		}else if($ajuste->tipo_ajuste == '2'){
			$tipo_ajuste = "N.D.";
			$total_ajustes += $ajuste->totalajuste;
			$total_bi_ajustes += $ajuste->base_imponible;
			$total_iva_ajustes += $ajuste->monto_iva;
			$total_exento_ajustes += $ajuste->monto_exento;
		}
		$fecha_ajuste = explode('-',$ajuste->fecha_ajuste);
		$fecha_ajuste = $fecha_ajuste[2].'-'.$fecha_ajuste[1].'-'.$fecha_ajuste[0];
		$this->pdf->SetFont('Arial','',8);
		$this->pdf->Cell(45,4,"$tipo_ajuste $ajuste->codajuste Fact. $ajuste->codfactura",'LTBR',0,'C',0);
		$this->pdf->Cell(20,4,"$fecha_ajuste",'LTBR',0,'C',1);
		$rif_cliente = "";
		$nom_cliente = "";
		foreach($clientes as $cliente){
			if($cliente->tipocliente."-".$cliente->cirif == $ajuste->rifcliente){
				$rif_cliente = $cliente->tipocliente."-".$cliente->cirif;
				$nom_cliente = $cliente->nombre;
			}
		}
		$this->pdf->Cell(20,4,"$rif_cliente",'LTBR',0,'C',0);
		$this->pdf->SetFont('Arial','',6);
		$this->pdf->Cell(50,4,"$nom_cliente",'LTBR',0,'L',0);
		
		$this->pdf->SetFont('Arial','',8);
		$this->pdf->Cell(20,4,"$signo$ajuste->totalajuste",'LTBR',0,'C',0);
		$this->pdf->Cell(20,4,"$signo$ajuste->monto_exento",'LTBR',0,'C',0);
		$this->pdf->Cell(25,4,"0,00",'LTBR',0,'C',0);
		$this->pdf->Cell(20,4,"$signo$ajuste->base_imponible",'LTBR',0,'C',0);
		$this->pdf->Cell(7,4,"$iva_ajuste",'LTBR',0,'C',0);
		$this->pdf->Cell(20,4,"$signo$ajuste->monto_iva",'LTBR',1,'C',0);
		//~ $this->pdf->Cell(15,4,"75",'LTBR',1,'C',0);
		$j += 1;
	}

$contadorpie2 = $i+1;
$this->pdf->Cell(135,4,"Totales",'LTB',0,'C',1);
$this->pdf->Cell(20,4,"$total_ajustes",'TB',0,'C',0);
$this->pdf->Cell(20,4,"$total_exento_ajustes",'TB',0,'C',0);
$this->pdf->Cell(25,4,"0,00",'TB',0,'C',0);
$this->pdf->Cell(20,4,"$total_bi_ajustes",'TB',0,'C',0);
$this->pdf->Cell(7,4,"",'TB',0,'C',0);
$this->pdf->Cell(20,4,"$total_iva_ajustes",'TBR',1,'C',0);
//~ $this->pdf->Cell(15,4,"",'TBR',1,'C',0);
}

// Montos totales
$t_venta = $total_ventas + $total_ajustes;
$t_exento = $total_exento + $total_exento_ajustes;
$t_bi = $total_bi + $total_bi_ajustes;
$t_impuesto = $total_iva + $total_iva_ajustes;

$this->pdf->Ln(4);
$this->pdf->SetFont('Arial','B',8);
$this->pdf->Cell(135,4,"TOTALES GENERALES -->",'',0,'C',1);
$this->pdf->Cell(20,4,"$t_venta",'TB',0,'C',0);
$this->pdf->Cell(20,4,"$t_exento",'TB',0,'C',0);
$this->pdf->Cell(25,4,"0,00",'TB',0,'C',0);
$this->pdf->Cell(20,4,"$t_bi",'TB',0,'C',0);
$this->pdf->Cell(7,4,"",'TBR',0,'C',0);
$this->pdf->Cell(20,4,"$t_impuesto",'TBR',1,'C',0);
//~ $this->pdf->Cell(15,4,"",'TBR',1,'C',0);


$this->pdf->Output('libro_ventas.pdf','I');
