<?php

use Cupa\File;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserCanUploadFilesTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    public function testUserCanViewAllFiles()
    {
        $files = factory(File::class, 10)->create();

        $this->visit('manage/files')
            ->seePageIs('manage/files')
            ->see('CUPA Files');

        $this->assertViewHas('files');
    }

    public function testUserCanClickOnAddButton()
    {
        if (file_exists(public_path().'/uploadtest')) {
            mkdir(public_path().'/uploadtest');
        }

        // create test file
        $fileLocation = public_path('uploadtest/test-file.txt');
        exec('echo "Just a test file" >'.escapeshellarg($fileLocation));

        $this->visit('manage/files')
            ->click('Add File')
            ->seePageIs('manage/files/add')
            ->attach($fileLocation)
            ->press('Upload File')
            ->seePageIs('manage/files');
    }
}
