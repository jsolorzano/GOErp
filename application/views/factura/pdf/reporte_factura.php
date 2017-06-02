<?php
$entregado_por = $factura->firma_almacen;
$fecha_entrega = $factura->fecha_entrega;
$hora_entrega = $factura->hora_entrega;
$estado = $factura->estado;

$this->pdf = new PdfFactura($orientation = 'L', $unit = 'mm', $format = 'A4');

// Asignamos el nombre del almacenista que firmó la entrega de la factura
$this->pdf->entregado_por = $entregado_por;
$this->pdf->fecha_entrega = $fecha_entrega;
$this->pdf->hora_entrega = $hora_entrega;
$this->pdf->estado = $estado;

// Agregamos una página
$this->pdf->AddPage();
// Define el alias para el número de página que se imprimirá en el pie
$this->pdf->AliasNbPages();

//~ $this->pdf->AliasNbPages() # LLAMADA DE PAGINACION
//~ $this->pdf->add_page() # AÑADE UNA NUEVA PAGINACION
#$this->pdf->SetFont('Times','',10) # TAMANO DE LA FUENTE
$this->pdf->SetFont('Arial','B',15);
$this->pdf->SetFillColor(157,188,201); # COLOR DE BOLDE DE LA CELDA
$this->pdf->SetTextColor(24,29,31); # COLOR DEL TEXTO
$this->pdf->SetMargins(15,15,10); # MARGENE DEL DOCUMENTO
#$this->pdf->ln(20) # Saldo de linea
# 10 y 50 eje x y y 200 dimencion

$this->pdf->SetFillColor(255,255,255);
$this->pdf->SetFont('Arial','B',14);
$this->pdf->Ln(5);
//~ $this->pdf->Cell(185,5,"",'',1,'C',1);
$this->pdf->MultiCell(185,5,utf8_decode('GOErp Intranet'),'','C',0);
$this->pdf->SetFont('Arial','',8);
//~ $this->pdf->Cell(30,5,"RIF:",'',0,'C',1);
$this->pdf->Cell(185,5,"RIF:   J-40162325-5",'',1,'C',1);
$texto = 'Av. Ppal El Castaño - # 131 - Maracay, Aragua. Municipio Girardot. 2101.';
$this->pdf->MultiCell(185,5,utf8_decode($texto),'','C',0);
$fecha = str_replace('-', '/', $factura->fecha_emision);
//~ $this->pdf->Cell(185,5,utf8_decode("N° Control: $factura->num_control"),'B',1,'R',1);
$this->pdf->Cell(30,5,"FECHA: $fecha",'B',0,'L',1);
$this->pdf->Cell(30,5,"",'B',0,'L',1);
$this->pdf->Cell(30,5,"HORA: $factura->hora_emision ",'B',0,'L',1);
$this->pdf->Cell(45,5,utf8_decode("Código Unico: $factura->num_control"),'B',0,'R',1);
$this->pdf->Cell(50,5,"FACTURA: $factura->codfactura",'B',1,'R',1);

if($factura->estado == 4 || $factura->estado == 2){
	$condicion_pago = "";
	$num_referencia = "";
	if($factura->condicion_pago == '1'){
		$condicion_pago = "CHEQUE";
		$num_referencia = $factura->num_cheque;
	}else if($factura->condicion_pago == '2'){
		$condicion_pago = "DEBITO";
		$num_referencia = $factura->num_recibo;
	}else if($factura->condicion_pago == '3'){
		$condicion_pago = "EFECTIVO";
		$num_referencia = "";
	}else if($factura->condicion_pago == '4'){
		$condicion_pago = "TRANSFERENCIA";
		$num_referencia = $factura->num_transf;
	}else if($factura->condicion_pago == '5'){
		$condicion_pago = "DEPOSITO";
		$num_referencia = $factura->num_deposito;
	}
	$this->pdf->Cell(60,5,utf8_decode("MÉTODO DE PAGO: $condicion_pago"),'B',0,'L',1);
	$this->pdf->Cell(75,5,utf8_decode("NÚMERO DE REFERENCIA: $num_referencia"),'B',0,'L',1);
	$this->pdf->Cell(50,5,"",'B',1,'R',1);
}

$this->pdf->Cell(30,5,"DATOS DEL CLIENTE",'T',1,'L',1);
//~ print_r($cliente);
if($cliente != 'PUNTOVENTA'){
	$rif = $cliente->tipocliente."-".$cliente->cirif;
	$telf = $cliente->tlf;
	$dir = $cliente->direccion;
}else{
	$rif = "";
	$telf = "";
	$dir = "";
}
$this->pdf->Cell(35,5,"RIF/CI: ".$rif,'',0,'L',1);
$this->pdf->Cell(95,5,utf8_decode("RAZÓN SOCIAL: $factura->cliente"),'',0,'L',1);
$this->pdf->Cell(25,5,utf8_decode("TELÉFONO: ").$telf,'',1,'L',1);
$this->pdf->Cell(155,5,utf8_decode("DIRECCIÓN: $dir"),'',1,'L',1);


