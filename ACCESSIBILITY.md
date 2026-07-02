# Accessibility Statement — palcera_brochure

**Declared target:** WCAG 2.2 Level AA.

The `palcera_brochure` site template targets conformance with the Web Content
Accessibility Guidelines (WCAG) 2.2 at Level AA. This statement records the audit
performed against the template's rendered output, the evidence gathered, resolutions
applied at the template layer, and the known issues that remain in the underlying
theme (tracked for upstream fix).

## Audit method

- **Automated:** axe-core 4.10.2 driven via Playwright (headless Chromium) against a
  fresh consumer install of the final package (demo brand "Meridian Partners").
  Ruleset tags run: `wcag2a`, `wcag2aa`, `wcag21a`, `wcag21aa`, `wcag22aa`,
  `best-practice`. Ten pages scanned (all primary view modes plus the 404 page).
- **Manual expert checks:** landmark structure, heading hierarchy, keyboard skip link,
  focus visibility, page language, form labelling, and colour contrast (axe computed
  contrast plus visual review of image/gradient backgrounds axe cannot compute).
- **Date:** 2026-07-02.

**Scope statement (honest):** This audit combines automated scanning with manual
expert inspection of the rendered markup and theme templates. It does **not** include
assistive-technology user testing (screen-reader, voice-control, or switch-user
sessions), and it does not target WCAG Level AAA. Automated tooling detects a subset
of possible barriers; absence of automated violations is not a guarantee of full
conformance.

## Per-page results

Every automated violation found is a single rule — `link-name` (serious) — and every
instance is theme-owned (see Known Issues). No template-level (shipped content/config)
critical or serious violations were found.

| Page | URL | Critical | Serious | Moderate | Minor | axe passes | Incomplete |
|------|-----|:-------:|:-------:|:--------:|:-----:|:----------:|:----------:|
| Home | `/` | 0 | 1* | 0 | 0 | 42 | 1 |
| Articles (listing) | `/articles` | 0 | 1* | 0 | 0 | 42 | 0 |
| Team (listing) | `/team` | 0 | 1* | 0 | 0 | 42 | 0 |
| Services (listing) | `/services` | 0 | 1* | 0 | 0 | 41 | 0 |
| Article (full) | `/articles/5-signs-your-business-needs-operations-review` | 0 | 1* | 0 | 0 | 42 | 1 |
| Person (full) | `/profiles/alex-morgan` | 0 | 1* | 0 | 0 | 42 | 1 |
| Service (full) | `/strategy-consulting` | 0 | 1* | 0 | 0 | 42 | 2 |
| About | `/pages/about-us` | 0 | 1* | 0 | 0 | 42 | 1 |
| Contact | `/pages/contact-us` | 0 | 1* | 0 | 0 | 47 | 1 |
| 404 (not found) | `/this-page-does-not-exist` | 0 | 1* | 0 | 0 | 41 | 0 |

\* The single serious violation on every page is the same `link-name` issue (WCAG 2.4.4 /
4.1.2), sourced entirely from the theme's branding block and breadcrumb templates. It is
**not** a template-level defect. See Known Issues.

*Incomplete* entries are `color-contrast` checks that axe cannot compute because the
text overlays an image or gradient (hero sections). Visual review found these legible;
they are flagged for manual confirmation, not counted as failures.

## Manual checklist

