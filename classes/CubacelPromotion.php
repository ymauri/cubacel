<?php

class CubacelPromotion extends ObjectModel {
    public $id;
    public $start_date;
    public $end_date;
    public $description;
    public $enabled;
    public static $definition = [
        'table' => "cubacel_promotion",
        'primary' => 'id',
        'multilang' => false,
        'fields' => [
            'start_date' => [
                'type' => self::TYPE_STRING,
                'required' => true
            ],
            'end_date' => [
                'type' => self::TYPE_STRING,
                'required' => true
            ],
            'description' => [
                'type' => self::TYPE_HTML,
                'required' => true
            ],
            'enabled' => [
                'type' => self::TYPE_BOOL,
                'required' => true
            ],
            'type' => [
                'type' => self::TYPE_STRING,
                'required' => true
            ]
        ]
    ];
}