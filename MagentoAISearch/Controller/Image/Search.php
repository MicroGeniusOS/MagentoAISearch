<?php
/**
 * AISearch Magento Extension
 *
 * @category  Magento
 * @package   Magento_AISearch
 * @author    Magento
 * @copyright Copyright (c) Magento (https://www.magento.com)
 */

namespace Magento\AISearch\Controller\Image;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\AISearch\Model\Config;
use Magento\AISearch\Model\ImageSearch as ImageSearchModel;
use Magento\Framework\App\RequestInterface;
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
     * @var ImageSearchModel
     */
    protected $imageSearch;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param Config $config
     * @param ImageSearchModel $imageSearch
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Config $config,
        ImageSearchModel $imageSearch
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->config = $config;
        $this->imageSearch = $imageSearch;
    }

    /**
     * Execute image search
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        try {
            // Check if image search is enabled
            if (!$this->config->isImageSearchEnabled()) {
                throw new LocalizedException(__('Image search is disabled.'));
            }

            // Check if image was uploaded
            if (!isset($_FILES['image_search_file']) || !$_FILES['image_search_file']['tmp_name']) {
                throw new LocalizedException(__('No image file uploaded.'));
            }

            $file = $_FILES['image_search_file'];

            // Validate file
            $this->validateFile($file);

            // Process image and get search term
            $searchTerm = $this->imageSearch->getSearchTermFromImage($file['tmp_name']);

            if (empty($searchTerm)) {
                throw new LocalizedException(__('Could not identify objects in the image.'));
            }

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

    /**
     * Validate uploaded file
     *
     * @param array $file
     * @throws LocalizedException
     * @return void
     */
    private function validateFile($file)
    {
        // Check file size
        $maxFileSize = $this->config->getMaxFileSize() * 1024; // Convert KB to bytes
        if ($file['size'] > $maxFileSize) {
            throw new LocalizedException(
                __('The file is too large. Maximum allowed size is %1 KB.', $this->config->getMaxFileSize())
            );
        }

        // Check file type
        $allowedExtensions = explode(',', $this->config->getAllowedFileTypes());
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, $allowedExtensions)) {
            throw new LocalizedException(
                __('Invalid file type. Allowed types: %1.', $this->config->getAllowedFileTypes())
            );
        }

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new LocalizedException(__('There was an error uploading the file.'));
        }
    }
}
