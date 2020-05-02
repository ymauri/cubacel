{extends file='page.tpl'}

{block name='title'}Recargas Móviles Cubacel{/block}

// {block name="breadcrumb"}{/block} 

{block name='page_content'}
  <h1 class="text-center">SERVICIO DE RECARGA DE MÓVILES CUBACEL</h1>
  <p class="text-center text-uppercase"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-inverse fa-users fa-stack-1x"></i></span> Recarga en solo dos pasos</p>
  <div class="mt-5">
    <div class="">
      <div class="tab-pane active" aria-labelledby="tab1-tab" id="tab1">
        <div class="container">
          <!--<form method="post" action="#" autocomplete="off">-->
            <div class="row">
              <div class="col-md-6 ">
                <div class=" text-center">
                    <h2 class="mb-0 text-success">Paso 1</h2>
                    <p>Seleccione el monto de la recarga </p>
                </div>
                <div class="row"> 
                   {foreach $products as $product}
                      <div class="col-4 col-md-4 oferta-precio">
                        <div class="oft-content prod-recharge" style="position: relative;">
                        <div class="fade-recharge" style="position: absolute; display:none;">Seleccionado</div>
                        <span class="oft" id="USD" style="position: absolute; top: 8px; padding: 2px 0; background-color: #f0ed09b3; width: 90%;text-align: center; color: #222; font-weight: bold;margin: 5%;">10 CUC</span>
                          <img class="w-100 img-thumbnail img-fluid" src="{$link->getImageLink($product['link_rewrite'], $product['id_image'])}" alt="{$product['name']|truncate:30}" />
                            <span class="oft" id="precio" style="position: absolute; bottom: 2px; padding: 2px 0;background-color: #ffffffb3; width: 100%;text-align: center; color: #222; font-weight: bold; left: 0;">{$product['price']|string_format:"%.2f"}</span>
                        </div>
                      </div>
                  {/foreach}

                </div>
              </div>
              <div class="col-md-6">
                <div class=" text-center">
                  <h2 class="mb-0 text-success">Paso 2</h2>
                  <p>Introduzca el número del destinatario </p>
                </div>
                <form method="get" action="" id="recharge" class="text-center">
                    <input type="hidden" name="product" value="">
                         <input type="text" class="form-control d-inline pt-0" aria-label="Small" placeholder="Solo 8 dígitos" aria-describedby="inputGroup-sizing-sm">
                        <button type="submit" name="submit_search" class="btn button-recharge d-inline">Recargar&nbsp;<span class="fa fa-dollar"></span></button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <br/>
        <br/>
        <br/>
        <h4 class="text-center">¡Sorprende a tus amigos y familiares con una <strong>RECARGA desde MallHabana!</strong></strong></h4>
        <h4 class="text-center">Ahora es muy sencillo, seleccione el monto y escriba el número de teléfono deseado.</h4>
      </div>
    </div>
  </div>
{/block}


<a class="exclusive ajax_add_to_cart_button" rel="ajax_id_product_{$product['id']}" href="{$link->getPageLink('cart')}?qty=1&id_product={$product['id']}&token={$static_token}&add" title="{l s='Add to cart' mod='banditquote'}">{l s='Add to cart' mod='banditquote'}</a>
                                        <button type="submit" name="submit_search" class="btn button-action add-to-cart d-inline mt-1">Añadir al carrito</button>
                                    