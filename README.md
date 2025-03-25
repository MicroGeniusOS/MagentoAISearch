# Magento AISearch Extension (Commerce Edition)

## Overview
The Magento AISearch extension enables customers to search for products using image uploads or voice recognition. This extension leverages Google Cloud Vision API for image recognition and the Web Speech API for voice-to-text conversion.

> **IMPORTANT**: This extension is designed exclusively for **Magento Commerce**. It will not function correctly on Magento Open Source (Community Edition) as it depends on Commerce-specific features.

## Features
- Image-based search for products using Google Cloud Vision API
- Voice recognition search using Web Speech API
- Admin configuration panel for enabling/disabling features
- Standard Magento search results display
- Integration with Magento's existing product catalog
- Full compatibility with Magento Commerce features

## Requirements
- Magento Commerce 2.4.x and 2.5.x
- PHP 8.3+
- Google Cloud Vision API key for image search functionality

## Magento Commerce Integration Points
### B2B Catalog Permissions
- Respects B2B shared catalog permissions and company account structures
- Filters search results based on customer's company and shared catalog assignments
- Maintains proper catalog visibility rules for B2B customers

### Customer Segmentation
- Integrates with Magento Commerce customer segmentation system
- Tailors search results based on customer segments for personalized shopping
- Supports dynamic customer segment-based result prioritization

### Multi-Source Inventory (MSI)
- Integrates with Magento Commerce Multi-Source Inventory
- Provides accurate stock information in search results
- Supports inventory-aware search result prioritization

### Content Staging
- Respects scheduled content updates when processing search results
- Works with Magento Commerce Content Staging workflows
- Supports preview mode for scheduled search configurations

### Multi-Store Support
- Works across multiple websites and store views
- Supports store-specific configurations and search settings
- Provides locale-specific optimizations for multi-language stores

## Installation

### Manual Installation
1. Extract the module contents to `app/code/Magento/AISearch`
2. Run the following commands from the Magento root directory:
   ```
   bin/magento module:enable Magento_AISearch
   bin/magento setup:upgrade
   bin/magento setup:di:compile
   bin/magento setup:static-content:deploy -f
   bin/magento cache:flush
   ```

3. Configure the module in Magento admin:
   - Navigate to Stores > Configuration > Catalog > AISearch
   - Enter your Google Cloud Vision API key for image search
   - Enable/disable desired features
   - Configure Commerce-specific integration options

### Commerce Edition Verification
After installation, verify that the following Commerce-specific features are properly configured:

1. **B2B Integration**:
   - Navigate to B2B Features > Shared Catalogs
   - Ensure permissions are properly set for your product catalog

2. **Customer Segmentation**:
   - Navigate to Customers > Segments
   - Verify that your customer segments are configured correctly

3. **Multi-Source Inventory**:
   - Check that your inventory sources are properly configured
   - Verify that the module correctly considers inventory when processing search results

4. **Content Staging**:
   - Create a scheduled update for a product
   - Test the search functionality with upcoming changes

5. **Multi-Store Configuration**:
   - Navigate to AISearch configuration for each store view
   - Set store-specific configurations as needed

## Commerce Edition Licensing
This extension is part of Magento Commerce licensing and is not available for Magento Open Source. The use of this extension is governed by your Magento license agreement.

## Troubleshooting Commerce Integration

### B2B Catalog Integration Issues
- Verify that the B2B module is enabled in your Commerce installation
- Check that shared catalogs are properly configured
- Ensure customer company accounts are correctly assigned to shared catalogs
- Review Commerce ACL permissions for catalog access

### Customer Segmentation Issues
- Confirm that customer segments are properly defined
- Check segment conditions for proper syntax
- Verify segment assignment logic in the Commerce system
- Test customer segment assignments with test accounts

### Multi-Source Inventory Troubleshooting
- Ensure all inventory sources are properly configured
- Verify inventory source priority settings
- Check that products are assigned to the correct sources
- Test with stock reservation updates

### Content Staging Concerns
- Validate scheduled updates in the Commerce administration
- Check date/time settings for scheduled updates
- Verify preview mode functionality for upcoming content
- Test search results with pending content updates

### Multi-Store Configuration Problems
- Clear store configuration cache after making changes
- Verify store-specific settings are overriding default values
- Check website-level vs. store-view level configuration inheritance
- Test with multiple store views to confirm proper behavior

## Advanced Commerce Customization
The module is designed to be extended with additional Commerce-specific customizations:

### Adding Store-Specific Search Rules
```php
// Example of extending store-specific search rules in a custom module
public function applyStoreSpecificRules($searchParams, $storeId)
{
    // Get store-specific configuration
    $storeConfig = $this->commerceIntegration->getStoreConfig('custom_path', $storeId);
    
    // Apply custom rules based on store configuration
    $searchParams['custom_rule'] = $storeConfig;
    
    return $searchParams;
}
```

### Extending B2B Integration
```php
// Example of adding custom B2B rules to search results
public function applyCustomB2BRules($searchResults, $customerGroupId)
{
    // Get B2B permissions for the customer group
    $permissions = $this->commerceIntegration->getB2BPermissions($customerGroupId);
    
    // Filter results based on permissions
    return array_filter($searchResults, function($result) use ($permissions) {
        return $this->isAllowed($result, $permissions);
    });
}
```

## Support and Updates
For Commerce-specific support with this extension, please contact Magento Commerce Support through your usual support channels. Include your Commerce license information when requesting assistance.
