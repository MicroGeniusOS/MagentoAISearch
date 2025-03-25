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

use Magento\Framework\Exception\LocalizedException;
use Magento\AISearch\Model\Config;
use Magento\AISearch\Model\CommerceIntegration;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;

class ImageSearch implements \Magento\AISearch\Api\ImageSearchInterface
{
    /**
     * Google Cloud Vision API URL
     */
    const GOOGLE_CLOUD_VISION_API_URL = 'https://vision.googleapis.com/v1/images:annotate';

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var Json
     */
    protected $json;
    
    /**
     * @var CommerceIntegration
     */
    protected $commerceIntegration;

    /**
     * @param Config $config
     * @param Curl $curl
     * @param Json $json
     * @param CommerceIntegration $commerceIntegration
     */
    public function __construct(
        Config $config,
        Curl $curl,
        Json $json,
        CommerceIntegration $commerceIntegration
    ) {
        $this->config = $config;
        $this->curl = $curl;
        $this->json = $json;
        $this->commerceIntegration = $commerceIntegration;
    }

    /**
     * Get search term from image
     *
     * @param string $imagePath Path to the image
     * @return string Search term
     * @throws LocalizedException
     */
    public function getSearchTermFromImage($imagePath)
    {
        try {
            if (!file_exists($imagePath)) {
                throw new LocalizedException(__('Image file does not exist.'));
            }

            $apiKey = $this->config->getGoogleCloudVisionApiKey();
            if (empty($apiKey)) {
                throw new LocalizedException(__('Google Cloud Vision API key is not configured.'));
            }

            // Read image and encode as base64
            $imageData = base64_encode(file_get_contents($imagePath));

            // Prepare request to Google Cloud Vision API
            $requestData = [
                'requests' => [
                    [
                        'image' => [
                            'content' => $imageData
                        ],
                        'features' => [
                            [
                                'type' => 'LABEL_DETECTION',
                                'maxResults' => 10
                            ],
                            [
                                'type' => 'OBJECT_LOCALIZATION',
                                'maxResults' => 5
                            ]
                        ]
                    ]
                ]
            ];

            // Send request to Google Cloud Vision API
            $this->curl->addHeader('Content-Type', 'application/json');
            $this->curl->get(self::GOOGLE_CLOUD_VISION_API_URL . '?key=' . $apiKey);
            $this->curl->post(
                self::GOOGLE_CLOUD_VISION_API_URL . '?key=' . $apiKey,
                $this->json->serialize($requestData)
            );

            $response = $this->json->unserialize($this->curl->getBody());

            if (isset($response['error'])) {
                throw new LocalizedException(__(
                    'API Error: %1',
                    $response['error']['message'] ?? 'Unknown error'
                ));
            }

            // Extract labels and objects from the response
            $labels = $this->extractLabels($response);
            $objects = $this->extractObjects($response);

            // Combine labels and objects, prioritizing objects
            $terms = array_merge($objects, $labels);
            
            // Apply Commerce-specific features if they're enabled
            $searchResults = [];
            
            // Create a mock search result for each term
            foreach ($terms as $term) {
                $searchResults[] = [
                    'term' => $term,
                    'relevance' => 1.0
                ];
            }
            
            // Apply Commerce-specific features to search results
            if ($this->commerceIntegration->isCommerceFeatureEnabled('b2b')) {
                $searchResults = $this->commerceIntegration->applyB2BCatalogPermissions($searchResults);
            }
            
            if ($this->commerceIntegration->isCommerceFeatureEnabled('customer_segments')) {
                $searchResults = $this->commerceIntegration->applyCustomerSegmentation($searchResults);
            }
            
            if ($this->commerceIntegration->isCommerceFeatureEnabled('msi')) {
                $searchResults = $this->commerceIntegration->applyMultiSourceInventory($searchResults);
            }
            
            if ($this->commerceIntegration->isCommerceFeatureEnabled('content_staging')) {
                $searchResults = $this->commerceIntegration->applyContentStaging($searchResults);
            }
            
            // Extract terms from processed search results
            $processedTerms = [];
            foreach ($searchResults as $result) {
                $processedTerms[] = $result['term'];
            }
            
            // Return the first term if available, otherwise empty string
            return !empty($processedTerms) ? $processedTerms[0] : '';
        } catch (\Exception $e) {
            throw new LocalizedException(__('Error processing image: %1', $e->getMessage()));
        }
    }

    /**
     * Extract labels from API response
     *
     * @param array $response API response
     * @return array Labels
     */
    private function extractLabels($response)
    {
        $labels = [];

        if (isset($response['responses'][0]['labelAnnotations'])) {
            foreach ($response['responses'][0]['labelAnnotations'] as $label) {
                if (isset($label['description']) && $label['score'] >= 0.7) {
                    $labels[] = $label['description'];
                }
            }
        }

        return $labels;
    }

    /**
     * Extract objects from API response
     *
     * @param array $response API response
     * @return array Objects
     */
    private function extractObjects($response)
    {
        $objects = [];

        if (isset($response['responses'][0]['localizedObjectAnnotations'])) {
            foreach ($response['responses'][0]['localizedObjectAnnotations'] as $object) {
                if (isset($object['name']) && $object['score'] >= 0.7) {
                    $objects[] = $object['name'];
                }
            }
        }

        return $objects;
    }
}
