# AGENTS.md - AI Agent Guide for cwh_hobo

This guide helps AI agents understand how to work with the cwh_hobo
Textpattern plugin project.

## Project Overview

**cwh_hobo** is a Textpattern CMS plugin that displays random hobo names
from John Hodgman's book "The Areas of my Expertise". It's a simple,
self-contained plugin with no external dependencies.

- **Language**: PHP 8.0+
- **Framework**: Textpattern CMS 4.7+
- **Type**: Textpattern plugin (public-side)
- **License**: GPL-2.0

## Project Structure

```text
.
├── cwh_hobo.php          # Main plugin file (single-file plugin)
├── manifest.json         # Plugin metadata for package managers
├── test_plugin.php       # Test suite (executable PHP script)
├── zem_tpl.php          # Plugin compiler (Textpattern compiler)
├── README.md            # User documentation
├── CHANGELOG.md         # Version history
├── LICENSE              # GPL-2.0 license
├── CODEOWNERS           # GitHub code ownership
└── .github/
    └── workflows/
        ├── test.yml     # GitHub Actions CI/CD
        └── release.yml  # GitHub Actions release automation
```

## Key Files

### cwh_hobo.php

The complete plugin in a single file. Contains:

- Plugin metadata (`$plugin` array)
- Inline help documentation (HTML format)
- Plugin code (3 functions)
- 700 hardcoded hobo names in an array

**Important**: This is a self-contained plugin file that can be used directly
in Textpattern's plugin cache directory OR compiled into a distributable
format.

### test_plugin.php

Standalone test suite with 15+ tests. Run with: `php test_plugin.php`

- Mocks Textpattern functions (`lAtts`, `doTag`)
- Tests functionality, randomness, performance
- Exit code 0 = success, 1 = failure

### manifest.json

Plugin metadata for modern package managers (not required for plugin
functionality).

## Development Workflow

### Local Testing

1. **Syntax check**:

   ```bash
   php -l cwh_hobo.php
   ```

2. **Run tests**:

   ```bash
   php test_plugin.php
   ```

3. **Build distribution**:

   ```bash
   php cwh_hobo.php > cwh_hobo-1.0.0.txt
   ```

4. **Lint markdown** (all new/changed markdown files must pass):

   ```bash
   npx markdownlint-cli README.md CHANGELOG.md AGENTS.md
   ```

### Testing in Textpattern

#### Method 1: Plugin Cache (Recommended for Development)

- Set "Plugin cache directory path" in Textpattern Admin → Preferences →
  Admin
- Copy `cwh_hobo.php` to that directory
- Changes reflect immediately without reinstalling

#### Method 2: Install via Admin

- Build the distribution file (`php cwh_hobo.php > output.txt`)
- Upload via Admin → Plugins
- Requires rebuild and reinstall for each change

## Code Architecture

### Functions

1. **`cwh_hobo($atts)`** - Main tag handler
   - Parses attributes using `lAtts()` (Textpattern function)
   - Returns formatted hobo name with optional HTML wrapper

2. **`cwh_hobo_get_random()`** - Returns random hobo name
   - Uses static caching (lazy loading)
   - Calls `cwh_hobo_get_list()` only once

3. **`cwh_hobo_get_list()`** - Returns array of 700 hobo names
   - Hardcoded array
   - Names include HTML entities (e.g., `&#8217;` for apostrophes)

### Textpattern Dependencies

The plugin uses these Textpattern core functions:

- `lAtts($defaults, $atts)` - Merges tag attributes with defaults
- `doTag($content, $tag, $class)` - Wraps content in HTML tag

When testing standalone, these are mocked in `test_plugin.php`.

## Coding Conventions

### PHP Style

- **Arrays**: Modern short syntax `[]` not `array()`
- **Type hints**: Not used (maintains compatibility with Textpattern's
  coding style)
- **Docblocks**: PHPDoc format for all functions
- **Naming**: Snake_case with `cwh_hobo_` prefix for all functions

### Plugin Conventions

- Single-file plugin format
- Plugin metadata at top in `$plugin` array
- Help documentation between `# --- BEGIN PLUGIN HELP ---` markers
- Plugin code between `# --- BEGIN PLUGIN CODE ---` markers
- No namespaces (Textpattern convention)

