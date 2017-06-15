<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class CustomerAddresses { 
        
        /**
         * @Type("Application\API\Canonicals\Entity\Address")
         */
        public $deliveryaddress;

        /**
         * @Type("Application\API\Canonicals\Entity\Address")
         */
        public $billingaddress;
    }
}
