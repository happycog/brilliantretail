<?php

require_once(dirname(__FILE__).'/tcpdf/tcpdf.php');

class br_pdf
{
	function print_html($html_array=array(),$title='Packing Slip')
	{
		// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			
			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('BrilliantRetail');
			$pdf->SetTitle('Packing Slip');
			$pdf->SetSubject('Order Packing Slip');
			$pdf->SetKeywords('Order Packing Slip');
			
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			
			// set header and footer fonts
			#$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			#$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			
			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			
			// set margins
			
			$pdf->SetMargins(PDF_MARGIN_LEFT, 0, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			
			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			
			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			
			// set some language-dependent strings (optional)
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			    require_once(dirname(__FILE__).'/lang/eng.php');
			    $pdf->setLanguageArray($l);
			}
			
			// ---------------------------------------------------------
			
			// set font
			$pdf->SetFont('helvetica', '', 10);
			
			// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
			// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
			
			// create some HTML content
				foreach($html_array as $html){
					// add a page
					$pdf->AddPage();
			
					// output the HTML content
					$pdf->writeHTML($html, true, false, true, false, '');
					
					// reset pointer to the last page
					$pdf->lastPage();
				}

		
		//Close and output PDF document
		$pdf->Output($title,'FD');
	}
}