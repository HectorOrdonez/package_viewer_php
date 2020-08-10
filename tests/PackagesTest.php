<?php

use AgriPlace\Package\PackageRepositoryInterface;
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
            return new FilePackageRepository($fakeSourceFile);
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
        $response = $this->get('api/packages/show/' . $packageThatDoesNotExist);

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
        $response = $this->get('api/packages/show/' . $packageThatExists);

        // Assert
        $response->assertResponseStatus(200);
        $response->shouldReturnJson([
            'name' => 'debconf',
            'description' => 'Debian configuration management system',
        ]);
    }

    /** @todo */
    public function show_responds_with_package_contents_when_description_is_multiline()
    {
        // Arrange

        // @todo Implement me

        // Act

        // Assert
    }

    /** @test */
    public function show_responds_with_dependency_without_reference_when_package_has_dependency_not_in_package_file()
    {
        // Arrange
        $this->useFakeSourceFile('/tests/Support/status-1-with-dependency-without-reference-entry');
        $packageWithMissingDependency = 'libdrm-radeon1';

        // Act
        $response = $this->get('api/packages/show/' . $packageWithMissingDependency);

        // Assert
        $response->assertResponseStatus(200);
        $response->shouldReturnJson([
            'name' => 'libdrm-radeon1',
            'description' => 'Userspace interface to radeon-specific kernel DRM services -- runtime',
            'dependencies' => [
                'libc6 (>= 2.14)',
                'libdrm2 (>= 2.4.3)',
            ]
        ]);
    }

    /** @test */
    public function show_responds_with_dependency_with_reference_when_package_has_dependency_in_package_file()
    {
        // Arrange
        $this->useFakeSourceFile('/tests/Support/status-3-with-dependency-with-reference-entry');
        $packageWithMissingDependency = 'libdrm-radeon1';

        // Act
        $response = $this->get('api/packages/show/' . $packageWithMissingDependency);

        // Assert
        $response->assertResponseStatus(200);
        $response->shouldReturnJson([
            'name' => 'libdrm-radeon1',
            'description' => 'Userspace interface to radeon-specific kernel DRM services -- runtime',
            'dependencies' => [
                'libc6 (>= 2.14) reference: ' . Url::to('packages/show/') . 'libc6',
                'libdrm2 (>= 2.4.3) reference: ' . Url::to('packages/show/') . 'libdrm2',
            ]
        ]);
    }
}
