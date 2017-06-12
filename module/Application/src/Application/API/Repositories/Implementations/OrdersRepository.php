<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManagerInterface;
    use Doctrine\ORM\EntityRepository;
    use Doctrine\ORM\Mapping\ClassMetadata;
    use Doctrine\Common\Collections\Criteria;
    use Application\API\Canonicals\Dto\OrderResult;
    use Application\API\Canonicals\Dto\OrderSearch;
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
        
        /**
         * @var string
         */
        private $supportEmail;
        
        /**
         * @var string
         */
        private $domainName;
        
        /**
         * @var string
         */
        private $isDevelopment;
        
        public function __construct(EntityManagerInterface $em, IEMailService $emailSvc, $supportEmail, $domainName, $isDevelopment) {
            $this->em = $em;
            $this->emailSvc = $emailSvc;
            $this->ordersRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Order()))));
            $this->customerRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Customer()))));
            $this->userRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new User()))));
            $this->addressRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Address()))));
            $this->addressViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Addressview()))));
            $this->cartViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Shoppingcartview()))));
            $this->cartRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Shoppingcart()))));
            $this->orderViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Orderview()))));
            $this->orderHeaderViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Orderheaderview()))));
            $this->supportEmail = $supportEmail;
            $this->domainName = $domainName;
            $this->isDevelopment = $isDevelopment;
        }

        public function searchOrderHeaders(array $criteria, array $orderBy = null, $page = 0, $pageSize = 10) {
            $params = new OrderSearch($criteria);
            $criteriaObj = new Criteria();

            if ($params->getStatus() == OrderStatuses::Creating) {
                $criteriaObj->andWhere($criteriaObj->expr()->eq('allcreating', 1));
            } else if ($params->getStatus() == OrderStatuses::Received) {
                $criteriaObj->andWhere($criteriaObj->expr()->eq('allreceived', 1));
            } else if ($params->getStatus() == OrderStatuses::Dispatched) {
                $criteriaObj->andWhere($criteriaObj->expr()->eq('alldispatched', 1));
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
            $this->orderViewRepo->findBy(['groupkey' => $groupKey]);
        }

        public function getNewGroupKey() {
            do {
                $uniqid = uniqid();
                $splits = [];
                $index = 0;
                $divisor = 4;

                for ($index = 0; $index + $divisor < strlen($uniqid); $index += $divisor) {
                    $splits[] = substr($uniqid, $index, $divisor);
                }

                if ($index < strlen($uniqid)) {
                    $splits[] = substr($uniqid, $index, strlen($uniqid) - $index);
                }

                $groupKey = strtoupper(join('-', $splits));
                $duplicateFound = $this->ordersRepo->count(['groupkey' => $groupKey]) > 0;
                
            } while ($duplicateFound);
            
            return $groupKey;
        }
        
        public function getGroupByCookie($cookieKey) {
            $cartItems = $this->cartRepo->findBy(['cookiekey' => $cookieKey]);
            
            if (count($cartItems) == 0) {
                return null;
            }
            
            $shoppingCartKey = $cartItems[0]->getShoppingcartkey();
            $orderItems = $this->ordersRepo->findBy(['shoppingcartkey' => $shoppingCartKey]);
            
            if (count($orderItems) == 0) {
                return null;
            }
            
            return $orderItems[0]->getGroupkey();
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
            throw new \Exception("Not implemented");
        }
        
        
        public function deleteItem($groupKey, $coffeeKey) {
            $orderItem = $this->ordersRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey]);
            
            if ($orderItem == null) {
                throw new \Exception("Could not find the coffee to delete");
            } else if ($orderItem->getStatuskey() != OrderStatuses::Received) {
                throw new \Exception("This operation is only available for Received orders");
            }
            
            $this->ordersRepo->delete($orderItem);
        }

        public function requestItemRefund($groupKey, $coffeeKey) {
            throw new \Exception("Not implemented");
        }

        public function refundItem($groupKey, $coffeeKey) {
            $orderItem = $this->ordersRepo->findOneBy(['groupkey' => $groupKey, 'coffeekey' => $coffeeKey]);
            
            if ($orderItem == null) {
                throw new \Exception("Could not find the coffee to refund");
            } else if ($orderItem->getStatuskey() != OrderStatuses::Returned) {
                throw new \Exception("This operation is only available for Returned orders");
            }
            
            throw new \Exception ("Partialy implemented");
        }
        
        public function returnItem($groupKey, $coffeeKey) {
            throw new \Exception("Not implemented");
        }

        
        public function dispatchOrder($groupKey) {
            $this->em->transactional(function(EntityManagerInterface $em) use($groupKey) {
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
                $shoppersCopy = $this->createDispatchedEmail($orderViewItems, $groupKey);
                $this->emailSvc->sendMail($shoppersCopy);
            });
        }

        public function cancelOrder($groupKey) {
            $this->em->transactional(function(EntityManagerInterface $em) use($groupKey) {
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
                $shoppersCopy = $this->createCancelledEmail($orderViewItems, $groupKey);
                $this->emailSvc->sendMail($shoppersCopy);
                
                return $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey]);
            });
        }

        public function returnOrder($groupKey) {
            $this->em->transactional(function(EntityManagerInterface $em) use($groupKey) {
                $orderHeader = $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey]);

                if ($orderHeader == null) {
                    throw new \Exception("Could not find the order to cancel");
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
                $shoppersCopy = $this->createReturnedEmail($orderViewItems, $groupKey);
                $this->emailSvc->sendMail($shoppersCopy);
            });
        }

        public function requestOrderRefund($groupKey) {
            
        }

        public function refundOrder($groupKey) {
            $orderHeader = $this->orderHeaderViewRepo->findOneBy(['groupkey' => $groupKey]);
            
            if ($orderHeader == null) {
                throw new \Exception("Could not find the order to cancel");
            } else if ($orderHeader->getAllreturned() != 1) {
                throw new \Exception("This operation is only available for Returned orders");
            }
            
            throw new \Exception ("Partialy implemented");
        }

        public function receiveOrder($groupKey) {
            $this->em->transactional(function(EntityManagerInterface $em) use($groupKey) {
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
                    $orderItem->setUpdateddate(new \DateTime("now", new \DateTimeZone("UTC")));
                    $this->ordersRepo->update($orderItem);
                    $this->cartRepo->delete($cartItem);
                }
                
                $orderViewItems = $this->orderViewRepo->findBy(['groupkey' => $groupKey]);
                $shoppersCopy = $this->createReceivedEmail($orderViewItems, $groupKey);
                $topbeansCopy = $this->createNewOrderAlertEmail($orderViewItems, $groupKey);
                $this->emailSvc->sendMail($shoppersCopy);
                $this->emailSvc->sendMail($topbeansCopy);
            });
        }

        public function receiveOrderByCookie($cookie) {
            $groupKey = $this->getGroupByCookie($cookie);
            $this->receiveOrder($groupKey);
        }
        
        
        public function createReceivedEmail(array $orders, $orderGroupKey) {
            
            if (count($orders) == 0) {
                throw new \Exception("Could not find the order required to prepare an Order Received Email");
            }
            
            $orderTotal = 0;
            foreach($orders as $order) {
                $orderTotal += $order->getItemprice();
                if ($order->getGroupkey() != $orderGroupKey) {
                    throw new \Exception("Orders Items must be from same group");
                }
            }

            $customer = $this->getCustomerByGroup($orderGroupKey);
            $deliveryAddress = $this->addressViewRepo->fetch($customer->getDeliveryaddresskey());
            $billingAddress = $this->addressViewRepo->fetch($customer->getBillingaddresskey());
            $domainPath = ($this->isDevelopment ? "http" : "https") . "://$this->domainName";
            
            $template = new TemplateEngine("data/templates/order-confirmation.phtml", [
                'domainPath' => $domainPath,
                'orderGroupKey' => $orderGroupKey,
                'orders' => $orders,
                'orderTotal' => $orderTotal,
                'deliveryAddress' => $deliveryAddress,
                'billingAddress' => $billingAddress
            ]);
            
            $request = new EmailRequest();
            $request->recipient = $deliveryAddress->getEmail();
            $request->subject = "Your TopBeans.co.uk Order has been Received";
            $request->htmlbody = $template->render();
            $request->textbody = null;
            
            return $request;
        }
        
        public function createNewOrderAlertEmail(array $orders, $orderGroupKey) {
            
            if (count($orders) == 0) {
                throw new \Exception("Could not find the order required to prepare an Order Received Email");
            }
            
            $orderTotal = 0;
            foreach($orders as $order) {
                $orderTotal += $order->getItemprice();
                if ($order->getGroupkey() != $orderGroupKey) {
                    throw new \Exception("Orders Items must be from same group");
                }
            }

            $customer = $this->getCustomerByGroup($orderGroupKey);
            $deliveryAddress = $this->addressViewRepo->fetch($customer->getDeliveryaddresskey());
            $billingAddress = $this->addressViewRepo->fetch($customer->getBillingaddresskey());
            $domainPath = ($this->isDevelopment ? "http" : "https") . "://$this->domainName";
            
            $template = new TemplateEngine("data/templates/order-alert.phtml", [
                'domainPath' => $domainPath,
                'orderGroupKey' => $orderGroupKey,
                'orders' => $orders,
                'orderTotal' => $orderTotal,
                'deliveryAddress' => $deliveryAddress,
                'billingAddress' => $billingAddress
            ]);
            
            $request = new EmailRequest();
            $request->recipient = $deliveryAddress->getEmail();
            $request->subject = "New Order Alert";
            $request->htmlbody = $template->render();
            $request->textbody = null;
            
            return $request;
        }

        public function createDispatchedEmail(array $orders, $orderGroupKey) {
            
            if (count($orders) == 0) {
                throw new \Exception("Could not find the order required to prepare an Order Dispatch Email");
            }
            
            $orderTotal = 0;
            foreach($orders as $order) {
                $orderTotal += $order->getItemprice();
                if ($order->getGroupkey() != $orderGroupKey) {
                    throw new \Exception("Orders Items must be from same group");
                }
            }

            $customer = $this->getCustomerByGroup($orderGroupKey);
            $deliveryAddress = $this->addressViewRepo->fetch($customer->getDeliveryaddresskey());
            $domainPath = ($this->isDevelopment ? "http" : "https") . "://$this->domainName";
            
            $template = new TemplateEngine("data/templates/order-dispatch.phtml", [
                'domainPath' => $domainPath,
                'orderGroupKey' => $orderGroupKey,
                'orders' => $orders,
                'orderTotal' => $orderTotal,
                'deliveryAddress' => $deliveryAddress
            ]);
            
            $request = new EmailRequest();
            $request->recipient = $deliveryAddress->getEmail();
            $request->subject = "Your TopBeans.co.uk Order has been Dispatched";
            $request->htmlbody = $template->render();
            $request->textbody = null;
            
            return $request;
        }

        public function createCancelledEmail(array $orders, $orderGroupKey) {
            
            if (count($orders) == 0) {
                throw new \Exception("Could not find the order required to prepare an Order Cancel Email");
            }
            
            $orderTotal = 0;
            foreach($orders as $order) {
                $orderTotal += $order->getItemprice();
                if ($order->getGroupkey() != $orderGroupKey) {
                    throw new \Exception("Orders Items must be from same group");
                }
            }

            $customer = $this->getCustomerByGroup($orderGroupKey);
            $deliveryAddress = $this->addressViewRepo->fetch($customer->getDeliveryaddresskey());
            $domainPath = ($this->isDevelopment ? "http" : "https") . "://$this->domainName";
            
            $template = new TemplateEngine("data/templates/order-cancelled.phtml", [
                'domainPath' => $domainPath,
                'orderGroupKey' => $orderGroupKey,
                'orders' => $orders,
                'orderTotal' => $orderTotal,
                'deliveryAddress' => $deliveryAddress
            ]);
            
            $request = new EmailRequest();
            $request->recipient = $deliveryAddress->getEmail();
            $request->subject = "Your TopBeans.co.uk Order has been Cancelled";
            $request->htmlbody = $template->render();
            $request->textbody = null;
            
            return $request;
        }
        
        public function createReturnedEmail(array $orders, $orderGroupKey) {
            
            if (count($orders) == 0) {
                throw new \Exception("Could not find the order required to prepare an Order Return Email");
            }
            
            $orderTotal = 0;
            foreach($orders as $order) {
                $orderTotal += $order->getItemprice();
                if ($order->getGroupkey() != $orderGroupKey) {
                    throw new \Exception("Orders Items must be from same group");
                }
            }

            $customer = $this->getCustomerByGroup($orderGroupKey);
            $deliveryAddress = $this->addressViewRepo->fetch($customer->getDeliveryaddresskey());
            $domainPath = ($this->isDevelopment ? "http" : "https") . "://$this->domainName";
            
            $template = new TemplateEngine("data/templates/order-returned.phtml", [
                'domainPath' => $domainPath,
                'orderGroupKey' => $orderGroupKey,
                'orders' => $orders,
                'orderTotal' => $orderTotal,
                'deliveryAddress' => $deliveryAddress
            ]);
            
            $request = new EmailRequest();
            $request->recipient = $deliveryAddress->getEmail();
            $request->subject = "Your TopBeans.co.uk Order has been Returned";
            $request->htmlbody = $template->render();
            $request->textbody = null;
            
            return $request;
        }
    }
}
