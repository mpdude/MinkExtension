<?php

/*
 * This file is part of the Behat MinkExtension.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\MinkExtension\Context;

use Behat\Mink\Mink;
use Behat\Mink\WebAssert;
use Behat\Mink\Session;

/**
 * Raw Mink context for Behat BDD tool.
 * Provides raw Mink integration (without step definitions) and web assertions.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class RawMinkContext implements MinkAwareContext
{
    private $mink;
    private $minkParameters;

    /**
     * Sets Mink instance.
     *
     * @param Mink $mink Mink session manager
     *
     * @return void
     */
    public function setMink(Mink $mink): void
    {
        $this->mink = $mink;
    }

    /**
     * Returns Mink instance.
     */
    public function getMink(): Mink
    {
        if (null === $this->mink) {
            throw new \RuntimeException(
                'Mink instance has not been set on Mink context class. ' .
                'Have you enabled the Mink Extension?'
            );
        }

        return $this->mink;
    }

    /**
     * Returns the parameters provided for Mink.
     */
    public function getMinkParameters(): array
    {
        return $this->minkParameters;
    }

    /**
     * Sets parameters provided for Mink.
     *
     * @param array $parameters
     */
    public function setMinkParameters(array $parameters): void
    {
        $this->minkParameters = $parameters;
    }

    /**
     * Returns specific mink parameter.
     *
     * @param string $name
     */
    public function getMinkParameter($name): mixed
    {
        return isset($this->minkParameters[$name]) ? $this->minkParameters[$name] : null;
    }

    /**
     * Applies the given parameter to the Mink configuration. Consider that all parameters get reset for each
     * feature context.
     *
     * @param string $name  The key of the parameter
     * @param string $value The value of the parameter
     */
    public function setMinkParameter($name, $value): void
    {
        $this->minkParameters[$name] = $value;
    }

    /**
     * Returns Mink session.
     *
     * @param string|null $name name of the session OR active session will be used
     */
    public function getSession($name = null): Session
    {
        return $this->getMink()->getSession($name);
    }

    /**
     * Returns Mink session assertion tool.
     *
     * @param string|null $name name of the session OR active session will be used
     */
    public function assertSession($name = null): WebAssert
    {
        return $this->getMink()->assertSession($name);
    }

    /**
     * Visits provided relative path using provided or default session.
     *
     * @param string      $path
     * @param string|null $sessionName
     */
    public function visitPath($path, $sessionName = null): void
    {
        $this->getSession($sessionName)->visit($this->locatePath($path));
    }

    /**
     * Locates url, based on provided path.
     * Override to provide custom routing mechanism.
     *
     * @param string $path
     */
    public function locatePath($path): string
    {
        $startUrl = rtrim($this->getMinkParameter('base_url') ?? '', '/') . '/';

        return 0 !== strpos($path, 'http') ? $startUrl . ltrim($path, '/') : $path;
    }

    /**
     * Save a screenshot of the current window to the file system.
     *
     * @param string $filename Desired filename, defaults to
     *                         <browser_name>_<ISO 8601 date>_<randomId>.png
     * @param string $filepath Desired filepath, defaults to
     *                         upload_tmp_dir, falls back to sys_get_temp_dir()
     */
    public function saveScreenshot($filename = null, $filepath = null): void
    {
        // Under Cygwin, uniqid with more_entropy must be set to true.
        // No effect in other environments.
        $filename = $filename ?: sprintf('%s_%s_%s.%s', $this->getMinkParameter('browser_name'), date('c'), uniqid('', true), 'png');
        $filepath = $filepath ?: (ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir());
        file_put_contents($filepath . '/' . $filename, $this->getSession()->getScreenshot());
    }
}
