<!-- review div -->
<div id="reviews" class="space-y-6">
    <div class="bg-slate-50 rounded-xl p-5 shadow-sm dark:bg-slate-800/50">
        <form action="#" data-action="#" method="post" id="reviewForm">
            <input type="hidden" name="stars" id="stars">
            <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}">
            @csrf
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-3">Add a Review</h3>

            <div class="mb-3">
                <label for="rating" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Your
                    rating</label>
                <span class="rating-stars inline-flex gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <img src="{{ URL::to('/images/icons/star.png') }}" id="star-{{ $i }}"
                            class="stars cursor-pointer hover:scale-110 transition-transform" alt="star" width="24"
                            height="24" data-value="{{ $i }}">
                    @endfor
                </span>
                <div class="text-red-500 text-sm mt-1 hidden" id="stars-error">Please provide a rating.</div>
            </div>

            @auth
                <div class="mb-3">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Your Review</label>
                    <textarea cols="5" name="review" rows="6" id="review"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white"></textarea>
                    <small class="text-blue-500 text-xs">Do not share any personal information.</small>
                    <div class="text-red-500 text-sm mt-1 hidden" id="review-error">Please provide a review.</div>
                </div>

                <button type="submit"
                    class="px-5 py-2 bg-primary text-white text-sm font-bold rounded-lg hover:bg-blue-600 transition submitReview">Submit</button>
            @endauth
        </form>
    </div>

    @if(count($product->reviews))
        <div class="space-y-4">
            @foreach($product->reviews()->orderBy("created_at", "DESC")->get() as $review)
                <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-4 dark:bg-slate-900 dark:ring-slate-800">
                    <div class="flex gap-3 mb-3">
                        <img src="{{ URL::to('/images/profile.png') }}" class="rounded-full size-12 object-cover"
                            alt="avatar" />
                        <div>
                            <h5 class="text-sm font-bold text-slate-900 dark:text-white">
                                @if($review->user)
                                    {{ $review->user->name }}
                                @else
                                    {{ $review->name }}
                                @endif
                            </h5>
                            <p class="text-xs text-slate-500">Posted:
                                {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</p>
                            <div class="flex gap-0.5 mt-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review->stars)
                                        <span class="material-symbols-outlined text-amber-400 text-[18px]"
                                            style="font-variation-settings: 'FILL' 1">star</span>
                                    @else
                                        <span class="material-symbols-outlined text-slate-300 text-[18px]">star</span>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                        {!! $review->review !!}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
<!-- add review div -->