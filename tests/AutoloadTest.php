<?php
namespace Yabacon\Paystack\Tests;

class AutoloadTest extends \PHPUnit\Framework\TestCase
{
    public function testAutoload()
    {
        $paystack_autoloader = require(__DIR__ . '/../src/autoload.php');
        $paystack_autoloader('Yabacon\\Paystack\\Routes\\Invoice');
    }
}
