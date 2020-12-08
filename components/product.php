<?php
class Product
{
    public $id;
    public $title;
    public $description;
    public $price;
    public $stock;

    function __construct($id, $title, $description, $price, $stock)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;

    }

    public function getProductImages()
        {
            $temp = $GLOBALS["dbh"]->sendQuerry("SELECT * FROM " . $GLOBALS["db_prefix"] . "PRODUCTS, ".$GLOBALS["db_prefix"]."IMAGES WHERE ".$GLOBALS["db_prefix"]."PRODUCTS.ID_PRODUCT = ".$GLOBALS["db_prefix"]."IMAGES.ID_PRODUCT AND ".$GLOBALS["db_prefix"]."IMAGES.ID_PRODUCT = ".$this->id);
            if (count($temp) != 0){
                $out = [];
                foreach ($temp as $image) {
                    $out[] = "/public/images/".$image['ID_IMAGE'].$image['EXTENSION_IMAGE'];
                }
                return $out;
            }
            return null;
    }

    public static function productify($query)
    {
        return new Product($query['ID_PRODUCT'], $query['NAME_PRODUCT'], $query['DESCRIPTION_PRODUCT'], $query['PRICE_PRODUCT'], $query['STOCK_PRODUCT']);
    }

    public static function getProduct($id)
    {
        $temp = $GLOBALS["dbh"]->sendQuerry("SELECT DISTINCT * FROM " . $GLOBALS["db_prefix"] . "PRODUCTS WHERE ID_PRODUCT = $id");
        if (count($temp) === 0) return null;
        return Product::productify($temp[0]);
    }



    public static function getEveryProducts()
    {
        $out = [];
        foreach ($GLOBALS["dbh"]->sendQuerry("SELECT * FROM " . $GLOBALS["db_prefix"] . "PRODUCTS") as $product) {
            $out[] = Product::productify($product);
        }
        return $out;
    }

    
}
