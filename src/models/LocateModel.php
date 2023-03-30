<?php
/**
 * Locate plugin for Craft CMS 4.x
 *
 * Harness the power of the Google Autocomplete API inside Craft. Adds an autocomplete search box to Craft entries.
 *
 * @link      https://www.vaersaagod.no/
 * @copyright Copyright (c) 2022 Værsågod AS
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
class LocateModel extends Model
{

    /** @var string */
    public string $lat = '';

    /** @var string */
    public string $lng = '';

    /** @var string */
    public string $location = '';

    /** @var string */
    public string $placeid = '';

    /** @var array|null */
    public ?array $locationData = null;

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // Account for a "location" index being set, but not "lat" and "lng", and try to parse that "location" string into a valid latitude and longitude
        // This makes it possible to paste a comma separated lat/lng string in the field, and have the field return a valid LocateModel even if Google wasn't involved.
        [
            'lat' => $lat,
            'lng' => $lng,
            'location' => $location,
        ] = $config + [
            'lat' => '',
            'lng' => '',
            'location' => '',
        ];
        if (empty($lat) && empty($lng) && !empty($location) && is_string($location)) {
            $maybeLatLng = explode(',', preg_replace('/\s+/', '', $location));
            if (count($maybeLatLng) === 2 && is_numeric($maybeLatLng[0]) && is_numeric($maybeLatLng[1])) {
                $config['lat'] = $maybeLatLng[0];
                $config['lng'] = $maybeLatLng[1];
            }
        }
        parent::__construct($config);
    }

    /** @inheritdoc */
    public function rules(): array
    {
        return [
            [['lat', 'lng', 'location', 'placeid'], 'string'],
            [['lat', 'lng', 'location', 'placeid'], 'default', 'value' => ''],
            [['locationData'], 'array'],
        ];
    }
}
