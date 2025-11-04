<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CollectionPoint;

class CollectionPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collectionPoints = CollectionPoint::with('posts')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('collection-points.index', compact('collectionPoints'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('collection-points.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'detailed_address' => 'required|string|max:255',
            'province' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'ward' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'address_note' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        CollectionPoint::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'detailed_address' => $request->detailed_address,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'postal_code' => $request->postal_code,
            'address_note' => $request->address_note,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('collection-points.index')
            ->with('success', 'Điểm tập kết đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CollectionPoint $collectionPoint)
    {
        // Chỉ cho phép chủ sở hữu xem
        if ($collectionPoint->user_id !== Auth::id()) {
            abort(403);
        }

        $collectionPoint->load(['posts.wasteType']);

        return view('collection-points.show', compact('collectionPoint'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CollectionPoint $collectionPoint)
    {
        // Chỉ cho phép chủ sở hữu chỉnh sửa
        if ($collectionPoint->user_id !== Auth::id()) {
            abort(403);
        }

        return view('collection-points.edit', compact('collectionPoint'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CollectionPoint $collectionPoint)
    {
        // Chỉ cho phép chủ sở hữu cập nhật
        if ($collectionPoint->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'detailed_address' => 'required|string|max:255',
            'province' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'ward' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'address_note' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $collectionPoint->update([
            'name' => $request->name,
            'detailed_address' => $request->detailed_address,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'postal_code' => $request->postal_code,
            'address_note' => $request->address_note,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('collection-points.index')
            ->with('success', 'Điểm tập kết đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CollectionPoint $collectionPoint)
    {
        // Chỉ cho phép chủ sở hữu xóa
        if ($collectionPoint->user_id !== Auth::id()) {
            abort(403);
        }

        // Kiểm tra có bài đăng nào đang sử dụng không
        if ($collectionPoint->posts()->count() > 0) {
            return redirect()->route('collection-points.index')
                ->with('error', 'Không thể xóa điểm tập kết này vì đang có bài đăng liên kết!');
        }

        $collectionPoint->delete();

        return redirect()->route('collection-points.index')
            ->with('success', 'Điểm tập kết đã được xóa!');
    }

    /**
     * API để lấy danh sách collection points của user
     */
    public function getUserCollectionPoints()
    {
        $collectionPoints = CollectionPoint::where('user_id', Auth::id())
            ->select('id', 'name', 'detailed_address', 'province', 'district', 'ward')
            ->get();

        return response()->json($collectionPoints);
    }
}
