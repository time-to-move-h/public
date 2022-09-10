<?php
declare(strict_types=1);
namespace Moviao\Http;
/**
 * Server Information
 *
 * @author MoviaoOne
 */
class ServerInfo {

    public static function getServerSuffix($domain = null) : string {
        try {
            if (null === $domain) {
                $domain = filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL);
            }
            if (null !== $domain) {
                $suffix = explode('.', $domain);
                if (! empty($suffix)) {
                    return trim(mb_strtoupper(end($suffix)));
                }
            }
        } catch (Exception $e) {
            error_log('ServerInfo : ' . $e);
        }
        return '';
    }

    public static function isSecure() : bool {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] === 443;
    }

    public static function getServerHost() : ?string {
        $host = null;
        try {
            $host = filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL);
        } catch (Exception $e) {
            error_log('getServerHost : ' . $e);
        }
        return $host;
    }

    public static function getServerURI() : ?string {
        $uri = null;
        try {
            $uri = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        } catch (Exception $e) {
            error_log('getServerURI : ' . $e);
        }
        return $uri;
    }

}