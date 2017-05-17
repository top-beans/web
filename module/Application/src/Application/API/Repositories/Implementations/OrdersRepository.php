<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManagerInterface;
    use Doctrine\ORM\EntityRepository;
    use Doctrine\ORM\Mapping\ClassMetadata;
    use Application\API\Canonicals\Entity\Order;
    use Application\API\Canonicals\Entity\Customer;
    use Application\API\Canonicals\Entity\Address;
    use Application\API\Canonicals\Entity\Shoppingcartview;
    use Application\API\Canonicals\Entity\Orderview;
    use Application\API\Canonicals\Entity\OrderStatuses;
    use Application\API\Repositories\Base\IRepository;
    use Application\API\Repositories\Base\Repository;
    use Application\API\Repositories\Interfaces\IOrdersRepository;
    use Application\API\Canonicals\Dto\EmailRequest;

    class OrdersRepository implements IOrdersRepository {
        
        /**
         * @var EntityManager 
         */
        private $em;
        
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
        private $addressRepo;
        
        /**
         * @var IRepository
         */
        private $cartViewRepo;
        
        /**
         * @var IRepository
         */
        private $orderViewRepo;
        
        public function __construct(EntityManagerInterface $em) {
            $this->em = $em;
            $this->ordersRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Order()))));
            $this->customerRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Customer()))));
            $this->addressRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Address()))));
            $this->cartViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Shoppingcartview()))));
            $this->orderViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Orderview()))));
        }

        public function addAnonymousOrder($cookieKey, Address $deliveryAddress, Address $billingAddress) {
            
            $orders = $this->orderViewRepo->findBy(['groupkey' => $cookieKey]);
            $customer = null;
            
            if (count($orders) > 0) {
                $customer = $this->customerRepo->fetch($orders[0]->getCustomerkey());
                
                $deliveryAddress->setAddresskey($customer->getDeliveryaddresskey());
                $billingAddress->setAddresskey($customer->getBillingaddresskey());
                
                $this->addressRepo->update($deliveryAddress);
                $this->addressRepo->update($billingAddress);
                
                $this->ordersRepo->deleteList($orders);
            } else {
                $customer = new Customer();

                $this->addressRepo->add($deliveryAddress);
                $customer->setDeliveryaddresskey($deliveryAddress->getAddresskey());

                $this->addressRepo->add($billingAddress);
                $customer->setBillingaddresskey($billingAddress->getAddresskey());

                $this->customerRepo->add($customer);
            }
            
            // Now Adding Order
            $cartItems = $this->cartViewRepo->findBy(['cookiekey' => $cookieKey]);
            
            $createdDate = new \DateTime();
            $requiresPayment = false;
            
            foreach($cartItems as $cartItem) {
                $order = new Order();
                $order->setGroupkey($cookieKey);
                $order->setStatuskey(OrderStatuses::Creating);
                if ($cartItem->getIspaidsample()) { $requiresPayment = true; }
                
                $order->setCustomerkey($customer->getCustomerkey());
                $order->setCoffeekey($cartItem->getCookiekey());
                $order->setRequestypekey($cartItem->getRequesttypekey());
                $order->setQuantity($cartItem->getQuantity());
                $order->setPrice($cartItem->getPrice());
                $order->setPricebaseunit($cartItem->getPricebaseunit());
                $order->setPackagingunit($cartItem->getPackagingunit());
                $order->setItemprice($cartItem->getItemPrice());
                $order->setCreateddate($createdDate);
                $this->orderRepo->add($order);
            }
            
            return $requiresPayment;
        }

        public function addUserOrder($cookieKey, $username, $password) {
            $customer = $this->customerRepo->findOneBy(['username' => $username, 'password' => $password]);
            
            if ($customer == null) {
                throw new \Exception("Could not find the customer");
            }
            
            $orders = $this->orderViewRepo->findBy(['groupkey' => $cookieKey]);
            
            if (count($orders) > 0) {
                $this->ordersRepo->deleteList($orders);
            }
            
            $cartItems = $this->cartViewRepo->findBy(['cookiekey' => $cookieKey]);
            $createdDate = new \DateTime();
            $requiresPayment = false;
            
            foreach($cartItems as $cartItem) {
                $order = new Order();
                $order->setGroupkey($cookieKey);
                $order->setStatuskey(OrderStatuses::Creating);
                if ($cartItem->getIspaidsample()) { $requiresPayment = true; }
                
                $order->setCustomerkey($customer->getCustomerkey());
                $order->setCoffeekey($cartItem->getCookiekey());
                $order->setRequestypekey($cartItem->getRequesttypekey());
                $order->setQuantity($cartItem->getQuantity());
                $order->setPrice($cartItem->getPrice());
                $order->setPricebaseunit($cartItem->getPricebaseunit());
                $order->setPackagingunit($cartItem->getPackagingunit());
                $order->setItemprice($cartItem->getItemPrice());
                $order->setCreateddate($createdDate);
                $this->orderRepo->add($order);
            }
            
            return $requiresPayment;
        }

        public function createDispatchedEmail($orderGroupKey) {
            $orders = $this->orderViewRepo->findBy(['groupkey' => $orderGroupKey, 'statuskey' => OrderStatuses::Dispatched]);
            
            if (count($orders) == 0) {
                throw new \Exception("Could not find the order required to prepare an Order Received Confirmation Email");
            }
            
            $customer = $this->customerRepo->fetch($orders[0]->getCustomerkey());
            $deliveryAddress = $this->addressRepo->fetch($customer->getDeliveryaddresskey());
            
            $request = new EmailRequest();
            $request->recipient = $deliveryAddress->getEmail();
            $request->subject = "Your Order has been Dispatched";
            $request->htmlbody = "";
            $request->textbody = "";
            
            return $request;
        }

        public function createReceivedEmail($orderGroupKey) {
            $orders = $this->orderViewRepo->findBy(['groupkey' => $orderGroupKey, 'statuskey' => OrderStatuses::Received]);
            
            if (count($orders) == 0) {
                throw new \Exception("Could not find the order required to prepare an Order Received Confirmation Email");
            }
            
            $customer = $this->customerRepo->fetch($orders[0]->getCustomerkey());
            $deliveryAddress = $this->addressRepo->fetch($customer->getDeliveryaddresskey());
            
            $request = new EmailRequest();
            $request->recipient = $deliveryAddress->getEmail();
            $request->subject = "Your Order has been Received";
            $request->htmlbody = "";
            $request->textbody = "";
            
            return $request;
        }
    }
}
