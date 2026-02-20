{{-- Review Modal --}}
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm" id="reviewModal">
    <div class="bg-white rounded-2xl shadow-2xl ring-1 ring-slate-200 w-full max-w-md mx-4 animate-modal-in">
        {{-- Header --}}
        <div class="flex items-center justify-between p-5 pb-0">
            <h3 class="text-lg font-bold text-text-main">Write a Review</h3>
            <button type="button" onclick="document.getElementById('reviewModal').classList.replace('flex','hidden')"
                class="p-1.5 rounded-full hover:bg-slate-100 transition">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        {{-- Form --}}
        <form id="reviewForm" class="p-5 space-y-5">
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            {{-- Star Rating --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Your Rating <span
                        class="text-red-500">*</span></label>
                <div class="flex gap-1" id="starRating">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" data-star="{{ $i }}"
                            class="star-btn p-0.5 rounded transition-transform hover:scale-110 focus:outline-none">
                            <span class="material-symbols-outlined text-[32px] text-gray-300 transition-colors">star</span>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="stars" id="starInput" required>
                <p class="text-xs text-red-500 hidden mt-1" id="starError">Please select a rating</p>
            </div>

            {{-- Review Text --}}
            <div>
                <label for="reviewText" class="block text-sm font-medium text-slate-700 mb-1">Your Review <span
                        class="text-xs text-slate-400 font-normal">(optional)</span></label>
                <textarea name="review" id="reviewText" rows="4"
                    class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition resize-none"
                    placeholder="Share your experience with this product..."></textarea>
            </div>

            {{-- Guest fields --}}
            @guest
                <div class="space-y-3">
                    <div>
                        <label for="reviewName" class="block text-sm font-medium text-slate-700 mb-1">Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="reviewName" required
                            class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition"
                            placeholder="Your name">
                    </div>
                    <div>
                        <label for="reviewEmail" class="block text-sm font-medium text-slate-700 mb-1">Email <span
                                class="text-red-500">*</span></label>
                        <input type="email" name="email" id="reviewEmail" required
                            class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition"
                            placeholder="your@email.com">
                    </div>
                </div>
            @endguest

            {{-- Error / Success --}}
            <div class="hidden" id="reviewAlert">
                <div class="text-sm rounded-lg px-4 py-3" id="reviewAlertBox"></div>
            </div>

            {{-- Submit --}}
            <button type="submit" id="reviewSubmitBtn"
                class="w-full py-2.5 bg-gradient-to-r from-primary to-primary/90 text-white text-sm font-bold rounded-xl hover:shadow-lg hover:shadow-primary/25 transition-all">
                Submit Review
            </button>
        </form>
    </div>
</div>

<script>
    (function () {
        const modal = document.getElementById('reviewModal');
        const form = document.getElementById('reviewForm');
        const starBtns = document.querySelectorAll('#starRating .star-btn');
        const starInput = document.getElementById('starInput');
        const starError = document.getElementById('starError');
        const alertBox = document.getElementById('reviewAlert');
        const alertContent = document.getElementById('reviewAlertBox');

        // Close on backdrop click
        modal.addEventListener('click', e => { if (e.target === modal) modal.classList.replace('flex', 'hidden'); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('flex')) modal.classList.replace('flex', 'hidden'); });

        // Star rating interaction
        let selectedStars = 0;
        starBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                selectedStars = parseInt(btn.dataset.star);
                starInput.value = selectedStars;
                starError.classList.add('hidden');
                updateStars();
            });
            btn.addEventListener('mouseenter', () => highlightStars(parseInt(btn.dataset.star)));
            btn.addEventListener('mouseleave', () => highlightStars(selectedStars));
        });

        function highlightStars(count) {
            starBtns.forEach(btn => {
                const star = parseInt(btn.dataset.star);
                const icon = btn.querySelector('span');
                icon.classList.toggle('text-yellow-400', star <= count);
                icon.classList.toggle('text-gray-300', star > count);
            });
        }
        function updateStars() { highlightStars(selectedStars); }

        // Submit review
        form.addEventListener('submit', async e => {
            e.preventDefault();
            alertBox.classList.add('hidden');

            if (!starInput.value) {
                starError.classList.remove('hidden');
                return;
            }

            const btn = document.getElementById('reviewSubmitBtn');
            btn.disabled = true;
            btn.textContent = 'Submitting...';

            try {
                const fd = new FormData(form);
                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';
                const res = await fetch('{{ route("review.post") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: fd
                });

                if (res.status === 419) {
                    alertContent.className = 'text-sm rounded-lg px-4 py-3 bg-amber-50 border border-amber-200 text-amber-700';
                    alertContent.textContent = 'Session expired. Refreshing page...';
                    alertBox.classList.remove('hidden');
                    setTimeout(() => window.location.reload(), 1500);
                    return;
                }

                const data = await res.json();

                if (data.success) {
                    alertContent.className = 'text-sm rounded-lg px-4 py-3 bg-green-50 border border-green-200 text-green-700';
                    alertContent.textContent = data.message || 'Review submitted successfully! It will appear after approval.';
                    alertBox.classList.remove('hidden');
                    form.reset();
                    selectedStars = 0;
                    updateStars();
                    btn.textContent = 'Review Submitted ✓';
                    setTimeout(() => modal.classList.replace('flex', 'hidden'), 2500);
                } else {
                    let msg = data.error || 'Something went wrong. Please try again.';
                    if (data.errors) {
                        msg = Object.values(data.errors).flat().join(', ');
                    }
                    alertContent.className = 'text-sm rounded-lg px-4 py-3 bg-red-50 border border-red-200 text-red-700';
                    alertContent.textContent = msg;
                    alertBox.classList.remove('hidden');
                    btn.disabled = false;
                    btn.textContent = 'Submit Review';
                }
            } catch {
                alertContent.className = 'text-sm rounded-lg px-4 py-3 bg-red-50 border border-red-200 text-red-700';
                alertContent.textContent = 'Something went wrong. Please try again.';
                alertBox.classList.remove('hidden');
                btn.disabled = false;
                btn.textContent = 'Submit Review';
            }
        });

        // Global open function
        window.openReviewModal = function () {
            const btn = document.getElementById('reviewSubmitBtn');
            btn.disabled = false;
            btn.textContent = 'Submit Review';
            alertBox.classList.add('hidden');
            modal.classList.replace('hidden', 'flex');
        };
    })();
</script>