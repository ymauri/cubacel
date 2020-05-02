<?php
class CubacelCustomizedModuleFrontController extends ModuleFrontController {
    public function postProcess() {

        if (Tools::getValue('ajax') && Tools::isSubmit('submitCustomizedData') && Tools::isSubmit('textField')  && Tools::isSubmit('id')) {
            //Access to cart or creat it if it not exists
            try {
                if (!$this->context->cart->id) {
                    if (Context::getContext()->cookie->id_guest)
                    {
                        $guest = new Guest(Context::getContext()->cookie->id_guest);
                        $this->context->cart->mobile_theme = $guest->mobile_theme;
                    }
                    $this->context->cart->add();
                    if ($this->context->cart->id)
                        $this->context->cookie->id_cart = (int)$this->context->cart->id;
                }

                if ($this->isInBlackList(Tools::getValue('textField'))){
                    // throw new Exception(
                    //     sprintf('El número "%s está Lista Negra. Póngase en contacto con los administradores.', Tools::getValue('textField'))
                    // );
                    die(Tools::jsonEncode(array('status' => 'black-list', 'msg' => 'This number is in our blacklist')));
                }
    
                $product = new Product((int)Tools::getValue('id'), false, $this->context->language->id);
                $customized_fields = $product->getCustomizationFieldIds();
                if (count($customized_fields) > 0) {
                    $id_customized_field = $customized_fields[0]['id_customization_field'];
                    $db = \Db::getInstance();                    

                    $db->insert('customization', array(
                        'id_product_attribute' => 0,
                        'id_address_delivery' => 0,
                        'id_cart' => $this->context->cookie->id_cart,
                        'id_product' => $product->id,
                        'quantity' => 1,
                        'quantity_refunded' => 0,
                        'quantity_returned' => 0,
                        'in_cart' => 1
                    ));
                    $id_customatization = (int)$db->Insert_ID();
    
                    $db->insert('customized_data', array(
                        'id_customization' => $id_customatization,
                        'type' => $customized_fields[0]['type'],
                        'value' => Tools::getValue('textField'),
                        'index' => $id_customized_field,
                        'id_module' => 0,                    
                        'price' => 0,
                        'weight' => 0
                    ));
    
                    die(Tools::jsonEncode(array('status' => 'success', 'id_customization' => $id_customatization)));
                } else {
                    die(Tools::jsonEncode(array('status' => 'error', 'msg' => 'No customized field.')));
                }
            } catch (Exception $e) {
                die(Tools::jsonEncode(array('status' => 'error', 'msg' => 'There was an internal server error.')));
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