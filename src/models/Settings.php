<?php
/**
 * Locate plugin for Craft CMS 5.x
 *
 * Harness the power of the Google Autocomplete API inside Craft. Adds an autocomplete search box to Craft entries.
 *
 * @link      https://www.vaersaagod.no/
 * @copyright Copyright (c) 2024 Værsågod AS
 */

namespace vaersaagod\locate\models;

use vaersaagod\locate\Locate;

use Craft;
use craft\base\Model;

/**
 * @author    Værsågod
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
    public ?string $googleMapsApiKey = null;

    /**
     * @var string|null Autocomplete options
     */
    public ?string $autocompleteOptions = null;

    /**
     * @var string|null
     */
    public ?string $apiLanguage = null;

    /**
     * @var string|null
     */
    public ?string $apiRegion = null;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        return array_merge($rules, [
            [['googleMapsApiKey', 'autocompleteOptions', 'apiLanguage', 'apiRegion'], 'string']
        ]);
    }
}
