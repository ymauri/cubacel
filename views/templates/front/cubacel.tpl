{if $products !== false}
    <div id="home_products_title"><h1>{l s='Recarga de móviles Cubacel' mod='blockproducts'}</h1></div>
    {foreach from=$products item=product name=productLoop}
        <div class="home_products_book">
         <div class="home_products_picture">
            {if $product.cover}
                {assign var='coverImage' value=Product::getCover($product->id)}
                {assign var='coverImageId' value="{$product->id}-{$coverImage.id_image}"}
                    <img src="{$link->getImageLink($product.link_rewrite, $coverImageId)}" alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}" data-full-size-image-url="{$product.cover.large.url}" />
            {else}
                    <img src="{$urls.no_picture_image.bySize.home_default.url}" />
            {/if}  
            <div class="home_products_info">
                <div class="home_products_title">{$product.name|strip_tags|escape:html:'UTF-8'}</div>
                <div class="home_products_price">${$product.price|string_format:"%.2f"}</div>
                <div class="home_products_openButton"><a href="{$product.link}" class="btn btn-inverse">{l s='View' mod='blockproducts'}</a></div>
            </div>
        </div>
    {/foreach}
{/if}