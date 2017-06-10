<?php

include 'vendor/autoload.php';

// http://www.zamzar.com - converter pdf to csv

$files = glob('src/reports/*.{csv}', GLOB_BRACE);
foreach ($files as $file) {
    $fileName = basename($file, ".csv");

    print_r('Start working with file ' . $fileName);
    print_r('<br/>');

    $csv = new parseCSV('src/reports/' . $fileName . '.csv');

    $pdf = new FPDF();
    $pdf->SetAuthor('Maks Shapovalov (-_-)');
    $pdf->SetTitle($fileName);

//set font for the entire document
    $pdf->SetFont('Helvetica', '', 22);
    $pdf->SetTextColor(0, 0, 0);

    foreach ($csv->data as $i => $val) {
//for ($i=0; $i <60 ; $i++) {
        $res = [];

        foreach ($csv->data[$i] as $key => $value) {
            if ($value == '' || strpos($value, 'StandardGBMag') !== false ||
                ( (strpos($value, 'POSA') !== false) && (strpos($value, 'Nintendo eShop POSA') === false)) ) {
                    continue;
            }
            $res[] = $value;
        }

        if (count($res) != 10) {
            if (count($res) != 11) {
                continue;
            }
        }


        $narrative = explode(':', $res[6]);
        if (!isset($narrative[1])){
            $narrative = explode(':', $res[7]);
        }
        if (!isset($narrative[1])){
            print_r('<h3>');
            print_r($res);
            print_r('</h3>');
            print_r('<h3>');
            print_r($narrative);
            print_r('</h3>');
            print_r('<br/>');
        }

        $product_name = iconv('UTF-8', 'windows-1252', $narrative[1]);
        $txn_no = $res[5];
        $status = $narrative[0];
        $date = $res[0];

        //set up a page
        $pdf->AddPage('Page');
        $pdf->SetDisplayMode('real', 'default');
        //display the title with a border around it
        $pdf->SetXY(0, 30);
        $pdf->Cell(0, 0, 'Select Premier Ltd', 0, 1, 'C');
        $pdf->SetXY(0, 40);
        $pdf->Cell(0, 0, 'Company No: 07939633', 0, 1, 'C');
        $pdf->SetXY(0, 50);
        $pdf->Cell(0, 0, 'VAT No: 129509305', 0, 1, 'C');
        $pdf->SetXY(0, 60);
        $pdf->Cell(0, 0, 'Unit 17c St. Joseph Business Park', 0, 1, 'C');
        $pdf->SetXY(0, 70);
        $pdf->Cell(0, 0, 'St. Joseph Close', 0, 1, 'C');
        $pdf->SetXY(0, 80);
        $pdf->Cell(0, 0, 'Hove', 0, 1, 'C');
        $pdf->SetXY(0, 90);
        $pdf->Cell(0, 0, 'East Sussex BN3 7HG', 0, 1, 'C');

        $pdf->SetXY(0, 120);
        $pdf->SetFont('Helvetica', 'U', 22);
        $pdf->Cell(0, 0, 'Sale Receipt', 0, 1, 'C');

        $pdf->SetFont('Helvetica', '', 22);
        $pdf->SetXY(0, 150);
        $pdf->Cell(0, 0, $product_name, 0, 1, 'C');
        $pdf->SetXY(0, 160);
        $pdf->Cell(0, 0, 'TXN No: ' . sprintf("%08s", $txn_no), 0, 1, 'C');
        $pdf->SetXY(0, 170);
        $pdf->Cell(0, 0, 'Status: ' . $status, 0, 1, 'C');
        $pdf->SetXY(0, 180);
        $pdf->Cell(0, 0, 'Date: ' . $date, 0, 1, 'C');

        $pdf->SetXY(0, 220);
        $pdf->Cell(0, 0, 'Thank you for your business', 0, 1, 'C');

    }

//Output the document
    $pdf->Output('F', 'res/' . $fileName . '.pdf');

}


