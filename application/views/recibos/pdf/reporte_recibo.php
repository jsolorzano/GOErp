<?php
$entregado_por = $recibo->firma_rrhh;
$fecha_entrega = $recibo->fecha_entrega;
$hora_entrega = $recibo->hora_entrega;

$this->pdf = new PdfRecibo($orientation = 'L', $unit = 'mm', $format = 'A4');

// Asignamos el nombre del almacenista que firmó la entrega de la recibo
$this->pdf->entregado_por = $entregado_por;
$this->pdf->fecha_entrega = $fecha_entrega;
$this->pdf->hora_entrega = $hora_entrega;

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
$fecha = str_replace('-', '/', $recibo->fecha_emision);
//~ $this->pdf->Cell(185,5,utf8_decode("N° Control: $recibo->num_control"),'B',1,'R',1);
$this->pdf->Cell(30,5,"FECHA: $fecha",'B',0,'L',1);
$this->pdf->Cell(30,5,"",'B',0,'L',1);
$this->pdf->Cell(30,5,"HORA: $recibo->hora_emision ",'B',0,'L',1);
$this->pdf->Cell(45,5,utf8_decode("Código Unico: $recibo->num_control"),'B',0,'R',1);
$this->pdf->Cell(50,5,"RECIBO: $recibo->codrecibo",'B',1,'R',1);

if($recibo->estado == 2){
	$condicion_pago = "";
	$num_referencia = "";
	if($recibo->condicion_pago == '1'){
		$condicion_pago = "CHEQUE";
		$num_referencia = $recibo->num_cheque;
	}else if($recibo->condicion_pago == '2'){
		$condicion_pago = "DEBITO";
		$num_referencia = $recibo->num_recibo;
	}else if($recibo->condicion_pago == '3'){
		$condicion_pago = "EFECTIVO";
		$num_referencia = "";
	}else if($recibo->condicion_pago == '4'){
		$condicion_pago = "TRANSFERENCIA";
		$num_referencia = $recibo->num_transf;
	}else if($recibo->condicion_pago == '5'){
		$condicion_pago = "DEPOSITO";
		$num_referencia = $recibo->num_deposito;
	}
	$this->pdf->Cell(60,5,utf8_decode("MÉTODO DE PAGO: $condicion_pago"),'B',0,'L',1);
	$this->pdf->Cell(75,5,utf8_decode("NÚMERO DE REFERENCIA: $num_referencia"),'B',0,'L',1);
	$this->pdf->Cell(50,5,"",'B',1,'R',1);
}

$this->pdf->Cell(30,5,"DATOS DEL EMPLEADO",'T',1,'L',1);
//~ print_r($empleado);
if($empleado != 'PUNTOVENTA'){
	$rif = $empleado->tipodoc."-".$empleado->cirif;
	$telf = $empleado->tlf;
	$dir = $empleado->direccion;
}else{
	$rif = "";
	$telf = "";
	$dir = "";
}
$this->pdf->Cell(35,5,"RIF/CI: ".$rif,'',0,'L',1);
$this->pdf->Cell(95,5,utf8_decode("EMPLEADO: $recibo->empleado"),'',0,'L',1);
$this->pdf->Cell(25,5,utf8_decode("TELÉFONO: ").$telf,'',1,'L',1);
$this->pdf->Cell(155,5,utf8_decode("DIRECCIÓN: $dir"),'',1,'L',1);


$this->pdf->Cell(30,5,"Tipo",'B',0,'C',1);
$this->pdf->Cell(95,5,utf8_decode("Concepto"),'B',0,'L',1);
$this->pdf->Cell(20,5,"Monto",'B',0,'L',1);
$this->pdf->Cell(20,5,"Cantidad",'B',0,'L',1);
$this->pdf->Cell(20,5,"Importe",'B',1,'R',1);

