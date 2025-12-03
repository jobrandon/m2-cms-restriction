<?php
declare(strict_types=1);

namespace Jobran\CmsRestriction\Block;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\Template;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\Response\RedirectInterface;

class RequireLogin extends Template
{
    protected $_template = 'Jobran_CmsRestriction::require_login.phtml';

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        protected RedirectInterface $redirectInterface,
        protected UrlInterface $urlInterface
    ) {
        parent::__construct($context);
    }

    public function getLoginUrl()
    {
        return $this->urlInterface
            ->getUrl('customer/account/login', 
                array(
                    'referer' => base64_encode($this->urlInterface->getCurrentUrl())
                )
            );
    }
}
