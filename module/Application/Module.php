<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application {
    use Zend\Mvc\ModuleRouteListener;
    use Zend\Mvc\MvcEvent;
    use Application\API\Canonicals\Constants\Layout;
    use Application\API\Canonicals\WordPress\PostSlugs;

    class Module {
        
        public function getConfig() {
            return include __DIR__ . '/config/module.config.php';
        }

        public function getAutoloaderConfig() {
            return [
                'Zend\Loader\StandardAutoloader' => [
                    'namespaces' => [
                        __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    ],
                ],
            ];
        } 

        public function onBootstrap(MvcEvent $e) {
            $eventManager        = $e->getApplication()->getEventManager();
            $moduleRouteListener = new ModuleRouteListener();
            $moduleRouteListener->attach($eventManager);

            $this->initializeMessages($e);
            $this->initializeLayoutVariables($e);
            $this->initializeCustomCssJsFiles($e);
        }

        private function initializeLayoutVariables(MvcEvent $e) {
            $config = $e->getTarget()->getServiceManager()->get('Config');
            $wpRepo = $e->getTarget()->getServiceManager()->get('WordPrRepo');

            $phoneNumber = $wpRepo->fetchPostBySlug(PostSlugs::PhoneNumber);
            $emailAddress = $wpRepo->fetchPostBySlug(PostSlugs::EmailAddress);
            $facebookUrl = $wpRepo->fetchPostBySlug(PostSlugs::FacebookUrl);
            $twitterUrl = $wpRepo->fetchPostBySlug(PostSlugs::TwitterUrl);
            $instagramUrl = $wpRepo->fetchPostBySlug(PostSlugs::InstagramUrl);
            $youtubeUrl = $wpRepo->fetchPostBySlug(PostSlugs::YoutubeUrl);

            $e->getViewModel()->setVariable(Layout::Env, $config['ENV']);
            $e->getViewModel()->setVariable(Layout::Analytics, $config['AnalyticsTrackingID']);
            $e->getViewModel()->setVariable(PostSlugs::PhoneNumber, $phoneNumber->getContent());
            $e->getViewModel()->setVariable(PostSlugs::EmailAddress, $emailAddress->getContent());
            $e->getViewModel()->setVariable(PostSlugs::FacebookUrl, $facebookUrl->getContent());
            $e->getViewModel()->setVariable(PostSlugs::TwitterUrl, $twitterUrl->getContent());
            $e->getViewModel()->setVariable(PostSlugs::InstagramUrl, $instagramUrl->getContent());
            $e->getViewModel()->setVariable(PostSlugs::YoutubeUrl, $youtubeUrl->getContent());
        }

        private function initializeMessages(MvcEvent $e) {
            $flash = $e->getTarget()->getServiceManager()->get('ControllerPluginManager')->get('flashmessenger');            

            if (count($flash->getSuccessMessages()) > 0) {
                $e->getViewModel()->setVariable(Layout::Succ, $flash->getSuccessMessages());
            } 

            if (count($flash->getInfoMessages()) > 0) {
                $e->getViewModel()->setVariable(Layout::Info, $flash->getInfoMessages());
            }

            if (count($flash->getWarningMessages()) > 0) {
                $e->getViewModel()->setVariable(Layout::Warning, $flash->getWarningMessages());
            }

            if (count($flash->getErrorMessages()) > 0) {
                $e->getViewModel()->setVariable(Layout::Err, $flash->getErrorMessages());
            }

            $flash->clearMessages();
        }

        private function initializeCustomCssJsFiles(MvcEvent $e) {
            $render = $e->getTarget()->getServiceManager()->get('Zend\View\Renderer\RendererInterface');
            $config = $e->getTarget()->getServiceManager()->get('Config');

            foreach($config['customcss'] as $item) {
                $cssfile = str_replace ("public", "", $item);
                $render->headLink()->appendStylesheet($cssfile);
            }        

            foreach($config['customjs'] as $item) {
                $jsfile = str_replace ("public", "", $item);
                $render->headScript()->appendFile($jsfile);
            }
        }
    }    
}
