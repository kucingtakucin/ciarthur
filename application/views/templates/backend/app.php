<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?= sistem()->nama ?> | <?= @$title ?></title>

	<!-- Meta description, keywords, author, icon -->
	<meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
	<meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
	<meta name="author" content="pixelstrap">
	<link rel="icon" href="<?= config_item('assets_backend') ?>images/favicon.png" type="image/x-icon">
	<link rel="shortcut icon" href="<?= config_item('assets_backend') ?>images/favicon.png" type="image/x-icon">

	<!-- Google font-->
	<link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">

	<!-- Font Awesome -->
	<link rel="stylesheet" type="text/css" href="<?= config_item('assets_backend') ?>css/fontawesome.css">

	<!-- ico-font-->
	<link rel="stylesheet" type="text/css" href="<?= config_item('assets_backend') ?>css/vendors/icofont.css">

	<!-- Themify icon-->
	<link rel="stylesheet" type="text/css" href="<?= config_item('assets_backend') ?>css/vendors/themify.css">

	<!-- Flag icon-->
	<link rel="stylesheet" type="text/css" href="<?= config_item('assets_backend') ?>css/vendors/flag-icon.css">

	<!-- Feather icon-->
	<link rel="stylesheet" type="text/css" href="<?= config_item('assets_backend') ?>css/vendors/feather-icon.css">

	<!-- DataTables -->
	<link rel="stylesheet" type="text/css" href="<?= config_item('assets_backend') ?>css/vendors/datatables.css">
	<link rel="stylesheet" type="text/css" href="<?= config_item('assets_backend') ?>css/vendors/datatable-extension.css">

	<!-- Bootstrap css-->
	<link rel="stylesheet" type="text/css" href="<?= config_item('assets_backend') ?>css/vendors/bootstrap.css">

	<!-- App css-->
	<link rel="stylesheet" type="text/css" href="<?= config_item('assets_backend') ?>css/style.css">
	<link id="color" rel="stylesheet" href="<?= config_item('assets_backend') ?>css/color-1.css" media="screen">

	<!-- Responsive css-->
	<link rel="stylesheet" type="text/css" href="<?= config_item('assets_backend') ?>css/responsive.css">

	<!-- Select2 -->
	<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"> -->
	<link rel="stylesheet" type="text/css" href="<?= config_item('assets_backend') ?>css/vendors/select2.css">

	<!-- Animate.css -->
	<link rel="stylesheet" type="text/css" href="<?= config_item('assets_backend') ?>css/vendors/animate.css">

	<!-- Bootstrap Datepicker -->
	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
	<link rel="stylesheet" type="text/css" href="<?= config_item('assets_backend') ?>css/vendors/date-picker.css">

	<!-- Leaflet -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />

	<!-- Pace -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">
	<script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>

	<!-- Summernote -->
	<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

	<!-- Toastr -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

	<?php if (session('dark_mode') === 'dark-only') : ?>
		<!-- Dark SweetAlert2 -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
	<?php endif ?>

	<!-- Custom Stylesheets -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/custom/offlinejs/css/offline.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/custom/offlinejs/css/offline-language-english.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
	<?= $style ? $this->load->view($style, [], true) : '' ?>
</head>

