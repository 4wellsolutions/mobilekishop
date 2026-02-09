@extends('layouts.frontend')

@section('title', 'My Reviews - MKS')
@section('description', 'View and manage your product reviews.')

@section("content")
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-6">
        <a class="hover:text-primary hover:underline" href="{{ url('/') }}">Home</a>
        <span class="material-symbols-outlined text-[12px]">chevron_right</span>
        <a class="hover:text-primary hover:underline" href="{{ route('user.index') }}">My Account</a>
        <span class="material-symbols-outlined text-[12px]">chevron_right</span>
        <span class="font-medium text-slate-900 dark:text-white">Reviews</span>
    </div>

    <div class="flex flex-col gap-8 lg:flex-row">
        <!-- Sidebar -->
        <aside class="w-full shrink-0 lg:w-64">
            @include("includes.user-sidebar")
        </aside>

        <!-- Main Content -->
        <div class="flex-1 min-w-0">
            @include("includes.info-bar")

            <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">
                Reviews ({{ (Auth::user()->reviews) ? Auth::user()->reviews->count() : '0' }})
            </h1>

            <div class="space-y-4">
                @foreach($reviews as $review)
                    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-4 sm:p-6 dark:bg-slate-900 dark:ring-slate-800">
                        <div class="flex gap-4">
                            <a href="{{ route('product.show', [$review->product->brand->slug ?? '', $review->product->slug]) }}" class="shrink-0">
                                <img src="{{ $review->product->thumbnail }}" width="65" height="65"
                                    alt="{{ $review->product->name }}" class="rounded-lg object-contain" />
                            </a>
                            <div class="flex-1 min-w-0">
                                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ Str::title($review->product->name) }}</h2>
                                <div class="flex text-yellow-400 mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->stars)
                                            <span class="material-symbols-outlined text-[16px]">star</span>
                                        @else
                                            <span class="material-symbols-outlined text-[16px] text-slate-300">star</span>
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">{!! $review->review !!}</p>
                                <span class="text-xs text-slate-400">Posted: {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                                <div class="flex items-center gap-2 mt-3">
                                    @if($review->is_active)
                                        <button class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg hover:bg-blue-100 transition editReview"
                                            onclick="openReviewModal({{ $review->id }}, {{ $review->stars }})">
                                            <span class="material-symbols-outlined text-[14px]">edit</span> Edit
                                        </button>
                                        <a href="{{ route('user.review.delete', [$review->id]) }}"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 text-xs font-bold rounded-lg hover:bg-red-100 transition"
                                            onclick="return confirm('Are you sure!')">
                                            <span class="material-symbols-outlined text-[14px]">delete</span> Delete
                                        </a>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 text-amber-600 text-xs font-bold rounded-lg">
                                            <span class="material-symbols-outlined text-[14px]">schedule</span> Pending Approval
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>

    <!-- Review Edit Modal -->
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm" id="reviewEditModal">
        <div class="bg-white rounded-2xl shadow-2xl ring-1 ring-slate-200 w-full max-w-md mx-4 dark:bg-slate-900 dark:ring-slate-800">
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-800">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Edit Review</h3>
                <button onclick="document.getElementById('reviewEditModal').classList.replace('flex','hidden')"
                    class="p-1 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('user.review.update') }}" method="post" id="reviewForm" class="p-4">
                @csrf
                <input type="hidden" name="review_id" id="review_id">
                <input type="hidden" name="stars" id="stars">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Your Rating</label>
                    <div class="flex gap-1" id="ratingStars">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="material-symbols-outlined text-[28px] cursor-pointer text-slate-300 hover:text-yellow-400 transition star-btn"
                                data-star="{{ $i }}" onclick="setRating({{ $i }})">star</span>
                        @endfor
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Review</label>
                    <textarea name="review" id="review" rows="4"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('reviewEditModal').classList.replace('flex','hidden')"
                        class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-bold text-white bg-primary rounded-lg hover:bg-blue-600 transition shadow-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section("script")
    <script>
        function openReviewModal(id, stars) {
            document.getElementById('review_id').value = id;
            document.getElementById('stars').value = stars;
            var text = document.querySelector('.comment-text-' + id);
            document.getElementById('review').value = text ? text.textContent.trim() : '';
            setRating(stars);
            document.getElementById('reviewEditModal').classList.replace('hidden', 'flex');
        }
        function setRating(rating) {
            document.getElementById('stars').value = rating;
            document.querySelectorAll('.star-btn').forEach(function(star) {
                star.classList.toggle('text-yellow-400', parseInt(star.dataset.star) <= rating);
                star.classList.toggle('text-slate-300', parseInt(star.dataset.star) > rating);
            });
        }
    </script>
@endsection