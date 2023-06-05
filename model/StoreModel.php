<?php

namespace proven\store\model;

require_once 'model/persist/UserDao.php';
require_once 'model/persist/CategoryDao.php';
require_once 'model/persist/ProductDao.php';
require_once 'model/persist/WarehouseDao.php';


require_once 'model/persist/WarehouseProductDao.php';
require_once 'model/User.php';


require_once 'model/Category.php';
require_once 'model/Product.php';
require_once 'model/Warehouse.php';

use Exception;
use proven\store\model\persist\CategoryDao;
use proven\store\model\persist\ProductDao;
use proven\store\model\persist\UserDao;
use proven\store\model\persist\WarehouseDao;
use proven\store\model\persist\WarehouseProductDao;

//use proven\store\model\User;

/**
 * Service class to provide data.
 * @author ProvenSoft
 */
class StoreModel
{


	public function __construct()
	{
	}

	public function findAllUsers(): array
	{
		$dbHelper = new UserDao();
		return $dbHelper->selectAll();
	}

	public function findUsersByRole(string $role): array
	{
		$dbHelper = new UserDao();
		return $dbHelper->selectWhere("role", $role);
	}

	public function findCategoryById(int $id): ?Category
	{
		$dbHelper = new CategoryDao();
		$c = new Category($id);
		return $dbHelper->select($c);
	}

	/**
	 * This function is added to send a personalized error message if the cateory is not found.
	 *
	 * @param integer $categoryId
	 * @return boolean
	 */
	public function categoryExists(int $categoryId): bool {
		$category = $this->findCategoryById($categoryId);
		return $category !== null;
	}
	
	


	public function addUser(User $user): ?int
	{
		$dbHelper = new UserDao();
		return $dbHelper->insert($user);
	}

	public function addProduct(Product $product): ?int
	{
		$dbHelper = new ProductDao();
		return $dbHelper->insert($product);
	}

	public function fetchToProduct($productId) {
		$dbHelper = new ProductDao();
		return $dbHelper->fetchToProduct($productId);
	}
	

	public function addWarehouse(Warehouse $warehouse): ?int
	{
		$dbHelper = new WarehouseDao();
		return $dbHelper->insert($warehouse);
	}

	public function modifyUser(User $user): ?int
	{
		$dbHelper = new UserDao();
		return $dbHelper->update($user);
	}

	public function modifyCategory(Category $category): int
	{
		$dbHelper = new CategoryDao();
		return $dbHelper->update($category);
	}

	public function modifyWarehouse(Warehouse $warehouse): ?int
	{
		$dbHelper = new WarehouseDao();
		return $dbHelper->update($warehouse);
	}

	public function modifyProduct(Product $product): ?int
{
    // Retrieve the existing product from the database to get the correct category ID
    $existingProduct = $this->findProductById($product->getId());

    if (!is_null($existingProduct)) {
        // Set the category ID of the existing product to the new product
        // $product->setCategoryId($existingProduct->getCategoryId());

        // Update the product in the database
        $dbHelper = new ProductDao();
        return $dbHelper->update($product);
    }

    return null;
}

	public function addCategory(Category $category): ?int
	{
		$dbHelper = new CategoryDao();
		return $dbHelper->insert($category);
	}




	public function removeUser(User $user): int
	{
		$dbHelper = new UserDao();
		return $dbHelper->delete($user);
	}

	public function removeCategory(Category $category): ?int
	{
		$dbHelper = new CategoryDao();
		return $dbHelper->delete($category);
	}

	public function removeProduct(Product $product): ?int
	{
		$dbHelper = new ProductDao();
		return $dbHelper->delete($product);
	}

	public function removeWarehouse(Warehouse $warehouse): ?int
	{
		$dbHelper = new WarehouseDao();
		return $dbHelper->delete($warehouse);
	}

	public function findUserById(int $id): ?User
	{
		$dbHelper = new UserDao();
		$u = new User($id);
		return $dbHelper->select($u);
	}

	public function findAllCategories(): ?array
	{
		$dbHelper = new CategoryDao();
		return $dbHelper->selectAll();
	}

	public function findAllProducts(): ?array
	{
		$dbHelper = new ProductDao();
		return $dbHelper->selectAll();
	}

	public function findProductById(int $id): ?Product
	{
		$dbHelper = new ProductDao();
		$u = new Product($id);
		return $dbHelper->select($u);
	}

	public function findProductByCode(int $code): ?Product
	{
		$dbHelper = new ProductDao();
		$u = new Product($code);
		return $dbHelper->select($u);
	}

	public function findProductsByCategory(Category $category): ?array
	{
		$dbHelper = new ProductDao();
		return $dbHelper->selectByCategory($category);
	}


	public function findWarehouseById(int $id): ?Warehouse // Setting ? as data type for null values
	{
		$dbHelper = new WarehouseDao();
		$p = new Warehouse($id);
		return $dbHelper->select($p);
	}

	public function findAllWarehouses(): array
	{
		$dbHelper = new WarehouseDao();
		return $dbHelper->selectAll();
	}

	public function findStocksByWarehouse(Warehouse $prod): array
	{
		$dbHelper = new WarehouseProductDao();
		try {
			$result = $dbHelper->selectByWarehouseId($prod);
		} catch (Exception $e) {
			throw $e;
		}
		return $result;
	}

	public function findStocksByProduct(Product $prod): array
	{
		$dbHelper = new WarehouseProductDao();
		try { // Searches by the product_id field and the input
			$result = $dbHelper->selectByProductId($prod);
		} catch (Exception $e) {
			throw $e;
		}
		return $result; // Returns warehouse product object
	}

	public function removeStockByProduct(Product $stock): int
	{
		$dbHelper = new WarehouseProductDao();
		return $dbHelper->removeByPid($stock);
	}

	public function findUserByUsernameAndPassword(string $username, string $password)
	{
		$dbHelper = new UserDao();
		return $dbHelper->selectWhereUsernameAndPassword($username,  $password);
	}

	public function updateStock(WarehouseProduct $warehouseProduct): int
	{
		$dbHelper = new WarehouseProductDao();
		return $dbHelper->updateStock($warehouseProduct);
	}

}