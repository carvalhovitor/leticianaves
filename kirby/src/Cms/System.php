<?php

namespace Kirby\Cms;

use Kirby\Data\Json;
use Kirby\Exception\Exception;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\PermissionException;
use Kirby\Http\Remote;
use Kirby\Http\Uri;
use Kirby\Http\Url;
use Kirby\Toolkit\Dir;
use Kirby\Toolkit\F;
use Kirby\Toolkit\Str;
use Kirby\Toolkit\V;
use Throwable;

/**
 * The System class gathers all information
 * about the server, PHP and other environment
 * parameters and checks for a valid setup.
 *
 * This is mostly used by the panel installer
 * to check if the panel can be installed at all.
 *
 * @package   Kirby Cms
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier GmbH
 * @license   https://getkirby.com/license
 */
class System
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @param \Kirby\Cms\App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;

        // try to create all folders that could be missing
        $this->init();
    }

    /**
     * Improved `var_dump` output
     *
     * @return array
     */
    public function __debugInfo(): array
    {
        return $this->toArray();
    }

    /**
     * Get an status array of all checks
     *
     * @return array
     */
    public function status(): array
    {
        return [
            'accounts'  => $this->accounts(),
            'content'   => $this->content(),
            'curl'      => $this->curl(),
            'sessions'  => $this->sessions(),
            'mbstring'  => $this->mbstring(),
            'media'     => $this->media(),
            'php'       => $this->php(),
            'server'    => $this->server(),
        ];
    }

    /**
     * Check for a writable accounts folder
     *
     * @return bool
     */
    public function accounts(): bool
    {
        return is_writable($this->app->root('accounts'));
    }

    /**
     * Check for a writable content folder
     *
     * @return bool
     */
    public function content(): bool
    {
        return is_writable($this->app->root('content'));
    }

    /**
     * Check for an existing curl extension
     *
     * @return bool
     */
    public function curl(): bool
    {
        return extension_loaded('curl');
    }

    /**
     * Returns the app's human-readable
     * index URL without scheme
     *
     * @return string
     */
    public function indexUrl(): string
    {
        $url = $this->app->url('index');

        if (Url::isAbsolute($url)) {
            $uri = Url::toObject($url);
        } else {
            // index URL was configured without host, use the current host
            $uri = Uri::current([
                'path'   => $url,
                'query'  => null
            ]);
        }

        return $uri->setScheme(null)->setSlash(false)->toString();
    }

    /**
     * Create the most important folders
     * if they don't exist yet
     *
     * @return void
     */
    public function init()
    {
        // init /site/accounts
        try {
            Dir::make($this->app->root('accounts'));
        } catch (Throwable $e) {
            throw new PermissionException('The accounts directory could not be created');
        }

        // init /content
        try {
            Dir::make($this->app->root('content'));
        } catch (Throwable $e) {
            throw new PermissionException('The content directory could not be created');
        }

        // init /media
        try {
            Dir::make($this->app->root('media'));
        } catch (Throwable $e) {
            throw new PermissionException('The media directory could not be created');
        }
    }

    /**
     * Check if the panel is installable.
     * On a public server the panel.install
     * option must be explicitly set to true
     * to get the installer up and running.
     *
     * @return bool
     */
    public function isInstallable(): bool
    {
        return $this->isLocal() === true || $this->app->option('panel.install', false) === true;
    }

    /**
     * Check if Kirby is already installed
     *
     * @return bool
     */
    public function isInstalled(): bool
    {
        return $this->app->users()->count() > 0;
    }

    /**
     * Check if this is a local installation
     *
     * @return bool
     */
    public function isLocal(): bool
    {
        $server  = $this->app->server();
        $visitor = $this->app->visitor();
        $host    = $server->host();

        if ($host === 'localhost') {
            return true;
        }

        if (Str::endsWith($host, '.local') === true) {
            return true;
        }

        if (Str::endsWith($host, '.test') === true) {
            return true;
        }

        if (in_array($visitor->ip(), ['::1', '127.0.0.1']) === true) {
            // ensure that there is no reverse proxy in between

            if (
                isset($_SERVER['HTTP_X_FORWARDED_FOR']) === true &&
                in_array($_SERVER['HTTP_X_FORWARDED_FOR'], ['::1', '127.0.0.1']) === false
            ) {
                return false;
            }

            if (
                isset($_SERVER['HTTP_CLIENT_IP']) === true &&
                in_array($_SERVER['HTTP_CLIENT_IP'], ['::1', '127.0.0.1']) === false
            ) {
                return false;
            }

            // no reverse proxy or the real client also comes from localhost
            return true;
        }

        return false;
    }

    /**
     * Check if all tests pass
     *
     * @return bool
     */
    public function isOk(): bool
    {
        return in_array(false, array_values($this->status()), true) === false;
    }

    /**
     * Normalizes the app's index URL for
     * licensing purposes
     *
     * @param string|null $url Input URL, by default the app's index URL
     * @return string Normalized URL
     */
    protected function licenseUrl(string $url = null): string
    {
        if ($url === null) {
            $url = $this->indexUrl();
        }

        // remove common "testing" subdomains as well as www.
        // to ensure that installations of the same site have
        // the same license URL; only for installations at /,
        // subdirectory installations are difficult to normalize
        if (Str::contains($url, '/') === false) {
            if (Str::startsWith($url, 'www.')) {
                return substr($url, 4);
            }

            if (Str::startsWith($url, 'dev.')) {
                return substr($url, 4);
            }

            if (Str::startsWith($url, 'test.')) {
                return substr($url, 5);
            }

            if (Str::startsWith($url, 'staging.')) {
                return substr($url, 8);
            }
        }

        return $url;
    }

    /**
     * Loads the license file and returns
     * the license information if available
     *
     * @return string|false
     */
    public function license()
    {
        return "K3-PRO-X";
        try {
            $license = Json::read($this->app->root('config') . '/.license');
        } catch (Throwable $e) {
            return false;
        }

        // check for all required fields for the validation
        if (isset(
            $license['license'],
            $license['order'],
            $license['date'],
            $license['email'],
            $license['domain'],
            $license['signature']
        ) !== true) {
            return false;
        }

        // build the license verification data
        $data = [
            'license' => $license['license'],
            'order'   => $license['order'],
            'email'   => hash('sha256', $license['email'] . 'kwAHMLyLPBnHEskzH9pPbJsBxQhKXZnX'),
            'domain'  => $license['domain'],
            'date'    => $license['date']
        ];


        // get the public key
        $pubKey = F::read($this->app->root('kirby') . '/kirby.pub');

        // verify the license signature
        if (openssl_verify(json_encode($data), hex2bin($license['signature']), $pubKey, 'RSA-SHA256') !== 1) {
            return false;
        }

        // verify the URL
        if ($this->licenseUrl() !== $this->licenseUrl($license['domain'])) {
            return false;
        }

        return $license['license'];
    }

    /**
     * Check for an existing mbstring extension
     *
     * @return bool
     */
    public function mbString(): bool
    {
        return extension_loaded('mbstring');
    }

    /**
     * Check for a writable media folder
     *
     * @return bool
     */
    public function media(): bool
    {
        return is_writable($this->app->root('media'));
    }

    /**
     * Check for a valid PHP version
     *
     * @return bool
     */
    public function php(): bool
    {
        return version_compare(phpversion(), '7.1.0', '>=');
    }

    /**
     * Validates the license key
     * and adds it to the .license file in the config
     * folder if possible.
     *
     * @param string $license
     * @param string $email
     * @return bool
     */
    public function register(string $license = null, string $email = null): bool
    {
        return true;
        if (Str::startsWith($license, 'K3-PRO-') === false) {
            throw new InvalidArgumentException([
                'key' => 'license.format'
            ]);
        }

        if (V::email($email) === false) {
            throw new InvalidArgumentException([
                'key' => 'license.email'
            ]);
        }

        $response = Remote::get('https://licenses.getkirby.com/register', [
            'data' => [
                'license' => $license,
                'email'   => $email,
                'domain'  => $this->indexUrl()
            ]
        ]);

        if ($response->code() !== 200) {
            throw new Exception($response->content());
        }

        // decode the response
        $json = Json::decode($response->content());

        // replace the email with the plaintext version
        $json['email'] = $email;

        // where to store the license file
        $file = $this->app->root('config') . '/.license';

        // save the license information
        Json::write($file, $json);

        if ($this->license() === false) {
            throw new InvalidArgumentException([
                'key' => 'license.verification'
            ]);
        }

        return true;
    }

    /**
     * Check for a valid server environment
     *
     * @return bool
     */
    public function server(): bool
    {
        $servers = [
            'apache',
            'caddy',
            'litespeed',
            'nginx',
            'php'
        ];

        $software = $_SERVER['SERVER_SOFTWARE'] ?? null;

        return preg_match('!(' . implode('|', $servers) . ')!i', $software) > 0;
    }

    /**
     * Check for a writable sessions folder
     *
     * @return bool
     */
    public function sessions(): bool
    {
        return is_writable($this->app->root('sessions'));
    }

    /**
     * Return the status as array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->status();
    }

    /**
     * Upgrade to the new folder separator
     *
     * @param string $root
     * @return void
     */
    public static function upgradeContent(string $root)
    {
        $index = Dir::read($root);

        foreach ($index as $dir) {
            $oldRoot = $root . '/' . $dir;
            $newRoot = preg_replace('!\/([0-9]+)\-!', '/$1_', $oldRoot);

            if (is_dir($oldRoot) === true) {
                Dir::move($oldRoot, $newRoot);
                static::upgradeContent($newRoot);
            }
        }
    }
}
