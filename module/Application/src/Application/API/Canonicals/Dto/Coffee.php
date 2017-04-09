<?php

namespace Application\API\Canonicals\Dto {
    use JMS\Serializer\Annotation\Type;
    
    class Coffee { 
        
        /**
         * @Type("string")  
         */ 
        public $country;
        
        /**
         * @Type("array<string>")  
         */ 
        public $sensorialDescriptors;
        
        /**  
         * @Type("string")  
         */ 
        public $packaging;
        
        /**  
         * @Type("string")  
         */ 
        public $availability;
        
        /**  
         * @Type("string")  
         */ 
        public $warehouse;
        
        /**  
         * @Type("array<string>")  
         */ 
        public $varieties;
        
        /**  
         * @Type("string")  
         */ 
        
        public $screenSize;
        
        /**
         * @Type("string")  
         */ 
        public $availableAmount;
        
        /**  
         * @Type("string")  
         */ 
        public $countryFlag;
        
        /**
         * @Type("string")  
         */ 
        public $cropYear;
        
        /**
         * @Type("string")
         */ 
        public $cuppingScore;
        
        /**
         * @Type("string")  
         */ 
        public $price;
        
        /**
         * @Type("string")  
         */ 
        public $name;
        
        /**
         * @Type("string")  
         */ 
        public $processingMethod;
        
        /**
         * @Type("string")  
         */ 
        public $producer;
        
        /**  
         * @Type("string")  
         */ 
        public $region;
        
        /**  
         * @Type("string")  
         */ 
        public $priceBaseUnit;
        
        /**  
         * @Type("string")  
         */ 
        public $url;
    }
}
