<?php
defined('BASEPATH') OR exit('No direct script access allowed');

trait CommonTrait
{
    /**
     * Map district codes to database groups
     */
    protected $db_map = [
        '02' => 'lsp3',
        '05' => 'lsp1',
        '13' => 'lsp2',
        '17' => 'lsp4',
        '15' => 'lsp5',
        '14' => 'lsp6',
        '07' => 'lsp7',
        '03' => 'lsp8',
        '18' => 'lsp9',
        '12' => 'lsp13',
        '24' => 'lsp10',
        '06' => 'lsp11',
        '11' => 'lsp12',
        '16' => 'lsp14',
        '32' => 'lsp15',
        '33' => 'lsp16',
        '34' => 'lsp17',
        '21' => 'lsp18',
        '08' => 'lsp19',
        '35' => 'lsp20',
        '36' => 'lsp21',
        '37' => 'lsp22',
        '25' => 'lsp23',
        '10' => 'lsp24',
        '38' => 'lsp25',
        '39' => 'lsp26',
        '22' => 'lsp27',
        '23' => 'lsp28',
        '01' => 'lsp29',
        '27' => 'lsp30',
        '26' => 'lsp31',
        'default' => 'default',
        'auth'    => 'auth'
    ];

    /**
     * Switch DB connection using session dcode
     */
    public function dataswitch()
    {
        $CI = &get_instance();
        $dcode = $CI->session->userdata('dcode');

        if (isset($this->db_map[$dcode])) {
            $this->db = $CI->load->database($this->db_map[$dcode], TRUE);
        } else {
            show_error("No database mapping found for dcode: {$dcode}");
        }
    }

    /**
     * Switch DB connection using parameter (or fallback to session dcode)
     */
    public function dbswitch($dist_code = null)
    {
        $CI = &get_instance();
        $dcode = $dist_code ?? $CI->session->userdata('dcode');

        if (isset($this->db_map[$dcode])) {
            $this->db = $CI->load->database($this->db_map[$dcode], TRUE);
        } else {
            show_error("No database mapping found for dcode: {$dcode}");
        }
    }
}
