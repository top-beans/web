<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Address;
    
    interface ICheckoutService {
        public function addAnonymousOrder($cookieKey, Address $deliveryAddress, Address $billingAddress);
        public function addUserOrder($cookieKey, $username, $password);
        public function createCheckoutEmail($orderGroupKey);
    }
}
