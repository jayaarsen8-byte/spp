<?php

namespace App\Services;

use App\Product;
use App\Stock;
use App\StockMovement;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function getStock($productId)
    {
        return Stock::where('product_id', $productId)->first();
    }

    public function addStock($productId, $quantity, $type, $userId, $reference = null, $note = null)
    {
        return DB::transaction(function () use ($productId, $quantity, $type, $userId, $reference, $note) {
            $stock = Stock::lockForUpdate()->firstOrCreate(
                ['product_id' => $productId],
                ['quantity' => 0]
            );

            $stock->quantity += $quantity;
            $stock->save();

            StockMovement::create([
                'product_id' => $productId,
                'user_id' => $userId,
                'type' => $type,
                'quantity' => $quantity,
                'reference' => $reference,
                'note' => $note,
            ]);

            return $stock;
        });
    }

    public function reduceStock($productId, $quantity, $type, $userId, $reference = null, $note = null)
    {
        return $this->addStock($productId, -$quantity, $type, $userId, $reference, $note);
    }

    public function validateStockAvailable($productId, $quantity)
    {
        $stock = $this->getStock($productId);
        return $stock && $stock->quantity >= $quantity;
    }
}
