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

            $productsDB = $GLOBALS["db_prefix"] . "PRODUCTS";
            $imageDB = $GLOBALS["db_prefix"] . "IMAGES";
            $temp = $GLOBALS["dbh"]->sendQuery("SELECT * FROM $productsDB, $imageDB WHERE ".$productsDB.".ID_PRODUCT = ".$imageDB.".ID_PRODUCT AND ".$imageDB.".ID_PRODUCT = ".$this->id);
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
        $productsDB = $GLOBALS["db_prefix"] . "PRODUCTS";
        $temp = $GLOBALS["dbh"]->sendQuery("SELECT DISTINCT * FROM $productsDB WHERE ID_PRODUCT = $id");
        if (count($temp) === 0) return null;
        return Product::productify($temp[0]);
    }



    public static function getEveryProducts()
    {
        $productsDB = $GLOBALS["db_prefix"] . "PRODUCTS";
        $out = [];
        foreach ($GLOBALS["dbh"]->sendQuery("SELECT * FROM $productsDB") as $product) {
            $out[] = Product::productify($product);
        }
        return $out;
    }

    
}
