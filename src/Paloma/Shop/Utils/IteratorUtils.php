<?php


namespace Paloma\Shop\Utils;


use Paloma\Shop\Catalog\CatalogClientInterface;

class IteratorUtils
{
    /**
     * Returns a generator which allows for deep iteration of all categories
     * within a single-rooted tree. Breadth-first iteration.
     *
     * @param array $category The root category to start the iteration from.
     * @return \Generator
     */
    public static function iterateCategory(array $category)
    {
        yield $category;

        if (!empty($category['subCategories'])) {
            foreach ($category['subCategories'] as $subCategory) {
                yield from self::iterateCategory($subCategory);
            }
        }
    }

    /**
     * Return a generator which allows for deep iteration of all categories
     * within a multi-rooted tree. Breadth-first iteration.
     *
     * @param array $categories An array of root categories.
     * @return \Generator
     */
    public static function iterateCategories(array $categories)
    {
        foreach ($categories as $category) {
            yield from self::iterateCategory($category);
        }
    }

    /**
     * Return a generator which allows for iteration of products from a search
     * query, including eventual results hidden in subsequent pages of a search
     * result.
     *
     * @param CatalogClientInterface $catalog
     * @param array|null $search Optional parameters to the Paloma catalog search service.
     * @param bool $iterateThroughAllPages Whether to iterate through all products
     * including those potentially hidden on subsequent result pages.
     * @param int $pageSize The page size to use to search for products.
     * You only should use this if you are not interested in all pages.
     * @return \Generator
     */
    public static function iterateProducts(
        CatalogClientInterface $catalog,
        array $search = null,
        $iterateThroughAllPages = true,
        $pageSize = 100)
    {
        if ($search === null) {
            $search = [];
        }
        $search['size'] = $pageSize;
        $search['page'] = 0;

        do {
            $result = $catalog->search($search);

            foreach ($result['content'] as $product) {
                yield $product;
            }

            $search['page']++;
        } while ($iterateThroughAllPages && !$result['last']);
    }

}
