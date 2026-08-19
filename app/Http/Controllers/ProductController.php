<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\Unit;
use App\Stock;
use App\Http\Requests\ProductRequest;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $auditService;

    public function __construct(AuditLogService $auditService)
    {
        $this->auditService = $auditService;
        $this->middleware('auth');
        $this->middleware('role:owner');
    }

    public function index()
    {
        $products = Product::with(['category', 'unit', 'stock'])
            ->orderBy('name')
            ->paginate(50);

        return view('products.index', ['products' => $products]);
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();

        return view('products.form', [
            'categories' => $categories,
            'units' => $units,
        ]);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        $product = Product::create($data);
        Stock::create(['product_id' => $product->id, 'quantity' => 0]);

        $this->auditService->log('product_create', 'Product', $product->id, null, "Created product: {$product->name}");

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();

        return view('products.form', [
            'product' => $product,
            'categories' => $categories,
            'units' => $units,
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $old = $product->toArray();
        $product->update($request->validated());

        $this->auditService->log(
            'product_update',
            'Product',
            $product->id,
            array_diff_assoc($product->toArray(), $old),
            "Updated product: {$product->name}"
        );

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->auditService->log('product_delete', 'Product', $product->id, null, "Deleted product: {$product->name}");
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function getByBarcode($barcode)
    {
        $product = Product::where('barcode', $barcode)
            ->orWhere('sku', $barcode)
            ->with(['category', 'unit', 'stock'])
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product);
    }
}
