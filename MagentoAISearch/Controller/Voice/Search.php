<?php
/**
 * AISearch Magento Extension
 *
 * @category  Magento
 * @package   Magento_AISearch
 * @author    Magento
 * @copyright Copyright (c) Magento (https://www.magento.com)
 */

namespace Magento\AISearch\Controller\Voice;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\AISearch\Model\Config;
use Magento\AISearch\Model\VoiceSearch as VoiceSearchModel;
use Magento\Framework\Exception\LocalizedException;

class Search extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var VoiceSearchModel
     */
    protected $voiceSearch;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Config $config
     * @param VoiceSearchModel $voiceSearch
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Config $config,
        VoiceSearchModel $voiceSearch
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->config = $config;
        $this->voiceSearch = $voiceSearch;
    }

    /**
     * Execute voice search
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        try {
            // Check if voice search is enabled
            if (!$this->config->isVoiceSearchEnabled()) {
                throw new LocalizedException(__('Voice search is disabled.'));
            }

            // Get search term from POST data
            $searchTerm = $this->getRequest()->getParam('voice_search_text');

            if (empty($searchTerm)) {
                throw new LocalizedException(__('No voice search text provided.'));
            }

            // Process search term if needed
            $searchTerm = $this->voiceSearch->processSearchTerm($searchTerm);

            return $resultJson->setData([
                'success' => true,
                'search_term' => $searchTerm,
                'redirect_url' => $this->_url->getUrl('catalogsearch/result', ['_query' => ['q' => $searchTerm]])
            ]);
        } catch (\Exception $e) {
            return $resultJson->setData([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
