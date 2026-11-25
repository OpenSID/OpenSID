@push('styles')
<style>
	.dropdown-submenu {
		position: relative;
	}

	.dropdown-submenu>.dropdown-menu {
		top: 0;
		left: 100%;
		margin-top: -1px;
		display: none;
	}

	.dropdown-submenu:hover>.dropdown-menu {
		display: block;
	}
</style>
@endpush
<div class="mobilemenu-container">
	<div class="withscroll">
		<div class="mlr-20">
			<form method=get action="{{ ci_route('') }}">
				<div class="formsearch r-flex" style="margin-bottom:20px;">
					<input type="text" name="cari" maxlength="50" class="form-control" value="{{ $cari }}" placeholder="Cari Artikel">
					<button type="submit" class="btn btn-success btn-sm" style="margin:0;"><i class="fa fa-search" style="opacity:0.6;"></i></button>
				</div>
			</form>
			<div class="mobile-menu">
				<nav class="navbar" style="font-size:16px !important;">
					<ul style="font-size:16px !important;">
						<li><a href="{{ ci_route('') }}">Beranda</a></li>
						@foreach (menu_tema() as $data)
							<li class="{{ count($data['childrens'] ?? []) > 0 ? 'dropdown' : '' }}" style="font-size:16px !important;">
								<a href="{{ $data['link_url'] }}" class="{{ count($data['childrens'] ?? []) > 0 ? 'dropdown-toggle' : '' }}" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
									{{ $data['nama'] }} {!! count($data['childrens'] ?? []) > 0 ? "<span class='caret'></span>" : '' !!}
								</a>
								@if (count($data['childrens'] ?? []) > 0)
									<ul class="dropdown-menu" style="background:rgba(0,0,0,0.2);margin-top:10px;padding:15px 20px 8px;font-size:16px !important;">
										@foreach ($data['childrens'] as $submenu)
											<li class="{{ count($submenu['childrens'] ?? []) > 0 ? 'dropdown-submenu' : '' }}" style="font-size:16px !important;">
												<a href="{{ $submenu['link_url'] }}" style="font-size:16px !important;" class="{{ count($submenu['childrens'] ?? []) > 0 ? 'dropdown-toggle' : '' }}" 
												{{ count($submenu['childrens'] ?? []) > 0 ? 'data-toggle="dropdown"' : '' }} role="button" aria-haspopup="true" aria-expanded="false">
													{{ $submenu['nama'] }} {!! count($submenu['childrens'] ?? []) > 0 ? "<span class='caret'></span>" : '' !!}
												</a>
												@if (count($submenu['childrens'] ?? []) > 0)
													<ul class="dropdown-menu" style="background:rgba(0,0,0,0.2);font-size:16px !important;">
														@foreach ($submenu['childrens'] as $subsubmenu)
															<li>
																<a href="{{ $subsubmenu['link_url'] }}" onclick="window.location.href='{{ $subsubmenu['link_url'] }}'; return false;" style="font-size:16px !important;">
																	{{ $subsubmenu['nama'] }}
																</a>
															</li>
														@endforeach
													</ul>
												@endif
											</li>
										@endforeach
									</ul>
								@endif
							</li>
						@endforeach
					</ul>
				</nav>
			</div>
		</div>
	</div>
</div>
@push('scripts')
<script>
	$(document).ready(function() {
		$('.dropdown-submenu a').on("click", function(e) {
			var submenu = $(this).next('.dropdown-menu');
			if (submenu.is(':visible')) {
				submenu.hide();
			} else {
				submenu.show();
			}
			e.stopPropagation();
			e.preventDefault(); // Prevents the link from being followed
		});
	});
</script>
@endpush