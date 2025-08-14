<?php

class ZiptableModel extends CI_Model
{
    public static $TABLES = [
        'chitha_basic',
        'chitha_pattadar',
        'chitha_dag_pattadar',
        'chitha_rmk_lmnote',
        'chitha_tenant',
        'chitha_subtenant',
        'land_bank_details',
        'jama_dag',
        'jama_pattadar',
        'jama_patta',
        'jama_remark'
    ];

    public function getDirName($filename)
    {
        $name = explode('-', $filename);
        $last_part = explode('.', $name[5]);
        $name[5] = $last_part[0];

        $dist_code  = $name[0];
        $subdiv_code  = $name[1];
        $cir_code  = $name[2];
        $mouza_pargona_code  = $name[3];
        $lot_no  = $name[4];
        $vill_townprt_code  = $name[5];

        $dir = $dist_code . '-' . $subdiv_code . '-' . $cir_code . '-' . $mouza_pargona_code . '-' . $lot_no . '-' . $vill_townprt_code;
        return $dir;
    }
    public function getLocCodes($filename)
    {
        $name = explode('-', $filename);
        $last_part = explode('.', $name[5]);
        $name[5] = $last_part[0];

        $location['dist_code']  = $name[0];
        $location['subdiv_code']  = $name[1];
        $location['cir_code']  = $name[2];
        $location['mouza_pargona_code']  = $name[3];
        $location['lot_no']  = $name[4];
        $location['vill_townprt_code']  = $name[5];
        return $location;
    }
}
