<?php

namespace Drupal\Tests\pfdp\Functional;

use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;

/**
 * Helps to create files.
 */
trait CreateFileTrait
{

  /**
   * Creates a file.
   */
  public function createFile(string $file_path, array $values = []): File
  {
    $fileRepository = \Drupal::service('file.repository');
    $fileRepository->writeData('Text file example content', $file_path, FileSystemInterface::EXISTS_REPLACE);

    $file = File::create([
        'filename' => 'testfile',
        'uri' => $file_path,
        'status' => 1,
        'uid' => 0,
      ] + $values);
    $file->save();
    return $file;
  }

}