### Important: Never Modify

- **The 700 hobo names array** - This is the canonical list from John
  Hodgman's book and must never be changed
- Plugin metadata structure (Textpattern standard)
- The zem_tpl.php compiler (third-party tool)

## Testing Strategy

### Automated Tests (test_plugin.php)

Tests cover:

- Function existence
- Output format (`#NUM: Name`)
- Attribute handling (`wraptag`, `class`)
- Randomness verification
- HTML entity preservation
- Performance (< 1ms per call)

### GitHub Actions CI

**test.yml** - Runs on every PR to `main`:

- PHP syntax check (PHP 8.4 and 8.5)
- Test suite execution
- Build verification
- Markdown linting (README.md and CHANGELOG.md)

**release.yml** - Runs on pushed git tags (v*):

- Builds plugin distribution file (`cwh_hobo.txt`)
- Creates zip archive with plugin and README
- Creates GitHub release with assets attached

**Important**: Always run tests and markdownlint before committing changes.

## Making Changes

### Modifying Plugin Behavior

1. Update code in `# --- BEGIN PLUGIN CODE ---` section
2. Update help documentation in `# --- BEGIN PLUGIN HELP ---` section
3. Update README.md if user-facing
4. Run `php test_plugin.php`
5. Run markdownlint on any changed markdown files
6. Update CHANGELOG.md

### Version Updates

1. Update `$plugin['version']` in cwh_hobo.php
2. Update `"version"` in manifest.json
3. Add entry to CHANGELOG.md
4. Update README.md if needed

## Common Tasks for AI Agents

### "Fix a bug"

1. Read `cwh_hobo.php` and `test_plugin.php`
2. Understand the issue
3. Make minimal changes to plugin code
4. Add test case if missing
5. Run test suite
6. Run markdownlint if markdown files changed
7. Update CHANGELOG.md

### "Improve performance"

1. Review `cwh_hobo_get_random()` and `cwh_hobo_get_list()`
2. Note: Static caching already implemented
3. Run performance test: `php test_plugin.php`
4. Any optimization must not break Textpattern compatibility

### "Add a new feature"

1. Check if it requires new attributes
2. Update `cwh_hobo()` function
3. Update help documentation
4. Add tests to `test_plugin.php`
5. Update README.md
6. Run markdownlint on changed markdown files
7. Update CHANGELOG.md

## Compatibility Requirements

### PHP Version

- **Minimum**: PHP 8.0
- **Tested**: PHP 8.4, 8.5
- **Features used**: Short array syntax `[]`, modern syntax

### Textpattern Version

- **Minimum**: 4.7+
- **Tested**: 4.8.x, 4.9.0
- **Dependencies**: `lAtts()`, `doTag()` functions

### Breaking Changes

If changes require higher PHP/Textpattern versions:

1. Update README requirements
2. Update CHANGELOG with breaking change notice
3. Consider version bump (major version for breaking changes)

## Build and Distribution

### Building Plugin Package

```bash
# Generate distributable plugin file
php cwh_hobo.php > cwh_hobo-1.0.0.txt
```

This creates a base64-encoded plugin package that can be installed via
Textpattern's admin interface.

### What Gets Packaged

- Plugin metadata
- Help documentation (rendered in Textpattern admin)
- All plugin code
- The 700 hobo names array

### What Doesn't Get Packaged

- test_plugin.php
- manifest.json
- README.md
- CHANGELOG.md
- GitHub Actions workflows

## Security Considerations

### No User Input

The plugin accepts only two attributes (`wraptag`, `class`) which are:

- Sanitized by Textpattern's `lAtts()` function
- Passed to Textpattern's `doTag()` function for HTML generation
- Never evaluated or executed

### No External Resources

- No network calls
- No file system access
- No database queries
- No external dependencies

### Static Data Only

All hobo names are hardcoded - no dynamic content generation beyond random
selection.

## Textpattern Context

### Plugin Type

- **Type 0**: Public-side only (not loaded in admin)
- **Order 5**: Default load order (no special requirements)
- **Flags 0**: No special capabilities

### Usage

