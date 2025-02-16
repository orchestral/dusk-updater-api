<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery as m;
use Orchestra\DuskUpdaterApi\ChromeVersionFinder;
use Orchestra\DuskUpdaterApi\HttpClient;

use function Orchestra\DuskUpdaterApi\join_paths;

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

it('can get legacy versions', function (int $milestone, string $version) {
    $finder = new ChromeVersionFinder;

    expect($finder->findVersionUrl($milestone))->toBe($version);
})->with([
    [60, '2.33'],
]);

