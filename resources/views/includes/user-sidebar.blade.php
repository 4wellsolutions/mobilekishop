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
			<li class="border-t border-slate-200 dark:border-slate-700 pt-1 mt-1">
				<form method="POST" action="{{ route('logout') }}">
					@csrf
					<button type="submit"
						class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-sm transition text-slate-600 hover:bg-red-50 hover:text-red-600 dark:text-slate-400 dark:hover:bg-red-900/20 dark:hover:text-red-400">
						<span class="material-symbols-outlined text-[18px]">logout</span> Logout
					</button>
				</form>
			</li>
		</ul>
	</div>
</aside>