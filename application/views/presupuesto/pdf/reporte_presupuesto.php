<?php

$this->pdf   = new Pdf($orientation = 'L', $unit        = 'mm', $format      = 'A4');
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

$estado = "";
if($presupuesto->estado == 1){
	$estado = "EMITIDO";
}else if($presupuesto->estado == 2){
	$estado = "VENCIDO";
}
$this->pdf->MultiCell(185,5,utf8_decode('GOErp Intranet'),'','C',0);
$this->pdf->SetFont('Arial','',8);
//~ $this->pdf->Cell(30,5,"RIF:",'',0,'C',1);
$this->pdf->Cell(185,5,"RIF:   J-40162325-5",'',1,'C',1);
$texto = 'Av. Ppal El Castaño - # 131 - Maracay, Aragua. Municipio Girardot. 2101.';
$this->pdf->MultiCell(185,5,utf8_decode($texto),'','C',0);
$fecha = str_replace('-', '/', $presupuesto->fecha_emision);
$this->pdf->Cell(30,5,"FECHA: $fecha",'B',0,'L',1);
$this->pdf->Cell(30,5,"",'B',0,'L',1);
$this->pdf->Cell(30,5,"HORA: $presupuesto->hora_emision ",'B',0,'L',1);
$this->pdf->Cell(30,5,"ESTATUS: $estado",'B',0,'R',1);
$this->pdf->Cell(65,5,"PRESUPUESTO: $presupuesto->codpresupuesto",'B',1,'R',1);

$this->pdf->Cell(30,5,"DATOS DEL CLIENTE",'T',1,'L',1);
$this->pdf->Cell(35,5,"RIF/CI: ".$cliente->tipocliente."-".$cliente->cirif,'',0,'L',1);
$this->pdf->Cell(95,5,utf8_decode("RAZÓN SOCIAL: $presupuesto->cliente"),'',0,'L',1);
$this->pdf->Cell(25,5,utf8_decode("TELÉFONO: ").$cliente->tlf,'',1,'L',1);
$this->pdf->Cell(155,5,utf8_decode("DIRECCIÓN: $cliente->direccion"),'',1,'L',1);


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

$bi = str_replace('', '', number_format($presupuesto->base_imponible, 2, ",", "."));
$iva = str_replace('', '', number_format($presupuesto->monto_iva, 2, ",", "."));
$porcentaje_iva = 0;
$total = str_replace('', '', number_format($presupuesto->totalpresupuesto, 2, ",", "."));

// Si hay base imponible con gravamen para el presupuesto
if ($bi > 0){
	$porcentaje_iva = (float)($presupuesto->monto_iva/$presupuesto->base_imponible)*100;
	//~ $this->pdf->Cell(145,5,utf8_decode("BI G $impuesto->valor%"),'T',0,'R',1);
	$this->pdf->Cell(145,5,utf8_decode("BI G $porcentaje_iva %"),'',0,'R',1);
	$this->pdf->Cell(20,5,"",'',0,'L',1);
	$this->pdf->Cell(20,5,"$bi",'',1,'R',1);
}
// Si hay base exenta para el presupuesto
if ($presupuesto->monto_exento > 0){
	$monto_exento = str_replace('', '', number_format($presupuesto->monto_exento, 2, ",", "."));
	$this->pdf->Cell(145,5,utf8_decode("BI E"),'',0,'R',1);
	$this->pdf->Cell(20,5,"",'',0,'L',1);
	$this->pdf->Cell(20,5,"$monto_exento",'',1,'R',1);
}
// Si hay descuento para el presupuesto
if ($presupuesto->descuento > 0){
	$desc = str_replace('', '', number_format($presupuesto->monto_desc, 2, ",", "."));
	$this->pdf->Cell(145,5,utf8_decode("Desct $presupuesto->descuento %"),'',0,'R',1);
	$this->pdf->Cell(20,5,"",'',0,'L',1);
	$this->pdf->Cell(20,5,"$desc",'',1,'R',1);
}
// Si hay iva con gravamen para la factura
if ($iva > 0){
	//~ $this->pdf->Cell(145,5,utf8_decode("IVA G $impuesto->valor%"),'',0,'R',1);
	$this->pdf->Cell(145,5,utf8_decode("IVA G $porcentaje_iva %"),'',0,'R',1);
	$this->pdf->Cell(20,5,"",'',0,'L',1);
	$this->pdf->Cell(20,5,"$iva",'',1,'R',1);
}

$this->pdf->SetFont('Arial','B',14);
$this->pdf->Cell(145,5,"TOTAL",'',0,'R',1);
$this->pdf->Cell(20,5,"",'',0,'L',1);
$this->pdf->Cell(20,5,"$total",'',1,'R',1);





/* Se define el titulo, márgenes izquierdo, derecho y
 * el color de relleno predeterminado
 */
//~ $this->pdf->SetTitle(utf8_decode("Acción Centralizada"));
//~ $this->pdf->SetLeftMargin(15);
//~ $this->pdf->SetRightMargin(15);
//~ $this->pdf->SetFillColor(56, 119, 119);
// Se define el formato de fuente: Arial, negritas, tamaño 9
//~ $this->pdf->SetFont('Arial', 'B', 9);
/*
 * TITULOS DE COLUMNAS
 *
 * $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
 */
// El encabezado del PDF
//~ $this->pdf->SetFont('Arial', 'B', 13);
//~ $this->pdf->Cell(30);
//~ $this->pdf->Cell(120, 10, utf8_decode('Dirección de Planificación, Presupuesto y Control de Gestión'), 0, 0, 'C');
//~ $this->pdf->Ln('5');
//~ $this->pdf->SetFont('Arial', 'B', 12);
//~ $this->pdf->Cell(30);
//~ $this->pdf->Cell(120, 10, utf8_decode('Acción Centralizada'), 0, 0, 'C');
//~ $this->pdf->Ln(10);

# Primera Pagina Todo lo referente a los datos principales de proyecto
//~ echo $presupuesto->fecha_emision;
/*$this->pdf->SetFont('Arial', 'B', 10);
$this->pdf->SetTextColor(255, 255, 255);  # COLOR DEL TEXTO
$this->pdf->Cell(180.2, 5, utf8_decode('1. IDENTIFICACIÓN DEL PROPONENTE'), 'TBL', 1, 'C', '1');
$this->pdf->SetFont('Arial', 'B', 8);
$this->pdf->SetTextColor(0, 0, 0);  # COLOR DEL TEXTO
$this->pdf->SetFillColor(255, 255, 255);
$this->pdf->SetFont('Arial', 'B', 8);
$this->pdf->Cell(15, 7, utf8_decode('1.2 Fecha:'), 'TBL', 0, 'L', '1');
$this->pdf->SetFont('Arial', '', 8);
$this->pdf->Cell(18, 7, '12/02/2015', 'TBR', 0, 'L', '1');
$this->pdf->SetFont('Arial', 'B', 8);
$this->pdf->Cell(25, 7, utf8_decode('1.4. Responsable:'), 'TBL', 0, 'L', '1');
$this->pdf->SetFont('Arial', '', 8);
$this->pdf->Cell(82, 7, 'Luisa Perez', 'TBR', 0, 'L', '1');
$this->pdf->SetFont('Arial', 'B', 8);
$this->pdf->Cell(20, 7, utf8_decode('1.6. Teléfono:'), 'TBL', 0, 'L', '1');
$this->pdf->SetFont('Arial', '', 8);
$this->pdf->Cell(20, 7, utf8_decode(''), 'TBR', 1, 'L', '1');*/


// Salida del Formato PDF
$this->pdf->Output("presupuesto.pdf", 'I');