$this->pdf->Cell(30,5,"Cant",'B',0,'C',1);
$this->pdf->Cell(30,5,utf8_decode("Descripción"),'B',0,'L',1);
$this->pdf->Cell(85,5,"",'B',0,'L',1);
$this->pdf->Cell(20,5,"Precio ",'B',0,'L',1);
$this->pdf->Cell(20,5,"Importe",'B',1,'R',1);

foreach ($productos_servicios as $ps){
	
	$precio = str_replace('', '', number_format($ps->precio, 2, ",", "."));
	$importe = str_replace('', '', number_format($ps->importe, 2, ",", "."));
	
	$this->pdf->Cell(30,5,"$ps->cantidad",'',0,'C',1);
	$this->pdf->Cell(115,5,utf8_decode("$ps->producto_servicio"),'',0,'L',1);
	$this->pdf->Cell(20,5,"$precio",'',0,'L',1);
	$this->pdf->Cell(20,5,"$importe",'',1,'R',1);
	#~ j = j + 10
}
$this->pdf->Cell(185,1,"",'T',1,'R',1);  // Cierre de bloque de productos/servicios

$bi = str_replace('', '', number_format($factura->base_imponible, 2, ",", "."));
$iva = str_replace('', '', number_format($factura->monto_iva, 2, ",", "."));
$nota_credito = str_replace('', '', number_format($factura->nota_credito, 2, ",", "."));
$nota_debito = str_replace('', '', number_format($factura->nota_debito, 2, ",", "."));
$porcentaje_iva = 0;
$total = str_replace('', '', number_format($factura->totalfactura, 2, ",", "."));

// Si hay base imponible con gravamen para la factura
if ($bi > 0){
	$porcentaje_iva = (float)($factura->monto_iva/$factura->base_imponible)*100;
	//~ $this->pdf->Cell(145,5,utf8_decode("BI G $impuesto->valor%"),'T',0,'R',1);
	$this->pdf->Cell(145,5,utf8_decode("BI G $porcentaje_iva %"),'',0,'R',0);
	$this->pdf->Cell(20,5,"",'',0,'L',1);
	$this->pdf->Cell(20,5,"$bi",'',1,'R',1);
}
// Si hay base exenta para la factura
if ($factura->monto_exento > 0){
	$monto_exento = str_replace('', '', number_format($factura->monto_exento, 2, ",", "."));
	$this->pdf->Cell(145,5,utf8_decode("BI E"),'',0,'R',0);
	$this->pdf->Cell(20,5,"",'',0,'L',1);
	$this->pdf->Cell(20,5,"$monto_exento",'',1,'R',1);
}
// Si hay descuento para la factura
if ($factura->descuento > 0){
	$desc = str_replace('', '', number_format($factura->monto_desc, 2, ",", "."));
	$this->pdf->Cell(145,5,utf8_decode("Desct $factura->descuento %"),'',0,'R',0);
	$this->pdf->Cell(20,5,"",'',0,'L',1);
	$this->pdf->Cell(20,5,"$desc",'',1,'R',1);
}
// Si hay base imponible con gravamen para la factura
if ($iva > 0){
	//~ $this->pdf->Cell(145,5,utf8_decode("IVA G $impuesto->valor%"),'',0,'R',1);
	$this->pdf->Cell(145,5,utf8_decode("IVA G $porcentaje_iva %"),'',0,'R',0);
	$this->pdf->Cell(20,5,"",'',0,'L',1);
	$this->pdf->Cell(20,5,"$iva",'',1,'R',1);
}
// Si hay nota de crédito para la factura
if ($nota_credito > 0){
	//~ $this->pdf->Cell(145,5,utf8_decode("IVA G $impuesto->valor%"),'',0,'R',1);
	$this->pdf->Cell(145,5,utf8_decode("Nota de Crédito"),'',0,'R',0);
	$this->pdf->Cell(20,5,"",'',0,'L',1);
	$this->pdf->Cell(20,5,"-$nota_credito",'',1,'R',1);
}
// Si hay nota de débito para la factura
if ($nota_debito > 0){
	//~ $this->pdf->Cell(145,5,utf8_decode("IVA G $impuesto->valor%"),'',0,'R',1);
	$this->pdf->Cell(145,5,utf8_decode("Nota de Débito"),'',0,'R',0);
	$this->pdf->Cell(20,5,"",'',0,'L',1);
	$this->pdf->Cell(20,5,"$nota_debito",'',1,'R',1);
}

$this->pdf->SetFont('Arial','B',14);
$this->pdf->Cell(145,5,"TOTAL",'',0,'R',0);
$this->pdf->Cell(20,5,"",'',0,'L',1);
$this->pdf->Cell(20,5,"$total",'',1,'R',0);


// Salida del Formato PDF
$this->pdf->Output("factura.pdf", 'I');
