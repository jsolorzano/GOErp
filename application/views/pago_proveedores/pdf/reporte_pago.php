<?php

$this->pdf = new PdfPago($orientation = 'L', $unit = 'mm', $format = 'A4');

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
$fecha = str_replace('-', '/', $pago->fecha_pago);
//~ $this->pdf->Cell(185,5,utf8_decode("N° Control: $pago->num_control"),'B',1,'R',1);
$this->pdf->Cell(30,5,"FECHA: $fecha",'B',0,'L',1);
$this->pdf->Cell(30,5,"",'B',0,'L',1);
$this->pdf->Cell(30,5,"HORA: $pago->hora_pago ",'B',0,'L',1);
$this->pdf->Cell(45,5,utf8_decode("Código único: $pago->num_control"),'B',0,'R',1);
$this->pdf->Cell(50,5,"PAGO: $pago->codpago",'B',1,'R',1);

if($pago->estado == 4 || $pago->estado == 2){
	$condicion_pago = "";
	$num_referencia = "";
	if($pago->condicion_pago == '1'){
		$condicion_pago = "CHEQUE";
		$num_referencia = $pago->num_cheque;
	}else if($pago->condicion_pago == '2'){
		$condicion_pago = "DEBITO";
		$num_referencia = $pago->num_recibo;
	}else if($pago->condicion_pago == '3'){
		$condicion_pago = "EFECTIVO";
		$num_referencia = "";
	}else if($pago->condicion_pago == '4'){
		$condicion_pago = "TRANSFERENCIA";
		$num_referencia = $pago->num_transf;
	}else if($pago->condicion_pago == '5'){
		$condicion_pago = "DEPOSITO";
		$num_referencia = $pago->num_deposito;
	}
	$this->pdf->Cell(60,5,utf8_decode("MÉTODO DE PAGO: $condicion_pago"),'B',0,'L',1);
	$this->pdf->Cell(75,5,utf8_decode("NÚMERO DE REFERENCIA: $num_referencia"),'B',0,'L',1);
	$this->pdf->Cell(50,5,"",'B',1,'R',1);
}

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
$this->pdf->Cell(95,5,utf8_decode("RAZÓN SOCIAL: $pago->proveedor"),'',0,'L',1);
$this->pdf->Cell(25,5,utf8_decode("TELÉFONO: ").$telf,'',1,'L',1);
$this->pdf->Cell(155,5,utf8_decode("DIRECCIÓN: $dir"),'',1,'L',1);

$this->pdf->Cell(185,1,"",'T',1,'R',1);  // Cierre de bloque de productos

$total = str_replace('', '', number_format($pago->monto, 2, ",", "."));

$this->pdf->SetFont('Arial','B',14);
$this->pdf->Cell(145,5,"TOTAL",'',0,'R',1);
$this->pdf->Cell(20,5,"",'',0,'L',1);
$this->pdf->Cell(20,5,"$total",'',1,'R',1);

// Salida del Formato PDF
$this->pdf->Output("factura.pdf", 'I');
