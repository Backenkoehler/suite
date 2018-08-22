<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\AlexaBot\Model\Product;

use Pyz\Client\AlexaBot\AlexaBotConfig;
use Pyz\Client\AlexaBot\Model\FileSession\FileSessionInterface;
use Spryker\Client\Catalog\CatalogClientInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\Kernel\Store;

class AlexaProduct extends AbstractPlugin implements AlexaProductInterface
{
    const VARIANT_ATTRIBUTE_NAME = 'variant';

    /**
     * @var \Pyz\Client\AlexaBot\AlexaBotConfig
     */
    private $alexaBotConfig;

    /**
     * @var \Spryker\Client\Catalog\CatalogClientInterface
     */
    private $catalogClient;

    // TODO Product-1: inject the product storage client.

    /**
     * @var \Pyz\Client\AlexaBot\Model\FileSession\FileSessionInterface
     */
    private $fileSession;

    /**
     * @param \Pyz\Client\AlexaBot\AlexaBotConfig $alexaBotConfig
     * @param \Spryker\Client\Catalog\CatalogClientInterface $catalogClient
     * TODO Product-1: inject the product storage client.
     * @param \Pyz\Client\AlexaBot\Model\FileSession\FileSessionInterface $fileSession
     */
    public function __construct(
        AlexaBotConfig $alexaBotConfig,
        CatalogClientInterface $catalogClient,
        // TODO Product-1: inject the product storage client.
        FileSessionInterface $fileSession
    ) {
        $this->alexaBotConfig = $alexaBotConfig;
        $this->catalogClient = $catalogClient;
        // TODO Product-1: inject the product storage client.
        $this->fileSession = $fileSession;
    }

    /**
     * @param string $productName
     *
     * @return string[]
     */
    public function getVariantsByProductName($productName)
    {
        $abstractProductId = $this->getAbstractIdByNameAndWriteToSession($productName);
        $productViewTransfer = $this->getStorageProduct($abstractProductId);

        return $productViewTransfer->getAttributeMap()->getSuperAttributes()[static::VARIANT_ATTRIBUTE_NAME];
    }

    /**
     * @param int $abstractProductId
     * @param string $variantName
     *
     * @return string
     */
    public function getVariantSkuByAbstractProductIdAndVariantName($abstractProductId, $variantName)
    {
        $selectedAttributes = [self::VARIANT_ATTRIBUTE_NAME => $variantName];
        $storageProductTransfer = $this->getStorageProduct($abstractProductId, $selectedAttributes);

        return $storageProductTransfer->getSku();
    }

    /**
     * @param string $productName
     *
     * @return int
     */
    private function getAbstractIdByNameAndWriteToSession($productName)
    {
        $catalogResponse = $this
            ->catalogClient
            ->catalogSuggestSearch($productName);

        $abstractProductId = $catalogResponse['suggestionByType']['product_abstract'][0]['id_product_abstract'];

        // TODO Product-2: write the abstract product ID to the file session using the FileSession (with the name alexa-product.session). You need this in order to use the ID later by the add-to-cart action.

        return $abstractProductId;
    }

    /**
     * @param $abstractProductId
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    private function getStorageProduct($abstractProductId, $selectedAttributes = [])
    {
        $localeName = Store::getInstance()->getCurrentLocale();

        $productData = $this
            ->productStorageClient
            ->getProductAbstractStorageData(
                $abstractProductId,
                $localeName
            );

        $productViewTransfer = $this
            ->productStorageClient
            ->mapProductStorageData(
                $productData,
                $localeName,
                $selectedAttributes
            );

        return $productViewTransfer;
    }
}
