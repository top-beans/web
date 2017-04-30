<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManager,
        Doctrine\ORM\EntityRepository,
        Doctrine\ORM\Mapping\ClassMetadata,
        Application\API\Repositories\Interfaces\ICheckoutService,
        Application\API\Canonicals\Entity\Customer,
        Application\API\Canonicals\Entity\Order,
        Application\API\Canonicals\Entity\Address,
        Application\API\Repositories\Base\IRepository,
        Application\API\Repositories\Base\Repository,
        Application\API\Canonicals\Entity\OrderStatuses,
        Application\API\Canonicals\Dto\EmailRequest,
        Application\API\Repositories\Interfaces\ICartRepository;

    class CheckoutService implements ICheckoutService {
        
        /**
         * @var EntityManager 
         */
        protected $em;
        
        /**
         * @var IRepository
         */
        private $customerRepo;
        
        /**
         * @var IRepository
         */
        private $orderRepo;
        
        /**
         * @var IRepository
         */
        private $addressRepo;
        
        /**
         * @var ICartRepository
         */
        private $cartRepo;
        
        public function __construct(EntityManager $em, ICartRepository $cartRepo) {
            $this->em = $em;
            $this->customerRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Customer()))));
            $this->orderRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Order()))));
            $this->addressRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Address()))));
            $this->cartRepo = $cartRepo;
        }

        public function addAnonymousOrder($cookieKey, Address $deliveryAddress, Address $billingAddress) {
            
            // Adding Customer
            $customer = new Customer();
            
            $this->addressRepo->add($deliveryAddress);
            $customer->setDeliveryaddresskey($deliveryAddress->getAddresskey());
            
            if ($billingAddress != null) {
                $this->addressRepo->add($billingAddress);
                $customer->setBillingaddresskey($billingAddress->getAddresskey());
            }

            $this->customerRepo->add($customer);
            
            // Now Adding Order
            $cartItems = $this->cartRepo->getCart($cookieKey);
            $groupKey = uniqid();
            
            foreach($cartItems as $cartItem) {
                $order = new Order();
                $order->setGroupkey($groupKey);
                $order->setStatuskey(OrderStatuses::Received);
                
                $order->setCustomerkey($customer->getCustomerkey());
                $order->setCoffeekey($cartItem->getCookiekey());
                $order->setRequestypekey($cartItem->getRequesttypekey());
                $order->setQuantity($cartItem->getQuantity());
                $order->setPrice($cartItem->getPrice());
                $order->setPricebaseunit($cartItem->getPricebaseunit());
                $order->setPackagingunit($cartItem->getPackagingunit());
                $order->setItemprice($cartItem->getItemPrice());
                $this->orderRepo->add($order);
            }
            
            return $groupKey;
        }

        public function addUserOrder($cookieKey, $username, $password) {
            
        }

        public function createCheckoutEmail($orderGroupKey) {
            
            $orders = $this->orderRepo->findBy(['groupkey' => $orderGroupKey]);
            
            if (count($orders) == 0) {
                throw new \Exception("Could not find the order required to prepare an Order Received Confirmation Email");
            }

            $customer = $this->customerRepo->fetch($orders[0]->getCustomerkey());
            $deliveryAddress = $this->addressRepo->fetch($customer->getDeliveryaddresskey());
            
            $request = new EmailRequest();
            $request->recipient = $deliveryAddress->getEmail();
            $request->subject = "Order Received at Top Beans";
            $request->htmlbody = "
                <html>
                <head>
                    <style>
                        p {
                            font-family: 'Trebuchet MS', Helvetica, sans-serif;
                        }
                        table {
                            border-collapse: collapse !important;
                            width: 70%;
                        }
                        td, th {
                            background-color: #fff !important;
                            border: 1px solid #ddd !important;
                            text-align: left;
                            padding:5px;
                            font-family: 'Trebuchet MS', Helvetica, sans-serif;
                        }
                    </style>
                </head>
                <body>
                <p>
                Dear Customer,<br/>
                This is to confirm that we have received your order at TopBeans.com as follows:
                </p>
                
                <table>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th style='text-align:right'>Cost</th>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <strong>Shipping To:</strong><br/>
                            <br/>
                            $deliveryAddress
                        </td>
                        <td style='text-align:right'>Free Shipping</td>
                    </tr>
                    <tr>
                        <th colspan=2>Total Cost</th>
                        <td style='text-align:right'>$currencySymbol $total</td>
                    </tr>
                </table>
                
                <p>
                If you wish to cancel your order, then please contact us as soon as possible.<br/>
                <br/>
                Please, however, note that once the order has been dispatched, then it would have to be returned before it can be cancelled.<br/>
                <br/>
                Please review our terms and conditions for more information.<br/>
                <br/>
                Thank you.<br/>
                Yours sincerely<br/>
                TopBeans.com
                </p>
                </body>
                </html>
            ";
            return $request;
        }

    }
}
