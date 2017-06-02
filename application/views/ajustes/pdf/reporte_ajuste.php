<?php

//Definimos la clase del reporte
class DOC_Ajuste extends FPDF{
	function Header(){
		#Arial bold 15
		$this->SetFont('Arial','B',15);
		
		# ALINEACION DE LA IMAGEN EN LA CABECERA DEL DOCUMENTO
		# (CAMPO 1 = HORIZONTAL , CAMPO 2 = VERTICAL, CAMPO 3 = DIMENSION DE LA IMAGEN)
		
		//~ $this->Image('static/img/logo_aragua_peque.jpg',13,10,35);
		//~ $this->Image('static/img/002.jpg',220,10,45);
		$this->SetDrawColor(0,80,180);
		$this->SetFillColor(28,108,198);
		$this->SetTextColor(220,50,50);
		$this->Ln(20);
	}	
	
	#METODO PARA CONSTRUIR LA PAGINACIÓN
	# Page footer
	function Footer(){
		$this->SetY(-30);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(75,5,utf8_decode("Gerente de Administración"),'T',0,'C',1);
		$this->Cell(95,5,"",'',0,'C',1);
		//~ $fecha_actual = date('d/m/Y');
		$this->Cell(75,5,"Presidente",'T',1,'C',1);
		$this->SetY(-15);
        $this->Cell(245, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'R');
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


$this->pdf = new DOC_Ajuste($orientation = 'L', $unit = 'mm', $format = 'letter');

$this->pdf->AddPage(); # AÑADE UNA NUEVA PAGINACIÓN
#$this->pdf->SetFont('Times','',10) # TAMANO DE LA FUENTE
$this->pdf->SetFont('Arial','B',15);
$this->pdf->SetFillColor(157,188,201); # COLOR DE BOLDE DE LA CELDA
$this->pdf->SetTextColor(24,29,31); # COLOR DEL TEXTO
$this->pdf->SetMargins(15,10,10); # MARGENE DEL DOCUMENTO

$this->pdf->SetFillColor(255,255,255);
$this->pdf->SetFont('Arial','B',8);
#~ $this->pdf->Cell(55,6,"",'',0,'L',1)
$this->pdf->SetY(15);
$this->pdf->SetX(65);
$this->pdf->Cell(20,8,utf8_decode("Dirección:"),'LT',0,'L',0);
$this->pdf->MultiCell(120,4,utf8_decode("Av. Ppal El Castaño - # 131 - Maracay, Aragua. Municipio Girardot. 2101."),'TR','L',0);
$this->pdf->SetY(23);
$this->pdf->SetX(65);
$this->pdf->Cell(20,4,"Rif: ",'LB',0,'L',0);
$this->pdf->Cell(120,4,"J-40162325-5",'BR',1,'L',1);

$fecha_actual = date('d / m / Y');
$this->pdf->Cell(245,5,utf8_decode("Fecha: $fecha_actual"),'',1,'R',1);

// Definimos el tratamiento
$tipo_ajuste = $ajuste->tipo_ajuste;
$tipo_ajuste_min = "";

if ($tipo_ajuste == '1'){
	$tipo_ajuste = utf8_decode("NOTA DE CRÉDITO");
	$tipo_ajuste_min = utf8_decode("Nota de Crédito");
}else if($tipo_ajuste == '2'){
	$tipo_ajuste = utf8_decode("NOTA DE DÉBITO");
	$tipo_ajuste_min = utf8_decode("Nota de Débito");
}else{
	$tipo_ajuste = "INDEFINIDO";
	$tipo_ajuste_min = utf8_decode("Indefinido");
}

$this->pdf->Ln(5);
$this->pdf->SetFont('Arial','B',10);
$this->pdf->Cell(245,5,"$tipo_ajuste",'',1,'C',1);
$this->pdf->Ln(5);

$this->pdf->SetFillColor(255,255,255);
$this->pdf->SetFont('Arial','',8);

$this->pdf->Cell(75,5,"Fecha de ajuste: $ajuste->fecha_ajuste",'B',0,'L',1);
$this->pdf->Cell(95,5,"",'B',0,'C',1);
$this->pdf->Cell(75,5,"AJUSTE: $ajuste->codajuste",'B',1,'R',1);

// Datos del cliente
$this->pdf->Cell(245,5,"DATOS DEL CLIENTE",'T',1,'L',1);
$this->pdf->Cell(55,5,"RIF/CI: G-30759058-0",'',0,'L',1);
$this->pdf->Cell(100,5,utf8_decode("RAZÓN SOCIAL: A.C BIBLIOTECAS VIRTUALES DE ARAGUA"),'',0,'L',1);
$this->pdf->Cell(90,5,utf8_decode("TELÉFONO: ").'02432336068','',1,'L',1);
$this->pdf->Cell(245,5,utf8_decode("DIRECCIÓN: AV. SUCRE. URB. SAN ISIDRO - MARACAY. EDIF. BIBLIOTECA VIRTUAL. NRO. 26"),'',1,'L',1);

$this->pdf->Ln(20);
$this->pdf->SetFont('Arial','',10);
$this->pdf->Cell(245,5,"$tipo_ajuste_min sobre la factura $ajuste->codfactura, de fecha de ".utf8_decode('emisión')." $factura->fecha_emision",'',1,'C',1);

// Sección para el concepto
$this->pdf->Ln(20);
$this->pdf->SetFont('Arial','B',8);
$this->pdf->Cell(245,5,"CONCEPTO",'',1,'L',1);
$this->pdf->SetFont('Arial','',8);
$this->pdf->Cell(245,5,"$ajuste->concepto",'',1,'L',1);

// Sección para los subtotales
$bi = str_replace('', '', number_format($ajuste->base_imponible, 2, ",", "."));
$iva = str_replace('', '', number_format($ajuste->monto_iva, 2, ",", "."));
$exento = str_replace('', '', number_format($ajuste->monto_exento, 2, ",", "."));
$this->pdf->Ln(10);
$this->pdf->Cell(245,5,"Subtotal: $bi",'',1,'R',1);
$this->pdf->Cell(245,5,"I.V.A.: $iva",'',1,'R',1);
$this->pdf->Cell(245,5,"Subtotal Exento: $exento",'',1,'R',1);

// Sección para el total
$total = str_replace('', '', number_format($ajuste->totalajuste, 2, ",", "."));
$this->pdf->Ln(10);
$this->pdf->Cell(75,5,"TOTAL MONTO $tipo_ajuste Bs:",'',0,'L',1);
$this->pdf->Cell(95,5,"",'',0,'R',1);
$this->pdf->Cell(75,5,"$total",'',1,'R',1);

// Salida del Formato PDF
$this->pdf->Output("ajuste.pdf", 'I');
