<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Post;
use App\Models\WasteType;
use App\Models\PriceTable;
use App\Models\Transaction;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['wasteType', 'user', 'collectionPoint'])
            ->where('status', 'active');

        // Lọc theo loại phế liệu
        if ($request->waste_type_id) {
            $query->where('waste_type_id', $request->waste_type_id);
        }

        // Lọc theo loại bài đăng
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Lọc theo tỉnh
        if ($request->province) {
            $query->whereHas('collectionPoint', function($q) use ($request) {
                $q->where('province', $request->province);
            });
        }

        // Tìm kiếm
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->latest()->paginate(12);
        $wasteTypes = WasteType::where('is_active', true)->get();

        // Lấy danh sách tỉnh từ collection_points
        $provinces = \App\Models\CollectionPoint::select('province')
            ->distinct()
            ->orderBy('province')
            ->pluck('province');

        return view('posts.index', compact('posts', 'wasteTypes', 'provinces'));
    }

    public function create()
    {
        $wasteTypes = WasteType::where('is_active', true)->get();
        $user = Auth::user();
        return view('posts.create', compact('wasteTypes', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'waste_type_id' => 'required|exists:waste_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0.01',
            'collection_point_id' => 'required|exists:collection_points,id',
            'images' => 'nullable|array|max:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        // Kiểm tra collection point thuộc về user hiện tại
        $user = Auth::user();
        $collectionPoint = $user->collectionPoints()->findOrFail($request->collection_point_id);

        // Tự động xác định loại bài đăng dựa trên role
        $postType = $this->getPostTypeByRole($user->role);

        // Kiểm tra giá theo quy tắc role
        $wasteType = WasteType::findOrFail($request->waste_type_id);
        $standardPrice = $wasteType->getCurrentPrice();

        if ($standardPrice) {
            $this->validatePriceByRole($user->role, $postType, $request->price, $standardPrice->price);
        }

        // Xử lý upload ảnh
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image && $image->isValid()) {
                    $path = $image->store('posts', 'public');
                    $imagePaths[] = $path;
                }
            }
        }

        // Debug info
        \Log::info('Image upload debug:', [
            'hasFile' => $request->hasFile('images'),
            'files_count' => $request->hasFile('images') ? count($request->file('images')) : 0,
            'imagePaths' => $imagePaths
        ]);

        Post::create([
            'user_id' => Auth::id(),
            'collection_point_id' => $request->collection_point_id,
            'waste_type_id' => $request->waste_type_id,
            'title' => $request->title,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'type' => $postType,
            'status' => 'active',
            'images' => $imagePaths,
        ]);

        return redirect()->route('posts.my-posts')->with('success', 'Đăng bài thành công!');
    }

    public function show(Post $post)
    {
        // Load relationships
        $post->load(['user', 'wasteType', 'collectionPoint', 'approvedReviews.user']);

        // Get linked posts (same user, same collection point, selling status, different post)
        $linkedPosts = Post::with(['user', 'wasteType', 'collectionPoint'])
            ->linkedTo($post)
            ->latest()
            ->limit(6)
            ->get();

        // Kiểm tra user hiện tại đã đánh giá chưa
        $userReview = null;
        if (auth()->check()) {
            $userReview = $post->reviews()
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('posts.show', compact('post', 'linkedPosts', 'userReview'));
    }

    public function edit(Post $post)
    {
        // Chỉ cho phép chủ sở hữu chỉnh sửa
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $wasteTypes = WasteType::where('is_active', true)->get();
        $user = Auth::user();
        return view('posts.edit', compact('post', 'wasteTypes', 'user'));
    }

    public function update(Request $request, Post $post)
    {
        // Chỉ cho phép chủ sở hữu cập nhật
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'waste_type_id' => 'required|exists:waste_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0.01',
            'collection_point_id' => 'required|exists:collection_points,id',
            'existing_images' => 'nullable|array',
            'remove_images' => 'nullable|array',
            'new_images' => 'nullable|array|max:3',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        // Kiểm tra collection point thuộc về user
        $user = Auth::user();
        $collectionPoint = $user->collectionPoints()->findOrFail($request->collection_point_id);

        // Tự động xác định loại bài đăng dựa trên role
        $postType = $this->getPostTypeByRole($user->role);

        // Kiểm tra giá theo quy tắc role
        $wasteType = WasteType::findOrFail($request->waste_type_id);
        $standardPrice = $wasteType->getCurrentPrice();

        if ($standardPrice) {
            $this->validatePriceByRole($user->role, $postType, $request->price, $standardPrice->price);
        }

        // Xử lý ảnh
        $existingImages = $request->input('existing_images', []);
        $removeImages = $request->input('remove_images', []);
        $imagePaths = [];

        // Giữ lại ảnh cũ không bị xóa
        if ($existingImages) {
            foreach ($existingImages as $index => $imagePath) {
                if (!isset($removeImages[$index]) || $removeImages[$index] !== '1') {
                    $imagePaths[] = $imagePath;
                }
            }
        }

        // Thêm ảnh mới
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                if ($image && $image->isValid() && count($imagePaths) < 3) {
                    $path = $image->store('posts', 'public');
                    $imagePaths[] = $path;
                }
            }
        }

        // Đảm bảo không vượt quá 3 ảnh
        $imagePaths = array_slice($imagePaths, 0, 3);

        $post->update([
            'waste_type_id' => $request->waste_type_id,
            'collection_point_id' => $request->collection_point_id,
            'title' => $request->title,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'type' => $postType,
            'images' => $imagePaths,
        ]);

        return redirect()->route('posts.my-posts')->with('success', 'Cập nhật bài đăng thành công!');
    }

    public function destroy(Post $post)
    {
        // Chỉ cho phép chủ sở hữu xóa
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $post->delete();
        return redirect()->route('posts.my-posts')->with('success', 'Xóa bài đăng thành công!');
    }

    public function buy(Post $post)
    {
        // Không cho phép mua bài đăng của chính mình
        if ($post->user_id === Auth::id()) {
            abort(403, 'Không thể mua bài đăng của chính mình');
        }

        return view('posts.buy', compact('post'));
    }

    public function purchase(Request $request, Post $post)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.01|max:' . $post->quantity,
            'notes' => 'nullable|string',
        ]);

        // Tạo giao dịch
        Transaction::create([
            'post_id' => $post->id,
            'buyer_id' => Auth::id(),
            'seller_id' => $post->user_id,
            'quantity' => $request->quantity,
            'price' => $post->price,
            'total_amount' => $request->quantity * $post->price,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Cập nhật số lượng còn lại trong post
        $post->quantity -= $request->quantity;
        if ($post->quantity <= 0) {
            $post->status = 'sold';
        }
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Đặt mua thành công! Vui lòng liên hệ người bán để hoàn tất giao dịch.');
    }

    public function myPosts()
    {
        $posts = Post::with(['wasteType', 'user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('my-posts', compact('posts'));
    }

    public function getWasteTypePrice($wasteTypeId)
    {
        $wasteType = WasteType::findOrFail($wasteTypeId);
        $standardPrice = $wasteType->getCurrentPrice();

        return response()->json([
            'success' => true,
            'price' => $standardPrice ? $standardPrice->price : 0,
            'waste_type_name' => $wasteType->name
        ]);
    }

    private function getPostTypeByRole($role)
    {
        // Xác định loại bài đăng dựa trên role
        switch ($role) {
            case 'waste_company':
                return 'doanh_nghiep_xanh'; // Doanh nghiệp xả rác: bán phế liệu
            case 'scrap_dealer':
                return 'co_so_phe_lieu'; // Cơ sở phế liệu: mua phế liệu
            default:
                return 'doanh_nghiep_xanh'; // Mặc định là doanh nghiệp xanh
        }
    }

    private function validatePriceByRole($role, $type, $price, $standardPrice)
    {
        if ($type === 'doanh_nghiep_xanh') {
            // Doanh nghiệp xanh: giá <= giá chuẩn (vì họ bán phế liệu với giá thấp hơn thị trường)
            if ($price > $standardPrice) {
                throw ValidationException::withMessages([
                    'price' => "Doanh nghiệp xanh: giá bán không được vượt quá giá thị trường: " . number_format($standardPrice, 0, ',', '.') . " VNĐ/kg"
                ]);
            }
        } elseif ($type === 'co_so_phe_lieu') {
            // Cơ sở phế liệu: giá >= giá chuẩn (vì họ mua với giá cao hơn để thu hút người bán)
            if ($price < $standardPrice) {
                throw ValidationException::withMessages([
                    'price' => "Cơ sở phế liệu: giá mua phải lớn hơn hoặc bằng giá chuẩn: " . number_format($standardPrice, 0, ',', '.') . " VNĐ"
                ]);
            }
        }
    }
}
