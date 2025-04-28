<?php
//echo dirname(__DIR__,1);exit;
require_once dirname(__DIR__,1). '/vendor/autoload.php';  // Adjust path if needed

use Dompdf\Dompdf;
use Dompdf\Options;

class Receipt
{
    function exportPdf($datas)
    {
        // Your HTML content

        try {
            // Instantiate Dompdf
            // Create Dompdf options
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);  // Enable HTML5 support
            $options->set('isPhpEnabled', true);  // Enable PHP (if needed)

// Instantiate Dompdf with the custom options
            $dompdf = new Dompdf($options);

// Set custom paper size (80mm width, 200mm height)
            $paperSize = array(0, 0, 320, 550);  // This corresponds to 80mm x 200mm (width x height)
            $dompdf->setPaper($paperSize);
// Load HTML content
            $dompdf->loadHtml($datas['contents']);

// Render the PDF (first pass)
            $dompdf->render();

// Output the generated PDF to the browser
            $dompdf->stream($datas['filename'], ['Attachment' => 0]);
        } catch (\Mpdf\MpdfException $e) {
            // Handle exceptions
            echo "Error generating PDF: " . $e->getMessage();
        }
    }
    function testPrint($datas){

        try {
            // Initialize options
            $options = new Options();
            $options->set('defaultFont', 'DejaVu Sans');

            // Initialize dompdf
            $dompdf = new Dompdf($options);

            // Your PDF generation code...

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

?>