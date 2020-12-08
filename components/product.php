<?php
class Product
{
    public $id;
    public $title;
    public $description;
    public $price;
    public $stock;
    public $images;

    function __construct($id, $title, $description, $price, $stock, $image=null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->images = $image;
    }


    public static function productify($query)
    {
        return new Product($query['ID_PRODUCT'], $query['NAME_PRODUCT'], $query['DESCRIPTION_PRODUCT'], $query['PRICE_PRODUCT'], $query['STOCK_PRODUCT'], $query['IMAGE_PRODUCT']);
    }

    public static function getProduct($id)
    {
        $temp = $GLOBALS["dbh"]->sendQuerry("SELECT DISTINCT * FROM " . $GLOBALS["db_prefix"] . "products WHERE ID_PRODUCT = $id");
        if(count($temp) === 0)return null;
        return Product::productify($temp[0]);
    }



    public static function getEveryProducts()
    {
        $out = [];
        foreach ($GLOBALS["dbh"]->sendQuerry("SELECT * FROM " . $GLOBALS["db_prefix"] . "products") as $product) {
            $out[] = Product::productify($product);
        }
        return $out;
    }

    public static function getProductImages($id){
        
    }

}
