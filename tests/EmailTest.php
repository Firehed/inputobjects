<?php

namespace Firehed\InputObjects;

/**
 * @covers Firehed\InputObjects\Email
 */
class EmailTest extends \PHPUnit\Framework\TestCase
{
    private Email $email;

    public function setUp(): void
    {
        $this->email = new Email();
    }

    /**
     * @return array{string, bool}[]
     */
    public function values()
    {
        // List from
        // http://blogs.msdn.com/b/testing123/archive/2009/02/05/email-address-test-cases.aspx
        return [
            // Valids
            ['email@domain.com', true],
            ['firstname.lastname@domain.com', true],
            ['email@subdomain.domain.com', true],
            ['firstname+lastname@domain.com', true],
            ['email@[123.123.123.123]', true],
            ['"email"@domain.com', true],
            ['1234567890@domain.com', true],
            ['email@domain-one.com', true],
            ['_______@domain.com', true],
            ['email@domain.name', true],
            ['email@domain.co.jp', true],
            ['firstname-lastname@domain.com', true],
            // Invalids
            ['plainaddress', false],
            ['#@%^%#$@#$@#.com', false],
            ['@domain.com', false],
            ['Joe Smith <email@domain.com>', false],
            ['email.domain.com', false],
            ['email@domain@domain.com', false],
            ['.email@domain.com', false],
            ['email.@domain.com', false],
            ['email..email@domain.com', false],
            ['あいうえお@domain.com', false],
            ['email@domain.com (Joe Smith)', false],
            ['email@domain', false],
            ['email@-domain.com', false],
            ['email@111.222.333.44444', false],
            ['email@domain..com', false],
            // Inverted from original list
            ['email@123.123.123.123', false], // Bare ip not allowed per RFC3696
            ['email@domain.web', true], // Removed, gTLDs
            // Addt'l cases not on original list
            ['email@sexy.xxx', true], // More TLDs
            ['email@myhotnew.app', true],
        ];
    }

    /**
     * @dataProvider values
     */
    public function testValidate(string $email, bool $isValid): void
    {
        $this->email->setValue($email);
        self::assertSame(
            $isValid,
            $this->email->isValid(),
            'Validation did not match expected output'
        );
    }
}
