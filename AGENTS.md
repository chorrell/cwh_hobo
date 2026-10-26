# AGENTS.md - AI Agent Guide for cwh_hobo

This guide helps AI agents understand how to work with the cwh_hobo
Textpattern plugin project.

## Project Overview

**cwh_hobo** is a Textpattern CMS plugin that displays random hobo names
from John Hodgman's book "The Areas of my Expertise". It's a simple,
self-contained plugin with no external dependencies.

- **Language**: PHP 8.4+
- **Framework**: Textpattern CMS 4.9+
- **Type**: Textpattern plugin (public-side)
- **License**: GPL-2.0

## Project Structure

```text
.
├── cwh_hobo.php               # Main plugin file (single-file plugin)
├── manifest.json              # Plugin metadata for package managers
├── test_plugin.php            # Unit tests (executable PHP script, mocks Textpattern)
├── tests/integration/         # Integration tests against real Textpattern releases
├── zem_tpl.php                # Plugin compiler (Textpattern compiler)
├── README.md                  # User documentation
├── CHANGELOG.md               # Version history (maintained by release-please)
├── LICENSE                    # GPL-2.0 license
├── CODEOWNERS                 # GitHub code ownership
├── release-please-config.json # release-please settings
├── .release-please-manifest.json # Current released version (don't edit by hand)
├── .markdownlint-cli2.yaml    # markdownlint ignores (CHANGELOG.md)
├── .pre-commit-config.yaml    # Local pre-commit hook configuration
└── .github/
    ├── dependabot.yml         # Dependency update automation (Actions + pre-commit)
    └── workflows/
        ├── test.yml           # Unit tests and build check
        ├── integration.yml    # Integration tests on real Textpattern + MariaDB
        ├── markdownlint.yml   # Markdown lint CI
        └── release.yml        # release-please: release PR, tags, Releases, assets
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
   npx markdownlint-cli README.md AGENTS.md
   ```

5. **Run pre-commit hooks** (recommended before committing; runs actionlint,
   gitleaks, markdownlint-cli2, zizmor, generic hygiene checks, and `php -l`).
   The markdownlint and `php -l` hooks run in Docker (`php:8.4-cli` image), so
   Docker must be running but PHP need not be installed locally:

   ```bash
   pre-commit run --all-files
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

These mock Textpattern, so they can't tell whether the plugin actually works
inside it (they passed while the tag was unregistered and output nothing).

### Integration Tests (tests/integration/)

`run.sh <version> <cwh_hobo.txt>` downloads a Textpattern release (checked
against `textpattern.sha256`), installs it with Textpattern's CLI setup,
installs the plugin with Textpattern's own plugin installer
(`install-plugin.php`), renders `page.txp` with debug mode on, and checks:

- No "tag does not exist", "unknown attribute", PHP warning, notice or
  deprecation in the page
- `<txp:cwh_hobo />` outputs `#NUM: Name` with NUM from 1 to 700
- `wraptag` and `class` produce `<p class="hobo">…</p>`, and `wraptag` alone
  produces `<span>…</span>`
- 10 page loads return more than one distinct name

It needs PHP with `mysqli`, curl, and an empty MySQL/MariaDB database
(`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`). To test another Textpattern
release, add its tarball's checksum (from the release's `.SHA256SUM` asset)
to `textpattern.sha256`, then add the version to the matrix in
`integration.yml`.

### GitHub Actions CI

**test.yml** - Runs on every PR to `main`:

- PHP syntax check (PHP 8.4 and 8.5)
- Test suite execution
- Build verification

**integration.yml** - Runs on every PR to `main`:

- Textpattern 4.9.0 and 4.9.1 × PHP 8.4 and 8.5, against a MariaDB service
- Installs Textpattern, installs the built `cwh_hobo.txt` with Textpattern's
  plugin installer, and checks the rendered tags (see Integration Tests below)

**markdownlint.yml** - Runs on every PR to `main`:

- Markdown linting (all Markdown files except CHANGELOG.md)

**release.yml** - Runs on every push to `main`:

- release-please opens or updates the release PR
- When a release PR is merged, builds `cwh_hobo.txt` and a zip and attaches
  them to the new GitHub release

**Important**: Always run tests and markdownlint before committing changes.

## Making Changes

### Modifying Plugin Behavior

1. Update code in `# --- BEGIN PLUGIN CODE ---` section
2. Update help documentation in `# --- BEGIN PLUGIN HELP ---` section
3. Update README.md if user-facing
4. Run `php test_plugin.php`
5. Run markdownlint on any changed markdown files
6. Use a conventional commit title for the PR (see below)

### Commits and Version Updates

Don't bump versions or edit CHANGELOG.md by hand; release-please does both.

- PRs are squash-merged, so the PR title becomes the commit release-please
  reads. Use conventional commits: `feat:` is a minor release, `fix:` a
  patch, and `docs:`/`ci:`/`chore:` don't trigger a release
