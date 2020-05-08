<?php

require_once dirname(__FILE__) . '/../../classes/Nomenclators.php';
require_once dirname(__FILE__) . '/../../classes/Service.php';

class CubacelInternetModuleFrontController extends ModuleFrontController {

    public function initContent() {

      parent::initContent();

      $service = new Service();

      $this->setTemplate('module:cubacel/views/templates/front/internet.tpl');

      $products = $service->getProducts(Configuration::get('CUBACEL_INTERNET_DEPARTMENT'));
      
      $promotion = $service->getPromotion(Nomenclators::RECHARGE_INTERNET);

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
