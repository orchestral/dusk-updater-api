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
        ->with('https://googlechromelabs.github.io/chrome-for-testing/latest-versions-per-milestone-with-downloads.json', ['verify' => true])
        ->andReturn(new Response(body: file_get_contents(join_paths(__DIR__, 'snapshots', 'latest-versions-per-milestone-with-downloads.json'))));
});

afterEach(function () {
    HttpClient::flushState();
});

it('can resolve legacy download url', function (string|int $version, string $url) {
    $finder = new ChromeVersionFinder;

    expect($finder->resolveChromeDriverDownloadUrl($version, 'linux'))->toBe($url);
})->with([
    ['2.44', 'https://chromedriver.storage.googleapis.com/2.44/chromedriver_linux64.zip'],
    ['114', 'https://chromedriver.storage.googleapis.com/114/chromedriver_linux64.zip'],
    ['133.0.6943.98', 'https://storage.googleapis.com/chrome-for-testing-public/133.0.6943.98/linux64/chromedriver-linux64.zip'],
]);