- Mark breaking changes with `!` (e.g. `feat!:`) or a `BREAKING CHANGE:`
  footer so they get a major release
- release-please bumps the version on the lines marked
  `x-release-please-version` in cwh_hobo.php and `"version"` in manifest.json
- No AI attribution lines in commits or PR descriptions

## Common Tasks for AI Agents

### "Fix a bug"

1. Read `cwh_hobo.php` and `test_plugin.php`
2. Understand the issue
3. Make minimal changes to plugin code
4. Add test case if missing
5. Run test suite
6. Run markdownlint if markdown files changed
7. Use a `fix:` PR title

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
7. Use a `feat:` PR title

## Compatibility Requirements

### PHP Version

- **Minimum**: PHP 8.4 (matches the tested versions and Textpattern's
  recommended PHP versions)
- **Tested**: PHP 8.4, 8.5 (the versions Textpattern recommends; latest
  patch release via setup-php's `update: true` env var)
- **Features used**: Short array syntax `[]`, modern syntax

### Textpattern Version

- **Minimum**: 4.9 (earlier versions don't run on PHP 8.4+; 4.8.8 fails in its
  own `index.php` on the deprecated `E_STRICT` constant)
- **Tested**: 4.9.0, 4.9.1 (integration.yml)
- **Dependencies**: `lAtts()`, `doTag()` functions, and the tag registry
  (`Txp::get('\Textpattern\Tag\Registry')->register()`). Textpattern 4.9
  only runs registered tags; an unregistered tag outputs nothing and logs
  "cwh_hobo tag does not exist"

### Breaking Changes

If changes require higher PHP/Textpattern versions:

1. Update README requirements
2. Mark the commit as breaking (`!` or a `BREAKING CHANGE:` footer) so
   release-please makes a major release

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

- Generated by release-please from conventional commit titles; don't edit
  it by hand except in the release PR, right before merging it
- Entries before 2.0.0 are hand-written in Keep a Changelog format

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

### Markdown Lint Workflow (.github/workflows/markdownlint.yml)

Runs on: Pull requests to `main`

- Checkout code
- Run markdownlint on all Markdown files (CHANGELOG.md is ignored via
  `.markdownlint-cli2.yaml` because release-please generates it)

**Important**: All checks must pass before merging. When changing markdown
files, always run markdownlint locally first.

### Release Workflow (.github/workflows/release.yml)

Runs on: Every push to `main`

**Jobs**:

1. **release-please** (in the `release` environment, using a GitHub App
   token so its PRs trigger CI)
   - Opens or updates a `chore(main): release X.Y.Z` PR after a releasable
     merge, with the version bumps and CHANGELOG entry
   - When that PR is merged, tags `vX.Y.Z` and creates the GitHub release
2. **build** (PHP 8.4, only when a release was created)
   - Checks out the new tag
   - Builds `cwh_hobo.txt` and `cwh_hobo-vX.Y.Z.zip`
   - Attaches them to the release

**Release Assets**:

- `cwh_hobo.txt` - Plugin installer for Textpattern admin
- `cwh_hobo-vX.Y.Z.zip` - Archive containing plugin and README

**Creating a Release**:

1. Merge conventional-commit PRs to `main` as usual
2. Review the release PR release-please keeps open; edit its CHANGELOG
   entry if needed, right before merging (any push to `main` regenerates it)
3. Merge the release PR. Don't merge it without being asked
4. The workflow tags the release and attaches the assets

Setup: the release GitHub App must be installed on this repo, and the
`release` environment (limited to `main`) needs the `RELEASE_APP_CLIENT_ID`
variable and `RELEASE_APP_PRIVATE_KEY` secret.

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

1. Does this change affect plugin compatibility with Textpattern 4.9+?
2. Does this change require features newer than PHP 8.4?
3. Does this change affect the output format (breaking change)?
4. Have you updated all documentation files? (README, this file, inline
   help)
5. Is the PR title a conventional commit, with `!` if it's breaking?
6. Have you run the test suite?
7. Have you run markdownlint on changed markdown files?
8. Does this change affect performance?
9. Have you considered backward compatibility?

## Quick Reference: File Purposes

| File                         | Purpose            | Modify When...               |
| ---------------------------- | ------------------ | ---------------------------- |
| `cwh_hobo.php`               | Main plugin        | Adding features, bugs        |
| `test_plugin.php`            | Test suite         | Adding tests                 |
| `manifest.json`              | Package metadata   | Never (release-please)       |
| `README.md`                  | User guide         | Changing usage/reqs          |
| `CHANGELOG.md`               | Version history    | Never (release-please)       |
| `AGENTS.md`                  | This file          | Changing arch/conventions    |
| `.pre-commit-config.yaml`    | Pre-commit hooks   | Changing/adding local checks |
| `.github/dependabot.yml`     | Dependency updates | Adding new update ecosystems |
| `.github/workflows/test.yml` | CI/CD              | Changing test reqs           |

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
