<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Shoppingcart;
    
    interface ICartRepository {
        public function getAllByCookie($cookieKey);
        public function getTotalByCookie($cookieKey);
        
        public function addToCart(Shoppingcart $cart);
        public function updateCart(Shoppingcart $cart);
        public function addOrUpdateCart(Shoppingcart $cart);
        public function deleteFromCart($coffeeKey);
    }
}

