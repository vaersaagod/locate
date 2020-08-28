<?php
/**
 * Locate plugin for Craft CMS 3.x
 *
 * Harness the power of the Google Autocomplete API inside Craft. Adds an autocomplete search box to Craft entries.
 *
 * @link      https://www.vaersaagod.no/
 * @copyright Copyright (c) 2018 Isaac Gray
 */

namespace vaersaagod\locate\fields;

use vaersaagod\locate\Locate;
use vaersaagod\locate\assetbundles\locatefield\LocateFieldAsset;
use vaersaagod\locate\models\LocateModel;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\elements\db\ElementQueryInterface;
use craft\helpers\Db;
use craft\helpers\Html;
use craft\helpers\Json;
use craft\helpers\StringHelper;
use craft\helpers\UrlHelper;

use yii\db\Schema;

/**
 * LocateField Field
 *
 * @author    Isaac Gray
 * @package   Locate
 * @since     2.0.0
 */
class LocateField extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $optionsObject = '';

    // Static Methods
    // =========================================================================

    /**
     * @return string The display name of this class.
     */
    public static function displayName(): string
    {
        return Craft::t('locate', 'Location');
    }

    // Public Methods
    // =========================================================================

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            ['optionsObject', 'string'],
            ['optionsObject', 'default', 'value' => ''],
        ]);
        return $rules;
    }

    /**
     * @return string The column type. [[\yii\db\QueryBuilder::getColumnType()]] will be called
     * to convert the give column type to the physical one. For example, `string` will be converted
     * as `varchar(255)` and `string(100)` becomes `varchar(100)`. `not null` will automatically be
     * appended as well.
     * @see \yii\db\QueryBuilder::getColumnType()
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * @param mixed $value The raw field value
     * @param ElementInterface|null $element The element the field is associated with, if there is one
     *
     * @return mixed The prepared field value
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        if ($value instanceof LocateModel) {
            return $value;
        }

        $attr = [];

        if (is_string($value)) {
            $attr += array_filter(json_decode($value, true) ?: [],
                function ($key) {
                    return in_array($key, ['lat', 'lng', 'location', 'placeid', 'locationData']);
                }, ARRAY_FILTER_USE_KEY);
        } else if (is_array($value) && isset($value['isCpFormData'])) {

            if (!array_key_exists('location', $value) || $value['location']  === '') {
                return new LocateModel();
            }
            $attr += [
                'lat' => $value['lat'] ?? null,
                'lng' => $value['lng'] ?? null,
                'location' => $value['location'],
                'locationData' => $this->formatLocationData(json_decode($value['locationData'], true)),
                'placeid' => $value['placeid'],
            ];
        } else if (is_array($value)) {
            $attr = $value;
        }

        return new LocateModel($attr);
    }



    private function formatLocationData($data)
    {
//        Craft::dd($data);
        $returnData = $data;
        $components = [];
        $addressComponents = $data['address_components'] ?? [];

        foreach ($addressComponents as $component) {
            $type = $component['types'][0];

            if (!$type) continue;

            $components[$type] = $component['long_name'];
            $components[$type . "_short"] = $component['short_name'];
        }

        $returnData['components'] = $components;

        return $returnData;

    }

    /**
     * @param ElementQueryInterface $query The element query
     * @param mixed $value The value that was set on this field’s corresponding [[ElementCriteriaModel]] param,
     *                                     if any.
     *
     * @return null|false `false` in the event that the method is sure that no elements are going to be found.
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
//        Craft::dd(StringHelper::encodeMb4(StringHelper::encodeMb4(json_encode($value))));
        return parent::serializeValue(StringHelper::encodeMb4(StringHelper::encodeMb4(json_encode($value))), $element);
    }

    /**
     * @return string|null
     */
    public function getSettingsHtml()
    {

        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'locate/_components/fields/LocateField_settings',
            [
                'field' => $this,
                'settings' => $this->getSettings(),
                'pluginSettingsUrl' => UrlHelper::cpUrl('settings/plugins/' . Locate::$plugin->getHandle())
            ]
        );
    }

    /**
     * @param mixed $value The field’s value. This will either be the [[normalizeValue() normalized value]],
     *                                               raw POST data (i.e. if there was a validation error), or null
     * @param ElementInterface|null $element The element the field is associated with, if there is one
     *
     * @return string The input HTML.
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        if (!$value) {
            $value = new LocateModel();
        }

        // Register our asset bundle
        Craft::$app->getView()->registerAssetBundle(LocateFieldAsset::class);

        $settings = Locate::getInstance()->getSettings();
        $projectConfigSettings = Craft::$app->getProjectConfig()->get('plugins.locate.settings') ?? [];

        $apiKey = Craft::parseEnv($settings->googleMapsApiKey ?: $projectConfigSettings['googleMapsApiKey'] ?? null);

        $id = Html::id($this->handle);

        if ($apiKey) {

            $apiUrl = 'https://maps.googleapis.com/maps/api/js?key=' . $apiKey . '&libraries=places';

            $apiLanguage = Craft::parseEnv($settings->apiLanguage ?: $projectConfigSettings['apiLanguage'] ?? null);
            if ($apiLanguage) {
                $apiUrl .= "&language={$apiLanguage}";
            }

            $apiRegion = Craft::parseEnv($settings->apiRegion ?: $projectConfigSettings['apiRegion'] ?? null);
            if ($apiRegion) {
                $apiUrl .= "&region={$apiRegion}";
            }

            if (($this->getSettings()['optionsObject'] ?? null)) {
                $jsonOptions = $this->getSettings()['optionsObject'];
            } else {
                $jsonOptions = Craft::parseEnv($settings->autocompleteOptions ?: $projectConfigSettings['autocompleteOptions'] ?? null);
            }

            $namespacedId = Craft::$app->getView()->namespaceInputId($id);

            $jsonVars = [
                'id' => $id,
                'name' => $this->handle,
                'optionsObject' => $jsonOptions,
                'namespace' => $namespacedId,
            ];

            $jsonVars = Json::encode($jsonVars);

            Craft::$app->getView()->registerJsFile($apiUrl);
            Craft::$app->getView()->registerJs("$('#{$namespacedId}-field').LocateField({$jsonVars});");
        }

        // Variables to pass down to our field JavaScript to let it namespace properly

        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'locate/_components/fields/LocateField_input',
            [
                'id' => $id,
                'name' => $this->handle,
                'field' => $this,
                'value' => $value,
                'apiKey' => $apiKey,
            ]
        );
    }
}
