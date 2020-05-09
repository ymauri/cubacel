{extends file='page.tpl'}

{block name='title'}Recargas Internet Cubacel{/block}

// {block name="breadcrumb"}{/block} 

{block name='content'}
  <h1 class="text-center">SERVICIO DE RECARGA DE INTERNET CUBACEL</h1>
{if $has_promotion eq true }
	<div class="row mt-2">
    <div class="col-12" id="promo-container">
      {$promotion['description'] nofilter}
    </div>
  </div>
{/if}
  <div class="mt-2">
      <div class="tab-pane active" aria-labelledby="tab1-tab" id="tab1">
        <div class="container">
          <!--<form method="post" action="#" autocomplete="off">-->
            <div class="row">          
                   {foreach $products as $product}
                      <div class="col-12 col-sm-6 col-lg-3 cubacel-product mb-2">
                          <div class="item">
                              <div class="item-inner hover_second_img">
                                <div class="js-product-miniature" data-id-product="{$product['id']}" data-id-product-attribute="0" itemscope="" itemtype="http://schema.org/Product">
                                    <div class="text-center">
                                        <img class="w-100 img-thumbnail img-fluid" src="{$link->getImageLink($product['link_rewrite'], $product['id_image'])}" width="370" height="448" alt="" style="opacity: 1;">
                                        <span class="box-new-sale"></span>
                                    <div class="line_bottom mt-0"></div>
                                    <form method="post" action="{$link->getModuleLink('cubacel', 'customized')}" enctype="multipart/form-data" id="customization-{$product['id']}" style="display:none;">
                                      <input type="hidden" value="{$product['id_customization']}" id="textField-{$product['id']}">
                                    </form>
                                    <form method="post" action="{$link->getPageLink('cart')}" class="recharge text-center">
                                          <input type="hidden" name="token" value="{$static_token}">
                                          <input type="hidden" name="id_product" value="{$product['id']}">
                                          <input type="hidden" name="id_customization" id="product_customization_id-{$product['id']}">
                                          <input type="hidden" name="qty" value="1">
                                          <input type="email" id="email-{$product['id']}" class="mb-1 form-control mt-1 text-center" required="required" aria-label="Small" placeholder="usuario@nauta.com.cu" aria-describedby="inputGroup-sizing-sm">
                                        <a href="#" class="btn button-action add-custom" data-id="{$product['id']}" title="Recargar">Recargar</a>
                                        <a href="javascript:void(0)" class="btn button-action add-to-cart add-{$product['id']}" data-button-action="add-to-cart" title="Añadir al carrito" style="display:none !important;">Añadir al carrito</a>
                                    </form>
                                </div>
                              </div>
                          </div>
                      </div>
                      </div>
                  {/foreach}

            </div>
              
        </div>
      </div>
      <br/>
      <h4 class="text-center">¡Sorprende a tus amigos y familiares con una <strong>RECARGA desde MallHabana!</strong></strong></h4>
    </div>

{/block}