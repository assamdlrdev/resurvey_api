<?php
require $_SERVER["DOCUMENT_ROOT"] . "/chithaentry/esign/verify_chitha_certificate/config.php";
$unsigned_file_path = $tmp_path . 'unsigned-' . $file_name_wo_ext . '.pdf';

// if(!file_exists(SIGNED_CHITHA_CERT_PDF)) {
//     mkdir(SIGNED_CHITHA_PDF . $sub_folder, 0777, true);
// }

// $signed_file_path = $project_path . 'cert/barak_chitha_pdf/' . $sub_folder . '/' . 'signed-' . $file_name_wo_ext . '.pdf';
$signed_file_path = SIGNED_CHITHA_CERT_PDF . 'signed-' . $file_name_wo_ext . '.pdf';

// if($user_desig_code == 'LM') {
//     // $unlink_file_path = $project_path . 'cert/barak_chitha_pdf/' . $sub_folder . '/' . $file_name_wo_ext . '.pdf';
// }
// else {
//     $signed_file_path = $project_path . 'cert/barak_chitha_pdf/' . $sub_folder . '/' . 'signed-' . $file_name_wo_ext . '.pdf';
//     // $unlink_file_path = $project_path . 'cert/barak_chitha_pdf/' . $sub_folder . '/' . $file_name_wo_ext . '.pdf';
// }

$errMsg = "";
$xmldata = (array) simplexml_load_string(filter_input(INPUT_POST, 'eSignResponse')) or die("Failed to load");
if ($xmldata["@attributes"]["errCode"] != 'NA') {
    $errCode = $xmldata["@attributes"]["errCode"];
    if(isset($xmldata ["@attributes"]["errMsg"])) {
        $msg = $xmldata ["@attributes"]["errMsg"];
        $errMsg = $msg;
    }
    else{
        $msg = 'eSign Request Cancelled.[#'.$errCode.']';
        $errMsg = $msg;
    }
    // print($msg);
    // exit();
}
$db_name = '';
$signed_document = '';
if($errMsg == "") {
    $unsigned_file = file_get_contents($unsigned_file_path);

    $txn = $xmldata ["@attributes"]["txn"];
    $txn_array = explode('----', $txn);
    $pdf_byte_range = $txn_array[1];
    
    $pkcs7 = (array) $xmldata['Signatures'];
    $pkcs7_value = $pkcs7['DocSignature'];
    $cer_value = $xmldata['UserX509Certificate'];
    
    
    $beginpem = "-----BEGIN CERTIFICATE-----\n";
    $endpem = "-----END CERTIFICATE-----\n";
    $pemdata = $beginpem . trim($cer_value) . "\n" . $endpem;
    
    $cert_data = openssl_x509_parse($pemdata);
    
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1', false);
    
    
    
    $file = $pdf->my_output($signed_file_path, 'F', $unsigned_file, $cer_value, $pkcs7_value, true, $pdf_byte_range);
    $pdf->_destroy();
    if(file_exists($unsigned_file_path)){
        unlink($unsigned_file_path);
    }
    
    // print_r([$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $user_code, $user_desig_code]);
    // exit('eSign completed.');
    
    foreach (DATABASES as $key => $value) {
        if($key == $dist_code) {
            $db_name = $value;
            break;
        }
    }
    
    
    
    if($db_name != '') {
        require("../db.php");
        $pdo=Database::connect($db_name);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $sql = "SELECT * FROM public.chitha_verification_certificates WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND user_desig_code=?";
        
        $q = $pdo->prepare($sql);
        
        $q->execute([$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $user_desig_code]);
        if($q->rowCount() > 0) {
            $record = $q->fetch();
            // $user_desig_code = '';
            // $uq->execute([$user_name, $user_code]);
            // if($uq->rowCount() > 0) {
            //     if($urow = $uq->fetch()) {
            //         $user_desig_code = $urow['user_desig_code'];
            //     }
            // }

            $token_array = $xmldata;
            $updsql = "UPDATE public.chitha_verification_certificates SET signed_by=?, token=?, updated_at=?, signed_file_name=? WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND user_desig_code=? AND is_final=?";
            
            $updq = $pdo->prepare($updsql);
            $updq->execute([$user_code, json_encode($token_array), date('Y-m-d H:i:s'), 'signed-' . $file_name_wo_ext . '.pdf', $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $user_desig_code, $is_final]);

            // //LM
            // if($user_desig_code == 'LM') {
            // }
            // //SK
            // else if($user_desig_code == 'SK') {
            //     $token_array = $xmldata;
            //     $updsql = "UPDATE chitha_verification_certificates SET signed_by=?, token=?, updated_at=?, signed_file_name=? WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND user_desig_code=?";
    
            //     $updq = $pdo->prepare($updsql);
    
            //     $updq->execute([$user_code, json_encode($token_array), date('Y-m-d H:i:s'), 'signed-' . $file_name_wo_ext . '.pdf', $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $user_desig_code]);
            // }
            // //CO
            // else if($user_desig_code == 'CO') {
            //     $token_array = $xmldata;
            //     $updsql = "UPDATE chitha_verification_certificates SET signed_by=?, token=?, updated_at=?, signed_file_name=? WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND user_desig_code=?";
    
            //     $updq = $pdo->prepare($updsql);
    
            //     $updq->execute([$user_code, json_encode($token_array), date('Y-m-d H:i:s'), 'signed-' . $file_name_wo_ext . '.pdf', $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $user_desig_code]);
            // }
            // //ADC
            // else if($user_desig_code == 'ADC') {
            //     $token_array = json_decode($record['token']);
            //     $token_array = (array) $token_array;
            //     $token_array['ADC'] = $xmldata;
            //     $updsql = "UPDATE verified_dags SET signed_by_adc=?, token=?, date_of_sign_adc=?, signed_doc=? WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?";
            //     $updAlottedSql = "UPDATE alloted_dags SET is_signed=1 WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=? AND alloted_to=? AND user_desig_code='ADC'";
    
            //     $updq = $pdo->prepare($updsql);
            //     $updAlottedq = $pdo->prepare($updAlottedSql);
    
            //     if($updq->execute([$user_code, json_encode($token_array), date('Y-m-d H:i:s'), $sub_folder . '/' . 'signed-' . $file_name_wo_ext . '.pdf', $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no])) {
            //         $updAlottedq->execute([$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $user_code]);
            //     }
            // }
            // //DC
            // else if($user_desig_code == 'DC') {
            //     $token_array = json_decode($record['token']);
            //     $token_array = (array) $token_array;
            //     $token_array['DC'] = $xmldata;
            //     $updsql = "UPDATE verified_dags SET signed_by_dc=?, token=?, date_of_sign_dc=?, signed_doc=? WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?";
            //     $updAlottedSql = "UPDATE alloted_dags SET is_signed=1 WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=? AND alloted_to=? AND user_desig_code='DC'";
    
            //     $updq = $pdo->prepare($updsql);
            //     $updAlottedq = $pdo->prepare($updAlottedSql);
    
            //     if($updq->execute([$user_code, json_encode($token_array), date('Y-m-d H:i:s'), $sub_folder . '/'. 'signed-' . $file_name_wo_ext . '.pdf', $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no])) {
            //         $updAlottedq->execute([$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $user_code]);
            //     }
            // }
        }
    
        // $q->execute([$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no]);
    
        // if($q->rowCount() > 0) {
        //     if($getDoc = $q->fetch()) {
        //         $signed_document = 'cert/barak_chitha_pdf/' . $getDoc['signed_doc'];
        //     }
        // }
        Database::disconnect();
    
    }

}
    
