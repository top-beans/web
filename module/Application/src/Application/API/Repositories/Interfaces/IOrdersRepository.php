<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Address;
    
    interface IOrdersRepository {
        public function addAnonymousOrder($cookieKey, Address $deliveryAddress, Address $billingAddress);
        public function addUserOrder($cookieKey, $username, $password);
        public function createReceivedEmail($orderGroupKey);
        public function createDispatchedEmail($orderGroupKey);
    }
}
