#!/usr/bin/env bash
# Installs a pinned Textpattern release, installs the built plugin with
# Textpattern's own installer, renders page.txp and checks the tag output.
#
# Usage: tests/integration/run.sh <textpattern version> <cwh_hobo.txt>
#
# Needs php (with mysqli) and curl, and a MySQL/MariaDB database described by
# DB_HOST, DB_NAME, DB_USER and DB_PASSWORD. CI provides these; see
# .github/workflows/integration.yml.
set -euo pipefail

version=${1:?usage: run.sh <textpattern version> <cwh_hobo.txt>}
plugin=$(realpath "${2:?usage: run.sh <textpattern version> <cwh_hobo.txt>}")
here=$(cd "$(dirname "$0")" && pwd)
port=${PORT:-8080}
work=$(mktemp -d)
server_pid=

cleanup() {
    if [ -n "$server_pid" ]; then
        kill "$server_pid" 2>/dev/null || true
    fi
    rm -rf "$work"
}
trap cleanup EXIT

fail() {
    echo "FAIL: $*" >&2
    exit 1
}

# Download the release and check it against the pinned checksum
tarball="textpattern-${version}.tar.gz"
grep -q "  ${tarball}$" "$here/textpattern.sha256" ||
    fail "no checksum for ${tarball} in textpattern.sha256"
curl -fsSL -o "$work/$tarball" \
    "https://github.com/textpattern/textpattern/releases/download/${version}/${tarball}"
(cd "$work" && grep "  ${tarball}$" "$here/textpattern.sha256" | sha256sum -c -)

mkdir "$work/site"
tar -xzf "$work/$tarball" -C "$work/site" --strip-components=1

# Test-only credentials for the throwaway site
cat >"$work/setup.json" <<EOF
{
  "site": {"language_code": "en", "public_url": "127.0.0.1:${port}"},
  "database": {
    "user": "${DB_USER}", "password": "${DB_PASSWORD}",
    "host": "${DB_HOST}", "db_name": "${DB_NAME}", "table_prefix": ""
  },
  "user": {
    "full_name": "Test Admin", "email": "admin@example.test",
    "login_name": "admin", "password": "integration-test-only"
  }
}
EOF

php "$work/site/textpattern/setup/setup.php" --config="$work/setup.json"
php "$here/install-plugin.php" "$work/site" "$plugin" "$here/page.txp"

php -S "127.0.0.1:${port}" -t "$work/site" >"$work/server.log" 2>&1 &
server_pid=$!

for _ in $(seq 30); do
    curl -fs -o /dev/null "http://127.0.0.1:${port}/" && break
    sleep 1
done

names=()
for i in $(seq 10); do
    page="$work/page-$i.html"
    curl -fsS -o "$page" "http://127.0.0.1:${port}/"

    # Debug mode prints tag errors and PHP warnings into the page
    if grep -qiE 'tag does not exist|unknown attribute|Warning:|Notice:|Deprecated:|Fatal error' "$page"; then
        sed -e 's/<[^>]*>//g' "$page" | grep -iE 'does not exist|unknown attribute|Warning|Notice|Deprecated|Fatal' | sort -u >&2
        fail "Textpattern reported errors while rendering the page"
    fi

    name='#([1-9][0-9]{0,2}): [^<]+'
    plain=$(sed -n 's/^PLAIN=\[\(.*\)\]$/\1/p' "$page")
    wrapped=$(sed -n 's/^WRAPPED=\[\(.*\)\]$/\1/p' "$page")
    no_class=$(sed -n 's/^NO_CLASS=\[\(.*\)\]$/\1/p' "$page")

    [[ $plain =~ ^${name}$ ]] || fail "plain tag output: '$plain'"
    number=${BASH_REMATCH[1]}
    ((number >= 1 && number <= 700)) || fail "hobo number out of range: $number"
    [[ $wrapped =~ ^\<p\ class=\"hobo\"\>${name}\</p\>$ ]] || fail "wraptag/class output: '$wrapped'"
    [[ $no_class =~ ^\<span\>${name}\</span\>$ ]] || fail "wraptag without class output: '$no_class'"

    names+=("$plain")
done

distinct=$(printf '%s\n' "${names[@]}" | sort -u | wc -l)
((distinct > 1)) || fail "10 page loads returned the same name: ${names[0]}"

echo "PASS: Textpattern ${version} on PHP $(php -r 'echo PHP_VERSION;'): ${distinct} distinct names in 10 loads, e.g. ${names[0]}"
