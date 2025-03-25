/**
 * AISearch Magento Extension
 *
 * @category  Magento
 * @package   Magento_AISearch
 * @author    Magento
 * @copyright Copyright (c) Magento (https://www.magento.com)
 */
define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'mage/translate'
], function ($, alert, $t) {
    'use strict';

    return function (config) {
        var imageSearchConfig = {
            uploadUrl: config.uploadUrl,
            maxFileSize: config.maxFileSize,
            allowedFileTypes: config.allowedFileTypes.split(','),
            buttonSelector: '#image-search-button',
            fileInputSelector: '#image-search-file',
            dropAreaSelector: '#image-search-droparea',
            statusMessageSelector: '#image-search-status',
            previewSelector: '#image-search-preview'
        };

        /**
         * Initialize image search functionality
         */
        function initImageSearch() {
            var $button = $(imageSearchConfig.buttonSelector),
                $fileInput = $(imageSearchConfig.fileInputSelector),
                $dropArea = $(imageSearchConfig.dropAreaSelector),
                $statusMessage = $(imageSearchConfig.statusMessageSelector),
                $preview = $(imageSearchConfig.previewSelector);

            // Handle file selection via button
            $button.on('click', function () {
                $fileInput.trigger('click');
            });

            // Handle file selection change
            $fileInput.on('change', function () {
                if (this.files && this.files[0]) {
                    handleFileUpload(this.files[0]);
                }
            });

            // Handle drag and drop
            $dropArea.on('dragover', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('active');
            }).on('dragleave', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('active');
            }).on('drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('active');
                
                if (e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files.length) {
                    handleFileUpload(e.originalEvent.dataTransfer.files[0]);
                }
            });
        }

        /**
         * Handle file upload
         * 
         * @param {File} file
         */
        function handleFileUpload(file) {
            var $statusMessage = $(imageSearchConfig.statusMessageSelector),
                $preview = $(imageSearchConfig.previewSelector);
            
            // Validate file
            if (!validateFile(file)) {
                return;
            }

            // Show preview
            showImagePreview(file);
            
            // Show loading message
            $statusMessage.text($t('Analyzing image...'));
            $statusMessage.show();

            // Upload and process the image
            var formData = new FormData();
            formData.append('image_search_file', file);
            
            $.ajax({
                url: imageSearchConfig.uploadUrl,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        $statusMessage.text($t('Found: ') + response.search_term);
                        setTimeout(function() {
                            window.location.href = response.redirect_url;
                        }, 1000);
                    } else {
                        $statusMessage.text($t('Error: ') + response.message);
                    }
                },
                error: function () {
                    $statusMessage.text($t('Error uploading or processing image.'));
                }
            });
        }

        /**
         * Validate file type and size
         * 
         * @param {File} file
         * @return {Boolean}
         */
        function validateFile(file) {
            var $statusMessage = $(imageSearchConfig.statusMessageSelector),
                fileExtension = file.name.split('.').pop().toLowerCase();
            
            // Check file type
            if (imageSearchConfig.allowedFileTypes.indexOf(fileExtension) === -1) {
                $statusMessage.text(
                    $t('Invalid file type. Allowed types: ') + imageSearchConfig.allowedFileTypes.join(', ')
                );
                $statusMessage.show();
                return false;
            }
            
            // Check file size (KB to bytes)
            if (file.size > imageSearchConfig.maxFileSize * 1024) {
                $statusMessage.text(
                    $t('The file is too large. Maximum allowed size is ') + imageSearchConfig.maxFileSize + ' KB.'
                );
                $statusMessage.show();
                return false;
            }
            
            return true;
        }

        /**
         * Show image preview
         * 
         * @param {File} file
         */
        function showImagePreview(file) {
            var $preview = $(imageSearchConfig.previewSelector),
                reader = new FileReader();
            
            reader.onload = function (e) {
                $preview.html('<img src="' + e.target.result + '" alt="Preview" />');
                $preview.show();
            };
            
            reader.readAsDataURL(file);
        }

        // Initialize image search when DOM is ready
        $(function () {
            initImageSearch();
        });
    };
});
