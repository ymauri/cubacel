<?php
require_once dirname(__FILE__) . '/../vendors/nusoap/nusoap.php';
require_once dirname(__FILE__) . '/../classes/Nomenclators.php';

class Recharger {

    private $gateway;
    private $user;
    private $pass;
    private $account;
    private $type;
    private $prefix = '53';
    private $amount;

    public function __construct ($account, $amount) {

        $this->user = Configuration::get('CUBACEL_USER');
        $this->pass = Configuration::get('CUBACEL_PASSWORD');
        $this->type = $this->accountType($account);
        $this->account = $this->getAccountByMode($account);
        $this->amount = $amount;

        $wsdl=trim(Configuration::get('CUBACEL_URL'));
        $this->gateway = new nusoap_client($wsdl, 'wsdl');
        $this->gateway->soap_defencoding = 'UTF-8';

    }

    public function make(){
        if( $this->type && $this->account) {
            return $this->sendRecharge();            
        }
        return false;
    }

    private function sendRecharge() {
        $datos_balance = array('wb_rm_user' => $this->user, 'wb_rm_password' => $this->pass, 'messageId' => time());
        $res = $this->gateway->call('GetBalance', array($datos_balance));

        if ($res[0]["ResultId"] == 1) {
            $datos_send = [
                'wb_rm_user' => $this->user,
                'wb_rm_password' => $this->pass,
                'messageId' => time(),
                'PhoneNumber' => $this->account,
                'Amount' => $this->amount,
                'CountryCode' => 'CU',
                'OperatorCode' => $this->type == 'Movil' ? 'CU' : 'NU'
            ];

            $res_send = $this->gateway->call('SendPay', array($datos_send));

            if ($res_send[0]["ResultId"] == 1) {
                $status = (string)$res_send[0]["ResultId"];
                $message = (string)"Success";
                $reference = (string)$res_send[0]["ConfirmId"];                
            } else {
                $status = $res_send[0]["ResultId"];
                $message = $res_send[0]["ResultStr"];
                $reference = '000000';
            }

        } else {
            $status = $res[0]["ResultId"];
            $message = $res[0]["ResultStr"];
            $reference = "000000";
        }

        return [
            'status'    => $status,
            'message'   => $message,
            'reference' => $reference,
            'response'  => sprintf("Status: %s. Message: %s. Reference: %s.", $status, $message, $reference)
        ];
    }

    private function accountType ($account) {
        if (ctype_digit($account)) {
            return Nomenclators::RECHARGE_MOBILE;
        } else if(filter_var($account, FILTER_VALIDATE_EMAIL)){
            return Nomenclators::RECHARGE_INTERNET;
        }
        return false;
    }

    private function getAccountByMode ($account) {
        switch ($this->type) {
            case Nomenclators::RECHARGE_MOBILE:
                return $this->prefix.(Configuration::get('CUBACEL_MOBILE_ACTIVE') == 1 ? $account : Configuration::get('CUBACEL_MOBILE_TEST'));
            case Nomenclators::RECHARGE_INTERNET:
                return Configuration::get('CUBACEL_INTERNET_ACTIVE') == 1 ? $account : Configuration::get('CUBACEL_INTERNET_TEST');
            default:
                return false;
        }
    }

}