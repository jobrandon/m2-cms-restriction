<?php

namespace Jobran\CmsRestriction\Observer;

use Magento\Customer\Model\Session;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Result\PageFactory;

class CheckRestriction implements ObserverInterface
{
    public function __construct(
        protected PageFactory $resultPageFactory,
        protected Session $customerSession
    ) {
    }

    public function execute(EventObserver $observer)
    {
        $page = $observer->getPage();

        if (!(bool) $page->getIsLogin()) {
            return $this;
        }

        //We are using HTTP headers to control various page caches (varnish, fastly, built-in php cache)
        $page->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);

        if (!$this->customerSession->isLoggedIn()) {
            $resultPage = $this->resultPageFactory->create();
            $loginContent = $resultPage->getLayout()
                ->createBlock(\Jobran\CmsRestriction\Block\RequireLogin::class)
                ->toHtml();
            $page->setContent($loginContent);
        }

        return $this;
    }
}
