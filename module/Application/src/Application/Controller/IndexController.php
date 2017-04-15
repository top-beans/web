<?php

namespace Application\Controller {
    
    use Zend\Authentication\AuthenticationServiceInterface;
    use JMS\Serializer\SerializerInterface;
    use Zend\Navigation\AbstractContainer;
    use Application\API\Repositories\Interfaces\IWordPressRepository;
    use Application\API\Canonicals\Constants\Navigation;
    use Application\API\Canonicals\WordPress\CategorySlugs;
    use Application\API\Canonicals\WordPress\PostSlugs;
    
    class IndexController extends BaseController {
        
        /**
         * @var IWordPressRepository
         */
        private $wpRepo;
        
        /**
         * @var string
         */
        private $coffeeShopUrl;
        
        public function __construct(AbstractContainer $navService, AuthenticationServiceInterface $authService, SerializerInterface $serializer, IWordPressRepository $wpRepo, $coffeeShopUrl) {
            parent::__construct($navService, $authService, $serializer);
            $this->wpRepo = $wpRepo;
            $this->coffeeShopUrl = $coffeeShopUrl;
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
            return [
                'coffeeShopUrl' => $this->coffeeShopUrl,
            ];
        }
        
        public function shoppingcartAction() {
            return [];
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
    }
}
