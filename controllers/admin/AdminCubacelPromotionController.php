<?php

require_once dirname(__FILE__) . '/../../classes/CubacelPromotion.php';
require_once dirname(__FILE__) . '/../../classes/Nomenclators.php';

/**
 * Class AdminCubacelPromotionController
 */
class AdminCubacelPromotionController extends ModuleAdminController {
     public function __construct() {
        parent::__construct();

        $this->bootstrap = true; 
        $this->table = CubacelPromotion::$definition['table'];
        $this->identifier = CubacelPromotion::$definition['primary']; 
        $this->className = CubacelPromotion::class;
        $this->allow_export = false;
        $this->lang = false; 
        $this->_defaultOrderBy = CubacelPromotion::$definition['primary'];

        $this->fields_list = [
            'id' => [
                'title' => $this->module->l('Id'), 
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],            
            'type' => [
                'title' => $this->module->l('Tipo'),
                'align' => 'center',
            ],
            'start_date' => [
                'title' => $this->module->l('Inicio de la promoción'),
                'align' => 'center',
            ],
            'end_date' => [
                'title' => $this->module->l('Fin de la promoción'),
                'align' => 'center',
            ],            
            'enabled' => [
                'title' => $this->module->l('Habilitada'),
                'align' => 'center',
            ]
        ];
    }

    public function renderForm() {
        $this->fields_form = [
            'tinymce' => true,
            'legend' => [
                'title' => $this->l('Gestionar Período de Promoción'),
            ],
            'input' => [                
                [
                    'type' => 'switch',
                    'label' => $this->l('Habilitado'),
                    'name' => 'enabled',
                    'required' => true,
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Si')
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ]
                    ],
                ],
                [
                    'type' => 'radio',
                    'label' => $this->l('Tipo de promoción'),
                    'name' => 'type',
                    'required' => true,
                    'values' => [
                        [
                            'id' => 'rechage_type_on',
                            'value' => Nomenclators::RECHARGE_MOBILE,
                            'label' => $this->l(Nomenclators::RECHARGE_MOBILE)
                        ],
                        [
                            'id' => 'rechage_type_off',
                            'value' => Nomenclators::RECHARGE_INTERNET,
                            'label' => $this->l(Nomenclators::RECHARGE_INTERNET)
                        ]
                    ],
                ],
               
                [
                    'type' => 'date',
                    'label' => $this->l('Fecha de inicio'),
                    'name' => 'start_date',
                    'required' => true,
                ],
                [
                    'type' => 'date',
                    'label' => $this->l('Fecha de fin'),
                    'name' => 'end_date',
                    'required' => true,
                ],                
                [
                    'type' => 'textarea',
                    'label' => $this->l('Descripción de la promoción'),
                    'name' => 'description',
                    'required' => true,
                    'autoload_rte' => true,
                    'cols' => 50,
                    'rows' => 10
                ]
            ],
            'submit' => [
                'title' => $this->l('Guardar'),
            ],
        ];
        return parent::renderForm();
    }

    public function renderList() {        
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }
   
    public function viewAccess($disable = false) {
        if (version_compare(_PS_VERSION_, '1.7', '<='))
            return true;
        return parent::viewAccess($disable);
    }

    public function renderView()
    {
        return parent::renderView();
    } 
}
