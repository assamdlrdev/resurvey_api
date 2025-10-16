<?php
interface AdhaarSignProcess{
    public function setConfig();
    public function esignProcess();
    public function esignResponse();
}