<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery as m;
use Orchestra\DuskUpdaterApi\ChromeVersionFinder;
use Orchestra\DuskUpdaterApi\HttpClient;

use function Orchestra\Sidekick\join_paths;

beforeEach(function () {
    $client = m::mock(Client::class);

    HttpClient::$instance = $client;

    $client->shouldReceive('get')
        ->with('https://googlechromelabs.github.io/chrome-for-testing/last-known-good-versions-with-downloads.json', ['verify' => true])
        ->andReturn(new Response(body: file_get_contents(join_paths(__DIR__, 'snapshots', 'last-known-good-versions-with-downloads.json'))));
    $client->shouldReceive('get')
        ->with('https://googlechromelabs.github.io/chrome-for-testing/latest-versions-per-milestone-with-downloads.json', ['verify' => true])
        ->andReturn(new Response(body: file_get_contents(join_paths(__DIR__, 'snapshots', 'latest-versions-per-milestone-with-downloads.json'))));
});

afterEach(function () {
    HttpClient::flushState();
});

it('can get version', function (?string $given, string $version) {
    $finder = new ChromeVersionFinder;

    expect($finder->findVersionUrl($given))->toBe($version);
})->with([
    [null, '133.0.6943.98'],
    ['133', '133.0.6943.98'],
]);

it('can get version when given version is not a valid `ctype_digit()`', function (?string $given, string $version) {
    $finder = new ChromeVersionFinder;

    expect($finder->findVersionUrl($given))->toBe($version);
})->with([
    ['133a', '133a'],
]);

it('can get legacy version (43-69)', function (int $given, string $version) {
    $finder = new ChromeVersionFinder;

    expect($finder->findVersionUrl($given))->toBe($version);
})->with([
    [43, '2.20'],
    [50, '2.22'],
    [60, '2.33'],
    [69, '2.44'],
]);

it('can get previous version (70-114)', function (int $given, string $version) {
    HttpClient::$instance->shouldReceive('get')
        ->once()
        ->with(sprintf('https://chromedriver.storage.googleapis.com/LATEST_RELEASE_%d', $given), ['verify' => true])
        ->andReturn(new Response(body: (string) $given));

    $finder = new ChromeVersionFinder;

    expect($finder->findVersionUrl($given))->toBe($version);
})->with([
    [70, '70'],
    [80, '80'],
    [90, '90'],
    [100, '100'],
    [114, '114'],
]);
