<x-dropdown align="right" width="48">
    <x-slot name="trigger">
        <a class="header-notifications__dropdown mr-2 flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
            <i class="fa fa-bell" aria-hidden="true"></i><span class="header-notifications__badge">3</span>
        </a>
    </x-slot>

    <x-slot name="content">
        <ul class="header-notifications__list px-2 py-2">
            <li class="header-notifications__item">
                <a href="#">
                    <span class="header-notifications__user-photo" style="https://ui-avatars.com/api/?size=300&bold=true&background=7149a8&color=ffffff&name=DH&bold=true&rounded=true&format=png"></span>
                    <div class="header-notifications__content">
                        <h6 class="header-notifications__body notification-heading"> This is a sample notiifaction heading </h6>
                        <span class="header-notifications__created-at notification-text">  May 30, 2022 :  </span> 
                        <span class="header-notifications__read-at notification-text">  8 minutes after  </span> 
                    </div>
                </a>
            </li>
        </ul>
    </x-slot>
</x-dropdown>