<form action="{{ route('search') }}" class="my-3" method="get">
  <div class="flex items-center border-2 border-border-light rounded-full bg-white overflow-hidden max-w-md mx-auto">
    <input type="search" name="query" id="searchInput" placeholder="Search..."
      class="flex-1 px-4 py-2 text-sm border-0 outline-none bg-transparent" required>
    <button type="submit" class="px-3 py-2 bg-transparent border-0 hover:bg-surface-alt transition-colors">
      <span class="material-symbols-outlined text-xl text-text-muted">search</span>
    </button>
  </div>
</form>