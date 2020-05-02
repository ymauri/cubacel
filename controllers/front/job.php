<?php
class CubacelJobModuleFrontController extends ModuleFrontController {
    
    public function postProcess() {
        $logger = new FileLogger(0);
        $logger->setFilename(_PS_ROOT_DIR_."\log\recharge.log");

        $minute = (int) date('m');
        if ($minute % 4 == 0) {
            $db = \Db::getInstance();
            $sql = 'SELECT * FROM '. _DB_PREFIX_.'cubacel_log WHERE `status` LIKE "Pagado" AND attemps < 4';
            
            $result = $db->getRow($sql);

            if(!empty($result['id']) && !$this->isInBlackList($result['account'])) {
                $recharger = new Recharger($result['account'], $result['amount']);
                $rechargerResult = $recharger->make();
                $attemp = $result['attemps'] += 1;
                
                $logger->logDebug($rechargerResult['response']);
                $status = $rechargerResult['status'] == "Success" ? "Recargado" : "Pagado";
                $sql = 'UPDATE '. _DB_PREFIX_.'cubacel_log SET attemps = '.$attemp.', reference = "'.$rechargerResult['reference'].'", status = "'.$status.'"  WHERE `id` = '.$result['id'];
                $db->executeS($sql);
                //Update order status. Find out a hook for update order status. 
                var_dump($rechargerResult['response']);die;
            }
        }
    }

    private function isInBlackList($phone) {
        $db = \Db::getInstance();
        $sql = 'SELECT * FROM '. _DB_PREFIX_.'cubacel_blacklist WHERE `data` LIKE "'.$phone.'"';
        
        $result = $db->getRow($sql);
        return !empty($result['id']);
    }
}