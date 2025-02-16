<?php

use Orchestra\DuskUpdaterApi\OperatingSystem;

it('matches possible os', function () {
    $this->assertTrue(in_array(OperatingSystem::id(), OperatingSystem::all()));
});

it('has correct os', function () {
    $this->assertSame([
        'linux',
        'mac',
        'mac-intel',
        'mac-arm',
        'win',
    ], OperatingSystem::all());
});

it('can resolve chrome version commands', function () {
    foreach (OperatingSystem::all() as $os) {
        $commands = OperatingSystem::chromeVersionCommands($os);

        $this->assertTrue(is_array($commands), 'Commands should be an array');
        $this->assertFalse(empty($commands), 'Commands should not be empty');
    }
});

it('cant resolve invalid chrome version commands', function () {
    $this->expectException('InvalidArgumentException');
    $this->expectExceptionMessage('Unable to find commands for Operating System [window_os]');

    OperatingSystem::chromeVersionCommands('window_os');
});

it('can resolve chromedriver binary', function (string $operatingSystem, string $expected) {
    $this->assertSame($expected, OperatingSystem::chromeDriverBinary($operatingSystem));
})->with('chromeDriverBinary');

it('cant resolve invalid chromedriver binary', function () {
    $this->expectException('InvalidArgumentException');
    $this->expectExceptionMessage('Unable to find ChromeDriver binary for Operating System [window_os]');

    OperatingSystem::chromeDriverBinary('window_os');
});

it('can resolve chromedriver slug', function (string $version, string $operatingSystem, string $expected) {
    $this->assertSame($expected, OperatingSystem::chromeDriverSlug($operatingSystem, $version));
})->with('chromeDriverSlug');

it('cant resolve invalid chromedriver slug', function () {
    $this->expectException('InvalidArgumentException');
    $this->expectExceptionMessage('Unable to find ChromeDriver slug for Operating System [window_os]');

    OperatingSystem::chromeDriverSlug('window_os');
});
