<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class PaymentDetails { 
        
        /**
         * @Type("string")
         */
        public $cookiekey;
        
        /**
         * @Type("string")
         */
        public $token;
    }
}
