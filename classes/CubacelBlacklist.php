<?php

class CubacelBlacklist extends ObjectModel {
    public $id;
    public $data;
    public $type;
    public static $definition = array(
        'table' => "cubacel_blacklist",
        'primary' => 'id',
        'multilang' => false,
        'fields' => array(
            'data' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
            'type' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            )
        )
    );
}