// if($user_desig_code == 'LM') {
//     $redirect = 'verification/LMController/verificationCertificate';
// }
// else if($user_desig_code == 'SK') {
//     $redirect = 'verification/SKController/verificationCertificate';
// }
// else if($user_desig_code == 'CO') {
//     $redirect = 'verification/COController/verificationCertificate';
// }
// else if($user_desig_code == 'ADC') {
//     $redirect = 'verification/ADCController/verificationCertificate';
// }
// else if($user_desig_code == 'DC') {
//     $redirect = 'verification/DCController/verificationCertificate';
// }

if($errMsg == "") {
    if(file_exists($unsigned_file_path)) {
        unlink($unsigned_file_path);
    }
}

//rollbacking
// if($errMsg != "") {
//     if(file_exists($unsigned_file_path)) {
//         unlink($unsigned_file_path);
//     }
//     foreach (DATABASES as $key => $value) {
//         if($key == $dist_code) {
//             $db_name = $value;
//             break;
//         }
//     }

//     if($db_name != "") {
//         require("../db.php");
//         $pdo=Database::connect($db_name);
//         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//         if($user_desig_code == 'LM') {
//             // if(file_exists($project_path.'cert/barak_chitha_fresh_pdf/'. $dynamic_file_path)) {
//             //     unlink($project_path.'cert/barak_chitha_fresh_pdf/'. $dynamic_file_path);
//             // }


//             // $vdsql = "SELECT id FROM verified_dags WHERE dist_code=? and subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?";
//             // $vddelsql = "DELETE FROM verified_dags WHERE id=?";

//             // $vdq = $pdo->prepare($vdsql);
//             // $vddelq = $pdo->prepare($vddelsql);

//             // $vdq->execute([$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no]);
//             // if($vdq->rowCount() > 0) {
//             //     if($vdrow = $vdq->fetch()) {
//             //         $id = $vdrow['id'];
//             //         $vddelq->execute([$id]);
//             //     }
//             // }
//         }
//         else if($user_desig_code == 'SK') {
            
//         }
//         else if($user_desig_code == 'CO') {
            
//         }
//         else if($user_desig_code == 'DC' || $user_desig_code == 'ADC') {
            
//         }

//         Database::disconnect();
//     }

// }





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Signed Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container-fluid" style="display:flex; justify-content:center; align-items:center;">
        <div class="m-2" style="width:70%;">
            <div class="card card-primary">
                <div class="card-header">
                    <h4 class="card-title">Status</h4>
                </div>
                <div class="card-body">
                    <?php if($db_name != '' && $signed_document != ''){ ?>
                        <embed src="<?php echo $signed_document; ?>" type="" width="100%" height="570px">
                    <?php } else { ?>
                        <?php if($errMsg != "") { ?>
                            <p style="color:red;"><?php echo $errMsg; ?></p>
                        <?php } else { ?>
                            <p style="color:green;">E-Signed completed successfully!</p>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="card-footer">
                    <a class="btn btn-primary" href="<?php echo MAIN_APPLICATION_SERVER . $redirect; ?>">
                        Back to Main Application
                    </a>
                </div>
            </div>
        </div>
        
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>
<script>
    
</script>