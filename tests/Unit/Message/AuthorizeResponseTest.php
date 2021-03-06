<?php

namespace Omnipay\CheckoutCom\Tests\Unit\Message;

use Omnipay\CheckoutCom\Message\AuthorizeRequest;
use Omnipay\CheckoutCom\Message\AuthorizeResponse;
use PHPUnit\Framework\TestCase;

class AuthorizeResponseTest extends TestCase
{

	public function testSuccessfulResponseIsCorrectlyParsed()
	{
		$data = include __DIR__ . '/../../Unit/Mocks/successful_capture.php';

		$request = $this->createMock(AuthorizeRequest::class);
		$authorizeResponse = new AuthorizeResponse($request, $data);

		$this->assertTrue($authorizeResponse->isSuccessful());
		$this->assertFalse($authorizeResponse->isRedirect());
		$this->assertSame('pay_7ac5ku5rl4du5gfe3jr6uamgwi', $authorizeResponse->getTransactionReference());
		$this->assertSame('Authorized', $authorizeResponse->getStatus());
	}

	public function testRedirectResponseIsCorrectlyParsed()
	{
		$data = include __DIR__ . '/../../Unit/Mocks/redirect_response.php';

		$request = $this->createMock(AuthorizeRequest::class);
		$authorizeResponse = new AuthorizeResponse($request, $data);

		$this->assertFalse($authorizeResponse->isSuccessful());
		$this->assertTrue($authorizeResponse->isRedirect());
		$this->assertSame('pay_6ljybxgl3asedi3qaurjrfms3i', $authorizeResponse->getTransactionReference());
		$this->assertSame('https://sandbox.checkout.com/api2/v2/3ds/acs/sid_ufbbwfo55y2evjpyu7wqpqtbfm', $authorizeResponse->getRedirectUrl());
		$this->assertSame('Pending', $authorizeResponse->getStatus());
	}

	public function testUnSuccessfulResponseIsCorrectlyParsed()
	{
		$data = include __DIR__ . '/../../Unit/Mocks/unsuccessful_response.php';

		$request = $this->createMock(AuthorizeRequest::class);
		$authorizeResponse = new AuthorizeResponse($request, $data);

		$this->assertFalse($authorizeResponse->isSuccessful());
		$this->assertFalse($authorizeResponse->isRedirect());
		$this->assertSame('pay_dgoraqrws6jurl5ghlwi7wa7mi', $authorizeResponse->getTransactionReference());
		$this->assertSame('Bad Track Data', $authorizeResponse->getMessage());
		$this->assertSame('20087', $authorizeResponse->getCode());
		$this->assertSame('Declined', $authorizeResponse->getStatus());
	}
}
