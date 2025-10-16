<?php
require $_SERVER["DOCUMENT_ROOT"] . "/esign/config.php";


//$file = file_get_contents(FILE_NAME); //binary value
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1', false);



// IMAGICK
$imagick = new Imagick();
$imagick->setBackgroundColor(new ImagickPixel('transparent'));
// $imagick->setResolution(288, 288);
$imagick->setResolution(160, 160);
$imagick->readImage($project_path.'cert/chitha_fresh_pdf/'. $dynamic_file_path); //$imagick can read pdf and image too

//get file name only w/o ext.

$num_pages = $imagick->getNumberImages();
// Convert PDF pages to images
for ($i = 0; $i < $num_pages; $i++) {
    $imagick->setIteratorIndex($i);
    $imagick->setImageFormat('jpeg');
    $imagick->stripImage();
    $imagick->writeImage($tmp_path . $file_name_wo_ext . '-' . $i . '.jpg');
   
}
$imagick->destroy();





// TCPDF
// set certificate file
$info = array();
for($i=0; $i<$num_pages; $i++) {
    // set document signature
    $pdf->my_set_sign('', '', '', '', 2, $info); //custom function TCPDF  library tcpdf.php
    $pdf->AddPage();
    $pdf->Image($tmp_path . $file_name_wo_ext . '-' . $i . '.jpg');
    // $pdf->SetFont('times', '', 8);
    $pdf->setCellPaddings(0, 0, 0, 0);
    $bMargin = 0;
    // $auto_page_break = $pdf->getAutoPageBreak();
    // $pdf->SetAutoPageBreak(false, 0);
    $pdf->setPageMark();
    // $pdf->setCellPaddings(0, 0, 0, 0);
    $pdfPageCount = $i + 1;
    $pdf->setPage(($pdfPageCount), true);

    $font_size = 8;
    if($user_desig_code == 'LM'){
        // $digital_sign = 'Digitally Signed by: '. $sign_users->lm->user_name . ' (' . $user_desig_code . ')' . "\n" . 'Date: ' . date('d-m-Y') . "\n" . date('h:i a');
        $digital_sign = 'Digitally Signed by: '. $sign_users->lm->user_name . ' (LRA)' . "\n" . 'Date: ' . date('d-m-Y') . "\n" . date('h:i a');
        $pdf->SetFont('freeserif', '', $font_size, '', false);
        $pdf->setSignatureAppearance(3, 255, 35, 17, $num_pages, $sign_users->lm->user_name); //X,Y,Width,Height
        $pdf->MultiCell(35, 10, $digital_sign, 0, '', 0, 1, 3, 255, true);
    }else if($user_desig_code == 'SK'){
        //PREVIOUS LM SIGNATURE
        $digital_sign = 'LRA Name: '. $sign_users->lm->user_name;
        // $digital_sign = 'Digitally Signed by: '. $sign_users->lm->user_name . ' (LM)' . "\n" . 'Date: ' . date('d-m-Y', strtotime($sign_users->lm->date_of_sign)) . "\n" . date('h:i a', strtotime($sign_users->lm->time_of_sign));
        $pdf->SetFont('freeserif', '', $font_size, '', false);
        $pdf->setSignatureAppearance(1, 255, 35, 17, $num_pages, $sign_users->lm->user_name); //X,Y,Width,Height
        $pdf->MultiCell(35, 10, $digital_sign, 0, '', 0, 1, 1, 255, true);
        //SK SIGNATURE
        $digital_sign = 'Digitally Signed by: '. $sign_users->sk->user_name . ' (LRS)' . "\n" . 'Date: ' . date('d-m-Y') . "\n" . date('h:i a');
        $pdf->SetFont('freeserif', '', $font_size, '', false);
        $pdf->setSignatureAppearance(40, 255, 35, 17, $num_pages, $sign_users->sk->user_name); //X,Y,Width,Height
        $pdf->MultiCell(35, 10, $digital_sign, 0, '', 0, 1, 40, 255, true);
    }else if($user_desig_code == 'CO'){
        //PREVIOUS LM SIGNATURE
        // $digital_sign = 'Digitally Signed by: '. $sign_users->lm->user_name . ' (LM)' . "\n" . 'Date: ' . date('d-m-Y', strtotime($sign_users->lm->date_of_sign)) . "\n" . date('h:i a', strtotime($sign_users->lm->time_of_sign));
        $digital_sign = 'LRA Name: '. $sign_users->lm->user_name;
        $pdf->SetFont('freeserif', '', $font_size, '', false);
        $pdf->setSignatureAppearance(1, 255, 35, 17, $num_pages, $sign_users->lm->user_name); //X,Y,Width,Height
        $pdf->MultiCell(35, 10, $digital_sign, 0, '', 0, 1, 1, 255, true);
        //PREVIOUS SK SIGNATURE
        $digital_sign = 'Digitally Signed by: '. $sign_users->sk->user_name . ' (LRS)' . "\n" . 'Date: ' . date('d-m-Y', strtotime($sign_users->sk->date_of_sign)) . "\n" . date('h:i a', strtotime($sign_users->sk->time_of_sign));
        $pdf->SetFont('freeserif', '', $font_size, '', false);
        $pdf->setSignatureAppearance(40, 255, 35, 17, $num_pages, $sign_users->sk->user_name); //X,Y,Width,Height
        $pdf->MultiCell(35, 10, $digital_sign, 0, '', 0, 1, 40, 255, true);
        //CO SIGNATURE
        $digital_sign = 'Digitally Signed by: '. $sign_users->co->user_name . ' (CO)' . "\n" . 'Date: ' . date('d-m-Y') . "\n" . date('h:i a');
        $pdf->SetFont('freeserif', '', $font_size, '', false);
        $pdf->setSignatureAppearance(80, 255, 35, 17, $num_pages, $sign_users->co->user_name); //X,Y,Width,Height
        $pdf->MultiCell(35, 10, $digital_sign, 0, '', 0, 1, 80, 255, true);
    }else if($user_desig_code == 'ADC'){
        //PREVIOUS LM SIGNATURE
        // $digital_sign = 'Digitally Signed by: '. $sign_users->lm->user_name . ' (LM)' . "\n" . 'Date: ' . date('d-m-Y', strtotime($sign_users->lm->date_of_sign)) . "\n" . date('h:i a', strtotime($sign_users->lm->time_of_sign));
        $digital_sign = 'LRA Name: '. $sign_users->lm->user_name;
        $pdf->SetFont('freeserif', '', $font_size, '', false);
        $pdf->setSignatureAppearance(1, 255, 35, 17, $num_pages, $sign_users->lm->user_name); //X,Y,Width,Height
        $pdf->MultiCell(35, 10, $digital_sign, 0, '', 0, 1, 1, 255, true);
        //PREVIOUS SK SIGNATURE
        $digital_sign = 'Digitally Signed by: '. $sign_users->sk->user_name . ' (LRS)' . "\n" . 'Date: ' . date('d-m-Y', strtotime($sign_users->sk->date_of_sign)) . "\n" . date('h:i a', strtotime($sign_users->sk->time_of_sign));
        $pdf->SetFont('freeserif', '', $font_size, '', false);
        $pdf->setSignatureAppearance(40, 255, 35, 17, $num_pages, $sign_users->sk->user_name); //X,Y,Width,Height
        $pdf->MultiCell(35, 10, $digital_sign, 0, '', 0, 1, 40, 255, true);
        //PREVIOUS CO SIGNATURE
        $digital_sign = 'Digitally Signed by: '. $sign_users->co->user_name . ' (CO)' . "\n" . 'Date: ' . date('d-m-Y', strtotime($sign_users->co->date_of_sign)) . "\n" . date('h:i a', strtotime($sign_users->co->time_of_sign));
        $pdf->SetFont('freeserif', '', $font_size, '', false);
        $pdf->setSignatureAppearance(80, 255, 35, 17, $num_pages, $sign_users->co->user_name); //X,Y,Width,Height
        $pdf->MultiCell(35, 10, $digital_sign, 0, '', 0, 1, 80, 255, true);
        //ADC SIGNATURE
        $digital_sign = 'Digitally Signed by: '. $sign_users->adc->user_name . ' (ADC)' . "\n" . 'Date: ' . date('d-m-Y') . "\n" . date('h:i a');
        $pdf->SetFont('freeserif', '', $font_size, '', false);
        $pdf->setSignatureAppearance(120, 255, 35, 17, $num_pages, $sign_users->adc->user_name); //X,Y,Width,Height
        $pdf->MultiCell(35, 10, $digital_sign, 0, '', 0, 1, 120, 255, true);
    }else if($user_desig_code == 'DC'){
        //PREVIOUS LM SIGNATURE
        // $digital_sign = 'Digitally Signed by: '. $sign_users->lm->user_name . ' (LM)' . "\n" . 'Date: ' . date('d-m-Y', strtotime($sign_users->lm->date_of_sign)) . "\n" . date('h:i a', strtotime($sign_users->lm->time_of_sign));
        $digital_sign = 'LRA Name: '. $sign_users->lm->user_name;
        $pdf->SetFont('freeserif', '', $font_size, '', false);
        $pdf->setSignatureAppearance(1, 255, 35, 17, $num_pages, $sign_users->lm->user_name); //X,Y,Width,Height
        $pdf->MultiCell(35, 10, $digital_sign, 0, '', 0, 1, 1, 255, true);
        //PREVIOUS SK SIGNATURE
        $digital_sign = 'Digitally Signed by: '. $sign_users->sk->user_name . ' (LRS)' . "\n" . 'Date: ' . date('d-m-Y', strtotime($sign_users->sk->date_of_sign)) . "\n" . date('h:i a', strtotime($sign_users->sk->time_of_sign));
        $pdf->SetFont('freeserif', '', $font_size, '', false);
        $pdf->setSignatureAppearance(40, 255, 35, 17, $num_pages, $sign_users->sk->user_name); //X,Y,Width,Height
        $pdf->MultiCell(35, 10, $digital_sign, 0, '', 0, 1, 40, 255, true);
        //PREVIOUS CO SIGNATURE
        $digital_sign = 'Digitally Signed by: '. $sign_users->co->user_name . ' (CO)' . "\n" . 'Date: ' . date('d-m-Y', strtotime($sign_users->co->date_of_sign)) . "\n" . date('h:i a', strtotime($sign_users->co->time_of_sign));
        $pdf->SetFont('freeserif', '', $font_size, '', false);
        $pdf->setSignatureAppearance(80, 255, 35, 17, $num_pages, $sign_users->co->user_name); //X,Y,Width,Height
        $pdf->MultiCell(35, 10, $digital_sign, 0, '', 0, 1, 80, 255, true);
        //PREVIOUS ADC SIGNATURE
        $digital_sign = 'Digitally Signed by: '. $sign_users->adc->user_name . ' (ADC)' . "\n" . 'Date: ' . date('d-m-Y', strtotime($sign_users->adc->date_of_sign)) . "\n" . date('h:i a', strtotime($sign_users->adc->time_of_sign));
        $pdf->SetFont('freeserif', '', $font_size, '', false);
        $pdf->setSignatureAppearance(120, 255, 35, 17, $num_pages, $sign_users->adc->user_name); //X,Y,Width,Height
        $pdf->MultiCell(35, 10, $digital_sign, 0, '', 0, 1, 120, 255, true);
        //DC SIGNATURE
        $digital_sign = 'Digitally Signed by: '. $sign_users->dc->user_name . ' (DC)' . "\n" . 'Date: ' . date('d-m-Y') . "\n" . date('h:i a');
        $pdf->SetFont('freeserif', '', $font_size, '', false);
        $pdf->setSignatureAppearance(160, 255, 35, 17, $num_pages, $sign_users->dc->user_name); //X,Y,Width,Height
        $pdf->MultiCell(35, 10, $digital_sign, 0, '', 0, 1, 160, 255, true);
    }
    
}
$doc_path = $tmp_path . 'unsigned-' . $file_name_wo_ext . '.pdf';
$file = $pdf->my_output($doc_path, 'F'); //F-Force download, S-Source buffer returns binary, reffer my_output function from tcpdf.php file
$pdf_byte_range = $pdf->pdf_byte_range;
$pdf->_destroy();