| Check | Result | Evidence |
|-------|--------|----------|
| Single `<main>` landmark | Pass | `<main role="main">`, one per page |
| Navigation landmark labelled | Pass | `<nav aria-labelledby="…-menu">` (main + footer menus) |
| Banner / contentinfo landmarks | Pass | `<header role="banner">`, `<footer>` present |
| Heading hierarchy — home | Pass | 1×H1, 9×H2, 12×H3; no level skipped |
| Heading hierarchy — listing (`/articles`) | Pass | 1×H1 ("Insights for business owners"), H2/H3 below (beta3 listing-heading fix present) |
| Heading hierarchy — article (full) | Pass | 1×H1, section H2s, no skip; axe `heading-order` clean |
| Skip link | Pass | `<a href="#main-content" class="visually-hidden focusable skip-link">Skip to main content</a>` |
| Focus visibility | Pass (theme-provided) | Theme ships focusable skip-link + Tailwind focus utilities; keyboard focus reaches interactive controls |
| Colour contrast | Pass | axe `color-contrast` reported **no** violations on any page (computed pairs); image-overlay text reviewed visually |
| Page language | Pass | `<html lang="en">` |
| Form labels (contact) | Pass | `Name`, `Email`, `Message` each have `<label for>`; honeypot labelled |

## Template-level findings and resolutions

**No template-level (shipped content/config) critical or serious violations were found.**

- Heading hierarchy on listing pages was corrected in a prior beta (beta3) so listing
  pages carry a page H1 and sectioned H2s — verified still present in this audit.
- Demo content images and form fields carry appropriate alternative text / labels.
- No template-level fixes were required in this audit; the package config was left
  unchanged (no post-export patch protocol items triggered).

## Known issues (theme-owned — fixes queued upstream)

These originate in `palcera_theme` component/template markup, not in the content or
config shipped by this template. Per the template's contribution boundary the theme is
not modified here; each item is queued for an upstream theme fix. The template ships
with `palcera_theme` at zero diff.

| Issue | WCAG | Severity | Source | Note |
|-------|------|----------|--------|------|
| Logo link has no accessible name | 2.4.4, 4.1.2 | Serious | `templates/block/block--system-branding-block.html.twig` (l.24) renders `alt="{{ site_name }}"`; the shipped header/footer regions set `use_site_name: false`, so `site_name` is empty and the logo-only link has no name | Theme fix: give the logo a name independent of the display-name toggle, e.g. `alt="{{ site_name|default('Home'|t) }}"` or an `aria-label` on the link. A config-only workaround (`use_site_name: true`) was rejected because the same template then renders the site name as visible heading text beside the wordmark logo — a design regression. **Queued upstream.** |
| Breadcrumb "home" link icon-only | 2.4.4, 4.1.2 | Serious | Breadcrumb home link on full-content pages is an icon-only `<a href="/">` wrapping an `aria-hidden="true"` SVG with no text | Theme fix: add visually-hidden "Home" text or `aria-label` to the breadcrumb home link. **Queued upstream.** |

### Feb-audit theme items — re-verified this audit

The following items were flagged in the earlier (February) theme audit and were
**re-checked** against current `palcera_theme` markup. All now resolve; none reproduce:

| Feb item | Current status | Evidence |
|----------|----------------|----------|
| status-messages missing role | Resolved | `role="{{ error/warning ? 'alert' : 'status' }}"` + `aria-label` (status-messages.html.twig l.45–46) |
| Cards use `div` not `article` | Resolved | `card.twig` root element is `<article>` (l.101) |
| Author-byline element choice | Resolved | `<address rel="author">` (author-byline.twig l.2) |
| Card-icon icon alt | Non-issue | Icons are decorative (`alt` defaults empty); axe reports no `image-alt` violation |
| Badge missing aria-label | Resolved | `<a … aria-label="{{ label }}">` (badge.twig l.42) |
| Hero-blog time lacks context | Resolved | `<time datetime>` with `<span class="sr-only">Published on </span>` (hero-blog.twig l.8) |
| Testimonial external-link indicator | Resolved | `rel="noopener"` + `<span class="sr-only"> (opens in new tab)</span>` (card-testimonial.twig l.99) |

## Summary

Against WCAG 2.2 AA, the template ships with **zero critical/serious violations at the
template (content/config) layer**. Two serious `link-name` issues remain in the
underlying `palcera_theme` markup (logo link, breadcrumb home link) and are queued for
upstream theme fixes; both prevent a clean "fully conformant" claim for the rendered
site until the theme lands those fixes. All other Feb-audit theme concerns have been
re-verified as resolved.
