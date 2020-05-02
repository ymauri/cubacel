<?php
if (!defined('_PS_VERSION_')) {
    exit;
}
require_once __DIR__. '/libs/Recharger.php';

class Cubacel extends Module {

    public function __construct() {
        $this->name = 'cubacel';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Yolanda Mauri Pérez';
        $this->need_instance = 0;
        $this->ps_versions_compliancy =  ['min' => '1.7', 'max' => _PS_VERSION_];
        $this->bootstrap = true;

        $this->logger = new FileLogger(0); 
        //Create folder if not exists
        $this->logger->setFilename(_PS_ROOT_DIR_."\log\debug.log");

        parent::__construct();

        $this->displayName = $this->l('Recargas Cubacel');
        $this->description = $this->l('Recargas de móviles e internet. Compañía Cubacel.');

        $this->confirmUninstall = $this->l('¿Está seguro de que desea instalar el módulo?');

        if (!Configuration::get('CUBACEL')) {
            $this->warning = $this->l('No name provided');
        }
    }
    

    public function install() {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
    
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('displayHome') &&
            $this->registerHook('header') &&
            $this->registerHook('actionPaymentConfirmation') &&
            // $this->registerHook('actionCronJob') &&
            Configuration::updateValue('CUBACEL', 'Recargas Cubacel');
    }

    public function uninstall() {      
        include(dirname(__FILE__).'/sql/uninstall.php');
        return !(!parent::uninstall() || !Configuration::deleteByName('CUBACEL')) ;
    }

    public function getContent() {
        $output = null;
        if (Tools::isSubmit('submit'.$this->name)) {            
            $cubacel = Tools::getValue('CUBACEL', true);
            if (!$cubacel || empty($cubacel)) {
                $output .= $this->displayError($this->l('Valor de configuración incorrecto.'));
            } else {
                foreach ($cubacel as $key => $value) {
                    if (Validate::isGenericName($value)) {
                        Configuration::updateValue($key, strval($value));
                    }
                }
                $output .= $this->displayConfirmation($this->l('Configuraciones actualizadas'));
            }
        }
        return $output.$this->displayForm();
    }

