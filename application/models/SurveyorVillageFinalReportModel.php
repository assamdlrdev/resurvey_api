<?php
class SurveyorVillageFinalReportModel extends CI_Model {

    public function getFinalDatasBySurevyorVillageId($connection, $surveyor_village_id){
        $cases = $this->getCase();
        $query = "SELECT *, $cases from surveyor_village_final_reports where surveyor_village_id=?";
        return $connection->query($query, array($surveyor_village_id))->result_array();
    }

    private function getCase(){
        return "CASE 
                    WHEN (file_identifier = 'final_surveyed_data') THEN 'Final Surveyed Data'
                    WHEN (file_identifier = 'field_survey_completion_report') THEN 'Field Survey Completion Report'
                    END AS document_name";
    }




}
