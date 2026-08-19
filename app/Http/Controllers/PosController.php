<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\Sale;
use App\SaleItem;
use App\Payment;
use App\Stock;
use App\StockMovement;
use App\Customer;
use App\Receivable;
use App\Services\SaleCalculationService;
use App\Services\StockService;
use App\Services\AuditLogService;
use App\Http\Requests\SaleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PosController extends Controller
{
    protected $calculationService;
    protected $stockService;
    protected $auditService;

    public function __construct(
        SaleCalculationService $calculationService,
        StockService $stockService,
        AuditLogService $auditService
    ) {
        $this->calculationService = $calculationService;
        $this->stockService = $stockService;
        $this->auditService = $auditService;
        $this->middleware('auth');
        $this->middleware('role:owner,admin');
    }

    public function index()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $customers = Customer::where('is_active', true)->orderBy('name')->get();

        return view('pos.index', [
            'categories' => $categories,
            'customers' => $customers,
        ]);
    }

    public function getProducts($categoryId = null)
    {
        $query = Product::where('is_active', true)
            ->with(['category', 'unit', 'stock']);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->orderBy('name')->get();

        return response()->json($products);
    }

    public function calculateItem(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;
        $length = $request->length;
        $sellingPrice = $request->selling_unit_price;

        $calculation = $this->calculationService->calculateSaleItem(
            $product,
            $quantity,
            $length,
            $sellingPrice
        );

        return response()->json($calculation);
    }

    public function store(SaleRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validated();

            $invoiceNumber = 'INV-' . date('YmdHis');
            $customer = null;
            if ($validated['customer_id']) {
                $customer = Customer::findOrFail($validated['customer_id']);
            }

            $saleData = [
                'invoice_number' => $invoiceNumber,
                'customer_id' => $customer?->id,
                'user_id' => auth()->id(),
                'price_type' => $validated['price_type'],
                'sold_at' => now(),
                'status' => 'completed',
            ];

            $items = [];
            $totalNormal = 0;
            $totalDiscount = 0;
            $grandTotal = 0;
            $totalProfit = 0;

            foreach ($validated['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);

                $calculation = $this->calculationService->calculateSaleItem(
                    $product,
                    $itemData['quantity'],
                    $itemData['length'] ?? null,
                    $itemData['selling_unit_price']
                );

                $stock = $this->stockService->getStock($product->id);
                if (!$stock || $stock->quantity < $calculation['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $calculation['quantity'],
                    'length' => $calculation['length'] ?? null,
                    'total_meter' => $calculation['total_meter'] ?? null,
                    'normal_unit_price' => $calculation['normal_unit_price'],
                    'selling_unit_price' => $calculation['selling_unit_price'],
                    'discount_per_unit' => $calculation['discount_per_unit'],
                    'cost_price' => $calculation['cost_price'],
                    'subtotal_normal' => $calculation['subtotal_normal'],
                    'total_discount' => $calculation['total_discount'],
                    'subtotal' => $calculation['subtotal'],
                    'profit' => $calculation['profit'],
                ];

                $totalNormal += $calculation['subtotal_normal'];
                $totalDiscount += $calculation['total_discount'];
                $grandTotal += $calculation['subtotal'];
                $totalProfit += $calculation['profit'];
            }

            $saleData['subtotal_normal'] = round($totalNormal, 2);
            $saleData['total_discount'] = round($totalDiscount, 2);
            $saleData['grand_total'] = round($grandTotal, 2);
            $saleData['payment_amount'] = round($validated['payment_amount'], 2);

            if ($validated['payment_amount'] > $saleData['grand_total']) {
                $saleData['change_amount'] = round($validated['payment_amount'] - $saleData['grand_total'], 2);
            } elseif ($validated['payment_amount'] < $saleData['grand_total']) {
                $saleData['receivable_amount'] = round($saleData['grand_total'] - $validated['payment_amount'], 2);
            }

            $sale = Sale::create($saleData);

            foreach ($items as $itemData) {
                SaleItem::create(array_merge($itemData, ['sale_id' => $sale->id]));
                $this->stockService->reduceStock(
                    $itemData['product_id'],
                    $itemData['quantity'],
                    'sale',
                    auth()->id(),
                    $sale->invoice_number
                );
            }

            Payment::create([
                'sale_id' => $sale->id,
                'method' => $validated['payment_method'],
                'amount' => $saleData['payment_amount'],
            ]);

            if ($saleData['receivable_amount'] > 0) {
                Receivable::create([
                    'sale_id' => $sale->id,
                    'customer_id' => $customer->id,
                    'total_amount' => $saleData['grand_total'],
                    'paid_amount' => $saleData['payment_amount'],
                    'remaining_amount' => $saleData['receivable_amount'],
                    'status' => 'partial',
                ]);
            }

            $this->auditService->log('sale', 'Sale', $sale->id, null, "Created sale: {$sale->invoice_number}");

            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
                'message' => 'Sale completed successfully.',
            ]);
        });
    }

    public function receipt($saleId)
    {
        $sale = Sale::with(['items.product', 'customer', 'user'])->findOrFail($saleId);
        return view('pos.receipt', ['sale' => $sale]);
    }
}
