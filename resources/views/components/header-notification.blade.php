<x-dropdown align="right" width="min-w-320px">
    <x-slot name="trigger">
        <a class="header-notifications__dropdown postition-relative mr-2 flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
            <i class="fa fa-bell" aria-hidden="true"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">99+</span>
        </a>
    </x-slot>

    <x-slot name="content">
        <div class="header-notifications__header px-4 py-4">
            <div class="flex align-items-center">
                <h6 class="flex-1"> Notifications </h6>
                <a href="#"> View All</a>
            </div>
        </div>

        <div>
            <ul class="header-notifications__list notifications__list p-0">
                <li class="notifications__item">
                    <a class="flex" href="#">
                        <img class="notifications__user-photo" src="https://ui-avatars.com/api/?size=300&bold=true&background=7149a8&color=ffffff&name=DH&bold=true&rounded=true&format=png" alt="Image description">
                        <div class="notifications__content ml-3">
                            <h6 class="notifications__body">  RNTVSH Hospitality Management, Tourism Management, Nutrition & Dietetics has submitted an application for review  </h6>
                            <div class="notifications__date-wrapper">
                                <i class="fa fa-clock" aria-hidden="true"></i>
                                <span class="notifications__created-at">  May 30, 2022:  </span> 
                                <span class="notifications__read-at">  8 minutes after </span> 
                            </div>
                        </div>
                        <span class="notifications__badge"></span>
                    </a>
                </li>
            </ul>
        </div>
        
    </x-slot>
</x-dropdown>