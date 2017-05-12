<?php

namespace Application\API\Repositories\Implementations {

    use Application\API\Repositories\Base\IRepository;
    use Application\API\Repositories\Interfaces\IOrdersRepository;

    class OrdersRepository implements IOrdersRepository {
        
        /**
         * @var IRepository
         */
        private $ordersRepo;
        
        public function __construct(IRepository $ordersRepo) {
            $this->ordersRepo = $ordersRepo;
        }
    }
}
