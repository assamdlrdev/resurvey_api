<?php
defined('BASEPATH') OR exit('No direct script access allowed');

trait CommonTrait{

    public function dataswitch()
    {
        $CI = &get_instance();
        if ($this->session->userdata('dcode') == "02") {
            $this->db = $CI->load->database('lsp3', TRUE);
        } else if ($this->session->userdata('dcode') == "05") {
            $this->db = $CI->load->database('lsp1', TRUE);
        } else if ($this->session->userdata('dcode') == "13") {
            $this->db = $CI->load->database('lsp2', TRUE);
        } else if ($this->session->userdata('dcode') == "17") {
            $this->db = $CI->load->database('lsp4', TRUE);
        } else if ($this->session->userdata('dcode') == "15") {
            $this->db = $CI->load->database('lsp5', TRUE);
        } else if ($this->session->userdata('dcode') == "14") {
            $this->db = $CI->load->database('lsp6', TRUE);
        } else if ($this->session->userdata('dcode') == "07") {
            $this->db = $CI->load->database('lsp7', TRUE);
        } else if ($this->session->userdata('dcode') == "03") {
            $this->db = $CI->load->database('lsp8', TRUE);
        } else if ($this->session->userdata('dcode') == "18") {
            $this->db = $CI->load->database('lsp9', TRUE);
        } else if ($this->session->userdata('dcode') == "12") {
            $this->db = $CI->load->database('lsp13', TRUE);
        } else if ($this->session->userdata('dcode') == "24") {
            $this->db = $CI->load->database('lsp10', TRUE);
        } else if ($this->session->userdata('dcode') == "06") {
            $this->db = $CI->load->database('lsp11', TRUE);
        } else if ($this->session->userdata('dcode') == "11") {
            $this->db = $CI->load->database('lsp12', TRUE);
        } else if ($this->session->userdata('dcode') == "12") {
            $this->db = $CI->load->database('lsp13', TRUE);
        } else if ($this->session->userdata('dcode') == "16") {
            $this->db = $CI->load->database('lsp14', TRUE);
        } else if ($this->session->userdata('dcode') == "32") {
            $this->db = $CI->load->database('lsp15', TRUE);
        } else if ($this->session->userdata('dcode') == "33") {
            $this->db = $CI->load->database('lsp16', TRUE);
        } else if ($this->session->userdata('dcode') == "34") {
            $this->db = $CI->load->database('lsp17', TRUE);
        } else if ($this->session->userdata('dcode') == "21") {
            $this->db = $CI->load->database('lsp18', TRUE);
        } else if ($this->session->userdata('dcode') == "08") {
            $this->db = $CI->load->database('lsp19', TRUE);
        } else if ($this->session->userdata('dcode') == "35") {
            $this->db = $CI->load->database('lsp20', TRUE);
        } else if ($this->session->userdata('dcode') == "36") {
            $this->db = $CI->load->database('lsp21', TRUE);
        } else if ($this->session->userdata('dcode') == "37") {
            $this->db = $CI->load->database('lsp22', TRUE);
        } else if ($this->session->userdata('dcode') == "25") {
            $this->db = $CI->load->database('lsp23', TRUE);
        } else if ($this->session->userdata('dcode') == "10") {
            $this->db = $CI->load->database('lsp24', TRUE);
        } else if ($this->session->userdata('dcode') == "38") {
            $this->db = $CI->load->database('lsp25', TRUE);
        } else if ($this->session->userdata('dcode') == "39") {
            $this->db = $CI->load->database('lsp26', TRUE);
        } else if ($this->session->userdata('dcode') == "22") {
            $this->db = $CI->load->database('lsp27', TRUE);
        } else if ($this->session->userdata('dcode') == "23") {
            $this->db = $CI->load->database('lsp28', TRUE);
        } else if ($this->session->userdata('dcode') == "01") {
            $this->db = $CI->load->database('lsp29', TRUE);
        }else if ($this->session->userdata('dcode') == "27") {
            $this->db = $CI->load->database('lsp30', TRUE);
        }else if ($this->session->userdata('dcode') == "26") {
            $this->db = $CI->load->database('lsp31', TRUE);
        }
    }
    public function dbswitch($dist_code = null)
    {
        $dist_code = $dist_code ? $dist_code : $this->session->userdata('dcode');
        $CI = &get_instance();
        if ($dist_code == "02") {
            $this->db = $CI->load->database('lsp3', TRUE);
        } else if ($dist_code == "05") {
            $this->db = $CI->load->database('lsp1', TRUE);
        } else if ($dist_code == "13") {
            $this->db = $CI->load->database('lsp2', TRUE);
        } else if ($dist_code == "17") {
            $this->db = $CI->load->database('lsp4', TRUE);
        } else if ($dist_code == "15") {
            $this->db = $CI->load->database('lsp5', TRUE);
        } else if ($dist_code == "14") {
            $this->db = $CI->load->database('lsp6', TRUE);
        } else if ($dist_code == "07") {
            $this->db = $CI->load->database('lsp7', TRUE);
        } else if ($dist_code == "03") {
            $this->db = $CI->load->database('lsp8', TRUE);
        } else if ($dist_code == "18") {
            $this->db = $CI->load->database('lsp9', TRUE);
        } else if ($dist_code == "12") {
            $this->db = $CI->load->database('lsp13', TRUE);
        } else if ($dist_code == "24") {
            $this->db = $CI->load->database('lsp10', TRUE);
        } else if ($dist_code == "06") {
            $this->db = $CI->load->database('lsp11', TRUE);
        } else if ($dist_code == "11") {
            $this->db = $CI->load->database('lsp12', TRUE);
        } else if ($dist_code == "12") {
            $this->db = $CI->load->database('lsp13', TRUE);
        } else if ($dist_code == "16") {
            $this->db = $CI->load->database('lsp14', TRUE);
        } else if ($dist_code == "32") {
            $this->db = $CI->load->database('lsp15', TRUE);
        } else if ($dist_code == "33") {
            $this->db = $CI->load->database('lsp16', TRUE);
        } else if ($dist_code == "34") {
            $this->db = $CI->load->database('lsp17', TRUE);
        } else if ($dist_code == "21") {
            $this->db = $CI->load->database('lsp18', TRUE);
        } else if ($dist_code == "08") {
            $this->db = $CI->load->database('lsp19', TRUE);
        } else if ($dist_code == "35") {
            $this->db = $CI->load->database('lsp20', TRUE);
        } else if ($dist_code == "36") {
            $this->db = $CI->load->database('lsp21', TRUE);
        } else if ($dist_code == "37") {
            $this->db = $CI->load->database('lsp22', TRUE);
        } else if ($dist_code == "25") {
            $this->db = $CI->load->database('lsp23', TRUE);
        } else if ($dist_code == "10") {
            $this->db = $CI->load->database('lsp24', TRUE);
        } else if ($dist_code == "38") {
            $this->db = $CI->load->database('lsp25', TRUE);
        } else if ($dist_code == "39") {
            $this->db = $CI->load->database('lsp26', TRUE);
        } else if ($dist_code == "22") {
            $this->db = $CI->load->database('lsp27', TRUE);
        } else if ($dist_code == "23") {
            $this->db = $CI->load->database('lsp28', TRUE);
        } else if ($dist_code == "01") {
            $this->db = $CI->load->database('lsp29', TRUE);
        }else if ($dist_code == "26") {
            $this->db = $CI->load->database('lsp31', TRUE);
        }else if($dist_code == 'default'){
            $this->db = $CI->load->database('default', TRUE);
        }else if($dist_code == 'auth'){
            $this->db = $CI->load->database('auth', TRUE);
        }
    }

