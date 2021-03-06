<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Shoppingcart;
    
    interface ICartRepository {
        public function getCart($cookieKey);
        public function getCartSize($cookieKey);
        public function getCartTotal($cookieKey);
        public function getCartBreakdown($cookieKey);
        
        public function addToCart(Shoppingcart $cart);
        public function updateCart(Shoppingcart $cart);
        public function mergeCart(Shoppingcart $cart);
        public function validateMergeCart(Shoppingcart $cart);
        public function deleteFromCart($cookiekey, $coffeeKey);
        public function incrementCartItem($cookiekey, $coffeeKey);
        public function decrementCartItem($cookiekey, $coffeeKey);
    }
}

