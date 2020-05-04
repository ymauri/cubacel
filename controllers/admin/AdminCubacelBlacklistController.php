<?php

require_once dirname(__FILE__) . '/../../classes/CubacelBlacklist.php';
require_once dirname(__FILE__) . '/../../classes/Nomenclators.php';

/**
 * Class CubacelBackController
 */
class AdminCubacelBlacklistController extends ModuleAdminController {
     public function __construct() {
        parent::__construct();

        $this->bootstrap = true; 
        $this->table = CubacelBlacklist::$definition['table'];
        $this->identifier = CubacelBlacklist::$definition['primary']; 
        $this->className = CubacelBlacklist::class;
        $this->allow_export = true;
        $this->lang = false; 
        $this->_defaultOrderBy = CubacelBlacklist::$definition['primary'];

        $this->fields_list = array(
            'id' => array(
                'title' => $this->module->l('ID'), 
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ),
            'data' => array(
                'title' => $this->module->l('Cuenta restringida'),
                'align' => 'center',
            ),
            'type' => array(
                'title' => $this->module->l('Tipo de cuenta'),
                'align' => 'left',
            )
        );
    }

    public function renderForm() {
        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Modificar recarga'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Cuenta restringida'),
                    'name' => 'data',
                    'required' => true,
                ],
                [
                    'type' => 'radio',
                    'label' => $this->l('Tipo de cuenta'),
                    'name' => 'type',
                    'required' => true,
                    'values' => [
                        [
                            'id' => 'internet_active_on',
                            'value' => Nomenclators::RECHARGE_MOBILE,
                            'label' => $this->l('Movil')
                        ],
                        [
                            'id' => 'internet_active_off',
                            'value' => Nomenclators::RECHARGE_INTERNET,
                            'label' => $this->l('Internet')
                        ]
                    ],
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
