<?php

namespace Symsonte\Http\Server\Request;

use Symsonte\Http\Server\GetRequest;
use Symsonte\Http\Server\OptionsRequest;
use Symsonte\Http\Server\PostRequest;
use Symsonte\Http\Server\PostRequest\FileField;
use Symsonte\Http\Server\PostRequest\StringField;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true
 * })
 *
 * @di\service({
 *     private: true
 * })
 */
class Resolver
{
    /**
     * @var GetRequest|PostRequest
     */
    private $request;

    /**
     * @return GetRequest|PostRequest
     */
    public function resolve()
    {
        if (isset($this->request)) {
            return $this->request;
        }

        $uri = $_SERVER['REQUEST_URI'];

        if (array_key_exists('HTTP_CONTENT_TYPE', $_SERVER)) {
            $_SERVER['CONTENT_TYPE'] = $_SERVER['HTTP_CONTENT_TYPE'];
        }
        $headers = $_SERVER;

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'OPTIONS':
                $request = new OptionsRequest(
                    $uri,
                    $headers
                );

                break;
            case 'GET':
                $request = new GetRequest(
                    $uri,
                    $headers
                );

                break;
            case 'POST':
                $fields = [];

                $request = new PostRequest(
                    $uri,
                    $headers,
                    $fields,
                    file_get_contents('php://input')
                );

                foreach ($_POST as $key => $value) {
                    $request->addField(new StringField(
                        $key,
                        $value
                    ));
                }

                foreach ($_FILES as $key => $value) {
                    $request->addField(new FileField(
                        $key,
                        new \SplFileObject($value['tmp_name']),
                        $value['name'],
                        $value['mime'],
                        $value['size']
                    ));
                }

                break;
            default:
                throw new \InvalidArgumentException(sprintf('Method %s not implemented yet.', $_SERVER['REQUEST_METHOD']));
        }

        $this->request = $request;

        return $request;
    }
}
