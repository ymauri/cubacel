<?php

class CubacelMobileModuleFrontController extends ModuleFrontController {
    public function initContent(){
      parent::initContent();

      $this->setTemplate('module:cubacel/views/templates/front/mobile.tpl');
      $productsObj = (Db::getInstance())->executeS((new DbQuery())
        ->from('product', 'p')
        ->innerJoin('customization_field', 'cf', 'cf.id_product=p.id_product')
        ->where('p.id_category_default = '. Configuration::get('CUBACEL_MOBILE_DEPARTMENT'))
        ->orderBy('p.price ASC')
      );

      $products = [];
      foreach ($productsObj as $prd) {
        $product = new Product((int)$prd['id_product'], false, $this->context->language->id);        
        $img = $product->getCover((int)$prd['id_product']);
        $products[] = [
          'id' => $prd['id_product'],
          'name' => $product->name,
          'price' => $product->price,
          'id_image' => (int)$img['id_image'],
          'link_rewrite' => $product->link_rewrite,
          'obj' => $product,
          'id_customization' => $prd['id_customization_field'],
        ];
      }
      $this->context->smarty->assign([
        'products' => $products
      ]);

    }

    public function setMedia() {
        parent::setMedia();
    }
  }
