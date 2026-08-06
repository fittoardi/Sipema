<x-layouts.dashboard title="Dashboard Dosen">

	<div class="space-y-8">

		<div>

			<h1 class="text-3xl font-bold text-slate-800">Dashboard Dosen</h1>

			<p class="mt-2 text-gray-500">Selamat datang,
				<span class="font-semibold">{{ auth()->user()?->name }}</span>
			</p>

		</div>

		<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
			<div class="rounded-xl bg-white p-6 shadow">
				<p class="text-gray-500">Total Mahasiswa</p>
				<h2 class="mt-3 text-2xl font-bold">--</h2>
			</div>
			<div class="rounded-xl bg-white p-6 shadow">
				<p class="text-gray-500">Mata Kuliah</p>
				<h2 class="mt-3 text-2xl font-bold">--</h2>
			</div>
			<div class="rounded-xl bg-white p-6 shadow">
				<p class="text-gray-500">Jadwal</p>
				<h2 class="mt-3 text-2xl font-bold">--</h2>
			</div>
			<div class="rounded-xl bg-white p-6 shadow">
				<p class="text-gray-500">Aksi</p>
				<h2 class="mt-3 text-2xl font-bold">--</h2>
			</div>
		</div>

	</div>

</x-layouts.dashboard>
