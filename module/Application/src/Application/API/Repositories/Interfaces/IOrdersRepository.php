<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Address;
    
    interface IOrdersRepository {
        public function searchOrderHeaders(array $criteria, array $orderBy = null, $page = 0, $pageSize = 10);
        public function getOrder($groupKey);
        
        public function deleteItem($groupkey, $coffeeKey);
        public function refundItem($groupkey, $coffeeKey);
        public function cancelOrder($groupKey);
        public function refundOrder($groupKey);
        
        public function getNewGroupKey();
        public function getGroupByCookie($cookie);
        public function getCustomerByGroup($groupKey);
        public function getAddress($key);
        public function getOrderTotalByCookie($cookie, $status);
        public function getOrderTotalByGroup($groupkey, $status = null);
        public function receiveOrderByCookie($cookie);

        public function addAnonymousOrder($cookieKey, Address $deliveryAddress, Address $billingAddress);
        public function createReceivedEmail($orderGroupKey);
        public function createNewOrderAlertEmail($orderGroupKey);
        public function createDispatchedEmail($orderGroupKey);
    }
}
