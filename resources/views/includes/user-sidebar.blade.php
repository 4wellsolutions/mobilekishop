<aside class="sticky top-24">
	<div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-4 dark:bg-slate-900 dark:ring-slate-800">
		<h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 uppercase tracking-wide">My Account</h3>
		<ul class="space-y-1">
			<li>
				<a href="{{ route('user.index') }}"
					class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition {{ Request::routeIs('user.index') ? 'bg-primary/10 text-primary font-bold' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
					<span class="material-symbols-outlined text-[18px]">person</span> Profile
				</a>
			</li>
			<li>
				<a href="{{ route('user.review') }}"
					class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition {{ Request::routeIs('user.review') ? 'bg-primary/10 text-primary font-bold' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
					<span class="material-symbols-outlined text-[18px]">rate_review</span> Reviews
				</a>
			</li>
			<li>
				<a href="{{ route('user.wishlist') }}"
					class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition {{ Request::routeIs('user.wishlist') ? 'bg-primary/10 text-primary font-bold' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
					<span class="material-symbols-outlined text-[18px]">favorite</span> Wishlist
				</a>
			</li>
		</ul>
	</div>
</aside>