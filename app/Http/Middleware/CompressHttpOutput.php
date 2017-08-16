<?php

namespace jnanagni\Http\Middleware;

use Closure;

class CompressHttpOutput {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $response = $next($request);

        if (headers_sent() || ob_get_contents() != '')
            return $response;

        $content = $response->content();
        $contentLength = strlen($content);

        $useCompression =
            ($contentLength > 0 &&
             isset($_SERVER['HTTP_ACCEPT_ENCODING']) &&
             strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false);

        if ($useCompression) {
            if (ini_get('zlib.output_compression') == 'Off')
                ini_set('zlib.output_compression', 'On');

            $compressedContent = gzencode($content, 9);
            $contentLength = strlen($compressedContent);
            $response->header('Content-Encoding', 'gzip');
        }

        $response->header('Content-Length', $contentLength);
        return $response;
    }
}
