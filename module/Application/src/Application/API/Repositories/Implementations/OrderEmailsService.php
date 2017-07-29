<?php

namespace Application\API\Repositories\Implementations {

    use Application\API\Repositories\Interfaces\IOrderEmailsService;
    use Application\API\Repositories\Interfaces\IWordPressRepository;
    use Application\API\Canonicals\Dto\EmailRequest;
    use Application\API\Canonicals\Dto\CustomerAddresses;
    use Application\API\Canonicals\WordPress\PostSlugs;

    class OrderEmailsService implements IOrderEmailsService {
        
        /**
         * @var string
         */
        private $domainName;
        
        /**
         * @var string
         */
        private $isDevelopment;
        
        /**
         * @var string
         */
        private $supportEmail;
        
        /**
         * @var string
         */
        private $domainPath;
        
        /**
         * @var IWordPressRepository
         */
        private $wpRepo;
        
        public function __construct(IWordPressRepository $wpRepo, $domainName, $isDevelopment, $supportEmail) {
            $this->wpRepo = $wpRepo;
            $this->domainName = $domainName;
            $this->isDevelopment = $isDevelopment;
            $this->supportEmail = $supportEmail;
            $this->domainPath = ($this->isDevelopment ? "http" : "https") . "://$this->domainName";
        }

        public function createReceivedEmail(array $orders, $orderGroupKey, CustomerAddresses $addresses) {

            if($orders == null || count($orders) == 0) { throw new \Exception("At least one Order Item is required"); }
            $orderTotal = 0;
            
            foreach($orders as $order) {
                $orderTotal += $order->getItemprice();
                if ($order->getGroupkey() != $orderGroupKey) { throw new \Exception("Orders Items must be from same group"); }
            }

            $template = new TemplateEngine("data/templates/order-received.phtml", [
                'title' => "Order Received",
                'domainPath' => $this->domainPath,
                'orderGroupKey' => $orderGroupKey,
                'orders' => $orders,
                'orderTotal' => number_format($orderTotal, 2, '.', ''),
                'deliveryAddress' => $addresses->deliveryaddress,
                'billingAddress' => $addresses->billingaddress,
                'companyAddress' => $this->wpRepo->fetchPostBySlug(PostSlugs::CompanyAddress)->getContent()
            ]);
            
            $request = new EmailRequest();
            $request->recipient = $addresses->deliveryaddress->getEmail();
            $request->subject = "Order Received - TopBeans.co.uk";
            $request->htmlbody = $template->render();
            return $request;
        }
        
        public function createNewOrderAlertEmail(array $orders, $orderGroupKey, CustomerAddresses $addresses) {
            
            if($orders == null || count($orders) == 0) { throw new \Exception("At least one Order Item is required"); }
            $orderTotal = 0;
            
            foreach($orders as $order) {
                $orderTotal += $order->getItemprice();
                if ($order->getGroupkey() != $orderGroupKey) { throw new \Exception("Orders Items must be from same group"); }
            }

            $template = new TemplateEngine("data/templates/order-received-alert.phtml", [
                'title' => "Order Received Alert",
                'domainPath' => $this->domainPath,
                'orderGroupKey' => $orderGroupKey,
                'orders' => $orders,
                'orderTotal' => number_format($orderTotal, 2, '.', ''),
                'deliveryAddress' => $addresses->deliveryaddress,
                'billingAddress' => $addresses->billingaddress
            ]);
            
            $request = new EmailRequest();
            $request->recipient = $this->supportEmail;
            $request->subject = "Order Alert";
            $request->htmlbody = $template->render();
            return $request;
        }

        public function createDispatchedEmail(array $orders, $orderGroupKey, CustomerAddresses $addresses) {
            
            if($orders == null || count($orders) == 0) { throw new \Exception("At least one Order Item is required"); }
            $orderTotal = 0;
            
            foreach($orders as $order) {
                $orderTotal += $order->getItemprice();
                if ($order->getGroupkey() != $orderGroupKey) { throw new \Exception("Orders Items must be from same group"); }
            }
            
            $template = new TemplateEngine("data/templates/order-dispatched.phtml", [
                'title' => "Order Dispatched",
                'domainPath' => $this->domainPath,
                'orderGroupKey' => $orderGroupKey,
                'orders' => $orders,
                'orderTotal' => number_format($orderTotal, 2, '.', ''),
                'deliveryAddress' => $addresses->deliveryaddress,
                'billingAddress' => $addresses->billingaddress
            ]);
            
            $request = new EmailRequest();
            $request->recipient = $addresses->deliveryaddress->getEmail();
            $request->subject = "Order Dispatched - TopBeans.co.uk";
            $request->htmlbody = $template->render();
            
            return $request;
        }

