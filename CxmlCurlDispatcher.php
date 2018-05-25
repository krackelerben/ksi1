<?php

/**
 * CxmlCurlDispatcher sends the request document, and returns the response.
 *
 * @author Brian Newsham
 *
 */
class CxmlCurlDispatcher
{

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
    }

    /**
     * Sends a request, and returns the response.
     *
     * @param CxmlEndpoint $endpoint
     * @param CxmlDocument $request
     * @return CxmlDocument The response document.
     */
    public function dispatch($endpoint, $request)
    {
        $url = null;
        $response_class = null;

        switch (get_class($request)) {
            case 'CxmlPunchOutSetupRequest':
                $url = $endpoint->setup_url;
                $response_class = 'CxmlPunchOutSetupResponse';
                break;
            case 'CxmlOrderRequest':
                $url = $endpoint->order_url;
                $response_class = 'CxmlOrderResponse';
                break;
            default:
                throw new Exception('No case for dispatching this type of request. ('.get_class($request).')');
                break;
        }

        $cxml = KCurl::rawPost($url, $request->__toString());

        /**
         * TODO: remove debug statements
         */
        $mt = (string)microtime(true);
        file_put_contents("protected/runtime/cxml_curl_dispatch-{$mt}.txt", $cxml);

        return new $response_class($cxml);
    }
}
