<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Controller\CarProfile;

use Magento\Customer\Model\Session;
use Magento\Customer\Model\Url;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Index implements HttpGetActionInterface
{
    /**
     * @param Url $customerUrl
     * @param Session $customerSession
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        private readonly Url           $customerUrl,
        private readonly Session       $customerSession,
        private readonly ResultFactory $resultFactory,
    ){
    }


    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->customerUrl->getLoginUrl());
            return $resultRedirect;
        }

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->set(__('My Car Profile'));
        return $resultPage;
    }
}
