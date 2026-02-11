# cwh_hobo

A [Textpattern CMS](https://textpattern.com) plugin that displays a random hobo
name from John Hodgeman's list of 700 hobo names taken from his book
[The Areas of my Expertise](https://www.amazon.ca/Areas-Expertise-Instructive-Annotation-2006-09-05/dp/B0163DW6CW/)
([more information on Wikipedia](https://en.wikipedia.org/wiki/The_Areas_of_My_Expertise)).

## Requirements

- Textpattern CMS 4.7+ (tested with 4.8.x and 4.9.0)
- PHP 8.0+ (tested with PHP 8.4 and 8.5)

## Installation

### Method 1: Standard Installation (Recommended)

1. Generate the plugin installer:

   ```bash
   php cwh_hobo.php > cwh_hobo-1.0.0.txt
   ```

2. In Textpattern admin:
   - Go to **Admin → Plugins**
   - Click "Install plugin"
   - Paste the contents of `cwh_hobo-1.0.0.txt`
   - Click "Upload"
   - Review and approve the code
   - Activate the plugin by setting its status to "Yes"

### Method 2: Plugin Cache Directory (Development)

For development and testing:

1. In Textpattern admin, go to **Admin → Preferences → Admin**
2. Set "Plugin cache directory path" to an absolute path (e.g.,
   `/var/www/txp-plugins`)
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

## What's New in v1.0.0

This version has been modernized with:

- **PHP 8.0+ compatibility** - Works with current PHP versions
- **Modern code style** - Clean, documented, maintainable
- **Better performance** - Lazy loading of hobo names
- **Same functionality** - Tag usage unchanged, fully backward compatible

### Upgrading from v0.2.1

No changes to your templates needed! Simply uninstall the old version and
install the new one. The `<txp:cwh_hobo />` tag works exactly the same.

**Note:** This version requires PHP 8.0+. If you need to run on older PHP
versions, please use v0.2.1 from the `main` branch.

## Development

### File Structure

- `cwh_hobo.php` - Main plugin file (PHP 8.0+)
- `manifest.json` - Plugin metadata for package managers
- `test_plugin.php` - Automated test script
- `zem_tpl.php` - Plugin compiler (updated to latest version)

### Building for Distribution

Generate the plugin installer text:

```bash
php cwh_hobo.php > cwh_hobo-1.0.0.txt
```

This creates a base64-encoded plugin file that can be distributed and installed
via the Textpattern admin interface.

## License

Released under the [GPL-2.0 license](LICENSE).

## Author

Christopher Horrell

- Website: [https://horrell.ca/](https://horrell.ca/)
- Textpattern Forum: Ask questions in the
  [Plugin Support forum](https://forum.textpattern.com/viewforum.php?id=4)

## Credits

Hobo names taken from John Hodgman's book
"[The Areas of my Expertise](https://www.amazon.ca/Areas-Expertise-Instructive-Annotation-2006-09-05/dp/B0163DW6CW/)".
