<?php

namespace Yabacon\Paystack\Tests\Exception;

use Yabacon\Paystack\Exception\PaystackException;

class PaystackExceptionTest extends \PHPUnit\Framework\TestCase
{
    public function testInitialize()
    {
        $e = new PaystackException('message');
        $this->assertNotNull($e);
    }
}
