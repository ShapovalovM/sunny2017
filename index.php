<?php

include 'vendor/autoload.php';

$csv = new parseCSV('src/PeriodDetailbyTerminal.csv');

foreach ($csv->data as $i => $val) {
//for ($i=0; $i <12 ; $i++) {
    $res = [];

    foreach ($csv->data[$i] as $key => $value) {
        if ($value == '' || strpos($value,'StandardGBMag') !== false || strpos($value,'POSA') !== false) {
            continue;
        }
        $res[] = $value;
    }

    if (count($res) != 10) {
        continue;
    }

    $narrative = explode(':',$res[6]);

    $product_name = iconv('UTF-8', 'windows-1252', $narrative[1]);
    $txn_no = $res[5];
    $status = $narrative[0];
    $date = $res[0];

    $pdf = new FPDF();
    $pdf->SetAuthor('Maks Shapovalov (-_-)');
    $pdf->SetTitle('file-' . $i);

    //set font for the entire document
    $pdf->SetFont('Helvetica','',22);
    $pdf->SetTextColor(0,0,0);
    //set up a page
    $pdf->AddPage('Page');
    $pdf->SetDisplayMode('real','default');
    //display the title with a border around it
    $pdf->SetXY(0,30);
    $pdf->Cell(0,0,'Select Premier Ltd',0,1,'C');
    $pdf->SetXY(0,40);
    $pdf->Cell(0,0,'Company No: 07939633',0,1,'C');
    $pdf->SetXY(0,50);
    $pdf->Cell(0,0,'VAT No: 129509305',0,1,'C');
    $pdf->SetXY(0,60);
    $pdf->Cell(0,0,'Unit 17c St. Joseph Business Park',0,1,'C');
    $pdf->SetXY(0,70);
    $pdf->Cell(0,0,'St. Joseph Close',0,1,'C');
    $pdf->SetXY(0,80);
    $pdf->Cell(0,0,'Hove',0,1,'C');
    $pdf->SetXY(0,90);
    $pdf->Cell(0,0,'East Sussex BN3 7HG',0,1,'C');

    $pdf->SetXY(0,120);
    $pdf->SetFont('Helvetica','U',22);
    $pdf->Cell(0,0,'Sale Receipt',0,1,'C');

    $pdf->SetFont('Helvetica','',22);
    $pdf->SetXY(0,150);
    $pdf->Cell(0,0, $product_name ,0,1,'C');
    $pdf->SetXY(0,160);
    $pdf->Cell(0,0,'TXN No: 000' . $txn_no ,0,1,'C');  // ^_^  000
    $pdf->SetXY(0,170);
    $pdf->Cell(0,0,'Status: ' . $status ,0,1,'C');
    $pdf->SetXY(0,180);
    $pdf->Cell(0,0,'Date: ' . $date ,0,1,'C');

    $pdf->SetXY(0,220);
    $pdf->Cell(0,0,'Thank you for your business',0,1,'C');


    //Output the document
    $pdf->Output('F','res/ticket-' . $i . '.pdf');

}


