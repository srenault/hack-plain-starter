<?hh // strict

require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/app/init.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/request/init.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/prismic/hack-sdk/src/init.php';

use \Prismic\Api;

final class Prismic {

    public static function apiHome(?string $accessToken = null): Api
    {
        $endpoint = App::config('api');
        if(!is_null($endpoint)) {
            return Api::get($endpoint, $accessToken);
        } else {
            throw new Exception("Please provide prismic endpoint url in the configuration file");
        }
    }

    public static function buildContext(Request $request): Context
    {
        $permanentToken = App::config('prismic.token');
        $accessToken = $request->getCookies()->get('ACCESS_TOKEN');
        $token = !is_null($accessToken) ? $accessToken : $permanentToken;
        $token = !is_null($token) ? (string)$token : null;
        $api = self::apiHome($token);
        $givenRef = $request->getParams()->get('ref');
        $ref = !is_null($givenRef) ? $givenRef : $api->master()->getRef();
        $linkResolver = new LinkResolver((string)$ref);
        return new Context($api, (string)$ref, (string)$token, $linkResolver);
    }
}