//FILE_HASH
$file_hash = hash_file('sha256',$doc_path);
//after pdf done using images, delete that temp images from folder.
for ($i = 0; $i < $num_pages; $i++) {
    unlink($tmp_path . $file_name_wo_ext . '-' . $i . '.jpg'); //remove images after PDF generated/converted from temp folder
}





//DOC PREPARATION AND SAVE
$doc = new DOMDocument();
//randome number gerator rand(1,9)
$txn = rand(111111111111,999999999999). '----' . $pdf_byte_range; //$pdf_byte_range signiture space location
$ts = date('Y-m-d\TH:i:s');
$doc_info = FILE_NAME;
$xmlstr = '<Esign AuthMode="1" aspId="' . ASPID . '" ekycId="" ekycIdType="A" responseSigType="pkcs7" responseUrl="' . RESPONSE_URL . '?param=' . $getParams . '" sc="y" ts="' . $ts . '" txn="' . $txn . '" ver="2.1"><Docs><InputHash docInfo="' . $txn . '" hashAlgorithm="SHA256" id="1">' . $file_hash . '</InputHash></Docs></Esign>';
$doc->loadXML($xmlstr); //parser
// Create a new Security object 
$objDSig = new RobRichards\XMLSecLibs\XMLSecurityDSig();
// Use the c14n exclusive canonicalization
$objDSig->setCanonicalMethod(RobRichards\XMLSecLibs\XMLSecurityDSig::C14N);
// Sign using SHA-256
$objDSig->addReference(
        $doc,
        RobRichards\XMLSecLibs\XMLSecurityDSig::SHA1,
        array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'),
        array('force_uri' => true)
);
// Create a new (private) Security key
$objKey = new RobRichards\XMLSecLibs\XMLSecurityKey(RobRichards\XMLSecLibs\XMLSecurityKey::RSA_SHA1, array('type' => 'private'));
//If key has a passphrase, set it using
$objKey->passphrase = '';
// Load the private key
$objKey->loadKey(PRIVATEKEY, TRUE);
// Sign the XML file
$objDSig->sign($objKey);
// Append the signature to the XML
$objDSig->appendSignature($doc->documentElement);
$signXML = $doc->saveXML();
$signXML = str_replace('<?xml version="1.0"?>', '', $signXML);
ob_end_clean();



?>
<form action="<?php echo ESIGN_URL; ?>" method="post" id="formid">
    <input type="hidden" id="eSignRequest" name="eSignRequest" value='<?php echo $signXML; ?>'/>
    <input type="hidden" id="aspTxnID" name="aspTxnID" value="<?php echo $txn; ?>"/>
    <input type="hidden" id="Content-Type" name="Content-Type" value="application/xml"/>
</form>
<script>

    document.getElementById("formid").submit();
</script>



