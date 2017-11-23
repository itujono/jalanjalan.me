<?php
$config = hikashop_config();
$imagehelper = hikashop_get('helper.image');
$productClassImg = hikashop_get('class.product');
$productClassImg->getProducts($displayData->product_id);
$products = $productClassImg->products;
$productImg = reset($products);
//var_dump($productImg);
if ($productImg->images && count($productImg->images) > 1):
	$image=$productImg->images[0];
	//$imagepath= HIKASHOP_LIVE.$config->get('uploadfolder').$img->file_path;	
	
	//$image = reset($product->images);
	
	
	
endif;
$currencyClass = hikashop_get('class.currency');
$ids = array($displayData->product_id);
$currencyClass->getPrices($displayData,$ids,hikashop_getCurrency(),(int)$config->get('main_currency',1), hikashop_getZone(null),(int)$config->get('discount_before_tax',0));
$formattedprice=$currencyClass->format($displayData->prices[0]->price_value_with_tax,$displayData->prices[0]->price_currency_id);
?>
<div class="ad_hkproductli ad_hkproduct_<?php echo $displayData->product_id;?>">
	<a href="<?php echo hikashop_contentLink('product&task=show&cid=' . $displayData->product_id . '&name=' . $displayData->alias, $displayData);?>">
		<h4><?php echo $displayData->product_name;?></h4>
		<?php if ($image){ 
			$width = $config->get('thumbnail_x');
			$height = $config->get('thumbnail_y');
			$imagehelper->checkSize($width,$height,$image);
			if (!$config->get('thumbnail')) {
				echo '<img src="'.$imagehelper->uploadFolder_url.$image->file_path.'" alt="'.$image->file_name.'" id="hikashop_main_image" style="margin-top:10px;margin-bottom:10px;display:inline-block;vertical-align:middle" />';
			} else { ?>
                 <div class="hikashop_cart_product_image_thumb" >
                 <?php echo $imagehelper->display($image->file_path,true,$image->file_name,'style="margin-top:10px;margin-bottom:10px;display:inline-block;vertical-align:middle"', '', $width, $height); ?>
                 </div><?php 
			}
		}
		?>	
		<div class="ad_hkproduct_price"><?php echo $formattedprice; ?> </div>
	</a>
</div>
