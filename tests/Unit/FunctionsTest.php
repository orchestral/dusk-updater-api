<?php

use function Orchestra\DuskUpdaterApi\join_paths;

it('can resolve path using `join_paths()`', function () {
    $this->assertSame(
        realpath(__DIR__.'/FunctionsTest.php'),
        join_paths(__DIR__, 'FunctionsTest.php'),
    );

    $this->assertSame(
        realpath(__DIR__.'/FunctionsTest.php'),
        join_paths(__DIR__, '', 'FunctionsTest.php'),
    );
});
