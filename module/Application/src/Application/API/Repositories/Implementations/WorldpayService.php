<?php

namespace Application\API\Repositories\Implementations {
    
    use Application\API\Repositories\Interfaces\IWorldpayService;
    use Worldpay\Worldpay;
    
    class WorldpayService implements IWorldpayService {
        
        /**
         * @var string
         */
        private $worldpayServiceKey;
        
        /**
         * @var string
         */
        private $settlementCurrency;
        
        public function __construct($worldpayServiceKey, $settlementCurrency) {
            $this->worldpayServiceKey = $worldpayServiceKey;
            $this->settlementCurrency = $settlementCurrency;
        }
        
        public function refundOrder($customerOrderCode, $amount = null) {
            $worldpay = new Worldpay($this->worldpayServiceKey);

            if (!$amount) {
                $worldpay->refundOrder($customerOrderCode);
            } else {
                $worldpay->refundOrder($customerOrderCode, $amount);
            }
        }
    }
}
