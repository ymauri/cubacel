<?php
class Service {

    /**
     * Create logs files
     */
    public function createLogFields () {
        if (!is_dir(_PS_ROOT_DIR_.'/log')) {
            mkdir(_PS_ROOT_DIR_.'/log', 766);
            $recharge = fopen(_PS_ROOT_DIR_.'/log/recharge.log', "w");
            fwrite($recharge,'Installed on '.date('Y-m-d h:i:s'));
            fclose($recharge);
            $debug = fopen(_PS_ROOT_DIR_.'/log/debug.log', "w");
            fwrite($debug,'Installed on '.date('Y-m-d h:i:s'));
            fclose($debug);
        }       
    }

    /**
     * Get recharge type
     */
    public function getType (string $category) {
        if ($category == Configuration::get('CUBACEL_MOBILE_DEPARTMENT'))
            return Nomenclators::RECHARGE_MOBILE;
        else if ($category == Configuration::get('CUBACEL_INTERNET_DEPARTMENT')) 
            return Nomenclators::RECHARGE_INTERNET;
        return false;
    }

    /**
     * Get active promotion
     * 
     * @param string type
     * @return CubacelPromotion promotion
     */
    public function getPromotion(string $type) {
        $promotion = (Db::getInstance())->executeS((new DbQuery())
            ->from('cubacel_promotion', 'cp')
            ->where("cp.type LIKE '$type'")
            ->where('cp.enabled = 1')
            ->orderBy('cp.start_date DESC'));
    
        if (count($promotion) > 0) {
            $promotion = $promotion[0];
            $today = strtotime(date("Y-m-d"));
            $startDate = strtotime($promotion['start_date']);
            $endDate = strtotime($promotion['end_date']);
            if ($today >= $startDate && $today <= $endDate) {
                return $promotion;
            }
        }
        return [];
    }

    /**
     * Get products by category
     * @param int category
     * @return array<Product> products
     */
    public function getProducts(int $category) {
        $productsObj = (Db::getInstance())->executeS((new DbQuery())
            ->from('product', 'p')
            ->innerJoin('customization_field', 'cf', 'cf.id_product=p.id_product')
            ->where('p.id_category_default = ' . $category)
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
              'id_customization' => $prd['id_customization_field']
            ];
        }
        return $products;
    }
}