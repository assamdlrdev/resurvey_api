<?php
class CommonModel extends CI_Model
{
    public static $ADD_QUERIES = [
        [
            'name' => 'chitha_dag_pattadar_chitha_basic_fk',
            'table' => 'chitha_dag_pattadar',
            'query' => 'ALTER TABLE IF EXISTS public.chitha_dag_pattadar
            ADD CONSTRAINT  chitha_dag_pattadar_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION',
        ],
        [
            'name' => 'chitha_rmk_gen_chitha_basic_fk',
            'table' => 'chitha_rmk_gen',
            'query' => 'ALTER TABLE IF EXISTS public.chitha_rmk_gen
            ADD CONSTRAINT  chitha_rmk_gen_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION',
        ],
        [
            'name' => 'chitha_rmk_infavor_of_chitha_basic_fk',
            'table' => 'chitha_rmk_infavor_of',
            'query' => 'ALTER TABLE IF EXISTS public.chitha_rmk_infavor_of
            ADD CONSTRAINT  chitha_rmk_infavor_of_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION',
        ],
        [
            'name' => 'chitha_rmk_inplace_of_chitha_basic_fk',
            'table' => 'chitha_rmk_inplace_of',
            'query' => 'ALTER TABLE IF EXISTS public.chitha_rmk_inplace_of
            ADD CONSTRAINT  chitha_rmk_inplace_of_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION',
        ],
        [
            'name' => 'chitha_rmk_onbehalf_chitha_basic_fk',
            'table' => 'chitha_rmk_onbehalf',
            'query' => 'ALTER TABLE IF EXISTS public.chitha_rmk_onbehalf
            ADD CONSTRAINT  chitha_rmk_onbehalf_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION',
        ],
        [
            'name' => 't_chitha_rmk_onbehalf_chitha_basic_fk',
            'table' => 't_chitha_rmk_onbehalf',
            'query' => 'ALTER TABLE IF EXISTS public.t_chitha_rmk_onbehalf
            ADD CONSTRAINT  t_chitha_rmk_onbehalf_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION',
        ],
        [
            'name' => 'chitha_rmk_ordbasic_chitha_basic_fk',
            'table' => 'chitha_rmk_ordbasic',
            'query' => "ALTER TABLE IF EXISTS public.chitha_rmk_ordbasic
            ADD CONSTRAINT  chitha_rmk_ordbasic_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_rmk_convorder_chitha_basic_fk',
            'table' => 'chitha_rmk_convorder',
            'query' => "ALTER TABLE IF EXISTS public.chitha_rmk_convorder
            ADD CONSTRAINT  chitha_rmk_convorder_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_rmk_allottee_chitha_basic_fk',
            'table' => 'chitha_rmk_allottee',
            'query' => "ALTER TABLE IF EXISTS public.chitha_rmk_allottee
            ADD CONSTRAINT  chitha_rmk_allottee_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 't_chitha_rmk_allottee_chitha_basic_fk',
            'table' => 't_chitha_rmk_allottee',
            'query' => "ALTER TABLE IF EXISTS public.t_chitha_rmk_allottee
            ADD CONSTRAINT  t_chitha_rmk_allottee_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_mcrop_chitha_basic_fk',
            'table' => 'chitha_mcrop',
            'query' => "ALTER TABLE IF EXISTS public.chitha_mcrop
            ADD CONSTRAINT  chitha_mcrop_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_noncrop_chitha_basic_fk',
            'table' => 'chitha_noncrop',
            'query' => "ALTER TABLE IF EXISTS public.chitha_noncrop
            ADD CONSTRAINT  chitha_noncrop_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_fruit_chitha_basic_fk',
            'table' => 'chitha_fruit',
            'query' => "ALTER TABLE IF EXISTS public.chitha_fruit
            ADD CONSTRAINT  chitha_fruit_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'apcancel_dag_details_chitha_basic_fk',
            'table' => 'apcancel_dag_details',
            'query' => "ALTER TABLE IF EXISTS public.apcancel_dag_details
            ADD CONSTRAINT  apcancel_dag_details_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'apcancel_petition_pattadar_chitha_basic_fk',
            'table' => 'apcancel_petition_pattadar',
            'query' => "ALTER TABLE IF EXISTS public.apcancel_petition_pattadar
            ADD CONSTRAINT  apcancel_petition_pattadar_chitha_basic_fk FOREIGN KEY (dist_code,subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code,subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'apt_chitha_rmk_ordbasic_chitha_basic_fk',
            'table' => 'apt_chitha_rmk_ordbasic',
            'query' => "ALTER TABLE IF EXISTS public.apt_chitha_rmk_ordbasic
            ADD CONSTRAINT  apt_chitha_rmk_ordbasic_chitha_basic_fk FOREIGN KEY (dist_code,subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code,subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'petition_dag_details_chitha_basic_fk',
            'table' => 'petition_dag_details',
            'query' => "ALTER TABLE IF EXISTS public.petition_dag_details
            ADD CONSTRAINT  petition_dag_details_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'apt_chitha_rmk_other_chitha_basic_fk',
            'table' => 'apt_chitha_rmk_other',
            'query' => "ALTER TABLE IF EXISTS public.apt_chitha_rmk_other
            ADD CONSTRAINT  apt_chitha_rmk_other_chitha_basic_fk FOREIGN KEY (dist_code,subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code,subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        // for bajali
        //  [
        //      'name' => 'apcancel_dag_details_apcancel_petition_basic_fk',
        //      'table' => 'apcancel_dag_details',
        //      'query' => "ALTER TABLE IF EXISTS public.apcancel_dag_details
        //      ADD CONSTRAINT  apcancel_dag_details_apcancel_petition_basic_fk FOREIGN KEY (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no)
        //      REFERENCES public.apcancel_petition_basic (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no) MATCH FULL
        //      ON UPDATE NO ACTION
        //      ON DELETE NO ACTION"
        //  ],
        // // for bajali
        //  [
        //      'name' => 'apcancel_petition_lm_note_apcancel_petition_basic_fk',
        //      'table' => 'apcancel_petition_lm_note',
        //      'query' => "ALTER TABLE IF EXISTS public.apcancel_petition_lm_note
        //      ADD CONSTRAINT  apcancel_petition_lm_note_apcancel_petition_basic_fk FOREIGN KEY (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no)
        //      REFERENCES public.apcancel_petition_basic (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no) MATCH FULL
        //      ON UPDATE NO ACTION
        //      ON DELETE NO ACTION"
        //  ],
        //  //for bajali
        //  [
        //      'name' => 'apcancel_petition_pattadar_apcancel_petition_basic_fk',
        //      'table' => 'apcancel_petition_pattadar',
        //      'query' => "ALTER TABLE IF EXISTS public.apcancel_petition_pattadar
        //      ADD CONSTRAINT  apcancel_petition_pattadar_apcancel_petition_basic_fk FOREIGN KEY (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no)
        //      REFERENCES public.apcancel_petition_basic (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no) MATCH FULL
        //      ON UPDATE NO ACTION
        //      ON DELETE NO ACTION"
        //  ],
        // // for bajali
        //  [
        //      'name' => 'apcancel_petitioner_apcancel_petition_basic_fk',
        //      'table' => 'apcancel_petitioner',
        //      'query' => "ALTER TABLE IF EXISTS public.apcancel_petitioner
        //      ADD CONSTRAINT  apcancel_petitioner_apcancel_petition_basic_fk FOREIGN KEY (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no)
        //      REFERENCES public.apcancel_petition_basic (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no) MATCH FULL
        //      ON UPDATE NO ACTION
        //      ON DELETE NO ACTION"
        //  ],
        [
            'name' => 'petition_lm_note_chitha_basic_fk',
            'table' => 'petition_lm_note',
            'query' => "ALTER TABLE IF EXISTS public.petition_lm_note
            ADD CONSTRAINT  petition_lm_note_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'petition_pattadar_chitha_basic_fk',
            'table' => 'petition_pattadar',
            'query' => "ALTER TABLE IF EXISTS public.petition_pattadar
            ADD CONSTRAINT  petition_pattadar_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 't_chitha_rmk_infavor_of_chitha_basic_fk',
            'table' => 't_chitha_rmk_infavor_of',
            'query' => "ALTER TABLE IF EXISTS public.t_chitha_rmk_infavor_of
            ADD CONSTRAINT  t_chitha_rmk_infavor_of_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 't_chitha_rmk_inplace_of_chitha_basic_fk',
            'table' => 't_chitha_rmk_inplace_of',
            'query' => "ALTER TABLE IF EXISTS public.t_chitha_rmk_inplace_of
            ADD CONSTRAINT  t_chitha_rmk_inplace_of_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 't_chitha_rmk_ordbasic_chitha_basic_fk',
            'table' => 't_chitha_rmk_ordbasic',
            'query' => "ALTER TABLE IF EXISTS public.t_chitha_rmk_ordbasic
            ADD CONSTRAINT  t_chitha_rmk_ordbasic_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_rmk_encro_chitha_basic_fk',
            'table' => 'chitha_rmk_encro',
            'query' => "ALTER TABLE IF EXISTS public.chitha_rmk_encro
            ADD CONSTRAINT  chitha_rmk_encro_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 't_chitha_rmk_convorder_chitha_basic_fk',
            'table' => 't_chitha_rmk_convorder',
            'query' => "ALTER TABLE IF EXISTS public.t_chitha_rmk_convorder
            ADD CONSTRAINT  t_chitha_rmk_convorder_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_basic_location_fk',
            'table' => 'chitha_basic',
            'query' => "ALTER TABLE IF EXISTS public.chitha_basic
            ADD CONSTRAINT  chitha_basic_location_fk FOREIGN KEY (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code)
            REFERENCES public.location (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_col8_occup_chitha_basic_fk',
            'table' => 'chitha_col8_occup',
            'query' => "ALTER TABLE IF EXISTS public.chitha_col8_occup
            ADD CONSTRAINT  chitha_col8_occup_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_col8_inplace_chitha_basic_fk',
            'table' => 'chitha_col8_inplace',
            'query' => "ALTER TABLE IF EXISTS public.chitha_col8_inplace
            ADD CONSTRAINT  chitha_col8_inplace_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 't_chitha_col8_inplace_chitha_basic_fk',
            'table' => 't_chitha_col8_inplace',
            'query' => "ALTER TABLE IF EXISTS public.t_chitha_col8_inplace
            ADD CONSTRAINT  t_chitha_col8_inplace_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 't_chitha_col8_occup_chitha_basic_fk',
            'table' => 't_chitha_col8_occup',
            'query' => "ALTER TABLE IF EXISTS public.t_chitha_col8_occup
            ADD CONSTRAINT  t_chitha_col8_occup_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 't_chitha_col8_order_chitha_basic_fk',
            'table' => 't_chitha_col8_order',
            'query' => "ALTER TABLE IF EXISTS public.t_chitha_col8_order
            ADD CONSTRAINT  t_chitha_col8_order_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'allotee_dag_fk',
            'table' => 'allotment_pet_dag',
            'query' => "ALTER TABLE IF EXISTS public.allotment_pet_dag
            ADD CONSTRAINT  allotee_dag_fk FOREIGN KEY (dist_code, subdiv_code, year_no, circle_code, case_no, mouza_pargona_code, vill_townprt_code,  lot_no)
            REFERENCES public.allotment_cert_basic (dist_code, subdiv_code, year_no, circle_code, case_no, mouza_pargona_code, vill_townprt_code,  lot_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        // [
        //     'name' => 'allotee_doc_fk',
        //     'table' => 'allotment_doc_details',
        //     'query' => "ALTER TABLE IF EXISTS public.allotment_doc_details
        //     ADD CONSTRAINT  allotee_doc_fk FOREIGN KEY (dist_code, subdiv_code, year_no, circle_code, case_no, mouza_pargona_code, vill_townprt_code,  lot_no)
        //     REFERENCES public.allotment_cert_basic (dist_code, subdiv_code, year_no, circle_code, case_no, mouza_pargona_code, vill_townprt_code,  lot_no) MATCH FULL
        //     ON UPDATE NO ACTION
        //     ON DELETE NO ACTION"
        // ],
        // [
        //     'name' => 'allotee_pet_fk',
        //     'table' => 'allotment_petitioner',
        //     'query' => "ALTER TABLE IF EXISTS public.allotment_petitioner
        //     ADD CONSTRAINT  allotee_pet_fk FOREIGN KEY (dist_code, subdiv_code, year_no, circle_code, case_no, mouza_pargona_code, vill_townprt_code,  lot_no)
        //     REFERENCES public.allotment_cert_basic (dist_code, subdiv_code, year_no, circle_code, case_no, mouza_pargona_code, vill_townprt_code,  lot_no) MATCH FULL
        //     ON UPDATE NO ACTION
        //     ON DELETE NO ACTION"
        // ],
        [
            'name' => 'chitha_col8_order_chitha_basic_fk',
            'table' => 'chitha_col8_order',
            'query' => "ALTER TABLE IF EXISTS public.chitha_col8_order
            ADD CONSTRAINT  chitha_col8_order_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'field_part_petitioner_chitha_basic_fk',
            'table' => 'field_part_petitioner',
            'query' => "ALTER TABLE IF EXISTS public.field_part_petitioner
            ADD CONSTRAINT  field_part_petitioner_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'field_part_petitioner_field_mut_basic_fk',
            'table' => 'field_part_petitioner',
            'query' => "ALTER TABLE IF EXISTS public.field_part_petitioner
            ADD CONSTRAINT  field_part_petitioner_field_mut_basic_fk FOREIGN KEY (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no)
            REFERENCES public.field_mut_basic (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_rmk_other_opp_party_chitha_basic_fk',
            'table' => 'chitha_rmk_other_opp_party',
            'query' => "ALTER TABLE IF EXISTS public.chitha_rmk_other_opp_party
            ADD CONSTRAINT  chitha_rmk_other_opp_party_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 't_chitha_rmk_other_opp_party_chitha_basic_fk',
            'table' => 't_chitha_rmk_other_opp_party',
            'query' => "ALTER TABLE IF EXISTS public.t_chitha_rmk_other_opp_party
            ADD CONSTRAINT  t_chitha_rmk_other_opp_party_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'petitioner_part_chitha_basic_fk',
            'table' => 'petitioner_part',
            'query' => "ALTER TABLE IF EXISTS public.petitioner_part
            ADD CONSTRAINT  petitioner_part_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_rmk_reclassification_chitha_basic_fk',
            'table' => 'chitha_rmk_reclassification',
            'query' => "ALTER TABLE IF EXISTS public.chitha_rmk_reclassification
            ADD CONSTRAINT  chitha_rmk_reclassification_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 't_reclassification_chitha_basic_fk',
            'table' => 't_reclassification',
            'query' => "ALTER TABLE IF EXISTS public.t_reclassification
            ADD CONSTRAINT  t_reclassification_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_subtenant_chitha_basic_fk',
            'table' => 'chitha_subtenant',
            'query' => "ALTER TABLE IF EXISTS public.chitha_subtenant
            ADD CONSTRAINT  chitha_subtenant_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        // [
        //     'name' => 'chitha_tenant_chitha_basic_fk',
        //     'table' => 'chitha_tenant_backup',
        //     'query' => "ALTER TABLE IF EXISTS public.chitha_tenant_backup
        //     ADD CONSTRAINT  chitha_tenant_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
        //     REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
        //     ON UPDATE NO ACTION
        //     ON DELETE NO ACTION"
        // ],
        [
            'name' => 'chitha_tenant_chitha_basic_fk',
            'table' => 'chitha_tenant',
            'query' => "ALTER TABLE IF EXISTS public.chitha_tenant
            ADD CONSTRAINT  chitha_tenant_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_rmk_alongwith_chitha_basic_fk',
            'table' => 'chitha_rmk_alongwith',
            'query' => "ALTER TABLE IF EXISTS public.chitha_rmk_alongwith
            ADD CONSTRAINT  chitha_rmk_alongwith_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 't_chitha_rmk_alongwith_chitha_basic_fk',
            'table' => 't_chitha_rmk_alongwith',
            'query' => "ALTER TABLE IF EXISTS public.t_chitha_rmk_alongwith
            ADD CONSTRAINT  t_chitha_rmk_alongwith_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'field_mut_petitioner_field_mut_basic_fk',
            'table' => 'field_mut_petitioner',
            'query' => "ALTER TABLE IF EXISTS public.field_mut_petitioner
            ADD CONSTRAINT  field_mut_petitioner_field_mut_basic_fk FOREIGN KEY (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no)
            REFERENCES public.field_mut_basic (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'field_mut_dag_details_field_mut_basic_fk',
            'table' => 'field_mut_dag_details',
            'query' => "ALTER TABLE IF EXISTS public.field_mut_dag_details
            ADD CONSTRAINT  field_mut_dag_details_field_mut_basic_fk FOREIGN KEY (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no)
            REFERENCES public.field_mut_basic (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'lb_id_fkey',
            'table' => 'land_bank_encroacher_details',
            'query' => "ALTER TABLE IF EXISTS public.land_bank_encroacher_details
            ADD CONSTRAINT  lb_id_fkey FOREIGN KEY (land_bank_details_id)
            REFERENCES public.land_bank_details (id) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        // [
        //     'name' => 'jama_pattadar_jama_patta_fk',
        //     'table' => 'jama_pattadar',
        //     'query' => "ALTER TABLE IF EXISTS public.jama_pattadar
        //     ADD CONSTRAINT  jama_pattadar_jama_patta_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, patta_no, patta_type_code)
        //     REFERENCES public.jama_patta (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, patta_no, patta_type_code) MATCH FULL
        //     ON UPDATE NO ACTION
        //     ON DELETE NO ACTION"
        // ],
        [
            'name' => 'field_mut_pattadar_field_mut_basic_fk',
            'table' => 'field_mut_pattadar',
            'query' => "ALTER TABLE IF EXISTS public.field_mut_pattadar
            ADD CONSTRAINT  field_mut_pattadar_field_mut_basic_fk FOREIGN KEY (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no)
            REFERENCES public.field_mut_basic (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, year_no, petition_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        // [
        //     'name' => 'jama_dag_jama_patta_fk',
        //     'table' => 'jama_dag',
        //     'query' => "ALTER TABLE IF EXISTS public.jama_dag
        //     ADD CONSTRAINT  jama_dag_jama_patta_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, patta_no, patta_type_code)
        //     REFERENCES public.jama_patta (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, patta_no, patta_type_code) MATCH FULL
        //     ON UPDATE NO ACTION
        //     ON DELETE NO ACTION"
        // ],
        // [
        //     'name' => 'jama_remark_jama_patta_fk',
        //     'table' => 'jama_remark',
        //     'query' => "ALTER TABLE IF EXISTS public.jama_remark
        //     ADD CONSTRAINT  jama_remark_jama_patta_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, patta_no, patta_type_code)
        //     REFERENCES public.jama_patta (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, patta_no, patta_type_code) MATCH FULL
        //     ON UPDATE NO ACTION
        //     ON DELETE NO ACTION"
        // ],
        [
            'name' => 'chitha_col8_tenant_chitha_basic_fk',
            'table' => 'chitha_col8_tenant',
            'query' => "ALTER TABLE IF EXISTS public.chitha_col8_tenant
            ADD CONSTRAINT  chitha_col8_tenant_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_rmk_lmnote_chitha_basic_fk',
            'table' => 'chitha_rmk_lmnote',
            'query' => "ALTER TABLE IF EXISTS public.chitha_rmk_lmnote
            ADD CONSTRAINT  chitha_rmk_lmnote_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_rmk_sknote_chitha_basic_fk',
            'table' => 'chitha_rmk_sknote',
            'query' => "ALTER TABLE IF EXISTS public.chitha_rmk_sknote
            ADD CONSTRAINT  chitha_rmk_sknote_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'chitha_settlement_allottee_chitha_basic_fk',
            'table' => 'chitha_settlement_allottee',
            'query' => "ALTER TABLE IF EXISTS public.chitha_settlement_allottee
            ADD CONSTRAINT  chitha_settlement_allottee_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'field_mut_dag_details_chitha_basic_fk',
            'table' => 'field_mut_dag_details',
            'query' => "ALTER TABLE IF EXISTS public.field_mut_dag_details
            ADD CONSTRAINT  field_mut_dag_details_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'field_mut_pattadar_chitha_basic_fk',
            'table' => 'field_mut_pattadar',
            'query' => "ALTER TABLE IF EXISTS public.field_mut_pattadar
            ADD CONSTRAINT  field_mut_pattadar_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'settlement_dag_details_chitha_basic_fk',
            'table' => 'settlement_dag_details',
            'query' => "ALTER TABLE IF EXISTS public.settlement_dag_details
            ADD CONSTRAINT  settlement_dag_details_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, mouza_pargona_code, cir_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'fk_chitha_col8_inplace_chitha_col8_order',
            'table' => 'chitha_col8_inplace',
            'query' => "ALTER TABLE IF EXISTS public.chitha_col8_inplace
            ADD CONSTRAINT  fk_chitha_col8_inplace_chitha_col8_order FOREIGN KEY (col8order_cron_no, dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_col8_order (col8order_cron_no, dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
        [
            'name' => 'land_acquisition_dag_details_chitha_basic_fk',
            'table' => 'land_acquisition_dag_details',
            'query' => "ALTER TABLE IF EXISTS public.land_acquisition_dag_details
            ADD CONSTRAINT  land_acquisition_dag_details_chitha_basic_fk FOREIGN KEY (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, dag_no)
            REFERENCES public.chitha_basic (dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, dag_no) MATCH FULL
            ON UPDATE NO ACTION
            ON DELETE NO ACTION",
        ],
    ];
    public static $REMOVE_QUERIES = [
        [
            'query' => "ALTER TABLE IF EXISTS chitha_dag_pattadar DROP CONSTRAINT IF EXISTS chitha_dag_pattadar_chitha_basic_fk",
            'name' => "chitha_dag_pattadar_chitha_basic_fk",
            'table' => "chitha_dag_pattadar",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_rmk_gen DROP CONSTRAINT IF EXISTS chitha_rmk_gen_chitha_basic_fk",
            'name' => "chitha_rmk_gen_chitha_basic_fk",
            'table' => "chitha_rmk_gen",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_rmk_infavor_of DROP CONSTRAINT IF EXISTS chitha_rmk_infavor_of_chitha_basic_fk",
            'name' => "chitha_rmk_infavor_of_chitha_basic_fk",
            'table' => "chitha_rmk_infavor_of",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_rmk_inplace_of DROP CONSTRAINT IF EXISTS chitha_rmk_inplace_of_chitha_basic_fk",
            'name' => "chitha_rmk_inplace_of_chitha_basic_fk",
            'table' => "chitha_rmk_inplace_of",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_rmk_onbehalf DROP CONSTRAINT IF EXISTS chitha_rmk_onbehalf_chitha_basic_fk",
            'name' => "chitha_rmk_onbehalf_chitha_basic_fk",
            'table' => "chitha_rmk_onbehalf",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS t_chitha_rmk_onbehalf DROP CONSTRAINT IF EXISTS t_chitha_rmk_onbehalf_chitha_basic_fk",
            'name' => "t_chitha_rmk_onbehalf_chitha_basic_fk",
            'table' => "t_chitha_rmk_onbehalf",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_rmk_ordbasic DROP CONSTRAINT IF EXISTS chitha_rmk_ordbasic_chitha_basic_fk",
            'name' => "chitha_rmk_ordbasic_chitha_basic_fk",
            'table' => "chitha_rmk_ordbasic",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_rmk_convorder DROP CONSTRAINT IF EXISTS chitha_rmk_convorder_chitha_basic_fk",
            'name' => "chitha_rmk_convorder_chitha_basic_fk",
            'table' => "chitha_rmk_convorder",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_rmk_allottee DROP CONSTRAINT IF EXISTS chitha_rmk_allottee_chitha_basic_fk",
            'name' => "chitha_rmk_allottee_chitha_basic_fk",
            'table' => "chitha_rmk_allottee",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS t_chitha_rmk_allottee DROP CONSTRAINT IF EXISTS t_chitha_rmk_allottee_chitha_basic_fk",
            'name' => "t_chitha_rmk_allottee_chitha_basic_fk",
            'table' => "t_chitha_rmk_allottee",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_mcrop DROP CONSTRAINT IF EXISTS chitha_mcrop_chitha_basic_fk",
            'name' => "chitha_mcrop_chitha_basic_fk",
            'table' => "chitha_mcrop",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_noncrop DROP CONSTRAINT IF EXISTS chitha_noncrop_chitha_basic_fk",
            'name' => "chitha_noncrop_chitha_basic_fk",
            'table' => "chitha_noncrop",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_fruit DROP CONSTRAINT IF EXISTS chitha_fruit_chitha_basic_fk",
            'name' => "chitha_fruit_chitha_basic_fk",
            'table' => "chitha_fruit",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS apcancel_dag_details DROP CONSTRAINT IF EXISTS apcancel_dag_details_chitha_basic_fk",
            'name' => "apcancel_dag_details_chitha_basic_fk",
            'table' => "apcancel_dag_details",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS apcancel_petition_pattadar DROP CONSTRAINT IF EXISTS apcancel_petition_pattadar_chitha_basic_fk",
            'name' => "apcancel_petition_pattadar_chitha_basic_fk",
            'table' => "apcancel_petition_pattadar",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS apt_chitha_rmk_ordbasic DROP CONSTRAINT IF EXISTS apt_chitha_rmk_ordbasic_chitha_basic_fk",
            'name' => "apt_chitha_rmk_ordbasic_chitha_basic_fk",
            'table' => "apt_chitha_rmk_ordbasic",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS apt_chitha_rmk_other DROP CONSTRAINT IF EXISTS apt_chitha_rmk_other_chitha_basic_fk",
            'name' => "apt_chitha_rmk_other_chitha_basic_fk",
            'table' => "apt_chitha_rmk_other",
        ],
        //for bajali
        // [
        //     'query' => "ALTER TABLE IF EXISTS apcancel_dag_details DROP CONSTRAINT IF EXISTS apcancel_dag_details_apcancel_petition_basic_fk",
        //     'name' => "apcancel_dag_details_apcancel_petition_basic_fk",
        //     'table' => "drop"
        // ],
        // //for bajali
        // [
        //     'query' => "ALTER TABLE IF EXISTS apcancel_petition_lm_note DROP CONSTRAINT IF EXISTS apcancel_petition_lm_note_apcancel_petition_basic_fk",
        //     'name' => "apcancel_petition_lm_note_apcancel_petition_basic_fk",
        //     'table' => "drop"
        // ],
        // //for bajali
        // [
        //     'query' => "ALTER TABLE IF EXISTS apcancel_petition_pattadar DROP CONSTRAINT IF EXISTS apcancel_petition_pattadar_apcancel_petition_basic_fk",
        //     'name' => "apcancel_petition_pattadar_apcancel_petition_basic_fk",
        //     'table' => "drop"
        // ],
        // //for bajali
        // [
        //     'query' => "ALTER TABLE IF EXISTS apcancel_petitioner DROP CONSTRAINT IF EXISTS apcancel_petitioner_apcancel_petition_basic_fk",
        //     'name' => "apcancel_petitioner_apcancel_petition_basic_fk",
        //     'table' => "drop"
        // ],
        [
            'query' => "ALTER TABLE IF EXISTS petition_dag_details DROP CONSTRAINT IF EXISTS petition_dag_details_chitha_basic_fk",
            'name' => "petition_dag_details_chitha_basic_fk",
            'table' => "petition_dag_details",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS petition_lm_note DROP CONSTRAINT IF EXISTS petition_lm_note_chitha_basic_fk",
            'name' => "petition_lm_note_chitha_basic_fk",
            'table' => "petition_lm_note",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS petition_pattadar DROP CONSTRAINT IF EXISTS petition_pattadar_chitha_basic_fk",
            'name' => "petition_pattadar_chitha_basic_fk",
            'table' => "petition_pattadar",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS t_chitha_rmk_infavor_of DROP CONSTRAINT IF EXISTS t_chitha_rmk_infavor_of_chitha_basic_fk",
            'name' => "t_chitha_rmk_infavor_of_chitha_basic_fk",
            'table' => "t_chitha_rmk_infavor_of",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS t_chitha_rmk_inplace_of DROP CONSTRAINT IF EXISTS t_chitha_rmk_inplace_of_chitha_basic_fk",
            'name' => "t_chitha_rmk_inplace_of_chitha_basic_fk",
            'table' => "t_chitha_rmk_inplace_of",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS t_chitha_rmk_ordbasic DROP CONSTRAINT IF EXISTS t_chitha_rmk_ordbasic_chitha_basic_fk",
            'name' => "t_chitha_rmk_ordbasic_chitha_basic_fk",
            'table' => "t_chitha_rmk_ordbasic",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS t_chitha_rmk_convorder DROP CONSTRAINT IF EXISTS t_chitha_rmk_convorder_chitha_basic_fk",
            'name' => "t_chitha_rmk_convorder_chitha_basic_fk",
            'table' => "t_chitha_rmk_convorder",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_rmk_encro DROP CONSTRAINT IF EXISTS chitha_rmk_encro_chitha_basic_fk",
            'name' => "chitha_rmk_encro_chitha_basic_fk",
            'table' => "chitha_rmk_encro",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_basic DROP CONSTRAINT IF EXISTS chitha_basic_location_fk",
            'name' => "chitha_basic_location_fk",
            'table' => "chitha_basic",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_col8_occup DROP CONSTRAINT IF EXISTS chitha_col8_occup_chitha_basic_fk",
            'name' => "chitha_col8_occup_chitha_basic_fk",
            'table' => "chitha_col8_occup",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_col8_inplace DROP CONSTRAINT IF EXISTS chitha_col8_inplace_chitha_basic_fk",
            'name' => "chitha_col8_inplace_chitha_basic_fk",
            'table' => "chitha_col8_inplace",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS t_chitha_col8_inplace DROP CONSTRAINT IF EXISTS t_chitha_col8_inplace_chitha_basic_fk",
            'name' => "t_chitha_col8_inplace_chitha_basic_fk",
            'table' => "t_chitha_col8_inplace",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS t_chitha_col8_occup DROP CONSTRAINT IF EXISTS t_chitha_col8_occup_chitha_basic_fk",
            'name' => "t_chitha_col8_occup_chitha_basic_fk",
            'table' => "t_chitha_col8_occup",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS t_chitha_col8_order DROP CONSTRAINT IF EXISTS t_chitha_col8_order_chitha_basic_fk",
            'name' => "t_chitha_col8_order_chitha_basic_fk",
            'table' => "t_chitha_col8_order",
        ],
        // [
        //     'query' => "ALTER TABLE IF EXISTS allotment_pet_dag DROP CONSTRAINT IF EXISTS allotee_dag_fk",
        //     'name' => "allotee_dag_fk",
        //     'table' => "allotment_pet_dag"
        // ],
        // [
        //     'query' => "ALTER TABLE IF EXISTS allotment_doc_details DROP CONSTRAINT IF EXISTS allotee_doc_fk",
        //     'name' => "allotee_doc_fk",
        //     'table' => "allotment_doc_details"
        // ],
        // [
        //     'query' => "ALTER TABLE IF EXISTS allotment_petitioner DROP CONSTRAINT IF EXISTS allotee_pet_fk",
        //     'name' => "allotee_pet_fk",
        //     'table' => "allotment_petitioner"
        // ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_col8_order DROP CONSTRAINT IF EXISTS chitha_col8_order_chitha_basic_fk",
            'name' => "chitha_col8_order_chitha_basic_fk",
            'table' => "chitha_col8_order",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS field_part_petitioner DROP CONSTRAINT IF EXISTS field_part_petitioner_chitha_basic_fk",
            'name' => "field_part_petitioner_chitha_basic_fk",
            'table' => "field_part_petitioner",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS field_part_petitioner DROP CONSTRAINT IF EXISTS field_part_petitioner_field_mut_basic_fk",
            'name' => "field_part_petitioner_field_mut_basic_fk",
            'table' => "drop",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_rmk_other_opp_party DROP CONSTRAINT IF EXISTS chitha_rmk_other_opp_party_chitha_basic_fk",
            'name' => "chitha_rmk_other_opp_party_chitha_basic_fk",
            'table' => "chitha_rmk_other_opp_party",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS t_chitha_rmk_other_opp_party DROP CONSTRAINT IF EXISTS t_chitha_rmk_other_opp_party_chitha_basic_fk",
            'name' => "t_chitha_rmk_other_opp_party_chitha_basic_fk",
            'table' => "t_chitha_rmk_other_opp_party",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS petitioner_part DROP CONSTRAINT IF EXISTS petitioner_part_chitha_basic_fk",
            'name' => "petitioner_part_chitha_basic_fk",
            'table' => "petitioner_part",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_rmk_reclassification DROP CONSTRAINT IF EXISTS chitha_rmk_reclassification_chitha_basic_fk",
            'name' => "chitha_rmk_reclassification_chitha_basic_fk",
            'table' => "chitha_rmk_reclassification",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS t_reclassification DROP CONSTRAINT IF EXISTS t_reclassification_chitha_basic_fk",
            'name' => "t_reclassification_chitha_basic_fk",
            'table' => "t_reclassification",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_subtenant DROP CONSTRAINT IF EXISTS chitha_subtenant_chitha_basic_fk",
            'name' => "chitha_subtenant_chitha_basic_fk",
            'table' => "chitha_subtenant",
        ],
        // [
        //     'query' => "ALTER TABLE IF EXISTS chitha_tenant_backup DROP CONSTRAINT IF EXISTS chitha_tenant_chitha_basic_fk",
        //     'name' => "chitha_tenant_chitha_basic_fk",
        //     'table' => "chitha_tenant_backup"
        // ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_tenant DROP CONSTRAINT IF EXISTS chitha_tenant_chitha_basic_fk",
            'name' => "chitha_tenant_chitha_basic_fk",
            'table' => "chitha_tenant",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_rmk_alongwith DROP CONSTRAINT IF EXISTS chitha_rmk_alongwith_chitha_basic_fk",
            'name' => "chitha_rmk_alongwith_chitha_basic_fk",
            'table' => "chitha_rmk_alongwith",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS t_chitha_rmk_alongwith DROP CONSTRAINT IF EXISTS t_chitha_rmk_alongwith_chitha_basic_fk",
            'name' => "t_chitha_rmk_alongwith_chitha_basic_fk",
            'table' => "t_chitha_rmk_alongwith",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS field_mut_petitioner DROP CONSTRAINT IF EXISTS field_mut_petitioner_field_mut_basic_fk",
            'name' => "field_mut_petitioner_field_mut_basic_fk",
            'table' => "drop",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS field_mut_dag_details DROP CONSTRAINT IF EXISTS field_mut_dag_details_field_mut_basic_fk",
            'name' => "field_mut_dag_details_field_mut_basic_fk",
            'table' => "drop",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS land_bank_encroacher_details DROP CONSTRAINT IF EXISTS lb_id_fkey",
            'name' => "lb_id_fkey",
            'table' => "alter",
        ],
        // [
        //     'query' => "ALTER TABLE IF EXISTS jama_pattadar DROP CONSTRAINT IF EXISTS jama_pattadar_jama_patta_fk",
        //     'name' => "jama_pattadar_jama_patta_fk",
        //     'table' => "jama_pattadar"
        // ],
        [
            'query' => "ALTER TABLE IF EXISTS field_mut_pattadar DROP CONSTRAINT IF EXISTS field_mut_pattadar_field_mut_basic_fk",
            'name' => "field_mut_pattadar_field_mut_basic_fk",
            'table' => "drop",
        ],
        // [
        //     'query' => "ALTER TABLE IF EXISTS jama_dag DROP CONSTRAINT IF EXISTS jama_dag_jama_patta_fk",
        //     'name' => "jama_dag_jama_patta_fk",
        //     'table' => "jama_dag"
        // ],
        // [
        //     'query' => "ALTER TABLE IF EXISTS jama_remark DROP CONSTRAINT IF EXISTS jama_remark_jama_patta_fk",
        //     'name' => "jama_remark_jama_patta_fk",
        //     'table' => "jama_remark"
        // ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_col8_tenant DROP CONSTRAINT IF EXISTS chitha_col8_tenant_chitha_basic_fk",
            'name' => "chitha_col8_tenant_chitha_basic_fk",
            'table' => "chitha_col8_tenant",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_rmk_lmnote DROP CONSTRAINT IF EXISTS chitha_rmk_lmnote_chitha_basic_fk",
            'name' => "chitha_rmk_lmnote_chitha_basic_fk",
            'table' => "chitha_rmk_lmnote",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_rmk_sknote DROP CONSTRAINT IF EXISTS chitha_rmk_sknote_chitha_basic_fk",
            'name' => "chitha_rmk_sknote_chitha_basic_fk",
            'table' => "chitha_rmk_sknote",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_settlement_allottee DROP CONSTRAINT IF EXISTS chitha_settlement_allottee_chitha_basic_fk",
            'name' => "chitha_settlement_allottee_chitha_basic_fk",
            'table' => "chitha_settlement_allottee",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS field_mut_dag_details DROP CONSTRAINT IF EXISTS field_mut_dag_details_chitha_basic_fk",
            'name' => "field_mut_dag_details_chitha_basic_fk",
            'table' => "field_mut_dag_details",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS field_mut_pattadar DROP CONSTRAINT IF EXISTS field_mut_pattadar_chitha_basic_fk",
            'name' => "field_mut_pattadar_chitha_basic_fk",
            'table' => "field_mut_pattadar",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS settlement_dag_details DROP CONSTRAINT IF EXISTS settlement_dag_details_chitha_basic_fk",
            'name' => "settlement_dag_details_chitha_basic_fk",
            'table' => "settlement_dag_details",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS chitha_col8_inplace DROP CONSTRAINT IF EXISTS fk_chitha_col8_inplace_chitha_col8_order",
            'name' => "fk_chitha_col8_inplace_chitha_col8_order",
            'table' => "chitha_col8_inplace",
        ],
        [
            'query' => "ALTER TABLE IF EXISTS land_acquisition_dag_details DROP CONSTRAINT IF EXISTS land_acquisition_dag_details_chitha_basic_fk",
            'name' => "land_acquisition_dag_details_chitha_basic_fk",
            'table' => "land_acquisition_dag_details",
        ],
    ];
    public function getLocations($dist = null, $sub = null, $cir = null, $mouza = null, $lot = null, $village = null)
    {

        if ($dist) {
            $location['dist'] = $this->db->select('loc_name,dist_code,locname_eng,loc_name')->where(array('dist_code' => $dist, 'subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->get('location')->row_array();
        }
        if ($sub) {
            $location['subdiv'] = $this->db->select('loc_name,dist_code,subdiv_code,locname_eng,loc_name')->where(array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->get('location')->row_array();
        }
        if ($cir) {
            $location['circle'] = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,locname_eng,loc_name')->where(array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => $cir, 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->get('location')->row_array();
        }
        if ($mouza) {
            $location['mouza'] = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng,loc_name')->where(array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => $cir, 'mouza_pargona_code' => $mouza, 'lot_no' => '00', 'vill_townprt_code' => '00000'))->get('location')->row_array();
        }
        if ($lot) {
            $location['lot'] = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,locname_eng,loc_name')->where(array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => $cir, 'mouza_pargona_code' => $mouza, 'lot_no' => $lot, 'vill_townprt_code' => '00000'))->get('location')->row_array();
        }
        if ($village) {
            $location['village'] = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code,locname_eng,loc_name,uuid')->where(array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => $cir, 'mouza_pargona_code' => $mouza, 'lot_no' => $lot, 'vill_townprt_code' => $village))->get('location')->row_array();
        }

        return $location;
    }

    /** get nc village dags */
    public function getNcVillageDags($application_no)
    {
        $q = "SELECT chitha_basic_nc.land_class_code , landclass_code.land_type as full_land_type_name, nc_village_dags.* FROM nc_village_dags 
        join chitha_basic_nc 
        on chitha_basic_nc.dist_code = nc_village_dags.dist_code
        and chitha_basic_nc.subdiv_code = nc_village_dags.subdiv_code
        and chitha_basic_nc.cir_code = nc_village_dags.cir_code
        and chitha_basic_nc.mouza_pargona_code = nc_village_dags.mouza_pargona_code
        and chitha_basic_nc.lot_no = nc_village_dags.lot_no
        and chitha_basic_nc.vill_townprt_code = nc_village_dags.vill_townprt_code
        and chitha_basic_nc.dag_no = nc_village_dags.dag_no
        join landclass_code on landclass_code.class_code = chitha_basic_nc.land_class_code
        WHERE application_no='$application_no'
        order by CAST(coalesce(nc_village_dags.dag_no_int, '0') AS numeric)";

        $query = $this->db->query($q);

        return $query->result();
    }
    /** generate application no */
    public function genearteCaseNo($mut)
    {
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $year_no = year_no;
        $define_date = CHANGE_DATE;
        $q = "Select dist_abbr,cir_abbr from location where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code!='00' ";
        $abbrname = $this->db->query($q)->row();
        $cir_dist_name = $abbrname->dist_abbr . "/" . $abbrname->cir_abbr;
        $financialyeardate = (date('m') < '07') ? date('Y', strtotime('-1 year')) . "-" . date('y') : date('Y') . "-" . date('y', strtotime('+1 year'));
        $case_no = 0;
        if ($mut == SERVICE_CODE_SVAMITVA) {
            $petition_no = $this->genearteNcVillagePetitionNo();
            $financialyeardate = (date('m') < '07') ? date('Y', strtotime('-1 year')) . "-" . date('y') : date('Y') . "-" . date('y', strtotime('+1 year'));
            $case_no = $cir_dist_name . "/" . $financialyeardate . "/" . $petition_no . "/NCVILL";
            $return = array('case_no' => $case_no, 'petition_no' => $petition_no);
            return $return;
        }
    }
    /** get max val for the nc vill application no */
    public function genearteNcVillagePetitionNo()
    {
        $petition_no = $this->db->query("select nextval('nc_villages_id_seq') as count ")->row()->count;
        return $petition_no;
    }

    /** Total Lessa */
    function totalLessa($bigha, $katha, $lessa)
    {
        $total_lessa = $lessa + ($katha * 20) + ($bigha * 100);
        return $total_lessa;
    }

    /** Bigha Katha Lessa */
    function Total_Bigha_Katha_Lessa($total_lessa)
    {
        $bigha = $total_lessa / 100;
        $rem_lessa = fmod($total_lessa, 100);
        $katha = $rem_lessa / 20;
        $r_lessa = fmod($rem_lessa, 20);
        $mesaure = array();
        $mesaure[] .= floor($bigha);
        $mesaure[] .= floor($katha);
        $mesaure[] .= $r_lessa;
        return $mesaure;
    }

    /** get nc village dags */
    public function getNcVillageDagsOldChitha($application_no)
    {
        $q = "SELECT chitha_basic.land_class_code , landclass_code.land_type as full_land_type_name, nc_village_dags.* FROM nc_village_dags 
        join chitha_basic 
        on chitha_basic.dist_code = nc_village_dags.dist_code
        and chitha_basic.subdiv_code = nc_village_dags.subdiv_code
        and chitha_basic.cir_code = nc_village_dags.cir_code
        and chitha_basic.mouza_pargona_code = nc_village_dags.mouza_pargona_code
        and chitha_basic.lot_no = nc_village_dags.lot_no
        and chitha_basic.vill_townprt_code = nc_village_dags.vill_townprt_code
        and chitha_basic.dag_no = nc_village_dags.dag_no
        join landclass_code on landclass_code.class_code = chitha_basic.land_class_code
        WHERE application_no='$application_no'
        order by CAST(coalesce(nc_village_dags.dag_no_int, '0') AS numeric)";

        $query = $this->db->query($q);

        return $query->result();
    }
    public function getVillageByUuid($dist, $uuid)
    {
        if (in_array($dist, json_decode(BTC_DISTIRTCS))) {
            return $this->db->get_where('location', array('dist_code' => $dist, 'uuid =' => $uuid))->row();
        } else {
            $village = callLandhubAPI('POST', 'getVillageByUuid', [
                'dist_code' => $dist,
                'uuid' => $uuid,
            ]);
            return $village;
        }
    }
}
