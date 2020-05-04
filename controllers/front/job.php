<?php

require_once dirname(__FILE__) . '/../../classes/Nomenclators.php';

class CubacelJobModuleFrontController extends ModuleFrontController {
    
    public function postProcess() {
        $logger = new FileLogger(0);
        $logger->setFilename(_PS_ROOT_DIR_."\log\recharge.log");

        $minute = (int) date('m');
        if ($minute % 4 == 0) {
            $db = \Db::getInstance();
            $sql = 'SELECT * FROM '._DB_PREFIX_.'cubacel_log WHERE `status` LIKE "'.Nomenclators::STATUS_PAYED.'" AND attemps < 4';
            
            $result = $db->getRow($sql);
            if(!empty($result['id']) && !$this->isInBlackList($result['account'])) {
                $recharger = new Recharger($result['account'], $result['amount']);
                $rechargerResult = $recharger->make();
                $attemp = $result['attemps'] += 1;
                
                $logger->logDebug($rechargerResult['response']);
                $status = $this->parseStatus($rechargerResult['status']);
            
                $resultQuery = $db->update('cubacel_log', [
                    'attemps' => $attemp,
                    'reference' => pSQL($rechargerResult['reference']),
                    'status' => pSQL($status),
                    'message' => pSQL($rechargerResult['message']),
                    'updated_at' => date('Y-m-d H:i:s'),
                ], 'id = ' . $result['id'] , 1, true);
                
                //Update order status. Find out a hook for update order status. 
                if ($status == Nomenclators::STATUS_RECHARGED && $resultQuery) {
                    $logger->logDebug($rechargerResult['response']);
                    $objOrder = new Order((int)$result['id_order']);
                    $history = new OrderHistory();
                    $history->id_order = (int)$objOrder->id;
                    $history->changeIdOrderState(5, (int)($objOrder->id)); //order status=3
                    $history->add();
                }                
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

    private function parseStatus ($status) {
        switch ($status) {
            case 1:
                return Nomenclators::STATUS_RECHARGED;
            default:
                return Nomenclators::STATUS_PAYED;
        }
    }
 }