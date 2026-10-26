# cwh_hobo

[![Test](https://github.com/chorrell/cwh_hobo/actions/workflows/test.yml/badge.svg)](https://github.com/chorrell/cwh_hobo/actions/workflows/test.yml)
[![Integration](https://github.com/chorrell/cwh_hobo/actions/workflows/integration.yml/badge.svg)](https://github.com/chorrell/cwh_hobo/actions/workflows/integration.yml)

A [Textpattern CMS](https://textpattern.com) plugin that displays a random hobo
name from John Hodgeman's list of 700 hobo names taken from his book
[The Areas of my Expertise](https://en.wikipedia.org/wiki/The_Areas_of_My_Expertise).

## Requirements

- Textpattern CMS 4.9+ (tested with 4.9.0 and 4.9.1)
- PHP 8.4+ (tested with PHP 8.4 and 8.5)

Earlier Textpattern versions don't run on PHP 8.4 or later. Versions of this
plugin before 2.0.1 don't register their tag, so on Textpattern 4.9 they
output nothing and log "cwh_hobo tag does not exist".

## Installation

### Method 1: Standard Installation (Recommended)

1. Download `cwh_hobo.txt` from the
   [latest release](https://github.com/chorrell/cwh_hobo/releases/latest)

2. In Textpattern admin:
   - Go to **Admin → Plugins**
   - Click "Install plugin"
   - Paste the contents of `cwh_hobo.txt`
   - Click "Upload"
   - Review and approve the code
   - Activate the plugin by setting its status to "Yes"

### Method 2: Plugin Cache Directory (Development)

For development and testing:

1. In Textpattern admin, go to **Admin → Preferences → Admin**
2. Set "Plugin cache directory path" to an absolute path (e.g., `/var/www/txp-plugins`)
3. Copy `cwh_hobo.php` to that directory
4. The plugin will appear in **Admin → Plugins** and can be activated

## Usage

Once installed, simply place the following tag in any article, page, or form
within Textpattern:

```html
<txp:cwh_hobo />
```

This will produce a random hobo name and number:

```text
#171: Twink the Reading-Room Snoozer
```

### Attributes

The plugin accepts two optional attributes:

- **`wraptag`** - HTML tag to wrap the output (default: none)
- **`class`** - CSS class to apply to the wraptag (default: empty)

### Examples

Wrap output in a paragraph tag:

```html
<txp:cwh_hobo wraptag="p" />
```

Output:

```html
<p>#171: Twink the Reading-Room Snoozer</p>
```

Add a CSS class:

```html
<txp:cwh_hobo wraptag="p" class="hobo" />
```

Output:

```html
<p class="hobo">#171: Twink the Reading-Room Snoozer</p>
```

Use in a styled div:

```html
<txp:cwh_hobo wraptag="div" class="highlight featured" />
```

Output:

```html
<div class="highlight featured">#171: Twink the Reading-Room Snoozer</div>
```

## Testing

### Automated Tests

- **Unit tests** (`test_plugin.php`) mock Textpattern's `lAtts()` and
  `doTag()` and check the plugin's output, attributes and randomness:

  ```bash
  php test_plugin.php
  ```

- **Integration tests** (`tests/integration/`) install real Textpattern
  releases (4.9.0 and 4.9.1, pinned by checksum), install the built plugin
  with Textpattern's own plugin installer, and check the rendered tag output
  with Textpattern's debug mode on, so any tag error or PHP warning fails the
  test. CI runs them on PHP 8.4 and 8.5 against MariaDB. To run them locally
  you need PHP with `mysqli`, curl, and an empty MySQL/MariaDB database:

  ```bash
  php cwh_hobo.php > cwh_hobo.txt
  DB_HOST=127.0.0.1 DB_NAME=txp DB_USER=txp DB_PASSWORD=... \
    tests/integration/run.sh 4.9.1 cwh_hobo.txt
  ```

### Plugin Cache Method (Recommended for Development)

1. In Textpattern admin, go to **Admin → Preferences → Admin**
2. Set "Plugin cache directory path" to an absolute path (e.g., `/path/to/plugins`)
3. Copy `cwh_hobo.php` to that directory
4. The plugin will appear in **Admin → Plugins** and can be activated
5. Changes to the file are reflected immediately without reinstalling

### Quick Test

1. Add `<txp:cwh_hobo />` to a page template
2. View the page in your browser
3. Refresh to see different random hobo names

### Test Examples

```html
<!-- Basic -->
<txp:cwh_hobo />

<!-- Multiple instances to test randomness -->
<ul>
  <txp:cwh_hobo wraptag="li" />
  <txp:cwh_hobo wraptag="li" />
  <txp:cwh_hobo wraptag="li" />
</ul>
```

## Upgrading

See [CHANGELOG.md](CHANGELOG.md) for what changed in each release. The
`<txp:cwh_hobo />` tag and its attributes haven't changed, so templates don't
need updating: install the new version over the old one.

## Development

### File Structure

- `cwh_hobo.php` - Main plugin file (PHP 8.4+)
- `manifest.json` - Plugin metadata for package managers
- `test_plugin.php` - Unit tests
- `tests/integration/` - Integration tests against real Textpattern releases
- `zem_tpl.php` - Plugin compiler (updated to latest version)

### Building for Distribution

Generate the plugin installer text:

```bash
php cwh_hobo.php > cwh_hobo.txt
```

This creates a base64-encoded plugin file that can be distributed and installed
via the Textpattern admin interface.

### Releases

Releases are automated with
[release-please](https://github.com/googleapis/release-please). PR titles use
[conventional commits](https://www.conventionalcommits.org/) (`feat:`, `fix:`,
`docs:`, etc.; add `!` for breaking changes). After a releasable merge,
release-please opens a release PR that bumps the version and updates
`CHANGELOG.md`. Merging it tags the release, and the workflow attaches
`cwh_hobo.txt` and a zip to the GitHub release.

### Pre-commit Hooks

This repo uses [pre-commit](https://pre-commit.com/) to catch issues (syntax
errors, secrets, markdown lint failures, etc.) before they're committed. Some
hooks (markdownlint, the PHP syntax check) run in Docker, so Docker must be
running, but PHP doesn't need to be installed locally:

```bash
pip install pre-commit   # or: brew install pre-commit
pre-commit install
pre-commit run --all-files
```

## License

Released under the [GPL-2.0 license](LICENSE).

## Author

Christopher Horrell

- Website: [https://horrell.ca/](https://horrell.ca/)
- Textpattern Forum: Ask questions in the
  [Plugin Support forum](https://forum.textpattern.com/viewforum.php?id=4)

## Credits

Hobo names taken from John Hodgman's book
"[The Areas of my Expertise](https://en.wikipedia.org/wiki/The_Areas_of_My_Expertise)".
