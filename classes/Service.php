<?php
class Service {

    /**
     * Create logs files
     */
    public function createLogFields () {
        if (!is_dir(_PS_ROOT_DIR_.'/log')) {
            mkdir(_PS_ROOT_DIR_.'/log', 766);
            $recharge = fopen(_PS_ROOT_DIR_.'/log/recharge.log', "w");
            fwrite($recharge,'Installed on '.date('Y-m-d h:i:s'));
            fclose($recharge);
            $debug = fopen(_PS_ROOT_DIR_.'/log/debug.log', "w");
            fwrite($debug,'Installed on '.date('Y-m-d h:i:s'));
            fclose($debug);
        }       
    }

    /**
     * Get recharge type
     */
    public function getType ($category) {
        if ($category == Configuration::get('CUBACEL_MOBILE_DEPARTMENT'))
            return Nomenclators::RECHARGE_MOBILE;
        else if ($category == Configuration::get('CUBACEL_INTERNET_DEPARTMENT')) 
            return Nomenclators::RECHARGE_INTERNET;
        return false;
    }
}