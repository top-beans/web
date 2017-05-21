<?php

namespace Application\Controller {
    
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Zend\Navigation\AbstractContainer;
    use Application\API\Repositories\Interfaces\IWordPressRepository;
    use Application\API\Canonicals\Constants\Navigation;
    use Application\API\Canonicals\WordPress\CategorySlugs;
    use Application\API\Canonicals\WordPress\PostSlugs;
    use Application\API\Canonicals\Constants\FlashMessages;
    
    class IndexController extends BaseController {
        
        /**
         * @var IWordPressRepository
         */
        private $wpRepo;
        
        /**
         * @var string
         */
        private $worldPayClientKey;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, IWordPressRepository $wpRepo, $worldPayClientKey) {
            parent::__construct($navService, $authService, $serializer);
            $this->wpRepo = $wpRepo;
            $this->worldPayClientKey = $worldPayClientKey;
        }        
        
        public function indexAction() {
            return [
                'carouselOne'           => $this->wpRepo->fetchPostBySlug(PostSlugs::CarouselOne),
                'carouselTwo'           => $this->wpRepo->fetchPostBySlug(PostSlugs::CarouselTwo),
                'carouselThree'         => $this->wpRepo->fetchPostBySlug(PostSlugs::CarouselThree),
                'moreThanCoffee'        => $this->wpRepo->fetchPostBySlug(PostSlugs::MoreThanCoffee),
                'ourTeam'               => $this->wpRepo->fetchPostBySlug(PostSlugs::OurTeam),
                'gradesOfCoffee'        => $this->wpRepo->fetchCategoryBySlug(CategorySlugs::GradesOfCoffee),
                'gradesOfCoffeePosts'   => $this->wpRepo->fetchCategoryPostsBySlug(CategorySlugs::GradesOfCoffee),
            ];
        }
        
        public function approachAction() {
            return [
                'whatWeDo'          => $this->wpRepo->fetchPostBySlug(PostSlugs::WhatWeDo),
                'aboutKenyanCoffee' => $this->wpRepo->fetchPostBySlug(PostSlugs::AboutKenyanCoffee),
                'gradesOfCoffee'        => $this->wpRepo->fetchCategoryBySlug(CategorySlugs::GradesOfCoffee),
                'gradesOfCoffeePosts'   => $this->wpRepo->fetchCategoryPostsBySlug(CategorySlugs::GradesOfCoffee),
            ];
        }
        
        public function buyorsampleAction() {
            return [];
        }
        
        public function shoppingcartAction() {
            return [
                'orderComplete' => in_array(FlashMessages::OrderComplete, $this->flashMessenger()->getSuccessMessages())
            ];
        }
        
        public function contactsAction() {
            return [
                'phoneNumber'   => $this->wpRepo->fetchPostBySlug(PostSlugs::PhoneNumber),
                'emailAddress'  => $this->wpRepo->fetchPostBySlug(PostSlugs::EmailAddress),
            ];
        }
        
        public function termsAction() {
            return [
                'terms'   => $this->wpRepo->fetchPostBySlug(PostSlugs::Terms),
            ];
        }
            
        public function privacyAction() {
            return [
                'privacy'   => $this->wpRepo->fetchPostBySlug(PostSlugs::Privacy),
            ];
        }
        
        public function previewAction() {
            $this->navService->findOneById(Navigation::Preview)->setVisible(true);
            $theLoop = $this->wpRepo->fetchTheLoop($this->getRequest()->getQuery()->toString());
            return array('posts' => $theLoop);
        }
        
        public function checkoutAction() {
            return [];
        }
        
        public function paymentAction() {
            return [
                'worldPayClientKey' => $this->worldPayClientKey
            ];
        }
    }
}
