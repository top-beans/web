<?php

namespace Application\API\Repositories\Interfaces {
    use Application\API\Canonicals\Dto\CustomerAddresses;
    
    interface IOrderEmailsService {
        public function createReceivedEmail(array $orders, $orderGroupKey, CustomerAddresses $addresses);
        public function createNewOrderAlertEmail(array $orders, $orderGroupKey, CustomerAddresses $addresses);
        public function createDispatchedEmail(array $orders, $orderGroupKey, CustomerAddresses $addresses);
        public function createCancelledEmail(array $orders, $orderGroupKey, CustomerAddresses $addresses);
        public function createReturnedEmail(array $orders, $orderGroupKey, CustomerAddresses $addresses);
    }
}
