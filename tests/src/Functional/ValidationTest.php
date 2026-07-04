<?php

declare(strict_types=1);

use Drupal\Core\Config\FileStorage;
use Drupal\canvas\Entity\ComponentTreeEntityInterface;
use Drupal\canvas\JsonSchemaDefinitionsStreamwrapper;
use Drupal\FunctionalTests\Core\Recipe\RecipeTestTrait;
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests that this site template can be applied without errors.
 *
 * All deprecation notices triggered by the recipe's dependencies will be
 * displayed. To suppress them, add the
 * \PHPUnit\Framework\Attributes\IgnoreDeprecations attribute to this class.
 */
#[RunTestsInSeparateProcesses]
class ValidationTest extends BrowserTestBase {

  use RecipeTestTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Returns the absolute path of the recipe this test is for.
   *
   * @return string
   *   The absolute path of the recipe.
   */
  protected static function getRecipePath(): string {
    return dirname(__FILE__, 4);
  }

  /**
   * Tests that the site template can be applied without errors.
   *
   * At the very least, this test ensures that this site template can be applied
   * against an empty site with the `drupal recipe` command-line tool. You
   * should customize this test to also confirm that the site template sets up
   * everything as you expect.
   *
   * If you need to test JavaScript interactions, you can convert this test to
   * a functional JavaScript test instead.
   *
   * Documentation on how to write functional (non-JavaScript) tests can be
   * found at https://www.drupal.org/docs/develop/automated-testing/phpunit-in-drupal/creating-functional-tests-simulated-browser.
   *
   * Documentation on how to write functional JavaScript tests can be found at
   * https://www.drupal.org/docs/develop/automated-testing/phpunit-in-drupal/creating-functionaljavascript-tests-real-browser.
   *
   * Further documentation on writing PHPUnit tests for Drupal can be found at
   * https://www.drupal.org/docs/develop/automated-testing/phpunit-in-drupal.
   */
  public function testApply(): void {
    $this->applyRecipe(self::getRecipePath());

    // If this site template uses Canvas, it is a best practice for it to ship
    // `canvas.component.*.yml` files for every component that is actually using
    // in content templates, page regions, patterns, landing pages, etc. This
    // method checks for that.
    $this->assertCanvasComponentsAreIncluded();

    $this->assertPagesRender();
    $this->assertSearchPageHasInput();
    $this->assertSocialMetatags();
  }

  /**
   * Checks that every shipped page path renders for anonymous users.
   */
  protected function assertPagesRender(): void {
    $paths = [
      '/home',
      '/pages/about-us',
      '/services',
      '/growth-planning',
      '/team',
      '/profiles/alex-morgan',
      '/articles',
      '/articles/5-signs-your-business-needs-operations-review',
      '/contact',
      '/404',
    ];
    foreach ($paths as $path) {
      $this->drupalGet($path);
      $this->assertSession()->statusCodeEquals(200);
    }
  }

  /**
   * Checks that /search offers a usable keyword input out of the box.
   */
  protected function assertSearchPageHasInput(): void {
    $this->drupalGet('/search');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->elementExists('css', 'input[name="keywords"]');
  }

  /**
   * Checks social/SEO metatags on the primary content types.
   */
  protected function assertSocialMetatags(): void {
    // Service pages must not fall back to the generic og:type and must emit a
    // canonical og:image (og:image:secure_url alone is not read by crawlers).
    $this->drupalGet('/growth-planning');
    $this->assertSession()->elementAttributeContains('css', 'meta[property="og:type"]', 'content', 'website');
    $this->assertSession()->elementExists('css', 'meta[property="og:image"]');
    $this->assertSession()->elementExists('css', 'meta[property="og:description"]');
    $this->assertSession()->elementExists('css', 'meta[name="description"]');

    $this->drupalGet('/articles/5-signs-your-business-needs-operations-review');
    $this->assertSession()->elementAttributeContains('css', 'meta[property="og:type"]', 'content', 'article');
    $this->assertSession()->elementExists('css', 'meta[property="og:image"]');

    $this->drupalGet('/profiles/alex-morgan');
    $this->assertSession()->elementAttributeContains('css', 'meta[property="og:type"]', 'content', 'profile');
  }

  /**
   * Checks that the site template includes all Canvas components that it uses.
   */
  protected function assertCanvasComponentsAreIncluded(): void {
    // Examine all entities that implement
    // \Drupal\canvas\Entity\ComponentTreeEntityInterface.
    $entity_types = array_filter(
      \Drupal::entityTypeManager()->getDefinitions(),
      fn ($entity_type): bool => $entity_type->entityClassImplements(ComponentTreeEntityInterface::class),
    );

    $included_components = (new FileStorage(self::getRecipePath() . '/config'))
      ->listAll('canvas.component.');

    foreach ($entity_types as $entity_type) {
      $entities = \Drupal::entityTypeManager()
        ->getStorage($entity_type->id())
        ->loadMultiple();

      foreach ($entities as $entity) {
        $this->assertInstanceOf(ComponentTreeEntityInterface::class, $entity);
        /** @var \Drupal\canvas\Plugin\Field\FieldType\ComponentTreeItem $item */
        foreach ($entity->getComponentTree() as $item) {
          $component = $item->getComponent()?->getConfigDependencyName();
          if ($component) {
            $this->assertContains($component, $included_components, 'The site template should include this component in its configuration.');
          }
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function rebuildAll(): void {
    // The rebuild won't succeed without the `json-schema-definitions` stream
    // wrapper. This would normally happen automatically whenever a module is
    // installed, but in this case, all of that has taken place in a separate
    // process, so we need to refresh *this* process manually.
    // @see canvas_module_preinstall()
    \Drupal::service('stream_wrapper_manager')->registerWrapper(
      'json-schema-definitions',
      JsonSchemaDefinitionsStreamwrapper::class,
      JsonSchemaDefinitionsStreamwrapper::getType(),
    );
    parent::rebuildAll();
  }

}
