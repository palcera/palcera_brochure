# Agent guidance for this Drupal site

This codebase is a Composer-managed Drupal site. Local development uses `ddev`.

This file is meant to be copied into sites created from a site template. Site template authors should customize the "Template-specific notes" section below.

## Local environment (DDEV)

Run commands from the project root:

- Start or restart the local environment with `ddev start`, `ddev restart`, and `ddev stop`.
- Install PHP dependencies with `ddev composer install`.
- Open the site with `ddev launch`.
- Run Drush commands with `ddev drush <command>` such as `status`, `user:login`,  `cache:rebuild`, and `update:db`.

DDEV project config lives in `.ddev/config.yaml`. Use `.ddev/config.local.yaml` for machine-specific overrides.

## Common Drupal workflows

- Add a module with `ddev composer require drupal/<project>`, then  `ddev drush pm:enable --yes <module_machine_name>`, then `ddev drush cache:rebuild`.
- Apply database updates after code changes with `ddev drush update:db --yes`.
- Import repository configuration into the site with `ddev drush config:import --yes`.
- Export site configuration back to the repo with `ddev drush config:export --yes`.

## Guardrails

- Do not commit secrets or machine-local overrides such as `.env`, `settings.local.php`, or `.ddev/config.local.yaml`.
- Do not commit `vendor/` or uploaded files under `web/sites/*/files`.
- Do not edit Drupal core or contributed projects in place.
- Put custom code in `web/modules/custom` and `web/themes/custom`.

## Template-specific notes

- Package: `drupal/palcera_brochure` — brochure site template seeded from the palcera_base
  content model (Schema.org via schemadotorg) and the palcera_theme (Mercury-generated,
  standalone; requires `drupal/cva`, works with Canvas).
- Demo brand is the fictional consultancy "Meridian Partners"; the theme's default logo is
  overridden via `palcera_theme.settings` (`public://branding/meridian-logo-light.svg`) —
  do NOT edit the theme to rebrand, override the logo path instead.
- Listing pages (/articles, /team, /services) are Canvas pages embedding Views blocks;
  the home page is a 9-section Canvas page. Content templates cover card + full view modes.
- Regenerate config/content with `drush site:export` from a configured site rather than
  hand-editing exported YAML wholesale.
