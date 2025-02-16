<?php

namespace Orchestra\DuskUpdaterApi;

/**
 * Find selected ChromeDriver version URL.
 *
 * @throws \Exception
 */
function find_version_url(?string $version): string
{
    if (! $version) {
        return $this->latestVersion();
    }

    if (! ctype_digit((string) $version)) {
        return $version;
    }

    $version = (int) $version;

    if ($version < 70) {
        return $this->legacyVersions[$version];
    } elseif ($version < 115) {
        return $this->fetchChromeVersionFromUrl($version);
    }

    $milestones = $this->resolveChromeVersionsPerMilestone();

    return $milestones['milestones'][$version]['version']
        ?? throw new Exception('Could not determine the ChromeDriver version.');
}
