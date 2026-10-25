# Changelog

All notable changes to cwh_hobo will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Pre-commit configuration (`.pre-commit-config.yaml`) with actionlint,
  gitleaks, markdownlint-cli2, zizmor, generic hygiene hooks, and a local
  `php -l` syntax check
- Dependabot updates for pre-commit hook versions

### Changed

- The `php -l` pre-commit hook runs in the `php:8.4-cli` Docker image, so PHP
  no longer needs to be installed locally

### Fixed

- `test_plugin.php` now defines `txpinterface` before loading the plugin.
  Previously the plugin loaded `zem_tpl.php`, which compiled the plugin and
  exited with status 0 before any test ran, so CI passed without testing
  anything

### Security

- CI and release workflows set the `update: true` env var on `setup-php` so
  they always use the latest PHP patch release (e.g. the fixes in PHP 8.4.27
  and 8.5.12 from 21 Oct 2026)

## [1.0.0] - 2026-02-10

### Changed

- Modernized code for PHP 8.0+ compatibility
- Replaced `array()` syntax with modern short array syntax `[]`
- Updated plugin structure to follow current Textpattern best practices
- Changed help documentation from Textile to HTML format
- Improved code organization with separate functions for data and logic
- Enhanced performance with lazy loading of hobo names list
- Updated documentation with comprehensive README and testing guide

### Added

- `manifest.json` for modern plugin package management
- `TESTING.md` with detailed testing instructions
- `CHANGELOG.md` to track version changes
- PHPDoc comments for better code documentation
- Static caching for hobo names list (loaded only once)

### Technical Details

- **PHP Compatibility**: Now requires PHP 8.0 or higher (up from PHP 5.x)
- **PHP Testing**: Tested with PHP 8.4 and 8.5
- **Textpattern**: Tested with Textpattern 4.7+, 4.8.x, and 4.9.0
- **Breaking Changes**: Requires PHP 8.0+ (for older PHP, use v0.2.1)
- **Tag Usage**: Fully backward compatible - no template changes needed

## [0.2.1] - Previous Release

### Features

- Random hobo name generator
- Support for `wraptag` and `class` attributes
- 700 hobo names from John Hodgman's book
- PHP 5.x compatibility
- Textile-formatted help documentation
