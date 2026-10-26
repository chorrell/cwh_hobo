# Changelog

All notable changes to cwh_hobo will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.1](https://github.com/chorrell/cwh_hobo/compare/v2.0.0...v2.0.1) (2026-10-26)


### Bug Fixes

* register the cwh_hobo tag so it works on Textpattern 4.9 ([#57](https://github.com/chorrell/cwh_hobo/issues/57)) ([53506f3](https://github.com/chorrell/cwh_hobo/commit/53506f3f068476843fad85a954eb831a6f96d7a5))

## [2.0.0](https://github.com/chorrell/cwh_hobo/compare/v1.0.0...v2.0.0) (2026-10-25)

### ⚠ BREAKING CHANGES

* The stated minimum PHP version is now 8.4 (was 8.0), matching the versions CI tests (8.4 and 8.5) and the versions [Textpattern recommends](https://textpattern.com/about/system-requirements). PHP 8.0 and 8.1 are end of life. No code changed, so the plugin may still run on older PHP 8 releases, but they're no longer tested or supported ([#54](https://github.com/chorrell/cwh_hobo/pull/54))

### Bug Fixes

* The test suite now actually runs. `test_plugin.php` defines `txpinterface` before loading the plugin; previously the plugin loaded `zem_tpl.php`, which compiled the plugin and exited with status 0 before any test ran, so CI passed without testing anything ([#53](https://github.com/chorrell/cwh_hobo/pull/53))

### Security

* CI and release builds always install the latest PHP patch release (e.g. 8.4.27 and 8.5.12, which fix the issues in advisory AV26-1070) ([#53](https://github.com/chorrell/cwh_hobo/pull/53))

### Continuous Integration

* Releases are automated with release-please, and the built `cwh_hobo.txt` and zip are attached to each GitHub release ([#55](https://github.com/chorrell/cwh_hobo/pull/55))
* Pre-commit hooks (actionlint, gitleaks, markdownlint-cli2, zizmor, hygiene checks, `php -l`), with the PHP syntax check running in Docker ([#42](https://github.com/chorrell/cwh_hobo/pull/42)) ([#53](https://github.com/chorrell/cwh_hobo/pull/53))
* zizmor and markdownlint run in CI via the shared `chorrell/workflows` reusable workflows ([#50](https://github.com/chorrell/cwh_hobo/pull/50)) ([#51](https://github.com/chorrell/cwh_hobo/pull/51))
* Workflow permissions are set explicitly and GitHub Actions are pinned by SHA ([#34](https://github.com/chorrell/cwh_hobo/pull/34)) ([#42](https://github.com/chorrell/cwh_hobo/pull/42))
* Dependabot updates GitHub Actions and pre-commit hooks monthly, in groups ([#41](https://github.com/chorrell/cwh_hobo/pull/41))

### Documentation

* Add AGENTS.md and a CLAUDE.md that imports it ([#28](https://github.com/chorrell/cwh_hobo/pull/28)) ([#49](https://github.com/chorrell/cwh_hobo/pull/49))
* Add a CI status badge and fix links and formatting in the README ([#31](https://github.com/chorrell/cwh_hobo/pull/31)) ([#52](https://github.com/chorrell/cwh_hobo/pull/52))

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
