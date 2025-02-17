<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery as m;
use Orchestra\DuskUpdaterApi\HttpClient;

use function Orchestra\Sidekick\join_paths;

beforeEach(function () {
    HttpClient::$instance = m::mock(Client::class);
});

afterEach(function () {
    HttpClient::flushState();
});

it('can download a file', function () {
    HttpClient::$instance->shouldReceive('get')
        ->with('https://storage.googleapis.com/chrome-for-testing-public/113.0.5672.63/linux64/chrome-linux64.zip', m::type('Array'))
        ->once()
        ->andReturn(new Response(body: 'Downloaded'));

    HttpClient::download(
        'https://storage.googleapis.com/chrome-for-testing-public/113.0.5672.63/linux64/chrome-linux64.zip',
        $file = join_paths(__DIR__, 'tmp', 'chrome-linux.zip')
    );

    $this->assertFileExists($file);
});

it('cannot download an invalid file', function () {
    HttpClient::$instance->shouldReceive('get')
        ->with('https://storage.googleapis.com/chrome-for-testing-public/113.0.5672.63/ubuntu64/chrome-ubuntu64.zip', m::type('Array'))
        ->once()
        ->andReturn(new Response(status: 404));

    HttpClient::download(
        'https://storage.googleapis.com/chrome-for-testing-public/113.0.5672.63/ubuntu64/chrome-ubuntu64.zip',
        join_paths(__DIR__, 'tmp', 'chrome-ubuntu.zip')
    );
})->throws(Exception::class, 'Unable to download from [https://storage.googleapis.com/chrome-for-testing-public/113.0.5672.63/ubuntu64/chrome-ubuntu64.zip]');
