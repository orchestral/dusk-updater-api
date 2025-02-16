<?php

use function Orchestra\DuskUpdaterApi\join_paths;

it('can resolve path using `join_paths()`', function () {
    expect(realpath(__DIR__.'/FunctionsTest.php'))
        ->toBe(join_paths(__DIR__, 'FunctionsTest.php'));

    expect(realpath(__DIR__.'/FunctionsTest.php'))
        ->toBe(join_paths(__DIR__, '', 'FunctionsTest.php'));
});
