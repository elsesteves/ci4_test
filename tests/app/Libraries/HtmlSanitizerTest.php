<?php

namespace App\Libraries;

use CodeIgniter\Test\CIUnitTestCase;

class HtmlSanitizerTest extends CIUnitTestCase
{
    protected HtmlSanitizer $sanitizer;

    protected function setUp(): void
    {
        $this->sanitizer = new HtmlSanitizer();
    }

    public function testReturnSameAsCleanTxt() : void
    {
        $inputTxt = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";

        $cleanTxt = $this->sanitizer->purify($inputTxt);

        $this->assertNotNull($cleanTxt);
        $this->assertEquals($cleanTxt, $inputTxt);
    }

    public function testReturnDiffFromDirtyTxt() : void
    {
        $inputTxt = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu<script>alert(1)</script> fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";

        $cleanTxt = $this->sanitizer->purify($inputTxt);

        $this->assertNotNull($cleanTxt);
        $this->assertNotEquals($cleanTxt, $inputTxt);
        $this->assertStringNotContainsString("<script>", $cleanTxt);
        $this->assertStringNotContainsString("alert(1)", $cleanTxt);
    }

}