        public function createCancelledEmail(array $orders, $orderGroupKey, CustomerAddresses $addresses) {
            
            if($orders == null || count($orders) == 0) { throw new \Exception("At least one Order Item is required"); }
            $orderTotal = 0;
            
            foreach($orders as $order) {
                $orderTotal += $order->getItemprice();
                if ($order->getGroupkey() != $orderGroupKey) { throw new \Exception("Orders Items must be from same group"); }
            }

            $template = new TemplateEngine("data/templates/order-cancelled.phtml", [
                'title' => "Order Cancelled",
                'domainPath' => $this->domainPath,
                'orderGroupKey' => $orderGroupKey,
                'orders' => $orders,
                'orderTotal' => number_format($orderTotal, 2, '.', ''),
                'deliveryAddress' => $addresses->deliveryaddress,
                'billingAddress' => $addresses->billingaddress
            ]);
            
            $request = new EmailRequest();
            $request->recipient = $addresses->deliveryaddress->getEmail();
            $request->subject = "Order Cancelled - TopBeans.co.uk";
            $request->htmlbody = $template->render();
            
            return $request;
        }
        
        public function createReturnedEmail(array $orders, $orderGroupKey, CustomerAddresses $addresses) {
            
            if($orders == null || count($orders) == 0) { throw new \Exception("At least one Order Item is required"); }
            $orderTotal = 0;
            
            foreach($orders as $order) {
                $orderTotal += $order->getItemprice();
                if ($order->getGroupkey() != $orderGroupKey) { throw new \Exception("Orders Items must be from same group"); }
            }

            $template = new TemplateEngine("data/templates/order-returned.phtml", [
                'title' => "Order Returned",
                'domainPath' => $this->domainPath,
                'orderGroupKey' => $orderGroupKey,
                'orders' => $orders,
                'orderTotal' => number_format($orderTotal, 2, '.', ''),
                'deliveryAddress' => $addresses->deliveryaddress,
                'billingAddress' => $addresses->billingaddress
            ]);
            
            $request = new EmailRequest();
            $request->recipient = $addresses->deliveryaddress->getEmail();
            $request->subject = "Order Returned - TopBeans.co.uk";
            $request->htmlbody = $template->render();
            
            return $request;
        }
        
        public function createRefundedEmail(array $orders, $orderGroupKey, CustomerAddresses $addresses) {
            
            if($orders == null || count($orders) == 0) { throw new \Exception("At least one Order Item is required"); }
            $orderTotal = 0;
            
            foreach($orders as $order) {
                $orderTotal += $order->getItemprice();
                if ($order->getGroupkey() != $orderGroupKey) { throw new \Exception("Orders Items must be from same group"); }
            }

            $template = new TemplateEngine("data/templates/order-refunded.phtml", [
                'title' => "Order Refunded",
                'domainPath' => $this->domainPath,
                'orderGroupKey' => $orderGroupKey,
                'orders' => $orders,
                'orderTotal' => number_format($orderTotal, 2, '.', ''),
                'deliveryAddress' => $addresses->deliveryaddress,
                'billingAddress' => $addresses->billingaddress
            ]);
            
            $request = new EmailRequest();
            $request->recipient = $addresses->deliveryaddress->getEmail();
            $request->subject = "Order Refunded - TopBeans.co.uk";
            $request->htmlbody = $template->render();
            
            return $request;
        }
        
        public function createRefundFailedAlertEmail(array $orders, $orderGroupKey, CustomerAddresses $addresses) {
            
            if($orders == null || count($orders) == 0) { throw new \Exception("At least one Order Item is required"); }
            $orderTotal = 0;
            
            foreach($orders as $order) {
                $orderTotal += $order->getItemprice();
                if ($order->getGroupkey() != $orderGroupKey) { throw new \Exception("Orders Items must be from same group"); }
            }

            $template = new TemplateEngine("data/templates/order-refund-failed.phtml", [
                'title' => "Refund Failed",
                'domainPath' => $this->domainPath,
                'orderGroupKey' => $orderGroupKey,
                'orders' => $orders,
                'orderTotal' => number_format($orderTotal, 2, '.', ''),
                'deliveryAddress' => $addresses->deliveryaddress,
                'billingAddress' => $addresses->billingaddress
            ]);
            
            $request = new EmailRequest();
            $request->recipient = $this->supportEmail;
            $request->subject = "Refund Failed Alert";
            $request->htmlbody = $template->render();
            
            return $request;
        }
        
        public function createConfirmationCodeEmail($code, $expiry, $deliveryEmail) {

            $template = new TemplateEngine("data/templates/confirmation-code.phtml", [
                'title' => "Confirmation Code",
                'domainPath' => $this->domainPath,
                'code' => $code,
                'expiry' => $expiry
            ]);
            
            $request = new EmailRequest();
            $request->recipient = $deliveryEmail;
            $request->subject = "Confirmation Code - $code";
            $request->htmlbody = $template->render();
            
            return $request;
        }
    }
}
