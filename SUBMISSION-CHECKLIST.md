# Marketplace submission checklist (for the maintainer — nothing here is automated)

All facts live-verified 2026-07-02 against drupal.org primary sources
(see the epic's `research/marketplace-facts-2026-07-02.md`). **Every step below is yours to
execute — nothing has been submitted, pushed, or published anywhere.**

## Pre-flight (already satisfied in this repo)
- [x] `recipe.yml` `type: Site`, name "Palcera Brochure", description set
- [x] `composer.json` `type: drupal-recipe`, `license: GPL-2.0-or-later`, name `palcera/palcera_brochure`
- [x] No pinned versions, no `patches`, no install-profile dependency (RequirementsTest enforces)
- [x] Naming rule: no `drupal_cms_` prefix
- [x] Single Mercury-derived theme (`palcera/palcera_theme`, Packagist) — RFC-aligned
- [x] Install proof green: InstallTest + ValidationTest + RequirementsTest (PHPUnit, local)
- [x] WCAG 2.2 AA evidence: `ACCESSIBILITY.md` (axe-core 10 pages, 0 template-level crit/serious)
- [x] Asset rights: `LICENSES-images.md` (17 assets; photos AI-generated + Magnific-enhanced)
- [x] `SECURITY.md` maintenance commitment; optional `sbom.cdx.json` (CycloneDX 1.5 — NOT required)

## Your steps
1. **Provenance: RESOLVED.** `login-wallpaper.png` verified byte-identical (md5) to the upstream
   `drupal_cms_admin_ui` asset — it is Drupal CMS's own GPL wallpaper swept in by site:export,
   documented as such in LICENSES-images.md. Demo photos documented per your 2026-07-01 confirmation.
2. **Decide hosting:** GitHub + Packagist under `palcera/` is explicitly permitted (GET-STARTED.md).
   Create the GitHub repo, push, publish on Packagist. (Or drupal.org project — then the composer
   name must become `drupal/palcera_brochure`.)
3. **Tag a release** (e.g. 1.0.0-beta1) — marketplace review runs against a shippable state.
4. **Confirm eligibility path:** Drupal Certified Partner (org) or Ripplemaker (≤3 people).
5. **Apply:** new.drupal.org/browse/site-templates → "Become a creator" → `/site-template/application`
   (auth-gated; exact form fields unverified — check whether WCAG/GDPR declarations are form
   checkboxes or file uploads). Fees: waived during pilot; 10% referral share on marketplace-tied upsells.
6. **Consumer install note for reviewers/users:** `composer config minimum-stability dev &&
   composer config prefer-stable true` before `composer require palcera/palcera_brochure`
   (schemadotorg alpha / webform RC / theme beta — documented in README).
7. **Optional upstream contributions** (raise as drupal.org issues if you wish):
   drupal_cms_search's dead `config.import: node:` on core 11.4 (fresh-install crash — ship
   `core.entity_view_mode.node.search_index` defensively as Haven does); schemadotorg mappings
   missing mapping_type config dependency; palcera_theme: neutralize author-byline SDC `examples:`
   + logo/breadcrumb `link-name` fixes (see ACCESSIBILITY.md Known Issues).

## Maintenance reminders
- After ANY `drush site:export`: re-apply the two post-export patches (AGENTS.md).
- Keep deps current with Drupal CMS minors — stale templates get delisted.
