# Palcera Brochure

A polished brochure **site template for Drupal CMS**: services, team, articles, and a
9-section Canvas home page — with a Schema.org content model, SEO defaults, search, and
demo content ready to replace with your own.

![Screenshot of the Palcera Brochure home page](screenshot.webp)

Mobile: the theme is fully responsive — [mobile screenshot](screenshot-mobile.webp).

## What you get

- **9-section Canvas home page** — hero, stats band, services grid, two feature sections,
  team grid, latest articles, testimonial, and call-to-action.
- **Content model with Schema.org mappings** (via `schemadotorg`): Person, Article,
  Service, and General Page content types with JSON-LD output.
- **Canvas content templates** for card + full view modes (articles, people, services),
  listing pages (`/articles`, `/team`, `/services`) built as Canvas pages with Views blocks.
- **SEO defaults** — metatags with sensible token fallbacks, XML sitemap, pathauto
  patterns, redirects, breadcrumbs.
- **Search**, cookie consent (Klaro), contact + webforms, editorial accessibility checks
  (Editoria11y), and the Drupal CMS admin experience (Gin, dashboard).
- **Neutral demo content** — a fictional consultancy ("Meridian Partners") you can edit or
  delete; all images ship with the template.

## Requirements

- Drupal CMS 2.1+ (Drupal core ^11.4)
- The [Palcera theme](https://github.com/palcera/palcera_theme) (installed automatically)

## Installation

Add the template to a Drupal CMS project and install the site using this recipe:

```bash
# The template currently carries three not-yet-stable dependencies
# (schemadotorg alpha, webform RC, palcera_theme beta) — allow them first:
composer config minimum-stability dev
composer config prefer-stable true

composer require palcera/palcera_brochure
drush site:install --site-name="My Site" recipes/palcera_brochure
```

Or select **Palcera Brochure** in the Drupal CMS installer when the template is present
in your project.

## After installing

1. Replace the demo company name, contact details, and logo (Appearance → Palcera settings).
2. Edit or delete the demo people, services, and articles.
3. Set your timezone (Regional settings — the template seeds `America/Chicago` as a default).
4. Browse add-ons in the Project Browser (`recommended.yml` is an optional curated-list stub — unused until stable Drupal CMS 2.x add-on recipes exist to recommend).

## Rebranding (colors, typography, design tokens)

The Palcera theme is fully token-driven: every color, font, radius, and shadow is a CSS
custom property in a shadcn/ui-compatible sheet. To rebrand **without rebuilding anything**:

1. Copy `web/themes/contrib/palcera_theme/src/theme.css` to your web root (next to
   `index.php`).
2. Edit the values — `--primary`, `--accent`, `--font-sans`, `--radius`, etc. Generators
   like [tweakcn](https://tweakcn.com) produce compatible palettes.
3. Clear caches. Every component picks up the new tokens immediately.

Swap the logo and favicon under **Appearance → Palcera** settings (the demo ships neutral
"Meridian Partners" assets you'll want to replace).

## License

GPL-2.0-or-later. See [LICENSE.txt](LICENSE.txt). Demo image licensing is documented in
[LICENSES-images.md](LICENSES-images.md). Security policy: [SECURITY.md](SECURITY.md).

A CycloneDX software bill of materials ([sbom.cdx.json](sbom.cdx.json)) is included as an
optional convenience — it is **not** a Drupal CMS marketplace requirement (verified
2026-07-02). It was generated with `cyclonedx/cyclonedx-php-composer` from an installed
build and reflects the dependency tree at generation time.
