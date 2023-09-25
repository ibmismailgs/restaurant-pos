<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{route('dashboard')}}">
            <div class="logo-img">
                <?php
                    $generalSettings = App\Models\Ingredients\GeneralSetting::first();
                ?>
                  @if (empty($generalSettings))
                  <h3>Scooby</h3>
                  @else
                    <img height="30" src="@isset($generalSettings) {{ asset('img/' . $generalSettings->logo) }} @endisset" class="header-brand-img" title="Scooby" height="50px"
                    width="156px">
                @endif
            </div>
        </a>
        <div class="sidebar-action"><i class="ik ik-arrow-left-circle"></i></div>
        <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
    </div>

    @php
        $segment1 = request()->segment(1);
        $segment2 = request()->segment(2);
        $route  = Route::current()->getName();
    @endphp

    <div class="sidebar-content">
        <div class="nav-container">
            <nav id="main-menu-navigation" class="navigation-main">

                <div class="nav-item {{ ($segment1 == 'users' || $segment1 == 'roles'||$segment1 == 'permission' ||$segment1 == 'user') ? 'active open' : '' }} has-sub">
                    <a href="#"><i class="ik ik-user"></i><span>{{ __('Adminstrator')}}</span></a>
                    <div class="submenu-content">
                        <!-- only those have manage_user permission will get access -->
                        @can('manage_user')
                        <a href="{{url('users')}}" class="menu-item {{ ($segment1 == 'users') ? 'active' : '' }}">{{ __('Users')}}</a>
                        <a href="{{url('user/create')}}" class="menu-item {{ ($segment1 == 'user' && $segment2 == 'create') ? 'active' : '' }}">{{ __('Add User')}}</a>
                         @endcan
                         <!-- only those have manage_role permission will get access -->
                        @can('manage_roles')
                        <a href="{{url('roles')}}" class="menu-item {{ ($segment1 == 'roles') ? 'active' : '' }}">{{ __('Roles')}}</a>
                        @endcan
                        <!-- only those have manage_permission permission will get access -->
                        @can('manage_permission')
                        <a href="{{url('permission')}}" class="menu-item {{ ($segment1 == 'permission') ? 'active' : '' }}">{{ __('Permission')}}</a>
                        @endcan
                    </div>
                </div>

                <div class="nav-item {{ ($route == 'general-settings' || $route == 'units.index' || $route == 'units.create' || $route == 'units.edit' || $route == 'units.show') ? 'active open' : '' }} has-sub">
                    <a href="javascript:void(0)" class="menu-item {{ ( $route == 'general-settings' ) ? 'active' : '' }}"><i class="fa fa-cog"></i>{{ __('Settings')}}</a>
                        <div class="submenu-content">
                            @can('manage_user')
                                <a href="{{route('general-settings')}}" class="menu-item {{ ( $route == 'general-settings') ? 'active' : '' }}">{{ __('General Settings')}}</a>
                            @endcan
                            @can('manage_user')
                                <a href="{{ route('units.index') }}" class="menu-item {{ ( $route == 'units.index' || $route == 'units.create' || $route == 'units.edit' || $route == 'units.show') ? 'active' : '' }}">{{ __('Unit')}}</a>
                            @endcan
                        </div>
                </div>

                <div class="nav-item has-sub {{ ( $route == 'ingredients.index'  || $route == 'ingredients.create' || $route == 'ingredients.edit' || $route == 'ingredients.show') ? 'open ' : '' }}">

                    <a href="javascript:void(0)" class="menu-item {{ ( $route == 'ingredients.index' || $route == 'ingredients.create' || $route == 'ingredients.edit' || $route == 'ingredients.show') ? 'active' : '' }}"><i class="fa fa-gift"></i>{{ __('Ingredients')}}</a>

                    <div class="submenu-content">
                        @can('manage_user')
                            <a href="{{route('ingredients.index')}}" class="menu-item {{ ( $route == 'ingredients.index' || $route == 'ingredients.edit' || $route == 'ingredients.create' || $route == 'ingredients.show') ? 'active' : '' }}">{{ __('Ingredients List')}}</a>
                        @endcan

                    </div>
                </div>

                <div class="nav-item has-sub {{ ( $route == 'purchase.index' || $route == 'purchase.create' || $route == 'purchase.edit' || $route == 'purchase.show') ? 'open ' : '' }}">
                    <a href="javascript:void(0)" class="menu-item {{ ( $route == 'purchase.index' || $route == 'purchase.create' || $route == 'purchase.edit' || $route == 'purchase.show') ? 'active' : '' }}"><i class="fa fa-shopping-cart"></i>{{ __('Purchase')}}</a>
                    <div class="submenu-content">
                        @can('manage_user')
                            <a href="{{route('purchase.index')}}" class="menu-item {{ ( $route == 'purchase.index' || $route == 'purchase.edit' || $route == 'purchase.create' || $route == 'purchase.show') ? 'active' : '' }}">{{ __('Purchase List')}}</a>
                        @endcan
                    </div>
                </div>

                <div class="nav-item has-sub {{ ( $route == 'product.index' || $route == 'product.create' || $route == 'product.edit' || $route == 'product.show') ? 'open ' : '' }}">
                    <a href="javascript:void(0)" class="menu-item {{ ( $route == 'product.index' || $route == 'product.create' || $route == 'product.edit' || $route == 'product.show') ? 'active' : '' }}"><i class="fa fa-list-alt"></i>{{ __('Product')}}</a>
                    <div class="submenu-content">
                        @can('manage_user')
                            <a href="{{route('product.index')}}" class="menu-item {{ ( $route == 'product.index' || $route == 'product.edit' || $route == 'product.create' || $route == 'product.show') ? 'active' : '' }}">{{ __('Product List')}}</a>
                        @endcan
                    </div>
                </div>

                <div class="nav-item has-sub {{ ( $route == 'recipes.index' || $route == 'recipes.create' || $route == 'recipes.edit' || $route == 'recipes.show') ? 'open ' : '' }}">
                    <a href="javascript:void(0)" class="menu-item {{ ( $route == 'recipes.index' || $route == 'recipes.create' || $route == 'recipes.edit' || $route == 'recipes.show') ? 'active' : '' }}"><i class="fas fa-receipt"></i>{{ __('Recipe')}}</a>
                    <div class="submenu-content">
                        @can('manage_user')
                            <a href="{{route('recipes.index')}}" class="menu-item {{ ( $route == 'recipes.index' || $route == 'recipes.edit' || $route == 'recipes.create' || $route == 'recipes.show') ? 'active' : '' }}">{{ __('Recipe List')}}</a>
                        @endcan
                    </div>
                </div>

                <div class="nav-item has-sub {{ ( $route == 'sales.index' || $route == 'sales.create' || $route == 'sales.edit' || $route == 'sales.show') ? 'open ' : '' }}">
                    <a href="javascript:void(0)" class="menu-item {{ ( $route == 'sales.index' || $route == 'sales.create' || $route == 'sales.edit' || $route == 'sales.show') ? 'active' : '' }}"><i class="fa fa-dollar-sign"></i>{{ __('Sales')}}</a>
                    <div class="submenu-content">
                        @can('manage_user')
                            <a href="{{route('sales.index')}}" class="menu-item {{ ( $route == 'sales.index' || $route == 'sales.edit' || $route == 'sales.create' || $route == 'sales.show') ? 'active' : '' }}">{{ __('Sales List')}}</a>
                        @endcan
                    </div>
                </div>

                <div class="nav-item has-sub {{ ( $route == 'inventory-report' || $route == 'purchase-report' || $route == 'sale-report') ? 'open ' : '' }}">
                    <a href="javascript:void(0)" class="menu-item {{ ( $route == 'inventory-report' || $route == 'purchase-report' || $route == 'sale-report') ? 'active' : '' }}"><i class="fa fa-file"></i>{{ __('Reports')}}</a>
                    <div class="submenu-content">
                        @can('manage_user')
                            <a href="{{route('inventory-report')}}" class="menu-item {{ ( $route == 'inventory-report') ? 'active' : '' }}">{{ __('Inventory')}}</a>

                            <a href="{{route('purchase-report')}}" class="menu-item {{ ( $route == 'purchase-report') ? 'active' : '' }}">{{ __('Purchase')}}</a>

                            <a href="{{route('sale-report')}}" class="menu-item {{ ( $route == 'sale-report') ? 'active' : '' }}">{{ __('Sale')}}</a>
                        @endcan
                    </div>
                </div>
        </div>
    </div>
</div>
