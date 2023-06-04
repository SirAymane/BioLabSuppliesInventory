<?php

namespace proven\store\model;

class WarehouseProduct {
	private int $product_id;
	private int $warehouse_id; // Warehouse ID is used, but not displayed
	private int $stock;

	public function __construct(
		int $pid = 0,
		int $wid = 0,
		int $stk = 0
	) {
		$this->product_id = $pid;
		$this->warehouse_id = $wid;
		$this->stock = $stk;
	}

	public function getProductId(): int {
		return $this->product_id;
	}

	public function getWarehouseId() {
		return $this->warehouse_id;
	}

	public function getStock() {
		return $this->stock;
	}

	public function setProductId(int $pid = 0) {
		$this->product_id = $pid;
	}

	public function setWarehouseId(int $wid = 0) {
		$this->warehouse_id = $wid;
	}

	public function setStock(int $stock = 0) {
		$this->stock = $stock;
	}

    public function __toString() {
        return sprintf("Warehouse{[product_id=%d][warehouse_id=%s][stock=%s]}",
                $this->product_id, $this->warehouse_id, $this->stock);
    }
}
