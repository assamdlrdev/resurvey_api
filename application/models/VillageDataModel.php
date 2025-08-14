<?php

class VillageDataModel extends CI_Model
{
    public static $VALIDATION_ERROR = '422';
    public static $PW = '123@#';
    public static $INCORRECT_PW = '105';
    public static $UNKNOWN_ERROR = '100';
    public static $SUCCESS = '200';

    public static $TABLES = array(
        'jama_dag',
        'jama_dag_doul',
        'jama_patta',
        'jama_patta_doul',
        'jama_pattadar',
        'jama_pattadar_doul',
        'jama_remark',
        'chitha_dag_pattadar',
        'chitha_pattadar',
        'chitha_rmk_convorder',
        't_chitha_rmk_convorder',
        'chitha_rmk_ordbasic',
        't_chitha_rmk_ordbasic',
        'petition_dag_details',
        'petition_lm_note',
        'petitioner_part',
        'petition_bo_note',
        'petition_byayprak',
        'petition_notified',
        'petition_pattadar',
        'field_part_petitioner',
        // 'petition_proceeding',
        // 'petition_proceeding_dc_adc',
        // 'petitioner',
        'chitha_col8_inplace',
        't_chitha_col8_inplace',
        'chitha_col8_occup',
        't_chitha_col8_occup',
        'chitha_col8_order',
        't_chitha_col8_order',
        'field_mut_dag_details',
        'field_mut_pattadar',
        'chitha_rmk_allottee',
        'chitha_rmk_alongwith',
        't_chitha_rmk_alongwith',
        'chitha_rmk_convorder',
        'chitha_rmk_encro',
        'chitha_rmk_gen',
        'chitha_rmk_infavor_of',
        't_chitha_rmk_infavor_of',
        'chitha_rmk_inplace_of',
        't_chitha_rmk_inplace_of',
        'chitha_rmk_lmnote',
        'chitha_rmk_onbehalf',
        'chitha_rmk_ordbasic',
        't_chitha_rmk_ordbasic',
        'chitha_rmk_other_opp_party',
        't_chitha_rmk_other_opp_party',
        'chitha_rmk_reclassification',
        't_reclassification',
        'chitha_mcrop',
        'chitha_noncrop',
        'chitha_fruit',
        'chitha_rmk_sknote',
        'apcancel_dag_details',
        'apcancel_petition_pattadar',
        'apt_chitha_rmk_ordbasic',
        'apt_chitha_rmk_other',
        'chitha_subtenant',
        'chitha_tenant',
        'chitha_basic',
        'chitha_basic_entry',
        'chitha_basic_issue',
        'patta_basic',
        'patta_basic_dag',
    );
    public static $DISTRICTS = [
        [
            'name' => 'kokrajhar',
            'code' => '01'
        ],
        [
            'name' => 'dhubri',
            'code' => '02'
        ],
        [
            'name' => 'goalpara',
            'code' => '03'
        ],
        [
            'name' => 'barpeta',
            'code' => '05'
        ],
        [
            'name' => 'nalbari',
            'code' => '06'
        ],
        [
            'name' => 'kamrup',
            'code' => '07'
        ],
        [
            'name' => 'darrang',
            'code' => '08'
        ],
        [
            'name' => 'chirang',
            'code' => '10'
        ],
        [
            'name' => 'sonitpur',
            'code' => '11'
        ],
        [
            'name' => 'lakhimpur',
            'code' => '12'
        ],
        [
            'name' => 'bongaigaon',
            'code' => '13'
        ],

        [
            'name' => 'golaghat',
            'code' => '14'
        ],
        [
            'name' => 'jorhat',
            'code' => '15'
        ],
        [
            'name' => 'sibsagar',
            'code' => '16'
        ],
        [
            'name' => 'dibrugarh',
            'code' => '17'
        ],
        [
            'name' => 'tinsukia',
            'code' => '18'
        ],
        [
            'name' => 'karimganj',
            'code' => '21'
        ],
        [
            'name' => 'chailakandi',
            'code' => '22'
        ],
        [
            'name' => 'cachar',
            'code' => '23'
        ],
        [
            'name' => 'kamrupm',
            'code' => '24'
        ],
        [
            'name' => 'dhemaji',
            'code' => '25'
        ],
        [
            'name' => 'morigaon',
            'code' => '32'
        ],
        [
            'name' => 'nagaon',
            'code' => '33'
        ],
        [
            'name' => 'majuli',
            'code' => '34'
        ],
        [
            'name' => 'biswanath',
            'code' => '35'
        ],
        [
            'name' => 'hojai',
            'code' => '36'
        ],
        [
            'name' => 'charaideo',
            'code' => '37'
        ],
        [
            'name' => 'ssalmara',
            'code' => '38'
        ],
        [
            'name' => 'bajali',
            'code' => '39'
        ]
    ];
}
