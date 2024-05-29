<!-- Settings Sidebar -->
<div class="settings-sidebar d-none d-xl-flex">
    <div class="theme-bar">
        <div class="active-theme-wrap">
            <div class="active-theme">
                <img src="{{theme_asset('assets/img/svg/light.svg')}}" alt="" class="svg light-icon">
                <img src="{{theme_asset('assets/img/svg/dark.svg')}}" alt="" class="svg dark-icon">
            </div>
            <button onclick="switchToLightMode()" class="light_button active">
                <img src="{{theme_asset('assets/img/svg/light.svg')}}" alt="" class="svg">
                <span>{{ translate('light') }}</span>
            </button>
        </div>
        <button onclick="switchToDarkMode()" class="dark_button">
            <img src="{{theme_asset('assets/img/svg/dark.svg')}}" alt="" class="svg">
            <span>{{ translate('dark') }}</span>
        </button>
    </div>
</div>

