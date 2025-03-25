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
        var voiceSearchConfig = {
            searchUrl: config.searchUrl,
            maxRecordingTime: config.maxRecordingTime,
            language: config.language,
            buttonSelector: '#voice-search-button',
            statusMessageSelector: '#voice-search-status',
            resultTextSelector: '#voice-search-text'
        };

        var recognition = null,
            isRecording = false,
            recordingTimeout = null;

        /**
         * Initialize voice search functionality
         */
        function initVoiceSearch() {
            var $button = $(voiceSearchConfig.buttonSelector),
                $statusMessage = $(voiceSearchConfig.statusMessageSelector),
                $resultText = $(voiceSearchConfig.resultTextSelector);

            // Check if browser supports speech recognition
            if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
                $button.prop('disabled', true);
                $statusMessage.text($t('Voice search is not supported in your browser.'));
                $statusMessage.show();
                return;
            }

            // Initialize speech recognition
            recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
            recognition.continuous = false;
            recognition.interimResults = true;
            recognition.lang = voiceSearchConfig.language;

            // Handle recording start
            recognition.onstart = function () {
                isRecording = true;
                $button.addClass('recording');
                $statusMessage.text($t('Listening...'));
                $statusMessage.show();
                
                // Set timeout for recording
                recordingTimeout = setTimeout(function () {
                    if (isRecording) {
                        stopRecording();
                    }
                }, voiceSearchConfig.maxRecordingTime * 1000);
            };

            // Handle recording end
            recognition.onend = function () {
                if (isRecording) {
                    stopRecording();
                }
            };

            // Handle recording error
            recognition.onerror = function (event) {
                stopRecording();
                $statusMessage.text($t('Error: ') + (event.error || 'unknown'));
            };

            // Handle speech results
            recognition.onresult = function (event) {
                var interim_transcript = '';
                var final_transcript = '';
                
                for (var i = event.resultIndex; i < event.results.length; ++i) {
                    if (event.results[i].isFinal) {
                        final_transcript += event.results[i][0].transcript;
                    } else {
                        interim_transcript += event.results[i][0].transcript;
                    }
                }
                
                if (final_transcript !== '') {
                    $resultText.val(final_transcript);
                } else {
                    $resultText.val(interim_transcript);
                }
            };

            // Handle button click
            $button.on('click', function () {
                if (isRecording) {
                    stopRecording();
                } else {
                    startRecording();
                }
            });
        }

        /**
         * Start voice recording
         */
        function startRecording() {
            var $resultText = $(voiceSearchConfig.resultTextSelector);
            
            $resultText.val('');
            try {
                recognition.start();
            } catch (e) {
                console.error('Error starting speech recognition:', e);
            }
        }

        /**
         * Stop voice recording and process results
         */
        function stopRecording() {
            var $button = $(voiceSearchConfig.buttonSelector),
                $statusMessage = $(voiceSearchConfig.statusMessageSelector),
                $resultText = $(voiceSearchConfig.resultTextSelector);
            
            isRecording = false;
            $button.removeClass('recording');
            
            if (recordingTimeout) {
                clearTimeout(recordingTimeout);
                recordingTimeout = null;
            }
            
            try {
                recognition.stop();
            } catch (e) {
                console.error('Error stopping speech recognition:', e);
            }
            
            var searchText = $resultText.val().trim();
            
            if (searchText === '') {
                $statusMessage.text($t('No speech detected. Please try again.'));
                return;
            }
            
            $statusMessage.text($t('Processing...'));
            
            // Send search text to server
            $.ajax({
                url: voiceSearchConfig.searchUrl,
                type: 'POST',
                data: {
                    voice_search_text: searchText
                },
                success: function (response) {
                    if (response.success) {
                        $statusMessage.text($t('Searching for: ') + response.search_term);
                        setTimeout(function() {
                            window.location.href = response.redirect_url;
                        }, 1000);
                    } else {
                        $statusMessage.text($t('Error: ') + response.message);
                    }
                },
                error: function () {
                    $statusMessage.text($t('Error processing search request.'));
                }
            });
        }

        // Initialize voice search when DOM is ready
        $(function () {
            initVoiceSearch();
        });
    };
});
