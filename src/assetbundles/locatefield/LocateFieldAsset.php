<?php
/**
 * Locate plugin for Craft CMS 5.x
 *
 * Harness the power of the Google Autocomplete API inside Craft. Adds an autocomplete search box to Craft entries.
 *
 * @link      https://www.vaersaagod.no/
 * @copyright Copyright (c) 2024 Værsågod AS
 */

namespace vaersaagod\locate\assetbundles\locatefield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * LocateFieldFieldAsset AssetBundle
 *
 * @author    Værsågod
 * @package   Locate
 * @since     2.0.0
 */
class LocateFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = "@vaersaagod/locate/assetbundles/locatefield/dist";

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'js/LocateField.js',
        ];

        $this->css = [
            'css/LocateField.css',
        ];

        parent::init();
    }
}
