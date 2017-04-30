<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class Checkout { 
        
        /**
         * @Type("string")
         */
        public $cookie;
        
        /**
         * @Type(<Application\API\Canonicals\Dto\Credentials>)
         */
        public $user;

        /**
         * @Type(<Application\API\Canonicals\Entity\Address>)
         */
        public $deliveryaddress;

        /**
         * @Type(<Application\API\Canonicals\Entity\Address>)
         */
        public $billingaddress;
    }
}
