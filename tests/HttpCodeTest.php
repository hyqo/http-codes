<?php

namespace Hyqo\Http\Test;

use Hyqo\Http\HttpCode;
use Hyqo\Http\HttpCodeException;
use PHPUnit\Framework\TestCase;

class HttpCodeTest extends TestCase
{
    public function test_header(): void
    {
        $this->assertEquals('HTTP/1.1 100 Continue', HttpCode::CONTINUE->header());
        $this->assertEquals('HTTP/1.0 200 OK', HttpCode::OK->header());

        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $this->assertEquals('HTTP/1.1 200 OK', HttpCode::from(200)->header());
        $this->assertEquals('HTTP/1.1 200 OK', HttpCode::from(200)->header(1.1));
        $this->assertEquals('HTTP/1.1 200 OK', HttpCode::from(200)->header('http/1.1'));

        $this->expectException(HttpCodeException::class);
        $this->expectExceptionMessage('Expected format');
        HttpCode::from(200)->header('http/');
    }

    /** @dataProvider provideCodeWithVersion */
    public function test_version(int $code, float $version): void
    {
        $this->assertEquals($version, HttpCode::from($code)->version(), sprintf('Incorrect version for %s', $code));
    }

    public function provideCodeWithVersion(): \Generator
    {
        yield [103, 1.1];

        foreach (range(200, 204) as $code) {
            if ($code === 203) {
                yield [$code, 1.1];
            } else {
                yield [$code, 1.0];
            }
        }

        foreach (range(300, 304) as $code) {
            if ($code === 303) {
                yield [$code, 1.1];
            } else {
                yield [$code, 1.0];
            }
        }

        foreach (range(400, 404) as $code) {
            yield [$code, 1.0];
        }

        foreach (range(500, 503) as $code) {
            yield [$code, 1.0];
        }

        yield [504, 1.1];
    }

    /** @dataProvider provideCodeWithMessage */
    public function test_message(int $code, string $message): void
    {
        $this->assertEquals($message, HttpCode::from($code)->message(), sprintf('Incorrect message for %s', $code));
    }

    public function provideCodeWithMessage(): \Generator
    {
        yield [100, 'Continue'];
        yield [101, 'Switching Protocols'];
        yield [102, 'Processing'];
        yield [103, 'Early Hints'];
        yield [200, 'OK'];
        yield [201, 'Created'];
        yield [202, 'Accepted'];
        yield [203, 'Non-Authoritative Information'];
        yield [204, 'No Content'];
        yield [205, 'Reset Content'];
        yield [206, 'Partial Content'];
        yield [207, 'Multi Status'];
        yield [208, 'Already Reported'];
        yield [226, 'IM Used'];
        yield [300, 'Multiple Choices'];
        yield [301, 'Moved Permanently'];
        yield [302, 'Found'];
        yield [303, 'See Other'];
        yield [304, 'Not Modified'];
        yield [305, 'Use Proxy'];
        yield [306, 'Switch Proxy'];
        yield [307, 'Temporary Redirect'];
        yield [308, 'Permanent Redirect'];
        yield [400, 'Bad Request'];
        yield [401, 'Unauthorized'];
        yield [402, 'Payment Required'];
        yield [403, 'Forbidden'];
        yield [404, 'Not Found'];
        yield [405, 'Method Not Allowed'];
        yield [406, 'Not Acceptable'];
        yield [407, 'Proxy Authentication Required'];
        yield [408, 'Request Timeout'];
        yield [409, 'Conflict'];
        yield [410, 'Gone'];
        yield [411, 'Length Required'];
        yield [412, 'Precondition Failed'];
        yield [413, 'Payload Too Large'];
        yield [414, 'URI Too Long'];
        yield [415, 'Unsupported Media Type'];
        yield [416, 'Range Not Satisfiable'];
        yield [417, 'Expectation Failed'];
        yield [418, 'I\'m a teapot'];
        yield [421, 'Misdirected Request'];
        yield [422, 'Unprocessable Entity'];
        yield [423, 'Locked'];
        yield [424, 'Failed Dependency'];
        yield [425, 'Too Early'];
        yield [426, 'Upgrade Required'];
        yield [428, 'Precondition Required'];
        yield [429, 'Too Many Requests'];
        yield [431, 'Request Header Fields Too Large'];
        yield [451, 'Unavailable For Legal Reasons'];
        yield [500, 'Internal Server Error'];
        yield [501, 'Not Implemented'];
        yield [502, 'Bad Gateway'];
        yield [503, 'Service Unavailable'];
        yield [504, 'Gateway Timeout'];
        yield [505, 'HTTP Version Not Supported'];
        yield [506, 'Variant Also Negotiates'];
        yield [507, 'Insufficient Storage'];
        yield [508, 'Loop Detected'];
        yield [510, 'Not Extended'];
        yield [511, 'Network Authentication Required'];
    }
}
