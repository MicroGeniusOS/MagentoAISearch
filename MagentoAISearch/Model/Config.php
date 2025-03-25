<?php
/**
 * AISearch Magento Extension
 *
 * @category  Magento
 * @package   Magento_AISearch
 * @author    Magento
 * @copyright Copyright (c) Magento (https://www.magento.com)
 */

namespace Magento\AISearch\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /**
     * Config paths
     */
    const XML_PATH_ENABLED = 'aisearch/general/enabled';
    const XML_PATH_IMAGE_SEARCH_ENABLED = 'aisearch/image_search/enabled';
    const XML_PATH_VOICE_SEARCH_ENABLED = 'aisearch/voice_search/enabled';
    const XML_PATH_GOOGLE_CLOUD_VISION_API_KEY = 'aisearch/image_search/google_cloud_vision_api_key';
    const XML_PATH_MAX_FILE_SIZE = 'aisearch/image_search/max_file_size';
    const XML_PATH_ALLOWED_FILE_TYPES = 'aisearch/image_search/allowed_file_types';
    const XML_PATH_MAX_RECORDING_TIME = 'aisearch/voice_search/max_recording_time';
    const XML_PATH_VOICE_LANGUAGE = 'aisearch/voice_search/language';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Check if the module is enabled
     *
     * @param string $scope
     * @param mixed $scopeCode
     * @return bool
     */
    public function isEnabled($scope = ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            $scope,
            $scopeCode
        );
    }

    /**
     * Check if image search is enabled
     *
     * @param string $scope
     * @param mixed $scopeCode
     * @return bool
     */
    public function isImageSearchEnabled($scope = ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        if (!$this->isEnabled($scope, $scopeCode)) {
            return false;
        }
        
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_IMAGE_SEARCH_ENABLED,
            $scope,
            $scopeCode
        );
    }

    /**
     * Check if voice search is enabled
     *
     * @param string $scope
     * @param mixed $scopeCode
     * @return bool
     */
    public function isVoiceSearchEnabled($scope = ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        if (!$this->isEnabled($scope, $scopeCode)) {
            return false;
        }
        
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_VOICE_SEARCH_ENABLED,
            $scope,
            $scopeCode
        );
    }

    /**
     * Get Google Cloud Vision API Key
     *
     * @param string $scope
     * @param mixed $scopeCode
     * @return string
     */
    public function getGoogleCloudVisionApiKey($scope = ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GOOGLE_CLOUD_VISION_API_KEY,
            $scope,
            $scopeCode
        );
    }

    /**
     * Get maximum file size for image upload (in KB)
     *
     * @param string $scope
     * @param mixed $scopeCode
     * @return int
     */
    public function getMaxFileSize($scope = ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_MAX_FILE_SIZE,
            $scope,
            $scopeCode
        );
    }

    /**
     * Get allowed file types for image upload
     *
     * @param string $scope
     * @param mixed $scopeCode
     * @return string
     */
    public function getAllowedFileTypes($scope = ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ALLOWED_FILE_TYPES,
            $scope,
            $scopeCode
        );
    }

    /**
     * Get maximum recording time for voice search (in seconds)
     *
     * @param string $scope
     * @param mixed $scopeCode
     * @return int
     */
    public function getMaxRecordingTime($scope = ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_MAX_RECORDING_TIME,
            $scope,
            $scopeCode
        );
    }

    /**
     * Get language for voice recognition
     *
     * @param string $scope
     * @param mixed $scopeCode
     * @return string
     */
    public function getVoiceRecognitionLanguage($scope = ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_VOICE_LANGUAGE,
            $scope,
            $scopeCode
        );
    }
}
