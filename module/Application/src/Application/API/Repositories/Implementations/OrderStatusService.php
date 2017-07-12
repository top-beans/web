<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManagerInterface;
    use Doctrine\ORM\EntityRepository;
    use Doctrine\ORM\Mapping\ClassMetadata;
    use Application\API\Canonicals\Entity\Order;
    use Application\API\Canonicals\Entity\Shoppingcart;
    use Application\API\Canonicals\Entity\Orderview;
    use Application\API\Canonicals\Entity\Orderheaderview;
    use Application\API\Canonicals\Entity\OrderStatuses;
    use Application\API\Repositories\Base\IRepository;
    use Application\API\Repositories\Base\Repository;
    use Application\API\Repositories\Interfaces\IOrderEmailsService;
    use Application\API\Repositories\Interfaces\IEMailService;
    use Application\API\Repositories\Interfaces\IWorldpayService;
    use Application\API\Repositories\Interfaces\IOrderStatusService;

    class OrderStatusService implements IOrderStatusService {
        
        /**
         * @var EntityManagerInterface 
         */
        private $em;
        
        /**
         * @var IEMailService
         */
        private $emailSvc;
        
        /**
         * @var IOrderEmailsService
         */
        private $orderEmailsService;
        
        /**
         * @var IWorldpayService
         */
        private $worldpayService;
        
        /**
         * @var IRepository
         */
        private $ordersRepo;
        
        /**
         * @var IRepository
         */
        private $cartRepo;
        
        /**
         * @var IRepository
         */
        private $orderViewRepo;
        
        /**
         * @var IRepository
         */
        private $orderHeaderViewRepo;
        
        public function __construct(EntityManagerInterface $em, IEMailService $emailSvc, IOrderEmailsService $orderEmailsService, IWorldpayService $worldpayService) {
            $this->em = $em;
            $this->emailSvc = $emailSvc;
            $this->orderEmailsService = $orderEmailsService;
            $this->worldpayService = $worldpayService;
            $this->ordersRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Order()))));
            $this->cartRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Shoppingcart()))));
            $this->orderViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Orderview()))));
            $this->orderHeaderViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Orderheaderview()))));
        }

        public function cancelItem($groupKey, $coffeeKey) {
            $this->em->transactional(function($em) use ($groupKey, $coffeeKey) {
                $orderViewItem = $this->orderViewRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey, 'received' => 1]);
                
                if ($orderViewItem == null) {
                    throw new \Exception("Could not find the requested order item to cancel in Received status");
                }

                $orderItem = $this->ordersRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey]);
                $orderItem->setStatuskey(OrderStatuses::Cancelled);
                $this->ordersRepo->update($orderItem);
                
                $shoppersCopy = $this->orderEmailsService->createCancelledEmail([$orderViewItem], $groupKey);
                $this->emailSvc->sendMail($shoppersCopy);
                
                $em->refresh($orderViewItem);
                return $orderViewItem;
            });            
        }

        public function returnItem($groupKey, $coffeeKey) {
            $this->em->transactional(function($em) use ($groupKey, $coffeeKey) {
                $orderViewItem = $this->orderViewRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey, 'dispatched' => 1]);

                if ($orderViewItem == null) {
                    throw new \Exception("Could not find the requested order item to return in Dispatched status");
                }
                
                $orderItem = $this->ordersRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey]);
                $orderItem->setStatuskey(OrderStatuses::Returned);
                $this->ordersRepo->update($orderItem);
                
                $shoppersCopy = $this->createReturnedEmail([$orderViewItem], $groupKey);
                $this->emailSvc->sendMail($shoppersCopy);
                
                $em->refresh($orderViewItem);
                return $orderViewItem;
            });
        }
        
        public function requestItemRefund($groupKey, $coffeeKey) {
            $this->em->transactional(function($em) use ($groupKey, $coffeeKey) {
                $orderViewItem = $this->orderViewRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey, 'isrefundable' => 1]);
                
                if ($orderViewItem == null) {
                    throw new \Exception("Could not find the requested order item to refund in Refundable status");
                }

                $orderItem = $this->ordersRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey]);
                $orderItem->setStatuskey(OrderStatuses::SentForRefund);
                $this->ordersRepo->update($orderItem);
                
                $refundAmount = round($orderItem->getItemprice(), 2) * 100;
                $this->worldpayService->refundOrder($orderItem->getWorldpayordercode(), $refundAmount);
                
                $em->refresh($orderViewItem);
                return $orderViewItem;
            });
        }
        
        public function dispatchItems($groupKey, array $coffeeKeys) {
            $this->em->transactional(function($em) use ($groupKey, $coffeeKeys) {
                $orderViewItems = [];
                
                foreach($coffeeKeys as $coffeeKey) {
                    $orderViewItem = $this->orderViewRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey, 'received' => 1]);

                    if ($orderViewItem == null) {
                        throw new \Exception("Could not find the requested order item to dispatch in Received status");
                    }

                    $orderItem = $this->ordersRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey]);
                    $orderItem->setStatuskey(OrderStatuses::Dispatched);
                    $this->ordersRepo->update($orderItem);
                    
                    $em->refresh($orderViewItem);
                    $orderViewItems[] = $orderViewItem;
                }
                
                $shoppersCopy = $this->orderEmailsService->createDispatchedEmail($orderViewItems, $groupKey);
                $this->emailSvc->sendMail($shoppersCopy);
                return $orderViewItems;
            });            
        }
        
        public function dispatchOrder($groupKey) {
            $this->em->transactional(function($em) use ($groupKey) {
                $orderHeader = $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey, 'allreceived' => 1]);

                if ($orderHeader == null) {
                    throw new \Exception("Could not find the requested order to dispatch in Received status");
                }
            
                $orderItems = $this->ordersRepo->findBy(['groupkey' => $groupKey]);
                
                foreach($orderItems as $orderItem) {
                    $orderItem->setStatuskey(OrderStatuses::Dispatched);
                    $this->ordersRepo->update($orderItem);
                }
                
                $orderViewItems = $this->orderViewRepo->findBy(['groupkey' => $groupKey]);
                $shoppersCopy = $this->orderEmailsService->createDispatchedEmail($orderViewItems, $groupKey);
                $this->emailSvc->sendMail($shoppersCopy);
                
                $em->refresh($orderHeader);
                return $orderHeader;
            });
        }

        public function cancelOrder($groupKey) {
            $this->em->transactional(function($em) use ($groupKey) {
                $orderHeader = $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey, 'allreceived' => 1]);

                if ($orderHeader == null) {
                    throw new \Exception("Could not find the requested order to cancel in Received status");
                }
            
                $orderItems = $this->ordersRepo->findBy(['groupkey' => $groupKey]);
                
                foreach($orderItems as $orderItem) {
                    $orderItem->setStatuskey(OrderStatuses::Cancelled);
                    $this->ordersRepo->update($orderItem);
                }

                $orderViewItems = $this->orderViewRepo->findBy(['groupkey' => $groupKey]);
                $shoppersCopy = $this->orderEmailsService->createCancelledEmail($orderViewItems, $groupKey);
                $this->emailSvc->sendMail($shoppersCopy);

                $em->refresh($orderHeader);
                return $orderHeader;
            });
        }

        public function returnOrder($groupKey) {
            $this->em->transactional(function($em) use ($groupKey) {
                $orderHeader = $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey, 'alldispatched' => 1]);

                if ($orderHeader == null) {
                    throw new \Exception("Could not find the requested order to return in Dispatched status");
                }
            
                $orderItems = $this->ordersRepo->findBy(['groupkey' => $groupKey]);
                
                foreach($orderItems as $orderItem) {
                    $orderItem->setStatuskey(OrderStatuses::Returned);
                    $this->ordersRepo->update($orderItem);
                }

                $orderViewItems = $this->orderViewRepo->findBy(['groupkey' => $groupKey]);
                $shoppersCopy = $this->orderEmailsService->createReturnedEmail($orderViewItems, $groupKey);
                $this->emailSvc->sendMail($shoppersCopy);
                
                $em->refresh($orderHeader);
                return $orderHeader;
            });
        }

        public function receiveOrder($groupKey, $worldpayOrderCode = null) {
            $this->em->transactional(function($em) use ($groupKey, $worldpayOrderCode) {
                $orderHeader = $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey, 'allcreating' => 1]);

                if ($orderHeader == null) {
                    throw new \Exception("Could not find the requested order to receive in Creating status");
                }
            
                $orderItems = $this->ordersRepo->findBy(['groupkey' => $groupKey]);
                
                foreach($orderItems as $orderItem) {
                    $cartItem = $this->cartRepo->fetch($orderItem->getShoppingcartkey());
                    $orderItem->setStatuskey(OrderStatuses::Received);
                    $orderItem->setShoppingcartkey(null);
                    $orderItem->setWorldpayordercode($worldpayOrderCode);
                    $this->ordersRepo->update($orderItem);
                    $this->cartRepo->delete($cartItem);
                }
                
                $orderViewItems = $this->orderViewRepo->findBy(['groupkey' => $groupKey]);
                $shoppersCopy = $this->orderEmailsService->createReceivedEmail($orderViewItems, $groupKey);
                $topbeansCopy = $this->orderEmailsService->createNewOrderAlertEmail($orderViewItems, $groupKey);
                
                $this->emailSvc->sendMail($shoppersCopy);
                $this->emailSvc->sendMail($topbeansCopy);
            });
        }
        
        public function requestOrderRefund($groupKey) {
            $this->em->transactional(function($em) use ($groupKey) {
                $orderHeader = $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey, 'isrefundable' => 1]);

                if ($orderHeader == null) {
                    throw new \Exception("Could not find the requested order to refund in Refundable status");
                }
            
                $orderItems = $this->ordersRepo->findBy(['groupkey' => $groupKey]);
                
                foreach($orderItems as $orderItem) {
                    $refundAmount = round($orderItem->getItemprice(), 2) * 100;

                    if ($refundAmount > 0) {
                        $orderItem->setStatuskey(OrderStatuses::SentForRefund);
                        $this->ordersRepo->update($orderItem);
                        $this->worldpayService->refundOrder($orderItem->getWorldpayordercode(), $refundAmount);
                    }
                }

                $em->refresh($orderHeader);
                return $orderHeader;
            });
        }
    }
}
