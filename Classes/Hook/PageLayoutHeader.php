<?php

namespace Kiefer\LayoutInfo\Hook;

use TYPO3\CMS\Backend\View\BackendLayoutView;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Backend\View\BackendLayout\BackendLayout;

class PageLayoutHeader
{

    public function render($parameters, $parentObject)
    {
        $view = GeneralUtility::makeInstance(BackendLayoutView::class);
        $layoutIdentifier = $view->getSelectedCombinedIdentifier($parentObject->id);
        $dataProviderCollection = $view->getDataProviderCollection();
        $backendLayout = $dataProviderCollection->getBackendLayout($layoutIdentifier, $parentObject->id);

        if ($backendLayout instanceof BackendLayout) {
            $backendLayoutTitle = $this->getLanguageService()->sL($backendLayout->getTitle());
            $messageText = 'Backend layout in use: ' . $backendLayoutTitle;
        } else {
            $messageText = 'No backend layout selected';
        }

        $message = GeneralUtility::makeInstance(FlashMessage::class,
            $messageText,
            '',
            FlashMessage::INFO,
            false
        );

        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $flashMessageService = $objectManager->get(FlashMessageService::class);
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);

    }

    /**
     * @return \TYPO3\CMS\Core\Localization\LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

}
