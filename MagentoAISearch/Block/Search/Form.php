<?php
/**
 * AISearch Magento Extension
 *
 * @category  Magento
 * @package   Magento_AISearch
 * @author    Magento
 * @copyright Copyright (c) Magento (https://www.magento.com)
 */

namespace Magento\AISearch\Block\Search;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\AISearch\Model\Config;

class Form extends Template
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        array $data = []
    ) {
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * Check if module is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->config->isEnabled();
    }

    /**
     * Check if image search is enabled
     *
     * @return bool
     */
    public function isImageSearchEnabled()
    {
        return $this->config->isImageSearchEnabled();
    }

    /**
     * Check if voice search is enabled
     *
     * @return bool
     */
    public function isVoiceSearchEnabled()
    {
        return $this->config->isVoiceSearchEnabled();
    }

    /**
     * Get maximum file size for image upload
     *
     * @return int
     */
    public function getMaxFileSize()
    {
        return $this->config->getMaxFileSize();
    }

    /**
     * Get allowed file types for image upload
     *
     * @return string
     */
    public function getAllowedFileTypes()
    {
        return $this->config->getAllowedFileTypes();
    }

    /**
     * Get maximum recording time for voice search
     *
     * @return int
     */
    public function getMaxRecordingTime()
    {
        return $this->config->getMaxRecordingTime();
    }

    /**
     * Get language for voice recognition
     *
     * @return string
     */
    public function getVoiceRecognitionLanguage()
    {
        return $this->config->getVoiceRecognitionLanguage();
    }

    /**
     * Get image upload URL
     *
     * @return string
     */
    public function getImageSearchUrl()
    {
        return $this->getUrl('aisearch/image/search');
    }

    /**
     * Get voice search URL
     *
     * @return string
     */
    public function getVoiceSearchUrl()
    {
        return $this->getUrl('aisearch/voice/search');
    }
}
