# Locate Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## 3.1.0 - 2023-02-15
### Added
- Location fields are now previewable in element indexes  

## 3.0.2 - 2023-02-07

### Fixed
- Fixed a JS error `Loading the Google Maps JavaScript API without a callback is not supported`  

## 3.0.1 - 2022-10-11

### Fixed
- Fixed an issue where an error would be thrown if the plugin hadn't been configured yet, and you added the field to an entry (Typed property must not be accessed before initialization).

## 3.0.0 - 2022-04-23

### Added
- Adds Craft 4.0 compatibility

### Fixed
- Fixes an issue where the Locate field's JS could initialise before Google's JS API had loaded  

## 2.3.2 - 2021-01-04

### Fixed
- Fixes an issue where Locate would not migrate `swixpop/locate` fields on install  

## 2.3.1 - 2020-09-23

### Changed
- Added additional properties to default fields (`name`, `place_id`)

## 2.3.0 - 2020-08-29  

### Fixed
- Fixes Craft 3.5 compatibility issues  

### Added  
- Added `swixpop/locate` -> `vaersaagod/locate` migration

### Improved  
- Add setting fields in Locate's settings page for API region and language settings  
- Settings fields in the CP now display a warning if they are being overridden using a config file
- Settings fields in the CP now shows the stored database/Project Config values, not the overridden values from config  
- Settings fields in the CP now accept environment variables  
- Locate now sets the `field` Places API options object to `geometry` and `address_components` by default   
- Added setting keys to example `config.php` file  

### Changed  
- Locate now requires Craft 3.5.7 or later  

## 2.2.0 - 2020-02-10

### Added
- Adds `apiLanguage` and `apiRegion` settings  

### Changed
- Changes plugin vendor name and namespace from "swixpop" to "vaersaagod"

## 2.1.1 - 2019-02-27

### Fixed
- Bug where place data with emojis would prevent saving of field
- Bug where hitting enter to select a place would instead trigger a save on the entry before place was selected

## 2.1.0 - 2018-09-14

### Fixed
- Clearing a location field now *actually* removes all field data from the database :)

### Added
- The field now returns `locationData`. This is the full API response from Google.
- Returns `locationData.components` which is a keyed array of all the Google `address_components` data.

## 2.0.2 - 2018-08-09
### Fixed
- Scoped all CSS to plugin

## 2.0.1 - 2018-04-25

### Added
- Fixed incorrect repo urls

## 2.0.0 - 2018-04-09

### Added
- Initial Craft3 release

## 0.4.9 - 2016-02-18

### Added
- Initial release
