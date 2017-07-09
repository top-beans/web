<?php

namespace Application\API\Repositories\Factories {
    
    use Zend\ServiceManager\FactoryInterface,
        Zend\ServiceManager\ServiceLocatorInterface,
        Application\API\Repositories\Implementations\WorldpayService;

    class WorldpayServiceFactory implements FactoryInterface {

        public function createService(ServiceLocatorInterface $serviceLocator) {
            $config = $serviceLocator->get('Config');
            $worldpayServiceKey = $config["WorldpayServiceKey"];
            $settlementCurrency = $config["SettlementCurrency"];
            return new WorldpayService($worldpayServiceKey, $settlementCurrency);
        }
    }
}
