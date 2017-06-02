<?php

$this->pdf = new PdfInventarioTerminal($orientation = 'L', $unit = 'mm', $format = 'A4');
// Agregamos una página
$this->pdf->AddPage();
// Define el alias para el número de página que se imprimirá en el pie
$this->pdf->AliasNbPages();

//~ $this->pdf->AliasNbPages() # LLAMADA DE PAGINACIÓN
//~ $this->pdf->add_page() # AÑADE UNA NUEVA PAGINACIÓN
#$this->pdf->SetFont('Times','',10) # TAMANO DE LA FUENTE
$this->pdf->SetFont('Arial','B',15);
$this->pdf->SetFillColor(157,188,201); # COLOR DE BOLDE DE LA CELDA
$this->pdf->SetTextColor(24,29,31); # COLOR DEL TEXTO
$this->pdf->SetMargins(15,15,10); # MARGENES DEL DOCUMENTO
#$this->pdf->ln(20) # Saldo de línea
# 10 y 50 eje x y y 200 dimensión

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
$fecha = date('d/m/Y');
$this->pdf->Cell(30,5,"FECHA: $fecha",'B',0,'L',1);
$this->pdf->Cell(30,5,"",'B',0,'L',1);
$hora = date("h:i:s a");
$this->pdf->Cell(125,5,"HORA: $hora ",'B',1,'L',1);
//~ $this->pdf->Cell(95,5,"INVENTARIO",'B',1,'R',1);

$this->pdf->Cell(30,5,"INVENTARIO DE PRODUCTOS PARA TERMINALES",'T',1,'L',1);
//~ $this->pdf->Cell(35,5,"RIF/CI: G-30759058-0",'',0,'L',1);
//~ $this->pdf->Cell(95,5,utf8_decode("RAZÓN SOCIAL: A.C BIBLIOTECAS VIRTUALES DE ARAGUA"),'',0,'L',1);
//~ $this->pdf->Cell(25,5,utf8_decode("TELÉFONO: ").'02432336068','',1,'L',1);
//~ $this->pdf->Cell(155,5,utf8_decode("DIRECCIÓN: AV. SUCRE. URB. SAN ISIDRO - MARACAY. EDIF. BIBLIOTECA VIRTUAL. NRO. 26"),'',1,'L',1);
//~ $this->pdf->Cell(155,5,utf8_decode("OBSERVACIÓN: $autoconsumo->observaciones"),'',1,'L',1);

$this->pdf->Cell(30,5,utf8_decode("Código"),'B',0,'C',1);
$this->pdf->Cell(85,5,"Nombre",'B',0,'L',1);
$this->pdf->Cell(35,5,"Stock",'B',0,'L',1);
$this->pdf->Cell(35,5,"Stock en sedes",'B',1,'L',1);
//~ $this->pdf->Cell(20,5,"Importe",'B',1,'R',1);

foreach ($productos as $productos){
	
	$this->pdf->Cell(30,5,"$productos->codigo",'',0,'C',1);
	$this->pdf->Cell(85,5,utf8_decode("$productos->nombre"),'',0,'L',1);
	$this->pdf->Cell(35,5,"$productos->existencia",'',0,'L',1);
	$this->pdf->Cell(35,5,"",'',1,'R',1);
	#~ j = j + 10
}

$this->pdf->Cell(185,1,"",'T',1,'R',1);  // Cierre de bloque de productos

// Salida del Formato PDF
$this->pdf->Output("autoconsumo.pdf", 'I');
