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

                    <div class="mt-4 d-flex">
                        <div class="w-50">
                            <input type="text" name="daterange" class="form-control" />
                        </div>
                        <div class="card-notifications__filter-read_at ms-auto">
                            <button type="button" data-filter="all" class="btn btn-primary">All</button>
                            <button type="button" data-filter="unread" class="btn btn-outline-primary">Unread</button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <ul class="card-notifications__list notifications__list p-0 ">
                            {{-- <li class="notifications__item">
                                <a class="d-flex" href="#">
                                    <span class="notifications__user-photo" style="background-image: url(https://ui-avatars.com/api/?size=300&bold=true&background=7149a8&color=ffffff&name=DH&bold=true&rounded=true&format=png)"></span>
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
                            </li> --}}
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    @push('scripts')
        <script type="module" src="/js/notifications/notifications.js"></script>
    @endpush
</x-app-layout>
