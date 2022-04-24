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
