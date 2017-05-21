<?php

namespace Application\API\Repositories\Implementations {

    use Doctrine\ORM\EntityManager,
        Doctrine\ORM\EntityRepository,
        Doctrine\ORM\Mapping\ClassMetadata,
        Application\API\Repositories\Interfaces\ICartRepository,
        Application\API\Repositories\Base\IRepository,
        Application\API\Repositories\Base\Repository,
        Application\API\Canonicals\Entity\Shoppingcart,
        Application\API\Canonicals\Entity\Coffee,
        Application\API\Canonicals\Entity\RequestTypes,
        Application\API\Canonicals\Response\ResponseUtils,
        Application\API\Canonicals\Entity\Shoppingcartview,
        Application\API\Canonicals\Entity\Order;

    class CartRepository implements ICartRepository {
        
        /**
         * @var EntityManager 
         */
        private $em;
        
        /**
         * @var IRepository
         */
        private $cartRepo;
        
        /**
         * @var IRepository
         */
        private $coffeeRepo;
        
        /**
         * @var IRepository
         */
        private $cartViewRepo;
        
        /**
         * @var IRepository
         */
        private $ordersRepo;
        
        public function __construct(EntityManager $em) {
            $this->em = $em;
            $this->cartRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Shoppingcart()))));
            $this->coffeeRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Coffee()))));
            $this->cartViewRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Shoppingcartview()))));
            $this->ordersRepo = new Repository($em, new EntityRepository($em, new ClassMetadata(get_class(new Order()))));
        }

        public function validateMergeCart(Shoppingcart $cart) {
            if($cart->getCookiekey() == null || $cart->getCoffeekey() == null) {
                return ResponseUtils::response(["Your cart is missing either a coffee or cookie key (coffee goes with cookies bruv!)"]);
            } 
            
            $coffee = $this->coffeeRepo->findOneBy(['coffeekey' => $cart->getCoffeekey(), 'active' => 1]);
            
            if ($coffee == null) {
                return ResponseUtils::response(["Your coffee could not be found"]);
            }
            
            $matching = $this->cartRepo->findOneBy([
                'cookiekey' => $cart->getCookiekey(),
                'coffeekey' => $cart->getCoffeekey()
            ]);

            if ($matching == null) {
                return ResponseUtils::response([]);
            }
            
            $errors = [];
            $warnings = [];
            $isPurchase = $cart->getRequesttypekey() == RequestTypes::Purchase;
            
            if ($cart->getQuantity() <= 0) {
                $errors[] = "A valid quantity greater than zero is required";
            } else if ($cart->getQuantity() > $coffee->getAvailableamount() * ($isPurchase ? 1 : $coffee->getBaseunitsperpackage())) {
                $errors[] = "Your quantity exceeds the available amount of " + $coffee->getAvailableamount() + " " + $coffee->getPackagingunit();
            }

            return ResponseUtils::response($errors, $warnings);
        }
        
        public function mergeCart(Shoppingcart $cart) {
            $matching = $this->cartRepo->findOneBy([
                'cookiekey' => $cart->getCookiekey(),
                'coffeekey' => $cart->getCoffeekey()
            ]);

            if ($matching == null) {
                $cart->setUpdateddate(null);
                $cart->setCreateddate(new \DateTime());
                $this->cartRepo->add($cart);
            } else {
                $cart->setShoppingcartkey($matching->getShoppingcartkey());
                $cart->setUpdateddate(new \DateTime());
                $this->cartRepo->update($cart);
            }
        }

        public function addToCart(Shoppingcart $cart) {
            $cart->setCreateddate(new \DateTime());
            $this->cartRepo->add($cart);
        }

        public function deleteFromCart($cookiekey, $coffeeKey) {
            $items = $this->cartRepo->findBy(['cookiekey' => $cookiekey, 'coffeekey' => $coffeeKey]);
            
            if ($items == null || count($items) == 0) {
                return;
            }

            foreach ($items as $item) {
                $order = $this->ordersRepo->findOneBy(['shoppingcartkey' => $item->getShoppingcartkey()]);
                if ($order != null) {
                    $this->ordersRepo->delete($order);
                }
            }
            
            $this->cartRepo->deleteList($items);
        }

        public function getCart($cookieKey) {
            return $this->cartViewRepo->findBy(['cookiekey' => $cookieKey]);
        }
        
        public function getCartSize($cookieKey) {
            return $this->cartViewRepo->count(['cookiekey' => $cookieKey]);
        }
        
        public function getCartTotal($cookieKey) {
            $items = $this->cartViewRepo->findBy(['cookiekey' => $cookieKey]);
            $total = 0;
            
            foreach($items as $item) {
                if ($item->getRequesttypekey() == RequestTypes::Purchase || $item->getQuantity() > $item->getMaxfreesamplequantity()) {
                    $total += $item->getItemprice();
                }
            }
            
            return number_format($total, 2, '.', '');
        }
        
        public function getCartBreakdown($cookieKey) {
            $coffees = $this->cartViewRepo->findBy(['cookiekey' => $cookieKey, 'requesttypekey' => RequestTypes::Purchase]);
            $coffeeTotal = 0;
            
            foreach($coffees as $item) {
                $coffeeTotal += $item->getItemprice();
            }
            
            $samples = $this->cartViewRepo->findBy(['cookiekey' => $cookieKey, 'requesttypekey' => RequestTypes::Sample]);
            $paidSampleTotal = 0;
            $paidSampleItems = [];
            $freeSampleItems = [];
            
            foreach($samples as $item) {
                if ($item->getQuantity() <= $item->getMaxfreesamplequantity()) {
                    $freeSampleItems[] = $item;
                } else {
                    $paidSampleTotal += $item->getItemprice();
                    $paidSampleItems[] = $item;
                }
            }
            
            return [
                'coffees' => [
                    'items' => $coffees,
                    'total' => number_format($coffeeTotal, 2, '.', ''),
                ],
                'paidSamples' => [
                    'items' => $paidSampleItems,
                    'total' => number_format($paidSampleTotal, 2, '.', ''),
                ],
                'freeSamples' => $freeSampleItems,
                'totalItems' => (count($coffees) + count($paidSampleItems) + count($freeSampleItems)),
                'total' => number_format($coffeeTotal + $paidSampleTotal, 2, '.', '')
            ];
        }

        public function updateCart(Shoppingcart $cart) {
            $cart->setUpdateddate(new \DateTime());
            $this->cartRepo->update($cart);
        }

        public function decrementCartItem($cookiekey, $coffeeKey) {
            $matching = $this->cartRepo->findOneBy(['cookiekey' => $cookiekey, 'coffeekey' => $coffeeKey]);
            
            if ($matching == null) {
                throw new \Exception("Could not find matching item");
            } else {
                if ($matching->getQuantity() <= 1) {
                    throw new \Exception("Cannot Decrement further");
                } else {
                    $matching->setQuantity($matching->getQuantity() - 1);
                    $matching->setUpdateddate(new \DateTime());
                    $this->cartRepo->update($matching);
                    return $this->cartViewRepo->findOneBy(['cookiekey' => $cookiekey, 'coffeekey' => $coffeeKey]);
                }
            }
        }

        public function incrementCartItem($cookiekey, $coffeeKey) {
            $matching = $this->cartRepo->findOneBy(['cookiekey' => $cookiekey, 'coffeekey' => $coffeeKey]);
            
            if ($matching == null) {
                throw new \Exception("Could not find matching item");
            } else {
                $coffee = $this->coffeeRepo->fetch($coffeeKey);
                $isPurchase = $matching->getRequesttypekey() == RequestTypes::Purchase;

                if ($matching->getQuantity() >= $coffee->getAvailableamount() * ($isPurchase ? 1 : $coffee->getBaseunitsperpackage())) {
                    throw new \Exception("Cannot Increment further");
                } else {
                    $matching->setQuantity($matching->getQuantity() + 1);
                    $matching->setUpdateddate(new \DateTime());
                    $this->cartRepo->update($matching);
                    return $this->cartViewRepo->findOneBy(['cookiekey' => $cookiekey, 'coffeekey' => $coffeeKey]);
                }
            }
        }
    }
}