    //Formulario de configuración
    public function displayForm() {
        // Get default language
        $defaultLang = (int)Configuration::get('"._DB_PREFIX_."LANG_DEFAULT');

        // Init Fields form array
        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Configuración de recargas'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Url'),
                    'name' => 'CUBACEL[CUBACEL_URL]',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Usuario'),
                    'name' => 'CUBACEL[CUBACEL_USER]',
                    'required' => true,
                ],
                [
                    'type' => 'password',
                    'label' => $this->l('Contraseña'),
                    'name' => 'CUBACEL[CUBACEL_PASSWORD]',
                ],
                [
                    'type' => 'switch',
                    'is_bool' => true,
                    'label' => $this->l('Activar recarga de móviles'),
                    'name' => 'CUBACEL[CUBACEL_MOBILE_ACTIVE]',                    
                    'values' => [
                        [
                            'id' => 'mobile_active_on',
                            'value' => true,
                            'label' => $this->l('Enabled')
                        ],
                        [
                            'id' => 'mobile_active_off',
                            'value' => false,
                            'label' => $this->l('Disabled')
                        ]
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Teléfono de prueba'),
                    'name' => 'CUBACEL[CUBACEL_MOBILE_TEST]',
                ],                      
                [
                    'type' => 'text',
                    'label' => $this->l('ID Departamento recarga móviles'),
                    'name' => 'CUBACEL[CUBACEL_MOBILE_DEPARTMENT]',
                ],          
                [
                    'type' => 'switch',
                    'is_bool' => true,
                    'label' => $this->l('Activar recarga de internet'),
                    'name' => 'CUBACEL[CUBACEL_INTERNET_ACTIVE]',
                    'values' => [
                        [
                            'id' => 'internet_active_on',
                            'value' => true,
                            'label' => $this->l('Enabled')
                        ],
                        [
                            'id' => 'internet_active_off',
                            'value' => false,
                            'label' => $this->l('Disabled')
                        ]
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Cuenta de internet de prueba'),
                    'name' => 'CUBACEL[CUBACEL_INTERNET_TEST]',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('ID Departamento recarga internet'),
                    'name' => 'CUBACEL[CUBACEL_INTERNET_DEPARTMENT]',
                ]
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]
        ];

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];

        // Load current value
        $helper->fields_value = [
            'CUBACEL[CUBACEL_URL]'                  => Configuration::get('CUBACEL_URL'),
            'CUBACEL[CUBACEL_USER]'                 => Configuration::get('CUBACEL_USER'),
            'CUBACEL[CUBACEL_PASSWORD]'             => Configuration::get('CUBACEL_PASSWORD'),
            'CUBACEL[CUBACEL_MOBILE_ACTIVE]'        => Configuration::get('CUBACEL_MOBILE_ACTIVE'),
            'CUBACEL[CUBACEL_MOBILE_TEST]'          => Configuration::get('CUBACEL_MOBILE_TEST'),
            'CUBACEL[CUBACEL_INTERNET_ACTIVE]'      => Configuration::get('CUBACEL_INTERNET_ACTIVE'),
            'CUBACEL[CUBACEL_INTERNET_TEST]'        => Configuration::get('CUBACEL_INTERNET_TEST'),
            'CUBACEL[CUBACEL_MOBILE_DEPARTMENT]'    => Configuration::get('CUBACEL_MOBILE_DEPARTMENT'),
            'CUBACEL[CUBACEL_INTERNET_DEPARTMENT]'  => Configuration::get('CUBACEL_INTERNET_DEPARTMENT'),
        ];

        return $helper->generateForm($fieldsForm);
    }

    //Mostrar personalizado el listado de productos
    //Esto puede servir para la vista del departamento
    public function hookDisplayHome($params) {
        $languageId = (int)($params['cookie']->id_lang);
        $categoryMobile = new Category(Configuration::get('CUBACEL_MOBILE_DEPARTMENT'));
        $products = $categoryMobile->getProducts($languageId, 1, 10);
        $this->smarty->assign(array(
            'products' => $products
        ));
 
        return $this->display($this->_path, 'views/templates/front/cubacel.tpl');
    }

    //Registrar el log para las recargas cubacel
    public function hookActionPaymentConfirmation($params) {
        

        $languageId = (int)($params['cookie']->id_lang);

        try {
            $query = "SELECT "._DB_PREFIX_."order_detail.id_order as id_order, 
                            "._DB_PREFIX_."orders.reference as reference, 
                            "._DB_PREFIX_."order_detail.product_id as product_id, 
                            "._DB_PREFIX_."order_detail.product_quantity as product_quantity, 
                            "._DB_PREFIX_."customized_data.value as data_value, 
                            "._DB_PREFIX_."product.id_category_default as category, 
                            "._DB_PREFIX_."feature_value_lang.value as amount 
                        FROM "._DB_PREFIX_."orders
                        INNER JOIN `"._DB_PREFIX_."order_detail` ON "._DB_PREFIX_."order_detail.id_order = "._DB_PREFIX_."orders.id_order
                        INNER JOIN `"._DB_PREFIX_."customized_data` on "._DB_PREFIX_."order_detail.id_customization = "._DB_PREFIX_."customized_data.id_customization
                        INNER JOIN `"._DB_PREFIX_."product` ON "._DB_PREFIX_."product.id_product = "._DB_PREFIX_."order_detail.product_id
                        INNER JOIN `"._DB_PREFIX_."feature_product` ON "._DB_PREFIX_."product.id_product = "._DB_PREFIX_."feature_product.id_product
                        INNER JOIN `"._DB_PREFIX_."feature_value_lang` ON "._DB_PREFIX_."feature_value_lang.id_feature_value = "._DB_PREFIX_."feature_product.id_feature_value
                        WHERE "._DB_PREFIX_."order_detail.id_order = ".$params['id_order']." AND "._DB_PREFIX_."feature_value_lang.id_lang = ".$languageId;

           
            $products = Db::getInstance()->executeS($query);

            foreach ($products as $product) {
                if ($product['category'] == Configuration::get('CUBACEL_MOBILE_DEPARTMENT'))
                    $type = 'Movil';
                else if ($product['category'] == Configuration::get('CUBACEL_MOBILE_DEPARTMENT')) 
                    $type = 'Internet';

                $query = "SELECT * FROM "._DB_PREFIX_."cubacel_log WHERE id_order LIKE '".$product['reference']."' AND account LIKE '".$product['data_value']."'";
                $productDb = Db::getInstance()->getRow($query);
                // $logger->logDebug($query);    
                // $logger->logDebug($productDb);    
                if (!empty($type) && !isset($productDb['id'])) {
                    Db::getInstance()->insert('cubacel_log', array(
                        'id_order' => $product['reference'],
                        'account' => $product['data_value'],
                        'type' => $type,
                        'attemps' => 0,
                        'amount' => $product['amount'],                    
                        'status' => 'Pagado'
                    ));
                    // $insert = "INSERT INTO "._DB_PREFIX_."cubacel_log (id_order, account, type, attemps, reference, amount, status)
                    //         VALUES ('".$product['reference']."', '".$product['data_value']."', '".$type."', 0, '', '".$product['amount']."', 'Pagado')";
                    //         $logger->logDebug($insert);
                    //         Db::getInstance()->execute($insert);
                }
            }   
            return true;
        } catch (Exception $e) {
           $this->logger->logDebug($e->getMessage()); 
            return false;
        }         
    }

    private function installTab() {
        $languages = Language::getLanguages(false);

        //Main Parent menu
        if (!(int) Tab::getIdFromClassName('AdminMallHabana')) {
            $parentTab = new Tab();
            $parentTab->active = 1;
            $parentTab->name = array();
            $parentTab->class_name = "AdminMallHabana";
            foreach ($languages as $language) {
                $parentTab->name[$language['id_lang']] = 'MallHabana';
            }
            $parentTab->id_parent = 0;
            $parentTab->module = '';
            $parentTab->add();
        }

        //Sub menu code
        if (!(int) Tab::getIdFromClassName('AdminCubacelLog')) {
            $parentTabID = Tab::getIdFromClassName('AdminMallHabana');
            $parentTab = new Tab($parentTabID);

            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = "AdminCubacelLog";
            $tab->name = array();
            foreach ($languages as $language) {
                $tab->name[$language['id_lang']] = $this->l('Cubacel');
            }
            $tab->id_parent = $parentTab->id;
            $tab->module = $this->name;
            $tab->add();
        }
        
        //Sub menu code
        if (!(int) Tab::getIdFromClassName('AdminCubacelBlacklist')) {
            $parentTabID = Tab::getIdFromClassName('AdminMallHabana');
            $parentTab = new Tab($parentTabID);

            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = "AdminCubacelBlacklist";
            $tab->name = array();
            foreach ($languages as $language) {
                $tab->name[$language['id_lang']] = $this->l('Lista Negra');
            }
            $tab->id_parent = $parentTab->id;
            $tab->module = $this->name;
            $tab->add();
        }
        
    }

    public function enable($force_all = false) {
        $this->installTab();
        return parent::enable($force_all);
    }

    public function disable($force_all = false) {
        return parent::disable($force_all) && $this->uninstallTab();
    }

    private function uninstallTab() {
        return true;
        $tabId = (int) Tab::getIdFromClassName('AdminInspiration');
        if (!$tabId) {
            return true;
        }
        $tab = new Tab($tabId);
        return $tab->delete();
    }

     /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader() {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookDisplayHeader() {
        $this->context->controller->addJS($this->_path.'views/js/front.js');
        $this->context->controller->addCSS($this->_path.'views/css/front.css', 'all');
    }
}