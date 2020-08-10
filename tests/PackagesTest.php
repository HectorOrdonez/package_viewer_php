<?php

use AgriPlace\Package\PackageRepositoryInterface;
use AgriPlace\Package\Parser\PackageParser;
use AgriPlace\Package\Repository\FilePackageRepository;

class PackagesTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->useFakeSourceFile('/tests/Support/status-1-entry');
    }

    private function useFakeSourceFile($fakeSourceFile): void
    {
        $this->app->bind(PackageRepositoryInterface::class, function () use ($fakeSourceFile) {
            return new FilePackageRepository(
                new PackageParser(),
                $fakeSourceFile
            );
        });
    }

    /**
     * @test
     */
    public function index_responds_with_200()
    {
        $this->useFakeSourceFile('/tests/Support/status-all-entries');

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
        $response = $this->get('api/packages/' . $packageThatDoesNotExist);

        // Assert
        $response->assertResponseStatus(400);
    }

    /** @test */
    public function show_responds_with_package_contents_when_package_exists()
    {
        // Arrange
        $this->useFakeSourceFile('/tests/Support/status-all-entries');
        $packageThatExists = 'debconf';

        // Act
        $response = $this->get('api/packages/' . $packageThatExists);

        // Assert
        $response->assertResponseStatus(200);
        $response->shouldReturnJson([
            'name' => 'debconf',
            'description' => 'Debian configuration management system',
        ]);
    }

    /** @test */
    public function show_responds_with_package_contents_when_description_is_multiline()
    {
        $packageThatExists = 'debconf';

        // Act
        $response = $this->get('api/packages/' . $packageThatExists);

        // Assert
        $response->assertResponseStatus(200);
        $response->shouldReturnJson([
            'name' => 'debconf',
            'description' => [
                'Debian configuration management system',
                'Debconf is a configuration management system for debian packages. Packages',
                'use Debconf to ask questions when they are installed.',
            ]
        ]);
    }

    /** @test */
    public function show_responds_with_dependency_without_reference_when_package_has_dependency_not_in_package_file()
    {
        // Arrange
        $this->useFakeSourceFile('/tests/Support/status-1-with-dependency-without-reference-entry');
        $packageWithMissingDependency = 'libdrm-radeon1';

        // Act
        $response = $this->get('api/packages/' . $packageWithMissingDependency);

        // Assert
        $response->assertResponseStatus(200);
        $response->shouldReturnJson([
            'name' => 'libdrm-radeon1',
            'description' => 'Userspace interface to radeon-specific kernel DRM services -- runtime',
            'dependencies' => [
                [
                    'name' => 'libc6',
                    'reference' => null
                ],
                [
                    'name' => 'libdrm2',
                    'reference' => null
                ],
            ]
        ]);
    }

    /** @test */
    public function show_responds_with_dependency_with_reference_when_package_has_dependency_in_package_file()
    {
        // Arrange
        $this->useFakeSourceFile('/tests/Support/status-3-with-dependency-with-references-entry');
        $packageWithMissingDependency = 'libdrm-radeon1';

        // Act
        $response = $this->get('api/packages/' . $packageWithMissingDependency);

        // Assert
        $response->assertResponseStatus(200);
        $response->shouldReturnJson([
            'name' => 'libdrm-radeon1',
            'description' => 'Userspace interface to radeon-specific kernel DRM services -- runtime',
            'dependencies' => [
                [
                    'name' => 'libc6',
                    'reference' => \Url::to('api/packages/libc6'),
                ],
                [
                    'name' => 'libdrm2',
                    'reference' => \Url::to('api/packages/libdrm2'),
                ],
            ]
        ]);
    }
}