```html
<!-- Basic -->
<txp:cwh_hobo />

<!-- With wrapper -->
<txp:cwh_hobo wraptag="p" />

<!-- With class -->
<txp:cwh_hobo wraptag="div" class="hobo-name" />
```

### Output Format

Always: `#NUM: Name` where NUM is 1-700

Example: `#171: Twink the Reading-Room Snoozer`

## Documentation Maintenance

### User Documentation (README.md)

- Installation instructions
- Usage examples
- Requirements
- Development setup

### Developer Documentation (This File)

- Architecture
- Conventions
- Testing
- Common tasks

### Change Log (CHANGELOG.md)

- Follow Keep a Changelog format
- Document all changes
- Note breaking changes clearly

## CI/CD Pipeline

### Test Workflow (.github/workflows/test.yml)

Runs on: Pull requests to `main`

**Jobs**:

1. **test** (PHP 8.4, 8.5)
   - Checkout code
   - Setup PHP
   - Lint cwh_hobo.php
   - Lint test_plugin.php
   - Run test suite
   - Build distribution
   - Verify build output

2. **markdown-lint**
   - Checkout code
   - Setup Node.js 20
   - Run markdownlint on README.md and CHANGELOG.md

**Important**: All checks must pass before merging. When changing markdown
files, always run markdownlint locally first.

### Release Workflow (.github/workflows/release.yml)

Runs on: Pushed git tags matching `v*` (e.g., v1.0.0, v1.2.3)

**Jobs**:

1. **build** (PHP 8.4)
   - Checkout code
   - Setup PHP
   - Extract version from git tag
   - Build plugin distribution (`cwh_hobo.txt`)
   - Create zip archive with plugin and README
   - Create GitHub release with assets

**Release Assets**:

- `cwh_hobo.txt` - Plugin installer for Textpattern admin
- `cwh_hobo-vX.Y.Z.zip` - Archive containing plugin and README

**Creating a Release**:

1. Update version in `cwh_hobo.php` and `manifest.json`
2. Update `CHANGELOG.md` with release notes
3. Commit changes
4. Create and push git tag: `git tag v1.0.0 && git push origin v1.0.0`
5. GitHub Actions automatically creates the release

## Known Limitations

1. **Randomness**: Uses PHP's `array_rand()` - sufficient for this use case
   but not cryptographically secure
2. **Single locale**: All hobo names are in English
3. **Static list**: Cannot be extended without modifying plugin code
4. **No caching control**: Random on every page load (by design)

## Resources

- **Textpattern CMS**: <https://textpattern.com>
- **Plugin Development**:
  <https://docs.textpattern.com/development/plugin-template>
- **John Hodgman's Book**: "The Areas of my Expertise"
- **Author**: Christopher Horrell (<https://horrell.ca/>)

## Questions to Ask Before Making Changes

1. Does this change affect plugin compatibility with Textpattern 4.7+?
2. Does this change require PHP 8.1+ features?
3. Does this change affect the output format (breaking change)?
4. Have you updated all documentation files? (README, CHANGELOG, this file,
   inline help)
5. Have you run the test suite?
6. Have you run markdownlint on changed markdown files?
7. Does this change affect performance?
8. Have you considered backward compatibility?

## Quick Reference: File Purposes

| File                         | Purpose          | Modify When...             |
| ---------------------------- | ---------------- | -------------------------- |
| `cwh_hobo.php`               | Main plugin      | Adding features, bugs      |
| `test_plugin.php`            | Test suite       | Adding tests               |
| `manifest.json`              | Package metadata | Updating version           |
| `README.md`                  | User guide       | Changing usage/reqs        |
| `CHANGELOG.md`               | Version history  | Every change               |
| `AGENTS.md`                  | This file        | Changing arch/conventions  |
| `.github/workflows/test.yml` | CI/CD            | Changing test reqs         |

## Summary for AI Agents

This is a **simple, self-contained Textpattern plugin** with:

- ✅ All code in one PHP file
- ✅ Comprehensive test suite
- ✅ CI/CD pipeline
- ✅ Clear documentation
- ✅ No external dependencies
- ✅ Stable, mature codebase

**Philosophy**: Keep it simple. The plugin does one thing well: return a
random hobo name. Don't over-engineer it.