<body class="<?= session('dark_mode') ?>">
	<!-- tap on top starts-->
	<div class="tap-top"><i data-feather="chevrons-up"></i></div>
	<!-- tap on tap ends-->
	<!-- page-wrapper Start-->
	<div class="page-wrapper <?= session('sidebar') === 'horizontal' ? 'horizontal-wrapper' : 'compact-wrapper' ?>" id="pageWrapper">
		<!-- Page Header Start-->
		<div class="page-header">
			<div class="header-wrapper row m-0">
				<div class="header-logo-wrapper">
					<div class="logo-wrapper">
						<a href="<?= base_url() ?>">
							<img class="img-fluid for-light" src="data:image/png;base64,<?= base64_encode(file_get_contents(config_item('assets_backend') . 'images/logo/logo.png')) ?>" alt="logo">
							<img class="img-fluid for-dark" src="data:image/png;base64,<?= base64_encode(file_get_contents(config_item('assets_backend') . 'images/logo/logo_dark.png')) ?>" alt="logo dark">
						</a>
					</div>
					<div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="sliders" id="sidebar-toggle"> </i></div>
				</div>
				<div class="left-header col horizontal-wrapper pl-0">
					<ul class="horizontal-menu"></ul>
				</div>
				<div class="nav-right col-8 pull-right right-header p-0">
					<ul class="nav-menus">
						<li>
							<div class="website" title="See Website" style="cursor: pointer;" onclick="javascript:location.replace('<?= base_url() ?>')">
								<i class="fa fa-globe"></i>
							</div>
						</li>
						<li>
							<div class="settings" id="c-pills-home-tab" title="Settings"><i class="fa fa-gear"></i></div>
						</li>
						<li>
							<div class="mode"><i class="fa <?= session('dark_mode') === 'dark-only' ? 'fa-lightbulb-o' : 'fa-moon-o' ?>" title="Mode"></i></div>
						</li>
						<li class="maximize"><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>
						<li class="profile-nav onhover-dropdown p-0 mr-0">
							<div class="media profile-media"><img class="b-r-10" src="<?= config_item('assets_backend') ?>images/dashboard/profile.jpg" alt="">
								<div class="media-body"><span><?= ucwords(user()->username) ?></span>
									<p class="mb-0 font-roboto"><?= ucwords(get_role()) ?> <i class="middle fa fa-angle-down"></i></p>
								</div>
							</div>
							<ul class="profile-dropdown onhover-show-div">
								<li id="edit-account">
									<a href="javascript:void(0);">
										<i data-feather="user"></i>
										<span>Account </span>
									</a>
								</li>
								<li id="logout">
									<a href="javascript:void(0);">
										<i data-feather="log-out"></i>
										<span>Log out </span>
									</a>
									<!-- Form Logout -->
									<form class="form-inline" id="form_logout">
										<input type="hidden" id="csrf-logout">
									</form>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- Page Header Ends -->
		<!-- Page Body Start-->
		<div class="page-body-wrapper <?= session('sidebar') === 'horizontal' ? 'horizontal-menu' : 'sidebar-icon' ?>">
			<!-- Page Sidebar Start-->
			<div class="sidebar-wrapper" sidebar-layout="iconcolor-sidebar">
				<div class="logo-wrapper">
					<a href="<?= base_url() ?>">
						<img class="img-fluid for-light" src="data:image/png;base64,<?= base64_encode(file_get_contents(config_item('assets_backend') . 'images/logo/logo.png')) ?>" alt="logo">
						<img class="img-fluid for-dark" src="data:image/png;base64,<?= base64_encode(file_get_contents(config_item('assets_backend') . 'images/logo/logo_dark.png')) ?>" alt="logo dark">
					</a>
				</div>
				<div class="logo-icon-wrapper"><a href="<?= base_url() ?>"><img class="img-fluid" src="<?= config_item('assets_backend') ?>images/logo/logo-icon.png" alt=""></a></div>
				<nav>
					<div class="sidebar-main">
						<div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
						<div id="sidebar-menu">
							<ul class="sidebar-links custom-scrollbar">
								<?= $this->load->view('templates/backend/sidebar', [], true) ?>
							</ul>
						</div>
						<div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
					</div>
				</nav>
			</div>
			<!-- Page Sidebar Ends-->
			<div class="page-body">
				<div class="container-fluid">
					<div class="page-title">
						<div class="row">
							<div class="col-lg-12">
								<h3><?= $title ?></h3>
								<ol class="breadcrumb">
									<li class="breadcrumb-item active">
										<a href="<?= base_url() ?>">
											<i class="fa fa-home fa-2x"></i>
										</a>
									</li>
									<?php
									if (@$breadcrumb) :
										$bc = 0;
										foreach ($breadcrumb as $url => $item) : ?>
											<li class="breadcrumb-item <?= $bc === (count($breadcrumb) - 1) ? 'active' : '' ?>">
												<?php if ($bc === (count($breadcrumb) - 1)) : ?>
													<a href="javascript:void(0);">
														<?= $item ?>
													</a>
												<?php else : ?>
													<?= $item ?>
												<?php endif ?>
											</li>
									<?php $bc++;
										endforeach;
									endif ?>
								</ol>
							</div>
						</div>
					</div>
				</div>

				<!-- Container-fluid starts-->
				<div class="container-fluid">
					<div class="row starter-main">
						<div class="col-sm-12">
							<?= $this->load->view($page, [], true) ?>
						</div>
					</div>
				</div>
				<!-- Container-fluid Ends-->
			</div>

			<!-- Modals -->
			<?php if (@$modals) :
				foreach ($modals as $modal) : ?>
					<?= $this->load->view($modal, [], true) ?>
			<?php endforeach;
			endif; ?>
			<!-- END Pop In Modal -->

			<?= $this->load->view('templates/backend/footer', [], true) ?>
		</div>
	</div>


	<!-- Latest jquery-->
	<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> -->
	<script src="<?= config_item('assets_backend') ?>js/jquery-3.5.1.min.js"></script>

	<!-- Bootstrap js-->
	<script src="<?= config_item('assets_backend') ?>js/bootstrap/popper.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/bootstrap/bootstrap.js"></script>

	<!-- feather icon js-->
	<script src="<?= config_item('assets_backend') ?>js/icons/feather-icon/feather.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/icons/feather-icon/feather-icon.js"></script>

	<!-- Sidebar jquery-->
	<script src="<?= base_url() ?>assets/cuba/js/sidebar-menu.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/config.js"></script>

	<!-- Clipboard .js-->
	<script src="<?= config_item('assets_backend') ?>js/clipboard/clipboard.min.js"></script>

	<!-- Custom Card -->
	<script src="<?= config_item('assets_backend') ?>js/custom-card/custom-card.js"></script>

	<!-- Tooltip init -->
	<script src="<?= config_item('assets_backend') ?>js/tooltip-init.js"></script>

	<!-- Select2 -->
	<script src="<?= config_item('assets_backend') ?>js/select2/select2.full.min.js"></script>

	<!-- DataTables -->
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatables/jquery.dataTables.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/dataTables.buttons.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/jszip.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/buttons.colVis.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/pdfmake.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/vfs_fonts.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/dataTables.autoFill.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/dataTables.select.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/buttons.bootstrap4.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/buttons.html5.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/buttons.print.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/dataTables.bootstrap4.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/dataTables.responsive.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/responsive.bootstrap4.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/dataTables.keyTable.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/dataTables.colReorder.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/dataTables.fixedHeader.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/dataTables.rowReorder.min.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datatable/datatable-extension/dataTables.scroller.min.js"></script>

	<!-- JQuery Validation -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

	<!-- Datepicker -->
	<script src="<?= config_item('assets_backend') ?>js/datepicker/date-picker/datepicker.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datepicker/date-picker/datepicker.en.js"></script>
	<script src="<?= config_item('assets_backend') ?>js/datepicker/date-picker/datepicker.custom.js"></script>

	<!-- SweetAlert2 -->
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- Leaflet.js -->
	<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

	<!-- Form Validation Custom -->
	<script src="<?= config_item('assets_backend') ?>js/form-validation-custom.js"></script>

	<!-- Bootstrap Custom File Input -->
	<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>

	<!-- Axios -->
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

	<!-- Apex Charts -->
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

	<!-- Pusher -->
	<script src="https://js.pusher.com/7.0/pusher.min.js"></script>

	<!-- SocketIO  -->
	<script src="https://cdn.socket.io/4.1.2/socket.io.min.js" integrity="sha384-toS6mmwu70G0fw54EGlWWeA4z3dyJ+dlXBtSURSKN4vyRFOcxd3Bzjj/AoOwY+Rg" crossorigin="anonymous"></script>

	<!-- Cookie js -->
	<script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js" integrity="sha256-0H3Nuz3aug3afVbUlsu12Puxva3CP4EhJtPExqs54Vg=" crossorigin="anonymous"></script>

	<!-- Summernote -->
	<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

	<!-- TinyMCE -->
	<script src="https://cdn.tiny.cloud/1/g63i2rcs560dviwqecgsz7kitie6ynyiut84jt5lph6j72rz/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

	<!-- Theme Customizer -->
	<script>
		$(`
			<div class="customizer-contain">
				<div class="tab-content" id="c-pills-tabContent">
					<div class="customizer-header"> <i class="icofont-close icon-close"></i>
						<h5>Preview Settings</h5>
						<p class="mb-0">Try It Real Time <i class="fa fa-thumbs-o-up txt-primary"></i></p>
					</div>
					<div class="customizer-body custom-scrollbar">
						<div class="tab-pane fade show active" id="c-pills-home" role="tabpanel" aria-labelledby="c-pills-home-tab">

							<h6 class="">Sidebar Type</h6>
							<ul class="sidebar-type layout-grid">
								<li class="select-sidebar" data-attr="normal-sidebar">
									<div class="header bg-light">
										<ul>
											<li></li>
											<li></li>
											<li></li>
										</ul>
									</div>
									<div class="body">
										<ul>
											<li class="bg-dark sidebar"></li>
											<li class="bg-light body"></li>
										</ul>
									</div>
								</li>
								<li class="select-sidebar" data-attr="compact-sidebar">
									<div class="header bg-light">
										<ul>
											<li></li>
											<li></li>
											<li></li>
										</ul>
									</div>
									<div class="body">
										<ul>
											<li class="bg-dark sidebar compact"></li>
											<li class="bg-light body"></li>
										</ul>
									</div>
								</li>
							</ul>

							<h6 class="">Mix Layout</h6>
							<ul class="layout-grid customizer-mix">
								<li class="color-layout active" data-attr="light-only">
									<div class="header bg-light">
										<ul>
											<li></li>
											<li></li>
											<li></li>
										</ul>
									</div>
									<div class="body">
										<ul>
											<li class="bg-light sidebar"></li>
											<li class="bg-light body"></li>
										</ul>
									</div>
								</li>
								<li class="color-layout" data-attr="dark-sidebar">
									<div class="header bg-light">
										<ul>
											<li></li>
											<li></li>
											<li></li>
										</ul>
									</div>
									<div class="body">
										<ul>
											<li class="bg-dark sidebar"></li>
											<li class="bg-light body"></li>
										</ul>
									</div>
								</li>
								<li class="color-layout" data-attr="dark-only">
									<div class="header bg-dark">
										<ul>
											<li></li>
											<li></li>
											<li></li>
										</ul>
									</div>
									<div class="body">
										<ul>
											<li class="bg-dark sidebar"></li>
											<li class="bg-dark body"></li>
										</ul>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		`).appendTo($('body'));

		// Live customizer js
		$(document).ready(function() {

			$("#c-pills-home-tab").click(function() {
				$(".customizer-contain").toggleClass("open");
			});

			$(".close-customizer-btn").on('click', function() {
				$(".floated-customizer-panel").removeClass("active");
			});

			$(".customizer-contain .icon-close").on('click', function() {
				$(".customizer-contain").removeClass("open");
				$(".customizer-links").removeClass("open");
			});

			$(".customizer-mix").on('click', 'li.color-layout', function(event) {
				event.preventDefault();

				$(".customizer-mix li").removeClass('active');
				$(this).addClass("active");
				let mixLayout = $(this).attr("data-attr");

				let formData = new FormData()
				formData.append('dark_mode', mixLayout)
				axios.post('<?= base_url('auth/users/dark_mode') ?>', formData)
					.then(res => {
						$("body").attr("class", mixLayout);

						Swal.fire({
							title: 'Berhasil',
							icon: 'success',
							text: res.data.message,
							showConfirmButton: false,
							allowEscapeKey: false,
							allowOutsideClick: false,
							timer: 1500
						}).then(() => location.reload())
					})
					.catch(err => {
						console.error(err.response)
					})
			});

			$('.sidebar-type').on('click', 'li.select-sidebar', function(event) {
				event.preventDefault()
				let type = $(this).attr("data-attr");

				let boxed = "";
				if ($(".page-wrapper").hasClass("box-layout")) {
					boxed = "box-layout";
				}

				let input = ""

				switch (type) {
					case 'compact-sidebar': {
						input = 'vertical';
						$(".page-wrapper").attr("class", "page-wrapper compact-wrapper " + boxed);
						$(".page-body-wrapper").attr("class", "page-body-wrapper sidebar-icon");
						break;
					}
					case 'normal-sidebar': {
						input = 'horizontal';
						$(".page-wrapper").attr("class", "page-wrapper horizontal-wrapper " + boxed);
						$(".page-body-wrapper").attr("class", "page-body-wrapper horizontal-menu");
						$(".logo-wrapper").find('img').attr('src', '../assets/images/logo/logo.png');
						break;
					}
					default: {
						input = 'vertical';
						$(".page-wrapper").attr("class", "page-wrapper compact-wrapper " + boxed);
						$(".page-body-wrapper").attr("class", "page-body-wrapper sidebar-icon");
						break;
					}
				}

				let formData = new FormData()
				formData.append('sidebar', input)
				axios.post('<?= base_url('auth/users/sidebar') ?>', formData)
					.then(res => {
						Swal.fire({
							title: 'Berhasil',
							icon: 'success',
							text: res.data.message,
							showConfirmButton: false,
							allowEscapeKey: false,
							allowOutsideClick: false,
							timer: 1500
						}).then(() => location.reload())
					})
					.catch(err => {
						console.error(err)
					})
			});
		});
	</script>

	<!-- Loading Overlay -->
	<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

	<!-- Toastr -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

	<!-- Offline Js -->
	<script src="<?= base_url() ?>assets/custom/offlinejs/js/offline.min.js"></script>

	<!-- CryptoJs AES -->
	<script src="<?= base_url() ?>assets/custom/cryptojs-aes/cryptojs-aes.min.js"></script>
	<script src="<?= base_url() ?>assets/custom/cryptojs-aes/cryptojs-aes-format.js"></script>

	<!-- Custom Scripts-->
	<script>
		// Dark Mode
		$(".nav-menus").on("click", '.mode', function(event) {
			event.preventDefault()

			let formData = new FormData()
			formData.append('dark_mode', '<?= session('dark_mode') === 'dark-only' ? 'light-only' : 'dark-only' ?>')
			axios.post('<?= base_url('auth/users/dark_mode') ?>', formData)
				.then(res => {
					$('.mode i').toggleClass("fa-moon-o").toggleClass("fa-lightbulb-o");
					$('body').removeClass('dark-sidebar');
					$('body').toggleClass("dark-only");

					Swal.fire({
						title: 'Berhasil',
						icon: 'success',
						text: res.data.message,
						showConfirmButton: false,
						allowEscapeKey: false,
						allowOutsideClick: false,
						timer: 1500
					}).then(() => location.reload())
				})
				.catch(err => {
					console.error(err.response)
				})
		});
	</script>
	<script src="<?= config_item('assets_backend') ?>js/script.js"></script>

	<script>
		$.fn.dataTable.ext.errMode = 'throw';

		// ================================================//
		/**
		 * Implement hex2bin and bin2hex in JavaScript
		 * https://gist.github.com/jasperck
		 *
		 * Copyright 2017, JasperChang <jasperc8@gmail.com>
		 * Licensed under The MIT License
		 * http://www.opensource.org/licenses/mit-license
		 */

		const _hex2bin = str => str.match(/.{1,2}/g).reduce((str, hex) => str += String.fromCharCode(parseInt(hex, 16)), '');

		const _bin2hex = str => str.split('').reduce((str, glyph) => str += glyph.charCodeAt().toString(16).length < 2 ? `0${glyph.charCodeAt().toString(16)}` :
			glyph.charCodeAt().toString(16), '');

		// Crypto.Js AES encrypt
		const _cryptoAesJson_encrypt = (valueToEncrypt) => {
			let password = "<?= config_item('cryptojs_aes_password') ?>";
			let encrypted = CryptoJSAesJson.encrypt(valueToEncrypt, password)
			return encrypted
		}

		// Crypto.Js AES decrypt
		const _cryptoAesJson_decrypt = (valueToDecrypt) => {
			let password = "<?= config_item('cryptojs_aes_password') ?>"
			let decrypted = CryptoJSAesJson.decrypt(valueToDecrypt, password)
			return decrypted
		}

		/**
		 * Keperluan generate csrf
		 */
		// ================================================== //

		// JQuery AJAX Interceptor 
		$.ajaxSetup({
			beforeSend: function(xhr, settings) {
				xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest")
				xhr.setRequestHeader("X-CI-XSRF-TOKEN", _cryptoAesJson_encrypt({
					'<?= bin2hex('token_name') ?>': '<?= base64_encode($this->security->get_csrf_token_name()) ?>',
					'<?= bin2hex('hash') ?>': Cookies.get(atob('<?= base64_encode(config_item('cookie_prefix') . config_item('csrf_cookie_name')) ?>'))
				}))

				let filename = ['foto', 'gambar', 'image', 'images', 'file', 'files', 'dokumen'];

				if (settings.data instanceof FormData) {
					for ([k, v] of settings.data.entries()) {
						if (settings.data.get(k) &&
							!filename.includes(k) &&
							!k.includes('[]')) settings.data.set(k, _cryptoAesJson_encrypt(v))
					}
				} else if (typeof settings.data === 'string') {
					const urlParams = new URLSearchParams(settings.data);
					const entries = urlParams.entries(); //returns an iterator of decoded [key,value] tuples
					const obj = {}
					for (const [key, value] of entries) { // each 'entry' is a [key, value] tupple
						if (value &&
							!filename.includes(key) &&
							!key.includes('[]')) {
							obj[key] = _cryptoAesJson_encrypt(value);
						}
					}
					settings.data = new URLSearchParams(obj).toString()
				}

			}
		});

		$(document).ajaxSuccess(function(event, jqxhr, settings) {});

		$(document).ajaxError(function(event, jqxhr, settings) {
			$.LoadingOverlay("hide")
		});

		$(document).ajaxStart((event, jqxhr, settings) => $.LoadingOverlay("show"));
		$(document).ajaxStop((event, jqxhr, settings) => $.LoadingOverlay("hide"));

		/** Set default AJAX headers */
		axios.defaults.headers.common = {
			"X-Requested-With": "XMLHttpRequest",
		};

		// Add a request interceptor
		axios.interceptors.request.use(function(config) {
			$.LoadingOverlay("show")
			// Do something before request is sent
			if (config?.headers) config.headers['X-CI-XSRF-TOKEN'] = _cryptoAesJson_encrypt({
				'<?= bin2hex('token_name') ?>': '<?= base64_encode($this->security->get_csrf_token_name()) ?>',
				'<?= bin2hex('hash') ?>': Cookies.get(atob('<?= base64_encode(config_item('cookie_prefix') . config_item('csrf_cookie_name')) ?>'))
			});

			if (config?.data && config?.data instanceof FormData) {
				let filename = ['foto', 'gambar', 'image', 'images', 'file', 'files', 'dokumen'];
				for ([k, v] of config?.data.entries()) {
					if (config?.data.get(k) &&
						!filename.includes(k) &&
						!k.includes('[]')) config?.data.set(k, _cryptoAesJson_encrypt(v))
				}
			}

			return config;
		}, function(error) {
			$.LoadingOverlay("hide")
			// Do something with request error
			return Promise.reject(error);
		});

		// Add a response interceptor
		axios.interceptors.response.use(function(response) {
			$.LoadingOverlay("hide")
			// Any status code that lie within the range of 2xx cause this function to trigger
			// Do something with response data
			return response;
		}, function(error) {
			$.LoadingOverlay("hide")
			// Any status codes that falls outside the range of 2xx cause this function to trigger
			// Do something with response error
			return Promise.reject(error);
		});

		/**
		 * Keperluan disable inspect element
		 */
		// ================================================== //

		// Disable right click
		$(document).contextmenu((event) => event.preventDefault())

		$(document).keydown(function(event) {
			// Disable F12
			if (event.keyCode == 123) return false;

			// Disable Ctrl + Shift + I
			if (event.ctrlKey &&
				event.shiftKey &&
				event.keyCode == 'I'.charCodeAt(0)) return false;


			// Disable Ctrl + Shift + J
			if (event.ctrlKey &&
				event.shiftKey &&
				event.keyCode == 'J'.charCodeAt(0)) return false;

			// Disable Ctrl + U
			if (event.ctrlKey &&
				event.keyCode == 'U'.charCodeAt(0)) return false;
		})

		/**
		 * Keperluan pusher 
		 */
		// ================================================== //
		// Pusher.logToConsole = true;

		// pusher = new Pusher('21a14c9bc94b57c3db03', {
		//     cluster: 'ap1'
		// });

		// channel = pusher.subscribe('kirim-pengaduan-channel');
		// channel.bind('kirim-pengaduan-event', function(data) {
		//     Swal.fire({
		//         title: data.title,
		//         icon: 'info',
		//         text: data.message
		//     })
		// })

		/**
		 * Keperluan show preloader
		 */
		// ================================================== //
		// $('.preloader-container').slideUp(500)

		/**
		 * Keperluan resize Google Recaptchaa
		 */
		// ================================================== //

		let width = $('.g-recaptcha').parent().width();
		if (width < 302) {
			let scale = width / 302;
			$('.g-recaptcha').css('transform', 'scale(' + scale + ')');
			$('.g-recaptcha').css('-webkit-transform', 'scale(' + scale + ')');
			$('.g-recaptcha').css('transform-origin', '0 0');
			$('.g-recaptcha').css('-webkit-transform-origin', '0 0');
		}

		/**
		 * Keperluan show sweet alert loading
		 */
		// ================================================== //
		const loading = () => {
			Swal.fire({
				title: 'Loading...',
				allowEscapeKey: false,
				allowOutsideClick: false,
				showConfirmButton: false,
				didOpen: () => {
					Swal.showLoading();
				}
			})
		}

		/**
		 * Keperluan edit account
		 */
		// ================================================== //
		$('#edit-account').click(function() {
			Swal.fire({
				title: 'Form Edit Account',
				width: '800px',
				icon: 'info',
				html: `<?= $this->load->view("templates/backend/components/form_edit_account", [], true); ?>`,
				confirmButtonText: '<i class="fa fa-check-square-o"></i> Simpan Data',
				showCancelButton: true,
				focusConfirm: false,
				showLoaderOnConfirm: true,
				allowOutsideClick: false,
				allowEscapeKey: false,
				allowEnterKey: false,
				showCloseButton: true,
				reverseButtons: true,
				didOpen: () => {
					$('.swal2-actions').css('z-index', '0')

					$('#input_ubah_username')
						.val('<?= user()->username ?>')
						.prop('readonly', true)

				},
				preConfirm: async () => {
					let formData = new FormData(document.getElementById('form_edit_account'));

					$('#form_edit_account .invalid-feedback').slideUp(500)
					$('#form_edit_account .is-invalid').removeClass('is-invalid')

					let response = await axios.post("<?= base_url('auth/users/edit_account') ?>", formData)
						.then(res => res.data.message)
						.catch(err => {
							let errors = err.response.data?.errors;
							if (errors && typeof errors === 'object') {
								Object.entries(errors).map(([key, value]) => {
									$(`#form_edit_account #input_ubah_${key}`).addClass('is-invalid')
									$(`#form_edit_account #error_ubah_${key}`).html(value).slideDown(500)
								})
							}
							Swal.showValidationMessage(err.response.data.message)
						})

					return {
						data: response
					}
				}
			}).then((result) => {
				if (result.value) {
					Swal.fire({
						title: 'Berhasil',
						icon: 'success',
						text: result.value.data,
						showConfirmButton: false,
						allowEscapeKey: false,
						allowOutsideClick: false,
						timer: 1500
					}).then(() => {
						datatable.ajax.reload()
					})
				}
			})
		})

		$('#logout').click((event) => {
			event.preventDefault()
			$('#form_logout').prop('action', "<?= base_url('~/logout') ?>")
			$('#form_logout').prop('method', 'POST')
			$('#form_logout #csrf-logout').prop('name', "<?= $this->security->get_csrf_token_name() ?>")
			$('#form_logout #csrf-logout').val(Cookies.get(atob('<?= base64_encode(config_item('cookie_prefix') . config_item('csrf_cookie_name')) ?>')))
			$('#form_logout').submit()
		})
	</script>
	<?= $this->load->view($script, [], true) ?>
</body>

</html>