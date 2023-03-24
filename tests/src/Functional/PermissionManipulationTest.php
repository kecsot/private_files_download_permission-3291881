<?php

namespace Drupal\Tests\pfdp\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Possible to manipulate tests.
 */
class PermissionManipulationTest extends BrowserTestBase
{

  use CreateFileTrait;

  /**
   * Modules to enable.
   *
   * @var string[]
   */
  protected static $modules = ['pfdp', 'pfdp_file_test_allow', 'file'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * If a 3rd party module gives permission to download a private file then,
   * PFDP should not deny the permission.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testNotDenyIfAnotherModuleGrantDownloadAccess()
  {
    $private_file_url = $this->createFile('private://testfile.txt')->createFileUrl(FALSE);
    $temporary_file_url = $this->createFile('temporary://testfile.txt')->createFileUrl(FALSE);

    // 3rd party module enable to download a private file
    \Drupal::state()->set('pfdp_file_test_allow.allow_next', TRUE);
    $this->drupalGet($private_file_url);
    $this->assertSession()->statusCodeEquals(200);

    // 3rd party module enable to download a private file
    \Drupal::state()->set('pfdp_file_test_allow.allow_next', TRUE);
    $this->drupalGet($temporary_file_url);
    $this->assertSession()->statusCodeEquals(200);
  }

}