// Cargamos de forma automática el salario del trabajador como un concepto más
$this->pdf->Cell(30,5,utf8_decode("Asignación"),'',0,'C',1);
$this->pdf->Cell(95,5,utf8_decode("$recibo->salario"),'',0,'L',1);
$this->pdf->Cell(20,5,str_replace('', '', number_format($recibo->monto_salario, 2, ',', '.')),'',0,'L',1);
$this->pdf->Cell(20,5,"1",'',0,'L',1);
$this->pdf->Cell(20,5,str_replace('', '', number_format($recibo->monto_salario, 2, ',', '.')),'',1,'R',1);
foreach ($abonos_descuentos as $ad){
	
	$monto = str_replace('', '', number_format($ad->monto, 2, ",", "."));
	$importe = str_replace('', '', number_format($ad->importe, 2, ",", "."));
	if($ad->tipo == 2){
		$this->pdf->Cell(30,5,utf8_decode("Deducción"),'',0,'C',1);
	}else{
		$this->pdf->Cell(30,5,utf8_decode("Asignación"),'',0,'C',1);
	}
	$this->pdf->Cell(95,5,utf8_decode("$ad->abono_descuento"),'',0,'L',1);
	if($ad->tipo == 2){
		$this->pdf->Cell(20,5,"-$monto",'',0,'L',1);
	}else{
		$this->pdf->Cell(20,5,"$monto",'',0,'L',1);
	}
	$this->pdf->Cell(20,5,"$ad->cantidad",'',0,'L',1);
	if($ad->tipo == 2){
		$this->pdf->Cell(20,5,"-$importe",'',1,'R',1);
	}else{
		$this->pdf->Cell(20,5,"$importe",'',1,'R',1);
	}
	#~ j = j + 10
}
$this->pdf->Cell(185,1,"",'T',1,'R',1);  // Cierre de bloque de productos/servicios

$abonos = str_replace('', '', number_format($recibo->abonos, 2, ",", "."));
$desc = str_replace('', '', number_format($recibo->descuento, 2, ",", "."));
$ret_faov = str_replace('', '', number_format($recibo->ret_faov, 2, ",", "."));
$ret_sso = str_replace('', '', number_format($recibo->ret_sso, 2, ",", "."));
$sub_total = str_replace('', '', number_format($recibo->subtotal, 2, ",", "."));
$total = str_replace('', '', number_format($recibo->totalrecibo, 2, ",", "."));

// Si hay abonos extra para el recibo
if ($recibo->abonos > 0){
	$this->pdf->Cell(145,5,utf8_decode("ASIGNACIONES"),'',0,'R',1);
	$this->pdf->Cell(20,5,"",'',0,'L',1);
	$this->pdf->Cell(20,5,"$abonos",'',1,'R',1);
}
// Si hay descuentos para el recibo
if ($recibo->descuento > 0){
	$this->pdf->Cell(145,5,utf8_decode("DESC"),'',0,'R',1);
	$this->pdf->Cell(20,5,"",'',0,'L',1);
	$this->pdf->Cell(20,5,"-$desc",'',1,'R',1);
}

$this->pdf->Cell(145,5,utf8_decode("RET FAOV"),'',0,'R',1);
$this->pdf->Cell(20,5,"",'',0,'L',1);
$this->pdf->Cell(20,5,"-$ret_faov",'',1,'R',1);
	

$this->pdf->Cell(145,5,utf8_decode("RET SSO"),'',0,'R',1);
$this->pdf->Cell(20,5,"",'',0,'L',1);
$this->pdf->Cell(20,5,"-$ret_sso",'',1,'R',1);

//~ $this->pdf->Cell(145,5,utf8_decode("SALARIO"),'',0,'R',1);
//~ $this->pdf->Cell(20,5,"",'',0,'L',1);
//~ $this->pdf->Cell(20,5,"$sub_total",'',1,'R',1);

$this->pdf->SetFont('Arial','B',14);
$this->pdf->Cell(145,5,"TOTAL A PAGAR",'',0,'R',1);
$this->pdf->Cell(20,5,"",'',0,'L',1);
$this->pdf->Cell(20,5,"$total",'',1,'R',1);


// Salida del Formato PDF
$this->pdf->Output("recibo.pdf", 'I');
