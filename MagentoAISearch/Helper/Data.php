<?php
/**
 * AISearch Magento Extension
 *
 * @category  Magento
 * @package   Magento_AISearch
 * @author    Magento
 * @copyright Copyright (c) Magento (https://www.magento.com)
 */

namespace Magento\AISearch\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\AISearch\Model\Config;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Context $context
     * @param Config $config
     */
    public function __construct(
        Context $context,
        Config $config
    ) {
        parent::__construct($context);
        $this->config = $config;
    }

    /**
     * Check if module is enabled
     *
     * @param mixed $store
     * @return bool
     */
    public function isEnabled($store = null)
    {
        return $this->config->isEnabled(ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Check if image search is enabled
     *
     * @param mixed $store
     * @return bool
     */
    public function isImageSearchEnabled($store = null)
    {
        return $this->config->isImageSearchEnabled(ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Check if voice search is enabled
     *
     * @param mixed $store
     * @return bool
     */
    public function isVoiceSearchEnabled($store = null)
    {
        return $this->config->isVoiceSearchEnabled(ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Log search to history table
     *
     * @param string $searchTerm
     * @param string $searchType
     * @param int|null $customerId
     * @return void
     */
    public function logSearch($searchTerm, $searchType, $customerId = null)
    {
        try {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $resource = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);
            $connection = $resource->getConnection();
            $tableName = $resource->getTableName('magento_aisearch_history');
            
            $data = [
                'search_term' => $searchTerm,
                'search_type' => $searchType,
                'customer_id' => $customerId,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $connection->insert($tableName, $data);
        } catch (\Exception $e) {
            $this->_logger->error('AISearch: Error logging search: ' . $e->getMessage());
        }
    }
}
