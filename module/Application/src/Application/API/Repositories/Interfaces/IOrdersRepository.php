<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Address;
    
    interface IOrdersRepository {
        public function searchOrderHeaders(array $criteria, array $orderBy = null, $page = 0, $pageSize = 10);
        public function getOrder($groupKey);
        public function getOrderHeader($groupKey);
        public function getNewGroupKey();
        public function getGroupByCookie($cookie);
        public function getCustomerByGroup($groupKey);
        public function getAddress($key);
        public function getOrderTotalByCookie($cookie, $status);
        public function getOrderTotalByGroup($groupkey, $status = null);
        
        public function updateAddresses(Address $deliveryAddress, Address $billingAddress);
        
        public function addAnonymousOrder($cookieKey, Address $deliveryAddress, Address $billingAddress);
        public function addAdminOrder($cookieKey, Address $deliveryAddress, Address $billingAddress);
        
        public function cancelItem($groupkey, $coffeeKey);
        public function refundItems(array $orderItems);
        public function returnItem($groupkey, $coffeeKey);
        
        public function dispatchOrder($groupkey);
        public function cancelOrder($groupKey);
        public function returnOrder($groupKey);
        public function receiveOrder($groupKey);
        public function receiveOrderByCookie($cookie);
        
        public function createReceivedEmail(array $orders, $orderGroupKey);
        public function createNewOrderAlertEmail(array $orders, $orderGroupKey);
        public function createDispatchedEmail(array $orders, $orderGroupKey);
        public function createCancelledEmail(array $orders, $orderGroupKey);
        public function createReturnedEmail(array $orders, $orderGroupKey);
    }
}
