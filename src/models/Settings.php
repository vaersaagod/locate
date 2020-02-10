<?php
/**
 * Locate plugin for Craft CMS 3.x
 *
 * Harness the power of the Google Autocomplete API inside Craft. Adds an autocomplete search box to Craft entries.
 *
 * @link      https://www.vaersaagod.no/
 * @copyright Copyright (c) 2018 Isaac Gray
 */

namespace vaersaagod\locate\models;

use vaersaagod\locate\Locate;

use Craft;
use craft\base\Model;

/**
 * @author    Isaac Gray
 * @package   Locate
 * @since     2.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string|null Google Maps API key
     */
    public $googleMapsApiKey;

    /**
     * @var string|null Autocomplete options
     */
    public $autocompleteOptions;

    /**
     * @var string|null
     */
    public $apiLanguage;

    /**
     * @var string|null
     */
    public $apiRegion;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['googleMapsApiKey', 'autocompleteOptions', 'apiLanguage', 'apiRegion'], 'string']
        ];
    }
}
