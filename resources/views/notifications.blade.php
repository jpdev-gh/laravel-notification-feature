<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Notifications

                    <div class="mt-8">
                        <ul class="notifications__list p-0">
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
