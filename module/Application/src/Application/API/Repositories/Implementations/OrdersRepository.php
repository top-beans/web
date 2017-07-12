<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManagerInterface;
    use Doctrine\ORM\EntityRepository;
    use Doctrine\ORM\Mapping\ClassMetadata;
    use Doctrine\Common\Collections\Criteria;
    use Application\API\Canonicals\Dto\OrderResult;
    use Application\API\Canonicals\Dto\OrderSearch;
    use Application\API\Canonicals\Dto\CustomerAddresses;
    use Application\API\Canonicals\Entity\Order;
    use Application\API\Canonicals\Entity\Customer;
    use Application\API\Canonicals\Entity\User;
    use Application\API\Canonicals\Entity\Addressview;
    use Application\API\Canonicals\Entity\Address;
    use Application\API\Canonicals\Entity\Shoppingcartview;
    use Application\API\Canonicals\Entity\Shoppingcart;
    use Application\API\Canonicals\Entity\Orderview;
    use Application\API\Canonicals\Entity\Orderheaderview;
    use Application\API\Canonicals\Entity\OrderStatuses;
    use Application\API\Repositories\Base\IRepository;
    use Application\API\Repositories\Base\Repository;
    use Application\API\Repositories\Interfaces\IEMailService;
    use Application\API\Repositories\Interfaces\IWorldpayService;
    use Application\API\Repositories\Interfaces\IOrderEmailsService;
    use Application\API\Repositories\Interfaces\IOrdersRepository;
    use Application\API\Canonicals\Dto\EmailRequest;

    class OrdersRepository implements IOrdersRepository {
        
        /**
         * @var EntityManagerInterface 
         */
        private $em;
        
        /**
         * @var IEMailService
         */
        private $emailSvc;
        
        /**
         * @var IWorldpayService
         */
        private $worldpayService;
        
        /**
         * @var IOrderEmailsService
         */
        private $orderEmailsSvc;
        
        /**
         * @var IRepository
         */
        private $ordersRepo;
        
        /**
         * @var IRepository
         */
        private $customerRepo;
        
        /**
         * @var IRepository
         */
        private $userRepo;
        
        /**
         * @var IRepository
         */
        private $addressRepo;
        
        /**
         * @var IRepository
         */
        private $addressViewRepo;
        
        /**
         * @var IRepository
         */
        private $cartViewRepo;
        
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
        
        public function __construct(EntityManagerInterface $em, IEMailService $emailSvc, IWorldpayService $worldpayService, IOrderEmailsService $orderEmailsSvc) {
            $this->em = $em;
            $this->emailSvc = $emailSvc;
            $this->worldpayService = $worldpayService;
            $this->orderEmailsSvc = $orderEmailsSvc;
            $this->ordersRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Order()))));
            $this->customerRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Customer()))));
            $this->userRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new User()))));
            $this->addressRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Address()))));
            $this->addressViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Addressview()))));
            $this->cartViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Shoppingcartview()))));
            $this->cartRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Shoppingcart()))));
            $this->orderViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Orderview()))));
            $this->orderHeaderViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Orderheaderview()))));
        }

        public function searchOrderHeaders(array $criteria, array $orderBy = null, $page = 0, $pageSize = 10) {
            $params = new OrderSearch($criteria);
            $criteriaObj = new Criteria();
            
            foreach($params->getStatuses() as $status) {
                if ($status == OrderStatuses::Creating) {
                    $criteriaObj->orWhere($criteriaObj->expr()->eq('allcreating', 1));
                } else if ($status == OrderStatuses::Received) {
                    $criteriaObj->orWhere($criteriaObj->expr()->eq('allreceived', 1));
                } else if ($status == OrderStatuses::Dispatched) {
                    $criteriaObj->orWhere($criteriaObj->expr()->eq('alldispatched', 1));
                } else if ($status == OrderStatuses::Cancelled) {
                    $criteriaObj->orWhere($criteriaObj->expr()->eq('allcancelled', 1));
                } else if ($status == OrderStatuses::SentForRefund) {
                    $criteriaObj->orWhere($criteriaObj->expr()->eq('allsentforrefund', 1));
                } else if ($status == OrderStatuses::Refunded) {
                    $criteriaObj->orWhere($criteriaObj->expr()->eq('allrefunded', 1));
                } else if ($status == OrderStatuses::Returned) {
                    $criteriaObj->orWhere($criteriaObj->expr()->eq('allreturned', 1));
                }
            }
            
            if ($params->getSearchtext() != null) {
                $criteriaObj->andWhere($criteriaObj->expr()->orX(
                    $criteriaObj->expr()->contains('groupkey', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('deliveryfirstname', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('deliverylastname', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('deliveryaddress1', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('deliveryaddress2', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('deliveryaddress3', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('deliverypostcode', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('deliverycity', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('deliverystate', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('deliveryemail', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('deliveryphone', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('deliverycountry', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('billingfirstname', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('billinglastname', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('billingaddress1', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('billingaddress2', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('billingaddress3', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('billingpostcode', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('billingcity', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('billingstate', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('billingemail', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('billingphone', $params->getSearchtext()),
                    $criteriaObj->expr()->contains('billingcountry', $params->getSearchtext())
                ));
            }
            
            if ($orderBy != null) {
                $criteriaObj->orderBy($orderBy);
            }
            
            return $this->orderHeaderViewRepo->searchByCriteria($criteriaObj, $page, $pageSize);
        }
        
        public function getOrder($groupKey) {
            return $this->orderViewRepo->findBy(['groupkey' => $groupKey]);
        }
        
        public function getOrderHeader($groupKey) {
            return $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey]);
        }

        public function getNewGroupKey() {
            do {
                $char = str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ3456789");
                $groupKey = "";
                $length = 5;
                
                for($i = 0, $l = strlen($char) - 1; $i < $length; $i ++) {
                    $groupKey .= strtoupper($char{mt_rand(0, $l)});
                }

            } while ($this->ordersRepo->count(['groupkey' => $groupKey]) > 0);
            
            return $groupKey;
        }
        
        public function getGroupByCookie($cookieKey) {
            $cartItems = $this->cartRepo->findBy(['cookiekey' => $cookieKey]);
            
            if (count($cartItems) == 0) {
                return null;
            }
            
            foreach ($cartItems as $cartItem) {
                $orderItems = $this->ordersRepo->findBy(['shoppingcartkey' => $cartItem->getShoppingcartkey()]);

                if (count($orderItems) > 0) {
                    return $orderItems[0]->getGroupkey();
                }
            }
            
            return null;
        }

        public function getCustomerByGroup($groupKey) {
            $orders = $this->orderViewRepo->findBy(['groupkey' => $groupKey]);
            
            if (count($orders) == 0) {
                return null;
            } else {
                $customerKey = $orders[0]->getCustomerkey();
                return $this->customerRepo->fetch($customerKey);
            }
        }

        public function getAddress($key) {
            return $this->addressViewRepo->fetch($key);
        }
        
        public function getCustomerAddressesByGroup($groupKey) {
            $orders = $this->orderViewRepo->findBy(['groupkey' => $groupKey]);
            
            if (count($orders) == 0) {
                return null;
            }
            
            $customerKey = $orders[0]->getCustomerkey();
            $customer = $this->customerRepo->fetch($customerKey);
            
            $deliveryAddress = $this->addressViewRepo->fetch($customer->getDeliveryaddresskey());
            $billingAddress = $this->addressViewRepo->fetch($customer->getBillingaddresskey());
            
            $addresses = new CustomerAddresses();
            $addresses->deliveryaddress = $deliveryAddress;
            $addresses->billingaddress = $billingAddress;
            
            return $addresses;
        }
        
        public function getOrderTotalByCookie($cookie, $status = null) {
            $groupKey = $this->getGroupByCookie($cookie);
            
            if ($status == null) {
                $orders = $this->orderViewRepo->findBy(['groupkey' => $groupKey]);
            } else {
                $orders = $this->orderViewRepo->findBy(['groupkey' => $groupKey, 'statuskey' => $status]);
            }
            
            $total = 0;
            
            foreach($orders as $order) {
                $total += $order->getItemPrice();
            }
            
            return number_format($total, 2, '.', '');
        }

        public function getOrderTotalByGroup($groupKey, $status = null) {

            if ($status == null) {
                $orders = $this->orderViewRepo->findBy(['groupkey' => $groupKey]);
            } else {
                $orders = $this->orderViewRepo->findBy(['groupkey' => $groupKey, 'statuskey' => $status]);
            }
            
            $total = 0;
            
            foreach($orders as $order) {
                $total += $order->getItemPrice();
            }
            
            return number_format($total, 2, '.', '');
        }
        
        
        public function updateAddresses(Address $deliveryAddress, Address $billingAddress) {
            try {
                $this->em->getConnection()->beginTransaction();
                
                $this->addressRepo->update($deliveryAddress);
                $this->addressRepo->update($billingAddress);
                
                $this->em->flush();
                $this->em->getConnection()->commit();
                
            } catch (\Exception $ex) {
                $this->em->getConnection()->rollBack();
                throw $ex;
            }
        }
        
        
        public function addAnonymousOrder($cookieKey, Address $deliveryAddress, Address $billingAddress) {
            
            try {
                $this->em->getConnection()->beginTransaction();
                $orderResult = new OrderResult();
                $orderResult->groupkey = $this->getGroupByCookie($cookieKey);

                if ($orderResult->groupkey == null) {
                    $orderResult->groupkey = $this->getNewGroupKey();
                    $customer = new Customer();

                    $this->addressRepo->add($deliveryAddress);
                    $this->addressRepo->add($billingAddress);
                    $customer->setDeliveryaddresskey($deliveryAddress->getAddresskey());
                    $customer->setBillingaddresskey($billingAddress->getAddresskey());
                    $this->customerRepo->add($customer);
                } else {
                    $customer = $this->getCustomerByGroup($orderResult->groupkey);
                    $deliveryAddress->setAddresskey($customer->getDeliveryaddresskey());
                    $billingAddress->setAddresskey($customer->getBillingaddresskey());
                    $this->addressRepo->update($deliveryAddress);
                    $this->addressRepo->update($billingAddress);
                    $this->ordersRepo->deleteList($this->ordersRepo->findBy(['groupkey' => $orderResult->groupkey]));
                }

                $cartItems = $this->cartViewRepo->findBy(['cookiekey' => $cookieKey]);
                $createdDate = new \DateTime("now", new \DateTimeZone("UTC"));
                $orderResult->requirespayment = false;

                foreach($cartItems as $cartItem) {
                    $order = new Order();
                    $order->setGroupkey($orderResult->groupkey);
                    $order->setStatuskey(OrderStatuses::Creating);

                    if (!$cartItem->getIsfreesample()) { 
                        $orderResult->requirespayment = true; 
                    }

                    $order->setCustomerkey($customer->getCustomerkey());
                    $order->setCoffeekey($cartItem->getCoffeekey());
                    $order->setRequestypekey($cartItem->getRequesttypekey());
                    $order->setQuantity($cartItem->getQuantity());
                    $order->setPrice($cartItem->getPrice());
                    $order->setPricebaseunit($cartItem->getPricebaseunit());
                    $order->setPackagingunit($cartItem->getPackagingunit());
                    $order->setItemprice($cartItem->getItemprice());
                    $order->setShoppingcartkey($cartItem->getShoppingcartkey());
                    $order->setCreateddate($createdDate);
                    $this->ordersRepo->add($order);
                }

                if(!$orderResult->requirespayment) {
                    $this->receiveOrder($orderResult->groupkey);
                }

                $this->em->flush();
                $this->em->getConnection()->commit();
                
                return $orderResult;
                
            } catch (\Exception $ex) {
                $this->em->getConnection()->rollBack();
                throw $ex;
            }
        }

        public function addAdminOrder($cookieKey, Address $deliveryAddress, Address $billingAddress) {
            throw new \Exception("Not Implemented");
        }
        
        
        public function cancelItem($groupKey, $coffeeKey) {
            try {
                $this->em->getConnection()->beginTransaction();

                $orderViewItem = $this->orderViewRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey]);
                
                if ($orderViewItem == null) {
                    throw new \Exception("Could not find the order item to cancel");
                } else if (!$orderViewItem->getReceived()) {
                    throw new \Exception("This operation is only available for Received items");
                }

                $orderItem = $this->ordersRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey]);
                $orderItem->setStatuskey(OrderStatuses::Cancelled);
                $orderItem->setUpdateddate(new \DateTime("now", new \DateTimeZone("UTC")));
                $this->ordersRepo->update($orderItem);
                
                $shoppersCopy = $this->orderEmailsSvc->createCancelledEmail([$orderViewItem], $groupKey, $this->getCustomerAddressesByGroup($groupKey));
                $this->emailSvc->sendMail($shoppersCopy);
                
                $this->em->flush();
                $this->em->getConnection()->commit();
                
                $this->em->refresh($orderViewItem);
                return $orderViewItem;
                
            } catch (\Exception $ex) {
                $this->em->getConnection()->rollBack();
                throw $ex;
            }
        }

        public function returnItem($groupKey, $coffeeKey) {
            try {
                $this->em->getConnection()->beginTransaction();

                $orderViewItem = $this->orderViewRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey]);

                if ($orderViewItem == null) {
                    throw new \Exception("Could not find the item to return");
                } else if (!$orderViewItem->getDispatched()) {
                    throw new \Exception("This operation is only available for Dispatched items");
                }
                
                $orderItem = $this->ordersRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey]);
                $orderItem->setStatuskey(OrderStatuses::Returned);
                $orderItem->setUpdateddate(new \DateTime("now", new \DateTimeZone("UTC")));
                $this->ordersRepo->update($orderItem);
                
                $shoppersCopy = $this->orderEmailsSvc->createReturnedEmail([$orderViewItem], $groupKey, $this->getCustomerAddressesByGroup($groupKey));
                $this->emailSvc->sendMail($shoppersCopy);
                
                $this->em->flush();
                $this->em->getConnection()->commit();
                
                $this->em->refresh($orderViewItem);
                return $orderViewItem;
                
            } catch (\Exception $ex) {
                $this->em->getConnection()->rollBack();
                throw $ex;
            }
        }
        
        public function requestItemRefund($groupKey, $coffeeKey) {
            try {
                $this->em->getConnection()->beginTransaction();

                $orderViewItem = $this->orderViewRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey]);
                
                if ($orderViewItem == null) {
                    throw new \Exception("Could not find the order item to cancel");
                } else if (!$orderViewItem->getIsrefundable()) {
                    throw new \Exception("This operation is only available for Cancelled or Returned items that are non-free");
                }

                $orderItem = $this->ordersRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey]);
                $refundAmount = round($orderItem->getItemprice(), 2) * 100;
                $orderItem->setStatuskey(OrderStatuses::SentForRefund);
                $orderItem->setUpdateddate(new \DateTime("now", new \DateTimeZone("UTC")));
                $this->ordersRepo->update($orderItem);
                $this->worldpayService->refundOrder($orderItem->getWorldpayordercode(), $refundAmount);
                
                $this->em->flush();
                $this->em->getConnection()->commit();

                $this->em->refresh($orderViewItem);
                return $orderViewItem;
                
            } catch (\Exception $ex) {
                $this->em->getConnection()->rollBack();
                throw $ex;
            }
        }
        
        public function dispatchItems($groupKey, array $coffeeKeys) {
            try {
                $this->em->getConnection()->beginTransaction();

                $orderViewItems = [];
                
                foreach($coffeeKeys as $coffeeKey) {
                    $orderViewItem = $this->orderViewRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey, 'received' => 1]);
                    $orderViewItems[] = $orderViewItem;

                    if ($orderViewItem == null) {
                        throw new \Exception("Could not find the requested order item to dispatch in Received status");
                    }

                    $orderItem = $this->ordersRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey]);
                    $orderItem->setStatuskey(OrderStatuses::Dispatched);
                    $this->ordersRepo->update($orderItem);
                }
                
                $shoppersCopy = $this->orderEmailsSvc->createDispatchedEmail($orderViewItems, $groupKey, $this->getCustomerAddressesByGroup($groupKey));
                $this->emailSvc->sendMail($shoppersCopy);
                
                $this->em->flush();
                $this->em->getConnection()->commit();
                
                foreach($orderViewItems as $orderItem) {
                    $this->em->refresh($orderItem);
                }
                
                return $orderViewItems;
                
            } catch (\Exception $ex) {
                $this->em->getConnection()->rollBack();
                throw $ex;
            }
        }
        
        public function dispatchOrder($groupKey) {
            try {
                $this->em->getConnection()->beginTransaction();

                $orderHeader = $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey]);

                if ($orderHeader == null) {
                    throw new \Exception("Could not find the order to cancel");
                } else if ($orderHeader->getAllreceived() != 1) {
                    throw new \Exception("This operation is only available for Received orders");
                }
            
                $orderItems = $this->ordersRepo->findBy(['groupkey' => $groupKey]);
                
                foreach($orderItems as $orderItem) {
                    $orderItem->setStatuskey(OrderStatuses::Dispatched);
                    $orderItem->setUpdateddate(new \DateTime("now", new \DateTimeZone("UTC")));
                    $this->ordersRepo->update($orderItem);
                }
                
                $orderViewItems = $this->orderViewRepo->findBy(['groupkey' => $groupKey]);
                $shoppersCopy = $this->orderEmailsSvc->createDispatchedEmail($orderViewItems, $groupKey, $this->getCustomerAddressesByGroup($groupKey));
                $this->emailSvc->sendMail($shoppersCopy);
                
                $this->em->flush();
                $this->em->getConnection()->commit();
                
                $this->em->refresh($orderHeader);
                return $orderHeader;
                
            } catch (\Exception $ex) {
                $this->em->getConnection()->rollBack();
                throw $ex;
            }
        }

        public function cancelOrder($groupKey) {
            try {
                $this->em->getConnection()->beginTransaction();

                $orderHeader = $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey]);

                if ($orderHeader == null) {
                    throw new \Exception("Could not find the order to cancel");
                } else if ($orderHeader->getAllreceived() != 1) {
                    throw new \Exception("This operation is only available for Received orders");
                }
            
                $orderItems = $this->ordersRepo->findBy(['groupkey' => $groupKey]);
                
                foreach($orderItems as $orderItem) {
                    $orderItem->setStatuskey(OrderStatuses::Cancelled);
                    $orderItem->setUpdateddate(new \DateTime("now", new \DateTimeZone("UTC")));
                    $this->ordersRepo->update($orderItem);
                }

                $orderViewItems = $this->orderViewRepo->findBy(['groupkey' => $groupKey]);
                $shoppersCopy = $this->orderEmailsSvc->createCancelledEmail($orderViewItems, $groupKey, $this->getCustomerAddressesByGroup($groupKey));
                $this->emailSvc->sendMail($shoppersCopy);

                $this->em->flush();
                $this->em->getConnection()->commit();
                
                $this->em->refresh($orderHeader);
                return $orderHeader;
                
            } catch (\Exception $ex) {
                $this->em->getConnection()->rollBack();
                throw $ex;
            }
        }

        public function returnOrder($groupKey) {
            try {
                $this->em->getConnection()->beginTransaction();

                $orderHeader = $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey]);

                if ($orderHeader == null) {
                    throw new \Exception("Could not find the order to return");
                } else if ($orderHeader->getAlldispatched() != 1) {
                    throw new \Exception("This operation is only available for Dispatched orders");
                }
            
                $orderItems = $this->ordersRepo->findBy(['groupkey' => $groupKey]);
                
                foreach($orderItems as $orderItem) {
                    $orderItem->setStatuskey(OrderStatuses::Returned);
                    $orderItem->setUpdateddate(new \DateTime("now", new \DateTimeZone("UTC")));
                    $this->ordersRepo->update($orderItem);
                }

                $orderViewItems = $this->orderViewRepo->findBy(['groupkey' => $groupKey]);
                $shoppersCopy = $this->orderEmailsSvc->createReturnedEmail($orderViewItems, $groupKey, $this->getCustomerAddressesByGroup($groupKey));
                $this->emailSvc->sendMail($shoppersCopy);
                
                $this->em->flush();
                $this->em->getConnection()->commit();
                
                $this->em->refresh($orderHeader);
                return $orderHeader;
                
            } catch (\Exception $ex) {
                $this->em->getConnection()->rollBack();
                throw $ex;
            }
        }

        public function receiveOrder($groupKey, $worldpayOrderCode = null) {
            try {
                $this->em->getConnection()->beginTransaction();

                $orderHeader = $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey]);

                if ($orderHeader == null) {
                    throw new \Exception("Could not find the order to receive");
                } else if ($orderHeader->getAllcreating() != 1) {
                    throw new \Exception("This operation is only available for non received orders (Creating)");
                }
            
                $orderItems = $this->ordersRepo->findBy(['groupkey' => $groupKey]);
                
                foreach($orderItems as $orderItem) {
                    $cartItem = $this->cartRepo->fetch($orderItem->getShoppingcartkey());
                    $orderItem->setStatuskey(OrderStatuses::Received);
                    $orderItem->setShoppingcartkey(null);
                    $orderItem->setWorldpayordercode($worldpayOrderCode);
                    $orderItem->setUpdateddate(new \DateTime("now", new \DateTimeZone("UTC")));
                    $this->ordersRepo->update($orderItem);
                    $this->cartRepo->delete($cartItem);
                }
                
                $orderViewItems = $this->orderViewRepo->findBy(['groupkey' => $groupKey]);
                $addresses = $this->getCustomerAddressesByGroup($groupKey);
                $shoppersCopy = $this->orderEmailsSvc->createReceivedEmail($orderViewItems, $groupKey, $addresses);
                $topbeansCopy = $this->orderEmailsSvc->createNewOrderAlertEmail($orderViewItems, $groupKey, $addresses);
                $this->emailSvc->sendMail($shoppersCopy);
                $this->emailSvc->sendMail($topbeansCopy);
                
                $this->em->flush();
                $this->em->getConnection()->commit();
                
            } catch (\Exception $ex) {
                $this->em->getConnection()->rollBack();
                throw $ex;
            }
        }
        
        public function requestOrderRefund($groupKey) {
            try {
                $this->em->getConnection()->beginTransaction();

                $orderHeader = $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey]);

                if ($orderHeader == null) {
                    throw new \Exception("Could not find the order to cancel");
                } else if (!$orderHeader->getIsrefundable()) {
                    throw new \Exception("This operation is only available for Cancelled or Returned orders that are non-free");
                }
            
                $orderItems = $this->ordersRepo->findBy(['groupkey' => $groupKey]);
                
                foreach($orderItems as $orderItem) {
                    $refundAmount = round($orderItem->getItemprice(), 2) * 100;

                    if ($refundAmount > 0) {
                        $orderItem->setStatuskey(OrderStatuses::SentForRefund);
                        $orderItem->setUpdateddate(new \DateTime("now", new \DateTimeZone("UTC")));
                        $this->ordersRepo->update($orderItem);
                        $this->worldpayService->refundOrder($orderItem->getWorldpayordercode(), $refundAmount);
                    }
                }

                $this->em->flush();
                $this->em->getConnection()->commit();
                
                $this->em->refresh($orderHeader);
                return $orderHeader;
                
            } catch (\Exception $ex) {
                $this->em->getConnection()->rollBack();
                throw $ex;
            }
        }
    }
}
