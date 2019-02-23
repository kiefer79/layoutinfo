<?php

namespace Kiefer\LayoutInfo\Hook;

use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayoutView;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\View\BackendLayout\BackendLayout;
use TYPO3\CMS\Backend\Controller\PageLayoutController;

class PageLayoutHeader
{

    /**
     * @var int $id
     */
    protected $id;


    public function __construct()
    {
        $this->id = (int)$GLOBALS['_GET']['id'];
    }

    /**
     * @param $parameters
     * @param PageLayoutController $pageLayoutController
     */
    public function render($parameters, $pageLayoutController)
    {
        $view = GeneralUtility::makeInstance(BackendLayoutView::class);
        $layoutIdentifier = $view->getSelectedCombinedIdentifier($this->id);
        $dataProviderCollection = $view->getDataProviderCollection();
        $backendLayout = $dataProviderCollection->getBackendLayout($layoutIdentifier, $this->id);

        if ($backendLayout instanceof BackendLayout) {
            $backendLayoutTitle = $this->getLanguageService()->sL($backendLayout->getTitle());
        } else {
            $backendLayoutTitle = 'None';
        }

        // params to edit page
        $onClickEditParams = '&edit[pages][' . $this->id . ']=edit';

        // generate icon and text for button
        $buttonText = $this->getLanguageService()->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:backend_layout') . ': ' . $backendLayoutTitle;

        /** @var ButtonBar $buttonBar */
        $buttonBar = $pageLayoutController->moduleTemplate->getDocHeaderComponent()->getButtonBar();

        $layoutInfoButton = $buttonBar
            ->makeLinkButton()
            ->setShowLabelText(true)
            ->setHref("#")
            ->setOnClick(BackendUtility::editOnClick($onClickEditParams))
            ->setTitle($buttonText)
            ->setIcon($pageLayoutController->iconFactory->getIcon('mimetypes-x-backend_layout', Icon::SIZE_SMALL));
        $buttonBar->addButton($layoutInfoButton, ButtonBar::BUTTON_POSITION_RIGHT);
    }

    /**
     * @return \TYPO3\CMS\Core\Localization\LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

}
