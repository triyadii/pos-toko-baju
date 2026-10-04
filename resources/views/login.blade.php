<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: MetronicProduct Version: 8.2.7
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
	<!--begin::Head-->
	<head>
<base href="../../../" />
		<title>POS-DII</title>
		<meta charset="utf-8" />
		<meta name="description" content="The most advanced Tailwind CSS & Bootstrap 5 Admin Theme with 40 unique prebuilt layouts on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel versions. Grab your copy now and get life-time updates for free." />
		<meta name="keywords" content="tailwind, tailwindcss, metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel starter kits, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Metronic - The World's #1 Selling Tailwind CSS & Bootstrap Admin Template by KeenThemes" />
		<meta property="og:url" content="https://keenthemes.com/metronic" />
		<meta property="og:site_name" content="Metronic by Keenthemes" />
		<link rel="canonical" href="http://preview.keenthemes.comauthentication/layouts/fancy/sign-in.html" />
		<link rel="shortcut icon" href="/assets/media/logos/fashion-pos-logo.png" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="app-blank">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<!--begin::Logo-->
				<a href="index.html" class="d-block d-lg-none mx-auto py-20">
					<img alt="Logo" src="/assets/media/logos/default.svg" class="theme-light-show h-25px" />
					<img alt="Logo" src="/assets/media/logos/default-dark.svg" class="theme-dark-show h-25px" />
				</a>
				<!--end::Logo-->
				<!--begin::Aside-->
				<div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
					<!--begin::Wrapper-->
					<div class="d-flex justify-content-between flex-column-fluid flex-column w-100 mw-450px">
						<!--begin::Header-->
						<div class="d-flex flex-stack py-2">
							<div class="me-2"></div>
							<div class="m-0">
							</div>
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="py-20">
							<!--begin::Form-->
							<form class="form w-100" method="POST" action="{{ route('login.submit') }}" id="login_form">
								@csrf
								<!--begin::Body-->
								<div class="card-body">
									<!--begin::Heading-->
									<div class="text-center mb-10">
                                        <img alt="Logo" src="/assets/media/logos/fashion-pos-logo.png" class="h-100px mb-5" />
										<!--begin::Title-->
										<h1 class="text-gray-900 mb-3 fs-2x">Fashion POS</h1>
										<!--end::Title-->
										<!--begin::Text-->
										<div class="text-gray-500 fw-semibold fs-6">Sistem Kasir & Manajemen Stok Toko Baju</div>
										<!--end::Link-->
									</div>
									<!--begin::Heading-->

                                    @if($errors->any())
                                        <!--begin::Alert-->
                                        <div class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row p-5 mb-10">
                                            <!--begin::Icon-->
                                            <i class="ki-duotone ki-information fs-2hx text-danger me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            <!--end::Icon-->
                                            <!--begin::Wrapper-->
                                            <div class="d-flex flex-column pe-0 pe-sm-10">
                                                <!--begin::Title-->
                                                <h4 class="fw-semibold text-danger">Login Gagal</h4>
                                                <!--end::Title-->
                                                <!--begin::Content-->
                                                <span>Username atau password yang Anda masukkan salah.</span>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Wrapper-->
                                            <!--begin::Close-->
                                            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                                <i class="ki-duotone ki-cross fs-1 text-danger"><span class="path1"></span><span class="path2"></span></i>
                                            </button>
                                            <!--end::Close-->
                                        </div>
                                        <!--end::Alert-->
                                    @endif
									<div class="fv-row mb-8">
										<!--begin::Username-->
										<input type="text" placeholder="Username" name="username" autocomplete="off" class="form-control form-control-solid" />
										<!--end::Username-->
									</div>
									<!--end::Input group=-->
									<div class="fv-row mb-7">
										<!--begin::Password-->
										<div class="position-relative mb-3">
											<input type="password" placeholder="Password" name="password" autocomplete="off" data-kt-translate="sign-in-input-password" class="form-control form-control-solid" id="password_input" />
											<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" onclick="
												var input = document.getElementById('password_input');
												var eyeShow = document.getElementById('eye_show');
												var eyeHide = document.getElementById('eye_hide');
												if (input.type === 'password') {
													input.type = 'text';
													eyeShow.classList.remove('d-none');
													eyeHide.classList.add('d-none');
												} else {
													input.type = 'password';
													eyeShow.classList.add('d-none');
													eyeHide.classList.remove('d-none');
												}
											">
												<span id="eye_hide">
													<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off text-muted"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
												</span>
												<span id="eye_show" class="d-none">
													<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-muted"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
												</span>
											</span>
										</div>
										<!--end::Password-->
									</div>
									<!--end::Input group=-->
									<!--begin::Wrapper-->
									<div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-10">
										<div></div>
										<!--begin::Link-->
										<a href="authentication/layouts/fancy/reset-password.html" class="link-primary" data-kt-translate="sign-in-forgot-password">Forgot Password ?</a>
										<!--end::Link-->
									</div>
									<!--end::Wrapper-->
									<!--begin::Actions-->
									<div class="d-flex justify-content-center">
										<!--begin::Submit-->
										<button type="submit" id="login_submit" class="btn btn-primary w-100">
											<!--begin::Indicator label-->
											<span class="indicator-label fs-5 fw-bold">Sign In</span>
											<!--end::Indicator label-->
											<!--begin::Indicator progress-->
											<span class="indicator-progress">
												<span data-kt-translate="general-progress">Please wait...</span>
												<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
											</span>
											<!--end::Indicator progress-->
										</button>
										<!--end::Submit-->
									</div>
									<!--end::Actions-->
								</div>
								<!--begin::Body-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Body-->
						<!--begin::Footer-->
						<div class="m-0">
							<!--begin::Toggle-->
							<button class="btn btn-flex btn-link rotate" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
								<img data-kt-element="current-lang-flag" class="w-25px h-25px rounded-circle me-3" src="/assets/media/flags/united-states.svg" alt="" />
								<span data-kt-element="current-lang-name" class="me-2">English</span>
								<i class="ki-duotone ki-down fs-2 text-muted rotate-180 m-0"></i>
							</button>
							<!--end::Toggle-->
							<!--begin::Menu-->
							<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-4" data-kt-menu="true" id="kt_auth_lang_menu">
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<a href="#" class="menu-link d-flex px-5" data-kt-lang="English">
										<span class="symbol symbol-20px me-4">
											<img data-kt-element="lang-flag" class="rounded-1" src="/assets/media/flags/united-states.svg" alt="" />
										</span>
										<span data-kt-element="lang-name">English</span>
									</a>
								</div>
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<a href="#" class="menu-link d-flex px-5" data-kt-lang="Spanish">
										<span class="symbol symbol-20px me-4">
											<img data-kt-element="lang-flag" class="rounded-1" src="/assets/media/flags/spain.svg" alt="" />
										</span>
										<span data-kt-element="lang-name">Spanish</span>
									</a>
								</div>
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<a href="#" class="menu-link d-flex px-5" data-kt-lang="German">
										<span class="symbol symbol-20px me-4">
											<img data-kt-element="lang-flag" class="rounded-1" src="/assets/media/flags/germany.svg" alt="" />
										</span>
										<span data-kt-element="lang-name">German</span>
									</a>
								</div>
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<a href="#" class="menu-link d-flex px-5" data-kt-lang="Japanese">
										<span class="symbol symbol-20px me-4">
											<img data-kt-element="lang-flag" class="rounded-1" src="/assets/media/flags/japan.svg" alt="" />
										</span>
										<span data-kt-element="lang-name">Japanese</span>
									</a>
								</div>
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<a href="#" class="menu-link d-flex px-5" data-kt-lang="French">
										<span class="symbol symbol-20px me-4">
											<img data-kt-element="lang-flag" class="rounded-1" src="/assets/media/flags/france.svg" alt="" />
										</span>
										<span data-kt-element="lang-name">French</span>
									</a>
								</div>
								<!--end::Menu item-->
							</div>
							<!--end::Menu-->
						</div>
						<!--end::Footer-->
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Aside-->
				<!--begin::Body-->
				<div class="d-none d-lg-flex flex-lg-row-fluid w-50 bgi-size-cover bgi-position-y-center bgi-position-x-center bgi-no-repeat" style="background-image: url(/assets/media/auth/fashion-pos-bg-illustration.png)"></div>
				<!--begin::Body-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Root-->
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="/assets/plugins/global/plugins.bundle.js"></script>
		<script src="/assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="/assets/js/custom/authentication/sign-in/general.js"></script>
		<script src="/assets/js/custom/authentication/sign-in/i18n.js"></script>
		<!--end::Custom Javascript-->
		
		@if(session('success_password'))
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				Swal.fire({
					text: "{{ session('success_password') }}",
					icon: "success",
					buttonsStyling: false,
					confirmButtonText: "Ok, mengerti!",
					customClass: { confirmButton: "btn btn-primary" }
				});
			});
		</script>
		@endif
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>