<?php
$entregado_por = $pedido->firma_almacen;
$fecha_ingreso = $pedido->fecha_ingreso;
$hora_ingreso = $pedido->hora_ingreso;

$this->pdf = new Pdfpedido($orientation = 'L', $unit        = 'mm', $format      = 'A4');

// Asignamos el nombre del almacenista que firmó el ingreso del pedido
$this->pdf->entregado_por = $entregado_por;
$this->pdf->fecha_ingreso = $fecha_ingreso;
$this->pdf->hora_ingreso = $hora_ingreso;

// Agregamos una página
$this->pdf->AddPage();
// Define el alias para el número de página que se imprimirá en el pie
$this->pdf->AliasNbPages();

$this->pdf->SetFont('Arial','B',15); # TAMAÑO Y TIPO DE LA FUENTE
$this->pdf->SetFillColor(157,188,201); # COLOR DE BOLDE DE LA CELDA
$this->pdf->SetTextColor(24,29,31); # COLOR DEL TEXTO
$this->pdf->SetMargins(15,15,10); # MARGENE DEL DOCUMENTO
#$this->pdf->ln(20) # Saldo de linea
# 10 y 50 eje x y y 200 dimencion

$this->pdf->SetFillColor(255,255,255);
$this->pdf->SetFont('Arial','B',14);
$this->pdf->Ln(1);
$this->pdf->Cell(185,5,"",'',1,'C',1);
$this->pdf->MultiCell(185,5,utf8_decode('GOErp Intranet'),'','C',0);
$this->pdf->SetFont('Arial','',8);
//~ $this->pdf->Cell(30,5,"RIF:",'',0,'C',1);
$this->pdf->Cell(185,5,"RIF:   J-40162325-5",'',1,'C',1);
$texto = 'Av. Ppal El Castaño - # 131 - Maracay, Aragua. Municipio Girardot. 2101.';
$this->pdf->MultiCell(185,5,utf8_decode($texto),'','C',0);
$fecha = str_replace('-', '/', $pedido->fecha_emision);
$this->pdf->Cell(30,5,"FECHA: $fecha",'B',0,'L',1);
$this->pdf->Cell(30,5,"",'B',0,'L',1);
$this->pdf->Cell(30,5,"HORA: $pedido->hora_emision ",'B',0,'L',1);
$this->pdf->Cell(95,5,"PEDIDO: $pedido->codpedido",'B',1,'R',1);

$this->pdf->Cell(30,5,"DATOS DEL PROVEEDOR",'T',1,'L',1);
//~ print_r($proveedor);
if($proveedor != 'PUNTOVENTA'){
	$rif = $proveedor->tipoproveedor."-".$proveedor->cirif;
	$telf = $proveedor->tlf;
	$dir = $proveedor->direccion;
}else{
	$rif = "";
	$telf = "";
	$dir = "";
}
$this->pdf->Cell(35,5,"RIF/CI: ".$rif,'',0,'L',1);
$this->pdf->Cell(95,5,utf8_decode("RAZÓN SOCIAL: $pedido->proveedor"),'',0,'L',1);
$this->pdf->Cell(25,5,utf8_decode("TELÉFONO: ").$telf,'',1,'L',1);
$this->pdf->Cell(155,5,utf8_decode("DIRECCIÓN: $dir"),'',1,'L',1);


$this->pdf->Cell(30,5,"Cant",'B',0,'C',1);
$this->pdf->Cell(155,5,utf8_decode("Descripción"),'B',1,'L',1);

foreach ($productos as $ps){
	
	$precio = str_replace('', '', number_format($ps->precio, 2, ",", "."));
	$importe = str_replace('', '', number_format($ps->importe, 2, ",", "."));
	
	$this->pdf->Cell(30,5,"$ps->cantidad",'',0,'C',1);
	$this->pdf->Cell(155,5,utf8_decode("$ps->producto_servicio"),'',1,'L',1);
	#~ j = j + 10
}
$this->pdf->Cell(185,1,"",'T',1,'R',1);  // Cierre de bloque de productos

// Salida del Formato PDF
$this->pdf->Output("pedido.pdf", 'I');
