<?php


dataset('chromeDriverBinary', function () {
    yield ['linux', 'chromedriver-linux'];
    yield ['mac', 'chromedriver-mac'];
    yield ['mac-intel', 'chromedriver-mac-intel'];
    yield ['mac-arm', 'chromedriver-mac-arm'];
    yield ['win', 'chromedriver-win.exe'];
});

dataset('chromeDriverSlug', function () {
    yield ['115.0', 'linux', 'linux64'];
    yield ['113.0', 'linux', 'linux64'];
    yield ['105.0', 'linux', 'linux64'];

    yield ['115.0', 'mac', 'mac-x64'];
    yield ['113.0', 'mac', 'mac64'];
    yield ['105.0', 'mac', 'mac64'];

    yield ['115.0', 'mac-intel', 'mac-x64'];
    yield ['113.0', 'mac-intel', 'mac64'];
    yield ['105.0', 'mac-intel', 'mac64'];

    yield ['115.0', 'mac-arm', 'mac-arm64'];
    yield ['113.0', 'mac-arm', 'mac_arm64'];
    yield ['105.0', 'mac-arm', 'mac64_m1'];

    yield ['115.0', 'win', 'win32'];
    yield ['113.0', 'win', 'win32'];
    yield ['105.0', 'win', 'win32'];
});
