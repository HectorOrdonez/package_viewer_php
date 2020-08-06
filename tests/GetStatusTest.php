<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class GetStatusTest extends TestCase
{
    /**
     * @test
     */
    public function index_responds_with_200()
    {
        $this->get('api/status')
            ->assertResponseOk();
    }

    /** @test */
    public function index_responds_with_debconf_when_only_that_entry_exists()
    {
        $response = $this->get('api/status')->shouldReturnJson([
            'debconf',
        ]);
    }

    /** @test */
    public function show_responds_with_error_when_package_does_not_exist()
    {
        $packageThatDoesNotExist = 'life-universe-and-everything';

        $this->get('api/status/show/' . $packageThatDoesNotExist)->assertResponseStatus(400);
    }

    /** @test */
    public function show_responds_with_package_contents_when_package_exists()
    {
        $packageThatExists = 'debconf';

        $response = $this->get('api/status/show/' . $packageThatExists);

        $response->assertResponseStatus(200);
        $response->shouldReturnJson([
            'Package' => 'debconf',
            'Status' => 'install ok installed',
            'Multi-Arch' => 'foreign',
            'Priority' => 'required',
            'Section' => 'admin',
            'Installed-Size' => '609',
            'Maintainer' => 'Colin Watson <cjwatson@ubuntu.com>',
            'Architecture' => 'all',
            'Version' => '1.5.42ubuntu1',
            'Replaces' => 'debconf-tiny',
            'Provides' => 'debconf-2.0',
            'Pre-Depends' => 'perl-base (>= 5.6.1-4)',
            'Recommends' => 'apt-utils (>= 0.5.1), debconf-i18n',
            'Suggests' => 'debconf-doc, debconf-utils, whiptail | dialog | gnome-utils, libterm-readline-gnu-perl, libgtk2-perl (>= 1:1.130), libnet-ldap-perl, perl, libqtgui4-perl, libqtcore4-perl',
            'Conflicts' => 'apt (<< 0.3.12.1), cdebconf (<< 0.96), debconf-tiny, debconf-utils (<< 1.3.22), dialog (<< 0.9b-20020814-1), menu (<= 2.1.3-1), whiptail (<< 0.51.4-11), whiptail-utf8 (<= 0.50.17-13)',
            'Description' => 'Debian configuration management system',
            'Original-Maintainer' => 'Debconf Developers <debconf-devel@lists.alioth.debian.org>'
        ]);
    }
}
