import re
import os

file_path = '/var/www/html/pos-toko-baju/resources/views/layouts/app.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

menu_start = '<div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">'
menu_end = '<!--end::Menu-->' # wait, there might not be this comment

# Let's find kt_app_sidebar_menu_wrapper instead
wrapper_start = '<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">'
# The sidebar wrapper ends before the footer
footer_start = '<div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">'

idx_start = content.find(wrapper_start)
idx_end = content.find(footer_start)

if idx_start != -1 and idx_end != -1:
    new_sidebar = """<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
    <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
        <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
            
            <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-element-11 fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </span>
                    <span class="menu-title">Dashboard</span>
                </a>
            </div>

            <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('pos') ? 'active' : '' }}" href="{{ route('pos') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-basket fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </span>
                    <span class="menu-title">POS</span>
                </a>
            </div>

            <div class="menu-item pt-5">
                <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Management</span></div>
            </div>
            
            <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-profile-user fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </span>
                    <span class="menu-title">Manajemen User</span>
                </a>
            </div>

            <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('roles.index') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                    <span class="menu-icon">
                        <i class="ki-duotone ki-shield-tick fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                    <span class="menu-title">Manajemen Roles</span>
                </a>
            </div>

        </div>
    </div>
</div>
						"""
    content = content[:idx_start] + new_sidebar + content[idx_end:]
    
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
    print("Sidebar updated!")
else:
    print("Could not find markers.")
