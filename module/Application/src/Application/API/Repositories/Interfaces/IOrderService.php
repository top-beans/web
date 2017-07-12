<?php

namespace Application\API\Repositories\Interfaces {
    
    use Application\API\Canonicals\Entity\Address;
    
    interface IOrderService {
        public function searchOrderHeaders(array $criteria, array $orderBy = null, $page = 0, $pageSize = 10);
        public function getOrder($groupKey);
        public function getOrderHeader($groupKey);
        public function getNewGroupKey();
        public function getGroupByCookie($cookie);
        public function getCustomerByGroup($groupKey);
        public function getCustomerAddressesByGroup($groupKey);
        public function getAddress($key);
        public function getOrderTotalByCookie($cookie, $status);
        public function getOrderTotalByGroup($groupkey, $status = null);
        
        public function updateAddresses(Address $deliveryAddress, Address $billingAddress);
        public function addAnonymousOrder($cookieKey, Address $deliveryAddress, Address $billingAddress);
        public function addAdminOrder($cookieKey, Address $deliveryAddress, Address $billingAddress);
    }
}
