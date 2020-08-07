<?php

use AgriPlace\Package\PackageRepositoryInterface;
use AgriPlace\Package\Repository\FilePackageRepository;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PackagesTest extends TestCase
{
    private $sourceFile;

    public function setUp()
    {
        parent::setUp();

        $this->app->bind(PackageRepositoryInterface::class, function () {
            return new FilePackageRepository('/tests/Support/status-1-entry');
        });
    }

    /**
     * @test
     */
    public function index_responds_with_200()
    {
        // Act
        $response = $this->get('api/packages');

        // Assert
        $response->assertResponseOk();
    }

    /** @test */
    public function index_responds_with_debconf_when_only_that_entry_exists()
    {
        // Act
        $response = $this->get('api/packages');

        // Assert
        $response->assertResponseOk();
        $response->shouldReturnJson(['debconf']);
    }

    /** @test */
    public function show_responds_with_error_when_package_does_not_exist()
    {
        // Arrange
        $packageThatDoesNotExist = 'life-universe-and-everything';

        // Act
        $response = $this->get('api/packages/show/' . $packageThatDoesNotExist);

        // Assert
        $response->assertResponseStatus(400);
    }

    /** @test */
    public function show_responds_with_package_contents_when_package_exists()
    {
        // Arrange
        $packageThatExists = 'debconf';

        // Act
        $response = $this->get('api/packages/show/' . $packageThatExists);

        // Assert
        $response->assertResponseStatus(200);
        $response->shouldReturnJson([
            'name' => 'debconf',
            'description' => 'Debian configuration management system',
        ]);
    }

    /** @test */
    public function show_responds_with_dependency_without_reference_when_package_has_dependency_not_in_package_file()
    {
        // Arrange

        // Act

        // Assert

    }

}
