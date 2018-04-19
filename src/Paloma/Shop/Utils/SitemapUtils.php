<?php


namespace Paloma\Shop\Utils;


use Paloma\Shop\Catalog\CatalogClientInterface;
use Psr\Log\LoggerInterface;

class SitemapUtils
{

    /**
     * Generate an XML sitemap document containing categories from a given
     * Paloma catalog service. If not all categories shall be included then
     * optionally a set of root categories may be provided.
     *
     * @param CatalogClientInterface $catalog
     * @param callable $urlGenerator A function(<category>) which has to return
     * an URL provided a category input.
     * @param array|null $rootCategories A set of root categories or null if all
     * root categories shall be taken into account.
     * @param \SimpleXMLElement|null $document A potential document to append the
     * sitemap entries to. If null then a new document will be generated.
     * @param LoggerInterface|null $logger
     * @return \SimpleXMLElement The sitemap XML document.
     */
    public static function generateXmlSitemapForCategories(
        CatalogClientInterface $catalog,
        callable $urlGenerator,
        array $rootCategories = null,
        \SimpleXMLElement $document = null,
        LoggerInterface $logger = null)
    {
        if ($rootCategories === null) {
            $categories = $catalog->categories(100, false);
        } else {
            $categories = [];
            foreach ($rootCategories as $rootCategory) {
                foreach ($catalog->category($rootCategory, 100) as $category) {
                    $categories[] = $category;
                }
            }
        }

        $document = $document ?? self::newSitemapDocument();

        foreach (IteratorUtils::iterateCategories($categories) as $category) {
            try {
                $url = call_user_func($urlGenerator, $category);
            } catch (\Exception $e) {
                if ($logger) {
                    $logger->error('generateXmlSitemapForCategories: unable to generate URL for category',
                        ['exception' => $e]);
                }
                continue;
            }

            $element = $document->addChild('url');
            $element->{'loc'} = $url;
        }

        return $document;
    }

    /**
     * Generate an XML sitemap document containing products from a given
     * Paloma catalog service.
     *
     * @param CatalogClientInterface $catalog
     * @param callable $urlGenerator A function(<category>) which has to return
     * an URL provided a category input.
     * @param \SimpleXMLElement|null $document A potential document to append the
     * sitemap entries to. If null then a new document will be generated.
     * @param LoggerInterface|null $logger
     * @return \SimpleXMLElement The sitemap XML document.
     */
    public static function generateXmlSitemapForProducts(
        CatalogClientInterface $catalog,
        callable $urlGenerator,
        \SimpleXMLElement $document = null,
        LoggerInterface $logger = null)
    {
        $document = $document ?? self::newSitemapDocument();

        foreach (IteratorUtils::iterateProducts($catalog) as $product) {
            try {
                $url = call_user_func($urlGenerator, $product);
            } catch (\Exception $e) {
                if ($logger) {
                    $logger->error('generateXmlSitemapForProducts: unable to generate URL for product',
                        ['exception' => $e]);
                }
                continue;
            }

            $element = $document->addChild('url');
            $element->{'loc'} = $url;
        }

        return $document;
    }

    /**
     * Create a new empty sitemap document.
     * 
     * @return \SimpleXMLElement
     */
    public static function newSitemapDocument()
    {
        return simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?>' .
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" />');
    }

}
