<?php

namespace Mateusz\ProductType\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MoveProduct
 * @package Mateusz\ProductType\Console\Command
 */
class MoveProduct extends \Symfony\Component\Console\Command\Command
{
    
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $_productRepository;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $_categoryRepository;

    /**
     * @var \Magento\Catalog\Api\CategoryLinkManagementInterface
     */
    protected $_categoryLinkManagement;

    /**
     * MoveProduct constructor.
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Catalog\Api\CategoryLinkManagementInterface $categoryLinkManagement
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Catalog\Api\CategoryLinkManagementInterface $categoryLinkManagement
    )
    {
        $this->_productRepository = $productRepository;
        $this->_categoryRepository = $categoryRepository;
        $this->_categoryLinkManagement = $categoryLinkManagement;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('mateusz:move_product')
            ->addArgument('product_id', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'product Id')
            ->setDescription('moves product with given id to its child category, requires product ID as parameter');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->moveProduct($input->getArgument('product_id'), $output);
        $output->writeln('<info>Done<info>');
    }

    /**
     * assigns product to first child category of first currently assigned category
     *
     * @param string $productId
     * @param OutputInterface $output
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function moveProduct(string $productId, OutputInterface $output)
    {
        if ($productId) {
            /** @var \Xcoding\Catalog\Model\Product $product */
            $product = $this->_productRepository->getById($productId);

            $output->writeln('Loaded product with id: ' . $productId);

            $categoryIds = $product->getCategoryIds();

            $parentCategory = $this->_categoryRepository->get($categoryIds[0]);
            $children = $parentCategory->getChildren();

            if ($children) {
                $children = explode(',', $children);
                $this->_categoryLinkManagement->assignProductToCategories($product->getSku(), [$children[0]]);
            }
        }
    }
}

