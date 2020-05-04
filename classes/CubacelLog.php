<?php

class CubacelLog extends ObjectModel  {
    public $id;
    public $id_order;
    public $account;
    public $type;
    public $attemps;
    public $reference;
    public $amount;
    public $status;
    public static $definition = array(
        'table' => "cubacel_log",
        'primary' => 'id',
        'multilang' => false,
        'fields' => array(
            'id_order' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
            'account' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
            'type' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
            'attemps' => array(
                'type' => self::TYPE_INT,
                'required' => true
            ),
            'reference' => array(
                'type' => self::TYPE_STRING
            ),
            'amount' => array(
                'type' => self::TYPE_INT,
                'required' => true
            ),            
            'status' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
        )
    );
}