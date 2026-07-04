# Security policy

Palcera Brochure is a Drupal CMS **site template** — it ships configuration and demo
content only. It contains **no custom PHP or other runtime code** (enforced by the
`RequirementsTest` kernel test, which fails the build if any module or theme code is
present). Its attack surface is therefore its shipped configuration, not application code.

## Supported version

Security fixes are provided for the **latest released version** of this template. Older
releases are not maintained; update to the latest release to receive fixes.

## Dependency policy

- **No pinned versions.** Every dependency in `composer.json` uses a caret (`^`)
  constraint, so `composer update` and Drupal CMS Package Manager pull the newest
  compatible release — including security releases — without any change to this template.
- **No patched dependencies.** This template ships no patches and does not depend on
  `cweagans/composer-patches`. Security fixes reach consumers directly from upstream, never
  gated behind a patch we would have to re-roll.
- Both rules are enforced by `tests/src/Kernel/RequirementsTest.php`, which fails the build
  on any pinned constraint or `patches` key. This is a deliberate design choice: it
  guarantees security updates are delivered immediately by the normal Drupal update flow.
- The template currently carries three not-yet-stable dependencies (`schemadotorg` alpha,
  `webform` release candidate, `palcera/palcera_theme` beta). These remain caret-constrained;
  the README documents the consumer minimum-stability configuration needed to install them.

## Maintenance commitment

- Dependencies are kept current with Drupal CMS minor releases.
- Security advisories (Drupal SAs) affecting configuration this template ships are reviewed,
  and the template is updated where its shipped config is implicated.
- We are aware the marketplace **delists templates that go stale**, and maintain this
  template accordingly. No numeric response SLA is claimed.

## Reporting a vulnerability

- **In this template's shipped configuration or content:** open an issue on the package
  repository issue queue (see `composer.json` `homepage`). For a sensitive report, mark it
  private / contact the maintainer directly rather than filing a public issue.
- **In Drupal core or a contributed dependency:** report through the **Drupal Security
  Team** process at <https://www.drupal.org/security-team/report-issues>, not this repo.
  Those projects are outside this template's control; the template only references them as
  caret-constrained dependencies.

## Scope note

- The template ships configuration and content only — no custom runtime code — so the
  security surface is configuration.
- A Content-Security-Policy is shipped in **report-only** mode (via `seckit`) so it never
  breaks a site on install; consumers are encouraged to review the reports and switch the
  policy to enforcing for their production deployment.

## Server-level hardening (operator checklist)

These cannot be set by a Drupal recipe and belong to your hosting layer:

- **Strict-Transport-Security (HSTS)** — enable at your TLS terminator / web server.
- **X-Powered-By** — hide the PHP version with `expose_php = Off` in php.ini.
- **Permissions-Policy** — add at the web-server level if your policy requires it; the
  template ships `Referrer-Policy: strict-origin-when-cross-origin` via seckit.
