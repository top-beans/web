<?php

namespace Application\API\Repositories\Interfaces {
    
    interface IWorldpayService {
        public function refundOrder($customerOrderCode, $amount = null);
    }
}
