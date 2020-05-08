<?php
require_once dirname(__FILE__) . '/../../classes/Nomenclators.php';
require_once dirname(__FILE__) . '/../../classes/Service.php';

class CubacelMobileModuleFrontController extends ModuleFrontController {

    public function initContent() {

        parent::initContent();

        $service = new Service();

        $this->setTemplate('module:cubacel/views/templates/front/mobile.tpl');

        $products = $service->getProducts(Configuration::get('CUBACEL_MOBILE_DEPARTMENT'));

        $promotion = $service->getPromotion(Nomenclators::RECHARGE_MOBILE);

        $this->context->smarty->assign([
          'products' => $products,
          'has_promotion' => count($promotion) > 0,
          'promotion' => $promotion 
        ]);
    }

    public function setMedia() {
        parent::setMedia();
    }
}
