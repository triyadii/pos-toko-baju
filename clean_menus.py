import re
import os

file_path = '/var/www/html/pos-toko-baju/resources/views/layouts/app.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Remove app-header-menu
header_menu_start = '<div class="app-header-menu app-header-mobile-drawer'
navbar_start = '<div class="app-navbar flex-shrink-0">'

idx1 = content.find(header_menu_start)
idx2 = content.find(navbar_start)

if idx1 != -1 and idx2 != -1:
    content = content[:idx1] + content[idx2:]

# 2. Remove items before user menu in navbar
navbar_start = '<div class="app-navbar flex-shrink-0">'
user_menu_start = '<div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">'

idx3 = content.find(navbar_start) + len(navbar_start)
idx4 = content.find(user_menu_start)

if idx3 != -1 and idx4 != -1:
    content = content[:idx3] + "\n" + content[idx4:]

# 3. Replace user menu content
user_menu_dropdown_start = '<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">'
mobile_toggle = '<div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">'

idx5 = content.find(user_menu_dropdown_start)
idx6 = content.find(mobile_toggle)

if idx5 != -1 and idx6 != -1:
    # Build new menu
    new_menu = user_menu_dropdown_start + """
    <div class="menu-item px-3">
        <div class="menu-content d-flex align-items-center px-3">
            <div class="symbol symbol-50px me-5">
                <img alt="Logo" src="assets/media/avatars/300-3.jpg" />
            </div>
            <div class="d-flex flex-column">
                <div class="fw-bold d-flex align-items-center fs-5">{{ Auth::user()->nama ?? 'User' }}</div>
                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->role->nama_role ?? '' }}</a>
            </div>
        </div>
    </div>
    <div class="separator my-2"></div>
    <div class="menu-item px-5">
        <a href="#" class="menu-link px-5">Profile</a>
    </div>
    <div class="menu-item px-5">
        <a href="#" class="menu-link px-5">Ganti Password</a>
    </div>
    <div class="separator my-2"></div>
    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
        <a href="#" class="menu-link px-5">
            <span class="menu-title position-relative">Mode 
            <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                <i class="ki-duotone ki-night-day theme-light-show fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                    <span class="path5"></span>
                    <span class="path6"></span>
                    <span class="path7"></span>
                    <span class="path8"></span>
                    <span class="path9"></span>
                    <span class="path10"></span>
                </i>
                <i class="ki-duotone ki-moon theme-dark-show fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </span></span>
        </a>
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
            <div class="menu-item px-3 my-0">
                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                    <span class="menu-icon" data-kt-element="icon">
                        <i class="ki-duotone ki-night-day fs-2"></i>
                    </span>
                    <span class="menu-title">Light</span>
                </a>
            </div>
            <div class="menu-item px-3 my-0">
                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                    <span class="menu-icon" data-kt-element="icon">
                        <i class="ki-duotone ki-moon fs-2"></i>
                    </span>
                    <span class="menu-title">Dark</span>
                </a>
            </div>
            <div class="menu-item px-3 my-0">
                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                    <span class="menu-icon" data-kt-element="icon">
                        <i class="ki-duotone ki-screen fs-2"></i>
                    </span>
                    <span class="menu-title">System</span>
                </a>
            </div>
        </div>
    </div>
    <div class="menu-item px-5">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-link px-5">Sign Out</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>
</div>
"""
    content = content[:idx5] + new_menu + content[idx6:]

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Menus updated")
