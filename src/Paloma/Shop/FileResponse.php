<?php


namespace Paloma\Shop;


use GuzzleHttp\Psr7;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class FileResponse
{
    /** @var StreamInterface */
    private $stream;

    /** @var string */
    private $contentType;

    /** @var string */
    private $fileName;

    /** @var int */
    private $contentLength;

    public function __construct(StreamInterface $stream, $contentType, $fileName, $contentLength)
    {
        $this->stream = $stream;
        $this->contentType = $contentType;
        $this->fileName = $fileName;
        $this->contentLength = $contentLength;
    }

    public static function createFromResponse(ResponseInterface $response)
    {
        $contentDisposition = Psr7\parse_header($response->getHeader('Content-disposition'));
        $fileName = isset($contentDisposition[0]['filename']) ? urldecode($contentDisposition[0]['filename']) : null;
        return new FileResponse($response->getBody(), self::getSimpleHeader($response, 'Content-type'),
            $fileName, self::getSimpleHeader($response, 'Content-length'));
    }

    private static function getSimpleHeader(ResponseInterface $response, $header)
    {
        return $response->hasHeader($header) ? $response->getHeader($header)[0] : null;
    }

    /**
     * @return StreamInterface
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return int
     */
    public function getContentLength()
    {
        return $this->contentLength;
    }
}
