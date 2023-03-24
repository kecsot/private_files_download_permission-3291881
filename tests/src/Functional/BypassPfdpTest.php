<?php

namespace Drupal\Tests\pfdp\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Basic tests for Pfdp module.
 */
class BypassPfdpTest extends BrowserTestBase {

  use CreateFileTrait;

  /*
   * Modules to install.
   */
  protected static $modules = ['pfdp', 'file'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Default permission checks.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testPermissions() {
    $private_file_url = $this->createFile('private://testfile.txt')->createFileUrl(FALSE);
    $temporary_file_url = $this->createFile('temporary://testfile.txt')->createFileUrl(FALSE);

    // User with 'bypass pfdp'.
    $user = $this->drupalCreateUser(['bypass pfdp']);
    $this->drupalLogin($user);
    $this->drupalGet($private_file_url);
    $this->assertSession()->statusCodeEquals(200);
    $this->drupalGet($temporary_file_url);
    $this->assertSession()->statusCodeEquals(200);
    $this->drupalLogOut();

    $user = $this->drupalCreateUser(['bypass pfdp for temporary files']);
    $this->drupalLogin($user);
    $this->drupalGet($private_file_url);
    $this->assertSession()->statusCodeEquals(403);
    $this->drupalGet($temporary_file_url);
    $this->assertSession()->statusCodeEquals(200);
    $this->drupalLogOut();

    // User without 'bypass pfdp'.
    $user = $this->drupalCreateUser();
    $this->drupalLogin($user);
    $this->drupalGet($private_file_url);
    $this->assertSession()->statusCodeEquals(403);
    $this->drupalGet($temporary_file_url);
    $this->assertSession()->statusCodeEquals(403);
    $this->drupalLogOut();
  }

}
