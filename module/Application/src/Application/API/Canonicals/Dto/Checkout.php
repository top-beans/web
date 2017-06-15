<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class Checkout extends CustomerAddresses { 
        
        /**
         * @Type("string")
         */
        public $cookie;
        
        /**
         * @Type("Application\API\Canonicals\Dto\Credentials")
         */
        public $user;
    }
}
