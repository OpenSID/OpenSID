<?php
require_once 'init.php';
require_once dirname(__FILE__) . '/simpletest/autorun.php';
require_once dirname(__FILE__) . '/simpletest/browser.php';

class TestOfParsedPages extends UnitTestCase {
	var $browser;

	function page($page) {
		return TEST_HOST. '/index.php/' . $page;
	}

	function testLogin() {
		$this->browser = new SimpleBrowser();
		$this->browser->useCookies();
		$this->browser->useFrames();
		$this->browser->__construct();
		$this->browser->get($this->page( 'siteman' ));
		$this->browser->setField('username', TEST_USERNAME);
		$this->browser->setField('password', TEST_PASSWORD);
		$this->browser->clickSubmit('LOGIN');

		$this->assertTrue( (strpos($this->browser->getTitle(), TEST_ADMIN_TITLE) !== false) ? true : false );
		$this->assertIdentical($this->browser->getResponseCode(), 200);
	}

	function testPagesResponse() {
		$pages = array(
			// tab home desa
			'pengurus',
			'hom_desa/about',
			// tab penduduk
			'sid_core/clear',
			'keluarga/clear',
			'penduduk/clear',
			'rtm/clear',
			'kelompok/clear',
			// tab statistik
			'statistik',
			'statistik/index/0',
			'statistik/index/1',
			'statistik/index/2',
			'statistik/index/3',
			'statistik/index/4',
			'statistik/index/5',
			'statistik/index/6',
			'statistik/index/7',
			'statistik/index/8',
			'statistik/index/9',
			'statistik/index/10',
			'statistik/index/11',
			'statistik/index/12',
			'statistik/index/13',
			'statistik/index/14',
			'statistik/index/15',
			'statistik/index/16',
			'statistik/index/17',
			'statistik/index/18',
			'statistik/index/19',

			'laporan/clear',
			'laporan_rentan/clear',
			
			
		);
		foreach ($pages as $page){
			$this->browser->get($this->page($page));
			$this->assertIdentical($this->browser->getResponseCode(), 200, "Response not 200: " . $page);
		}
	}
}
