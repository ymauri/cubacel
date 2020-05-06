<?php

require_once dirname(__FILE__) . '/../../classes/CubacelLog.php';

/**
 * Class CubacelBackController
 */
class AdminCubacelLogController extends ModuleAdminController {
     public function __construct() {
        parent::__construct();

        $this->bootstrap = true; 
        $this->table = CubacelLog::$definition['table'];
        $this->identifier = CubacelLog::$definition['primary']; 
        $this->className = CubacelLog::class;
        $this->allow_export = true;
        $this->lang = false; 
        $this->_defaultOrderBy = CubacelLog::$definition['primary'];

        $this->fields_list = array(
            'id' => array(
                'title' => $this->module->l('ID'), 
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ),
            'id_order' => array(
                'title' => $this->module->l('Pedido'),
                'align' => 'center',
            ),
            'account' => array(
                'title' => $this->module->l('Cuenta'),
                'align' => 'left',
            ),
            'attemps' => array(
                'title' => $this->module->l('Intentos'),
                'align' => 'center',
            ),            
            'amount' => array(
                'title' => $this->module->l('Monto'),
                'align' => 'center',
            ),
            'reference' => array(
                'title' => $this->module->l('Referencia'),
                'align' => 'center',
            ),
            'status' => array(
                'title' => $this->module->l('Estado'),
                'align' => 'left',
            ),            
            'updated_at' => array(
                'title' => $this->module->l('Actualizado'),
                'align' => 'left',
            ),
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
                    'label' => $this->l('Intentos'),
                    'name' => 'attemps',
                    'required' => true,
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
