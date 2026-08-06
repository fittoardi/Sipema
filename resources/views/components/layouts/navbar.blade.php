<header class="bg-white shadow-sm">
	<div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8 flex items-center justify-between">
		<h1 class="text-lg font-semibold">{{ $title ?? 'Dashboard' }}</h1>

		<div class="flex items-center gap-4">
			<span class="text-sm text-gray-600">{{ auth()->user()?->name }}</span>

			<form method="POST" action="{{ route('logout') }}">
				@csrf
				<button type="submit" class="text-sm text-red-600">Logout</button>
			</form>
		</div>
	</div>
</header>
