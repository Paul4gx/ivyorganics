<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
        protected $imageManager;

    public function __construct()
    {
        try {
            $this->imageManager = new ImageManager(new Driver());
        } catch (\Exception $e) {
            Log::error('Failed to initialize ImageManager: ' . $e->getMessage());
            throw $e;
        }
    }

    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|in:face,body,hair,wellness,package',
            'description' => 'required|string',
            'ingredients' => 'nullable|string',
            'perfect_for' => 'nullable|string',
            'image' => 'required|image|max:2048',
            'is_active' => 'required|boolean',
            'texture' => 'nullable|string',
            'color' => 'nullable|string',
            'scent' => 'nullable|string',
            'size' => 'nullable|string',
            'tagline' => 'required|string',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Create unique .webp filename
            $filename = time() . '.webp';
            $relativePath = 'products/' . $filename;
            $fullPath = storage_path('app/public/' . $relativePath);

            // Use the injected ImageManager with GD driver
            $this->imageManager->read($image)
                ->cover(500, 500)
                ->toWebp(80)
                ->save($fullPath);

            $validated['image'] = $relativePath;
        }
        Product::create($validated);



        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|in:face,body,hair,wellness,package',
            'description' => 'required|string',
            'ingredients' => 'nullable|string',
            'perfect_for' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'required|boolean',
            'texture' => 'nullable|string',
            'color' => 'nullable|string',
            'scent' => 'nullable|string',
            'size' => 'nullable|string',
            'tagline' => 'required|string',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $request->file('image');
            $filename = time() . '.webp';
            $relativePath = 'products/' . $filename;
            $fullPath = storage_path('app/public/' . $relativePath);

            // Resize, compress, and save using injected ImageManager
            $this->imageManager->read($image)
                ->cover(500, 500)
                ->toWebp(80)
                ->save($fullPath);

            $validated['image'] = $relativePath;
        }

        $product->update($validated);



        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
} 