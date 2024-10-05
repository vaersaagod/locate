/**
 * Locate plugin for Craft CMS
 *
 * LocateField Field JS
 *
 * @author    Værsågod
 * @copyright Copyright (c) 2024 Værsågod AS
 * @link      https://www.vaersaagod.no/
 * @package   Locate
 * @since     2.0.0
 */

;(function ($, window, document, undefined) {

    var pluginName = 'LocateField',
        defaults = {};

    // Plugin constructor
    function Plugin(element, options) {
        this.element = element;

        this.options = $.extend({}, defaults, options);

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Plugin.prototype = {

        init: function (id, time) {

            var _this = this;

            time = time || new Date().getTime();

            // Wait for Google
            if (!window.google) {
                if ((new Date().getTime() - time) < 5000) {
                    setTimeout(function () {
                        _this.init(id, time);
                    }, 100);
                }
                return;
            }

            $(function () {

                var fields = {
                    lat: document.getElementById(_this.options.namespace + '-lat'),
                    lng: document.getElementById(_this.options.namespace + '-lng'),
                    placeid: document.getElementById(_this.options.namespace + '-placeid'),
                    locationData: document.getElementById(_this.options.namespace + '-locationData')
                };

                var input = document.getElementById(_this.options.namespace + '-location');
                var options = {};

                if (_this.options.optionsObject) {
                    options = JSON.parse('{' + _this.options.optionsObject + '}') || {};
                }

                if (!options.fields || !options.fields.length) {
                    options.fields = ['place_id', 'name', 'geometry', 'address_component', 'address_components'];
                }

                var autocomplete = new google.maps.places.Autocomplete(input, options);
                autocomplete.addListener('place_changed', function () {
                    var place = autocomplete.getPlace();
                    if (typeof place === 'object') {
                        if (place.hasOwnProperty('geometry')) {
                            fields.lat.value = place.geometry.location.lat();
                            fields.lng.value = place.geometry.location.lng();
                        } else {
                            fields.lat.value = '';
                            fields.lng.value = '';
                        }

                        if (place.hasOwnProperty('place_id')) {
                            fields.placeid.value = place.place_id;
                        } else {
                            fields.placeid.value = '';
                        }

                        fields.locationData.value = JSON.stringify(place);

                    } else {
                        fields.lat.value = '';
                        fields.lng.value = '';
                        fields.placeid.value = '';
                        fields.locationData.value = '';
                    }
                });

                var value = input.value;

                input.addEventListener('change', function (e) {
                    if (!input.value) {
                        fields.lat.value = '';
                        fields.lng.value = '';
                        fields.placeid.value = '';
                        fields.locationData.value = '';
                    }
                    value = input.value;
                });

                input.addEventListener('keyup', function (e) {
                    if (input.value === value) {
                        return;
                    }
                    fields.lat.value = '';
                    fields.lng.value = '';
                    fields.placeid.value = '';
                    fields.locationData.value = '';
                    value = input.value;
                });

                document.addEventListener('keydown', function (e) {
                    var key = e.keyCode || e.which;

                    if (key === 13 && document.activeElement === input) {
                        e.preventDefault();
                        $(autocomplete)
                            .trigger('place_changed');
                    }
                });
            });
        }
    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName,
                    new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);
