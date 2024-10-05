<?php
/**
 * Locate plugin for Craft CMS 5.x
 *
 * Harness the power of the Google Autocomplete API inside Craft. Adds an autocomplete search box to Craft entries.
 *
 * @link      https://www.vaersaagod.no/
 * @copyright Copyright (c) 2024 Værsågod AS
 */

namespace vaersaagod\locate;

use vaersaagod\locate\models\Settings;
use vaersaagod\locate\fields\LocateField as LocateFieldField;

use Craft;
use craft\base\Plugin;
use craft\services\Fields;
use craft\events\RegisterComponentTypesEvent;

use yii\base\Event;

/**
 * @author    Værsågod
 * @package   Locate
 * @since     2.0.0
 *
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class Locate extends Plugin
{

    /** @inheritdoc */
    public string $schemaVersion = '3.0.0';
    
    /** @inheritdoc */
    public bool $hasCpSettings = true;

    /** @inheritdoc */
    public function init(): void
    {
        parent::init();

        // Register our fields
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = LocateFieldField::class;
            }
        );

        Craft::info(
            Craft::t(
                'locate',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    public static function icon(): string
    {
        return 'globe';
    }
    
    // Protected Methods
    // =========================================================================

    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }

    /**
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'locate/settings',
            [
                'settings' => Craft::$app->getProjectConfig()->get('plugins.locate.settings') ?? [],
            ]
        );
    }
}
