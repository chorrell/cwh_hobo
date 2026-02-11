# Changelog

All notable changes to cwh_hobo will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
