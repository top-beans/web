<?php

namespace Application\API\Canonicals\Dto {
    
    use JMS\Serializer\Annotation\Type;
    use JMS\Serializer\Annotation\SerializedName;
    
    class Webhook { 
        
        /**
         * @Type("string")
         * @SerializedName("merchantId")
         */
        public $merchantId;
        
        /**
         * @Type("string")
         * @SerializedName("notificationEventType")
         */
        public $notificationEventType;
        
        /**
         * @Type("string")
         * @SerializedName("adminCode")
         */
        public $adminCode;
        
        /**
         * @Type("string")
         * @SerializedName("merchantCode")
         */
        public $merchantCode;
        
        /**
         * @Type("string")
         * @SerializedName("orderCode")
         */
        public $orderCode;
        
        /**
         * @Type("string")
         * @SerializedName("paymentStatus")
         */
        public $paymentStatus;
        
        /**
         * @Type("string")
         * @SerializedName("paymentStatusReason")
         */
        public $paymentStatusReason;
        
        /**
         * @Type("string")
         * @SerializedName("environment")
         */
        public $environment;
    }
}
