@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="section-module penduduk">
<div class="margin-page">
	<div class="head-module left-desk mt-20 mb-20">
		<h1 class="head-border">Statistik Penduduk</h1>
		<p>Sistem digital yang berfungsi mempermudah pengelolaan data dan informasi terkait dengan kependudukan dan pendayagunaannya untuk pelayanan publik yang efektif dan efisien</p>
	</div>
	<div class="row">
		@foreach($stat_widget as $data)
		@if ($data['jumlah'] > 0 && $data['nama'] != "JUMLAH")
		<div class="col-lg-4 col-sm-12 population-data">
			<div class="population-grid box-shadow brd-10">
				<div class="population-left align-center flex-center">
					<div class="population-inner">
					{{ $data['jumlah'] }}
					</div>
				</div>
				<div class="population-right align-center flex-center">
					<div class="population-inner">{{ $data['nama'] }}</div>
				</div>
			</div>
		</div>
		@endif
		@endforeach	
	</div>
</div>
</div>


