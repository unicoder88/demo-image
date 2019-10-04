<?php
namespace Demo\Image\Controller\Thumbnail;

use Magento\Framework\Exception\LocalizedException;

/**
 * Generates product thumbnail
 */
class Make extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Catalog\Helper\Image
     */
    private $imageHelper;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->imageHelper = $imageHelper;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        /** @var \Magento\Framework\Controller\Result\Raw $result */
        $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW);

        try {
            $product = $this->productRepository->getById($id);
            $url = $this->imageHelper
                ->init($product, 'product_small_image')
                ->getUrl();
            $result->setContents("<img src=\"$url\" />");
        } catch (LocalizedException $e) {
            $result->setContents('There was an error - ' . $e->getMessage());
        }

        return $result;
    }
}
