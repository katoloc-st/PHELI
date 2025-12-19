<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Post;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:10|max:1000',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.min' => 'Đánh giá tối thiểu là 1 sao.',
            'rating.max' => 'Đánh giá tối đa là 5 sao.',
            'content.required' => 'Vui lòng nhập nội dung đánh giá.',
            'content.min' => 'Nội dung đánh giá phải có ít nhất 10 ký tự.',
            'content.max' => 'Nội dung đánh giá không được vượt quá 1000 ký tự.',
        ]);

        // Kiểm tra user đã đánh giá bài đăng này chưa
        $existingReview = Review::where('post_id', $post->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Bạn đã đánh giá bài đăng này rồi!');
        }

        // Không cho phép đánh giá bài đăng của chính mình
        if ($post->user_id === Auth::id()) {
            return back()->with('error', 'Bạn không thể đánh giá bài đăng của chính mình!');
        }

        Review::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'content' => $request->content,
            'status' => 'approved', // Tự động duyệt, có thể thay đổi thành 'pending' nếu cần
        ]);

        return back()->with('success', 'Đánh giá của bạn đã được gửi thành công!');
    }

    public function update(Request $request, Review $review)
    {
        // Chỉ cho phép chủ sở hữu sửa đánh giá
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:10|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Đánh giá đã được cập nhật!');
    }

    public function destroy(Review $review)
    {
        // Chỉ cho phép chủ sở hữu hoặc admin xóa đánh giá
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Đánh giá đã được xóa!');
    }
}
