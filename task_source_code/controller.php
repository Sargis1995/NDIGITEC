<?php
class Controller
{
    private $model = null;
    //new model on class init
    public function __construct()
    {
        $this->model = new Model();
    }
    //get products from db by model
    private function get_products($qt = 1)
    {
        return $this->model->get("SELECT 
                                    products.id,
                                    products.title,
                                    products.description,
                                    products.product_type,
                                    products.product_parent,
                                    products.quantity,
                                    products.price,
                                    products.sku,
                                    products.product_status,
                                    shops.name as shop_name,
                                    Brands.name as brand_name,
                                    attributes.name as attribute_name,
                                    attr_values.title as attribute_value 
                                 FROM products
                                     INNER JOIN shops
                                      ON shops.id = products.shop_id 
                                     INNER JOIN  Brands 
                                      ON Brands.id = products.brand_id 
                                     LEFT JOIN relationships 
                                      ON relationships.product_id = products.id 
                                     LEFT JOIN attributes 
                                      ON attributes.id = relationships.attr_id 
                                     LEFT JOIN attr_values 
                                      ON attr_values.id = relationships.val_id 
                                 WHERE products.quantity >= :quantity
                                 ORDER BY products.id ASC", array(":quantity" => $qt));
    }
    /*
    Reconstruct products  returned by query
    */
    public function array_reconstruct($qt = 1,$asc = true)
    {
        $data_from_db = $this->get_products($qt);
        if(!is_array($data_from_db) || (is_array($data_from_db) && count($data_from_db) <= 0)){
            return array("error"=>"no products");
        }
        $products = array();
        //
        foreach ($data_from_db as $product) {
            //if product haven't variation or product parent product
            //add to array
            if($product['product_parent'] == 0){
                $products[$product['id']]=array(
                        'id'=>$product['id'],
                        'title'=>$product['title'],
                        'description'=>$product['description'],
                        'type'=>$product['product_type'],
                        'quantity'=>$product['quantity'],
                        'price'=> $product['price'],
                        'sku'=>$product['sku'],
                        'status'=>$product['product_status'],
                        'shop'=>$product['shop_name'],
                        'brand'=>$product['brand_name'],
                    );

            }
            //if product is  variation product get and append to parent products
            //append to early created array
            else{
                $products[$product['product_parent']]['attributes'][] = $product['attribute_name'] ;//get product attributes
                $products[$product['product_parent']]['attributes']=array_unique($products[$product['product_parent']]['attributes']);//make attributes array unique
                if(!is_array($products[$product['product_parent']]['variations'])){
					$products[$product['product_parent']]['variations'] = array();
				}
				if(!isset($products[$product['product_parent']]['variations'][$product['id']])){
					$products[$product['product_parent']]['variations'][$product['id']] = array();
				}
				$products[$product['product_parent']]['variations'][$product['id']]['id'] = $product['id'] ; //get variation id
                $products[$product['product_parent']]['variations'][$product['id']]['price']=$product['price']; //get variation price
                $products[$product['product_parent']]['variations'][$product['id']]['quantity']=$product['quantity']; //get variation stock quantity
                $products[$product['product_parent']]['variations'][$product['id']]['sku']=$product['sku']; //get variation sku
                $products[$product['product_parent']]['variations'][$product['id']][$product['attribute_name']]=$product['attribute_value']; //get variation attr and attr_value pairs
            }
        }
		if($asc){
			ksort($products);
		}else{
			krsort($products);
		}
        //return reconstructed array
        return $products;
    }
}