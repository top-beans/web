<?php

namespace Application\API\Repositories\Interfaces {
    
    interface IOrderStatusService {
        public function cancelItem($groupKey, $coffeeKey);
        public function returnItem($groupKey, $coffeeKey);
        public function requestItemRefund($groupKey, $coffeeKey);
        public function dispatchItems($groupKey, array $coffeeKeys);

        public function dispatchOrder($groupKey);
        public function cancelOrder($groupKey);
        public function returnOrder($groupKey);
        public function receiveOrder($groupKey);
        public function requestOrderRefund($groupKey);
    }
}
