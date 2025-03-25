<?php
/**
 * AISearch Magento Extension
 *
 * @category  Magento
 * @package   Magento_AISearch
 * @author    Magento
 * @copyright Copyright (c) Magento (https://www.magento.com)
 */

// Display PHP version to confirm 8.3 compatibility
$phpVersion = phpversion();
$requiredVersion = '8.3.0';

$isCompatible = version_compare($phpVersion, $requiredVersion, '>=');
$compatibilityStatus = $isCompatible ? 'Compatible' : 'Not Compatible';
$statusColor = $isCompatible ? 'green' : 'red';

// Get module information
$moduleInfo = [
    'Name' => 'Magento AISearch',
    'Version' => '1.0.0',
    'PHP Compatibility' => "PHP $requiredVersion+",
    'Platform Compatibility' => 'Magento Commerce 2.4.x and 2.5.x',
    'Features' => [
        'Image-based Search' => 'Uses Google Cloud Vision API to identify objects in uploaded images',
        'Voice-based Search' => 'Uses Web Speech API for voice recognition and conversion to search terms'
    ],
    'Commerce Integration' => [
        'B2B Catalog Permissions' => [
            'description' => 'Respects B2B shared catalog permissions and company account structures when returning search results',
            'details' => [
                'Shared Catalog Integration' => 'Filters products based on customer\'s company and shared catalog assignments',
                'Company Structure Support' => 'Respects hierarchical permissions within company structures',
                'Role-Based Permissions' => 'Applies role-specific catalog visibility rules',
                'Requisition List Enhancement' => 'Improves search experience for B2B requisition lists'
            ]
        ],
        'Customer Segmentation' => [
            'description' => 'Tailors search results based on customer segments for personalized shopping experiences',
            'details' => [
                'Dynamic Segmentation' => 'Real-time segment application during search processing',
                'Weighted Algorithms' => 'Segment-specific result prioritization and boosting',
                'Personalized Results' => 'Customized search results based on customer behavior and segments',
                'Multiple Segment Support' => 'Handles customers belonging to multiple segments simultaneously'
            ]
        ],
        'Multi-Source Inventory' => [
            'description' => 'Integrates with MSI to provide accurate stock information in search results',
            'details' => [
                'Source-Specific Stock' => 'Displays availability from relevant inventory sources',
                'Distance Priority' => 'Optimizes results based on inventory location proximity',
                'Reservation System' => 'Respects inventory reservations in search results',
                'Channel-Specific Inventory' => 'Supports different inventory channels for Commerce'
            ]
        ],
        'Content Staging' => [
            'description' => 'Respects scheduled content updates when processing and displaying search results',
            'details' => [
                'Preview Mode Support' => 'Shows search results as they will appear at future dates',
                'Scheduled Updates' => 'Automatically incorporates future catalog changes',
                'Campaign Integration' => 'Coordinates with marketing campaigns and promotions',
                'Version Control' => 'Maintains consistent search results across staged content versions'
            ]
        ],
        'Multi-Store Support' => [
            'description' => 'Works across multiple websites and store views with locale-specific optimizations',
            'details' => [
                'Store-Specific Configuration' => 'Independent settings for each store view',
                'Locale Optimization' => 'Language-specific search processing for international stores',
                'Website-Specific Catalog' => 'Respects catalog differences between websites',
                'Custom Store View Features' => 'Supports unique search experiences per store view'
            ]
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magento AISearch Module</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2273c3;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        h2 {
            color: #1a568c;
            margin-top: 20px;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            color: white;
            font-weight: bold;
        }
        .features {
            margin-top: 20px;
        }
        .feature {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 3px;
            border-left: 4px solid #2273c3;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .feature h3 {
            margin-top: 0;
            color: #2273c3;
        }
        .php-info {
            margin-top: 30px;
            background-color: #f3f3f3;
            padding: 15px;
            border-radius: 3px;
        }
        .feature-details {
            margin-top: 10px;
            background-color: #f8f8f8;
            padding: 10px 15px;
            border-radius: 3px;
            border-left: 3px solid #4a90e2;
        }
        .feature-details h4 {
            margin-top: 0;
            color: #4a90e2;
            font-size: 16px;
        }
        .feature-details ul {
            margin-bottom: 0;
            padding-left: 20px;
        }
        .feature-details li {
            margin-bottom: 5px;
        }
        .commerce-badge {
            display: inline-block;
            background-color: #ff7200;
            color: white;
            font-size: 12px;
            padding: 3px 8px;
            border-radius: 3px;
            margin-left: 10px;
            vertical-align: middle;
        }
        .commerce-notice {
            margin-top: 25px;
            background-color: #fff8f2;
            border: 1px solid #ffcca9;
            border-left: 5px solid #ff7200;
            padding: 15px 20px;
            border-radius: 4px;
        }
        .commerce-notice h3 {
            color: #ff7200;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .commerce-icon {
            display: inline-block;
            margin-right: 5px;
        }
        .disclaimer {
            font-style: italic;
            color: #d04900;
            margin-top: 15px;
        }
        .commerce-notice ul {
            padding-left: 20px;
        }
        .commerce-notice li {
            margin-bottom: 7px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Magento AISearch Module <span class="commerce-badge">Commerce Edition</span></h1>
        
        <h2>Module Status</h2>
        <p>
            <strong>Running on:</strong> PHP <?php echo $phpVersion; ?><br>
            <strong>Required:</strong> PHP <?php echo $requiredVersion; ?>+<br>
            <strong>Status:</strong> <span class="status" style="background-color: <?php echo $statusColor; ?>;">
                <?php echo $compatibilityStatus; ?>
            </span>
        </p>
        
        <h2>Module Information</h2>
        <ul>
            <?php foreach ($moduleInfo as $key => $value): ?>
                <?php if (!is_array($value)): ?>
                    <li><strong><?php echo $key; ?>:</strong> <?php echo $value; ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        
        <h2>Features</h2>
        <div class="features">
            <?php foreach ($moduleInfo['Features'] as $featureName => $featureDesc): ?>
                <div class="feature">
                    <h3><?php echo $featureName; ?></h3>
                    <p><?php echo $featureDesc; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        
        <h2>Magento Commerce Integration</h2>
        <div class="features">
            <?php foreach ($moduleInfo['Commerce Integration'] as $featureName => $featureInfo): ?>
                <div class="feature">
                    <h3><?php echo $featureName; ?></h3>
                    <p><?php echo $featureInfo['description']; ?></p>
                    
                    <?php if (isset($featureInfo['details']) && !empty($featureInfo['details'])): ?>
                        <div class="feature-details">
                            <h4>Key Capabilities:</h4>
                            <ul>
                                <?php foreach ($featureInfo['details'] as $detailName => $detailDesc): ?>
                                    <li><strong><?php echo $detailName; ?>:</strong> <?php echo $detailDesc; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="php-info">
            <p>This module has been tested and verified to work with PHP 8.3 and above.</p>
            <p>This extension is specifically optimized for Magento Commerce 2.4.x and 2.5.x.</p>
            <p>For more information, please refer to the <a href="README.md">README.md</a> file.</p>
        </div>
        
        <div class="commerce-notice">
            <h3><span class="commerce-icon">⚠</span> Commerce Edition Notice</h3>
            <p>This extension is designed exclusively for Magento Commerce. It requires Commerce-specific features and will not function correctly on Magento Open Source (Community Edition).</p>
            <p>Key requirements:</p>
            <ul>
                <li><strong>Shared Catalogs:</strong> For B2B catalog permissions functionality</li>
                <li><strong>Customer Segmentation:</strong> For personalized search results</li>
                <li><strong>Multi-Source Inventory:</strong> For inventory-aware search</li>
                <li><strong>Content Staging:</strong> For scheduled content updates support</li>
                <li><strong>Multi-Store Configuration:</strong> For advanced store-view specific settings</li>
            </ul>
            <p class="disclaimer">Attempting to install this extension on Magento Open Source may result in errors or limited functionality.</p>
        </div>
    </div>
</body>
</html>