    public function checkDatabaseExists($dist_code)
    {
        if(count(SURVEY_DISTRICTS)){
            if(in_array($dist_code, SURVEY_DISTRICTS)) return true;
            else return false;
        }else{
            return true;
        }
    }

    public function checkDatabaseExists1($dist_code)
    {
        // do not use this function as of now, not working
        // Load the database library if not already loaded
        // $this->load->database();
        $this->load->config('database');
        
        if ($dist_code == "02") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp3');

            // $dbName = 'dhubri';
        } else if ($dist_code == "05") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp1');

            // $dbName = 'barpeta';
        } else if ($dist_code == "13") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp2');

            // $dbName = 'bongaigaon';
        } else if ($dist_code == "17") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp4');

            // $dbName = 'cdibrugarh';
        } else if ($dist_code == "15") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp5');

            // $dbName = 'cjorhat';
        } else if ($dist_code == "14") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp6');

            // $dbName = 'golaghat';
        } else if ($dist_code == "07") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp7');

            // $dbName = 'kamrup_uat';
        } else if ($dist_code == "03") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp8');

            // $dbName = 'goalpara';
        } else if ($dist_code == "18") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp9');

            // $dbName = 'tinsukia';
        } else if ($dist_code == "12") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp13');

            // $dbName = 'clakhimpur';
        } else if ($dist_code == "24") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp10');

            // $dbName = 'ckamrupm';
        } else if ($dist_code == "06") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp11');

            // $dbName = 'nalbari';
        } else if ($dist_code == "11") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp12');

            // $dbName = 'sonitpur';
        } else if ($dist_code == "12") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp13');

            // $dbName = 'clakhimpur';
        } else if ($dist_code == "16") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp14');

            // $dbName = 'sibsagar';
        } else if ($dist_code == "32") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp15');

            // $dbName = 'morigaon';
        } else if ($dist_code == "33") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp16');

            // $dbName = 'nagaon';
        } else if ($dist_code == "34") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp17');

            // $dbName = 'majuli';
        } else if ($dist_code == "21") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp18');

            // $dbName = 'ckarimganj';
        } else if ($dist_code == "08") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp19');

            // $dbName = 'darrang';
        } else if ($dist_code == "35") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp20');

            // $dbName = 'biswanath';
        } else if ($dist_code == "36") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp21');

            // $dbName = 'hojai';
        } else if ($dist_code == "37") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp22');

            // $dbName = 'ckamrupm';
        } else if ($dist_code == "25") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp23');

            // $dbName = 'cdhemaji';
        } else if ($dist_code == "10") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp24');

            // $dbName = 'cchirang';
        } else if ($dist_code == "38") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp25');

            // $dbName = 'ssalmara';
        } else if ($dist_code == "39") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp26');

            // $dbName = 'bajali';
        } else if ($dist_code == "22") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp27');

            // $dbName = 'chailakandi';
        } else if ($dist_code == "23") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp28');

            // $dbName = 'ccachar';
        } else if ($dist_code == "01") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp29');

            // $dbName = 'ckokrajhar';
        }else if ($dist_code == "26") {
            // Access the default database name
            $dbName = $this->config->item('database', 'lsp31');

            // $dbName = 'cbaksa';
        }else if($dist_code == 'default'){
            // Access the default database name
            $dbName = $this->config->item('database', 'default');

            // $dbName = 'locmaster';
        }

        // Query to check database existence
        $query = $this->db->query("SELECT datname FROM pg_database WHERE datname=?", array($dbName));

        // Check if the query returned a result
        if ($query->num_rows() > 0) {
            return true; // Database exists
        } else {
            return false; // Database does not exist
        }
    }


    public function validvalue($str)
    {
        if ($str!=''){
            $result = substr($str, 0, 1);
            if ($result != '0') {
                return true;
            } else if($result == '0') {
                $this->form_validation->set_message('validvalue', 'The %s field cannot start with zero');
                return FALSE;
            }
        }
    }
    public function kathavalue($str)
    {
        if ($str!=''){
            if (($this->session->userdata('dist_code')=='21') || ($this->session->userdata('dist_code')=='22') || ($this->session->userdata('dist_code')=='23')){
                if ($str <20) {
                    return true;
                } else if($str >= 20) {
                    $this->form_validation->set_message('kathavalue', 'The %s field cannot be more than 20');
                    return FALSE;
                }
            } else {
                if ($str <5) {
                    return true;
                } else if($str >= 5) {
                    $this->form_validation->set_message('kathavalue', 'The %s field cannot be more than 4');
                    return FALSE;
                }
            }
        }
    }


    public function lessavalue($str)
    {
        if ($str!=''){
            if ($str <20) {
                return true;
            } else if($str >= 20) {
                $this->form_validation->set_message('lessavalue', 'The %s field cannot be more than 19');
                return FALSE;
            }
        }
    }


    public function chatakvalue($str)
    {
        if ($str!=''){
            if ($str <16) {
                return true;
            } else if($str >= 16) {
                $this->form_validation->set_message('chatakvalue', 'The %s field cannot be more than 16');
                return FALSE;
            }
        }
    }


    public function gandavalue($str)
    {
        if ($str!=''){
            if ($str <20) {
                return true;
            } else if($str >= 20) {
                $this->form_validation->set_message('gandavalue', 'The %s field cannot be more than 20');
                return FALSE;
            }
        }
    }
    public function namecheck($str){
        $pattern  = '/^[a-zA-Z\/, \s_.-: ]{2,50000}$/';

        if($str!='')
        {
            if (preg_match($pattern, $str))
            {
                return TRUE;
            }
            else
            {
                $this->form_validation->set_message('namecheck', 'The %s field can not contain special Character.');
                return FALSE;
            }
        }
    }
}