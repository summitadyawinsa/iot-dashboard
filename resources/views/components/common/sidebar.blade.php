<div :class="{ 'dark text-white-dark': $store.app.semidark }">
    <nav x-data="sidebar"
        class="sidebar fixed min-h-screen h-full top-0 bottom-0 w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] z-50 transition-all duration-300">
        <div class="bg-white dark:bg-[#0e1726] h-full">
            <div class="flex justify-between items-center px-4 py-3">
                <a href="/" class="main-logo flex items-center shrink-0">
                    <img class="w-10 ml-[10px] flex-none rounded-full" src="/assets/images/icon_company.png"
                        alt="image" />
                    <span
                        class="text-2xl ltr:ml-1.5 rtl:mr-1.5  font-semibold  align-middle lg:inline dark:text-white-light">SAI</span>
                </a>
                <a href="javascript:;"
                    class="collapse-icon w-8 h-8 rounded-full flex items-center hover:bg-gray-500/10 dark:hover:bg-dark-light/10 dark:text-white-light transition duration-300 rtl:rotate-180"
                    @click="$store.app.toggleSidebar()">
                    <svg class="w-5 h-5 m-auto" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
            <ul class="perfect-scrollbar relative font-semibold space-y-0.5 h-[calc(100vh-80px)] overflow-y-auto overflow-x-hidden  p-4 py-0"
                x-data="{ activeDropdown: null }">
                {{-- <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">

                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Dashboard</span>
                </h2> --}}
                {{-- <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'dasboard_operator' }"
                                @click="activeDropdown === 'dasboard_operator' ? activeDropdown = null : activeDropdown = 'dasboard_operator'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Dashboard</span>
                                </div>
                                <div class="rtl:rotate-180"
                                    :class="{ '!rotate-90': activeDropdown === 'dasboard_operator' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'dasboard_operator'" x-collapse
                                class="sub-menu text-gray-500">
                                {{-- <li>
                                    <a href="{{ url('dashboard-profile') }}">Profile Operator</a>
                                </li> --}}
                {{-- <li>
                                    <a href="{{ url('dashboard-leader') }}">Dashboard leader</a>
                                </li> --}}
                {{-- <li>
                                    <a href="{{ url('user-management') }}">User management</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li> --}}
                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">

                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Stamping</span>
                </h2>

                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'line-a1' }"
                                @click="activeDropdown === 'line-a1' ? activeDropdown = null : activeDropdown = 'line-a1'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Line
                                        A1</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'line-a1' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'line-a1'" x-collapse class="sub-menu text-gray-500">
                                <li>
                                    <a href="/stamping/page/A1">Page</a>
                                </li>
                                <li>
                                    <a href="/stamping/summary-by-line/A1">Summary</a>
                                </li>
                                <li>
                                    <a href="/stamping/machine/A1">Machine</a>
                                </li>
                                {{-- <li>
                                    <a href="/stamping/trial-time-entry/A1">Trial Time Entry</a>
                                </li> --}}
                                {{-- <li>
                                    <a href="/dashboard/summary/stamping/A1">Line A1</a>
                                </li> --}}
                                <li>
                                    <a href="/stamping/dashboard-by-machine/A1-1">Mc. A1-1</a>
                                </li>
                                <li>
                                    <a href="/stamping/dashboard-by-machine/A1-2">Mc. A1-2</a>
                                </li>
                                <li>
                                    <a href="/stamping/dashboard-by-machine/A1-3">Mc. A1-3</a>
                                </li>
                                <li>
                                    <a href="/stamping/dashboard-by-machine/A1-4">Mc. A1-4</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'line-a2' }"
                                @click="activeDropdown === 'line-a2' ? activeDropdown = null : activeDropdown = 'line-a2'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Line
                                        A2</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'line-a2' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'line-a2'" x-collapse
                                class="sub-menu text-gray-500">
                                <li>
                                    <a href="/stamping/page/A2">Page</a>
                                </li>
                                <li>
                                    <a href="/stamping/summary-by-line/A2">Summary</a>
                                </li>
                                <li>
                                    <a href="/stamping/machine/A2">Machine</a>
                                </li>
                                {{-- <li>
                                    <a href="/stamping/trial-time-entry/A2">Trial Time Entry</a>
                                </li> --}}
                                {{-- <li>
                                    <a href="/dashboard/summary/stamping/A2">Line A2</a>
                                </li> --}}
                                <li>
                                    <a href="/stamping/dashboard-by-machine/A2-1">Mc. A2-1</a>
                                </li>
                                <li>
                                    <a href="/stamping/dashboard-by-machine/A2-2">Mc. A2-2</a>
                                </li>
                                <li>
                                    <a href="/stamping/dashboard-by-machine/A2-3">Mc. A2-3</a>
                                </li>
                                <li>
                                    <a href="/stamping/dashboard-by-machine/A2-4">Mc. A2-4</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'line-a6' }"
                                @click="activeDropdown === 'line-a6' ? activeDropdown = null : activeDropdown = 'line-a6'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Line
                                        A6</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'line-a6' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'line-a6'" x-collapse
                                class="sub-menu text-gray-500">
                                <li>
                                    <a href="/stamping/page/A6">Page</a>
                                </li>
                                <li>
                                    <a href="/stamping/summary-by-line/A6">Summary</a>
                                </li>
                                <li>
                                    <a href="/stamping/machine/A6">Machine</a>
                                </li>
                                {{-- <li>
                                    <a href="/stamping/trial-time-entry/A6">Trial Time Entry</a>
                                </li> --}}
                                {{-- <li>
                                    <a href="/dashboard/summary/stamping/A6">Line A6</a>
                                </li> --}}
                                <li>
                                    <a href="/stamping/dashboard-by-machine/A6-1">Mc. A6-1</a>
                                </li>
                                <li>
                                    <a href="/stamping/dashboard-by-machine/A6-2">Mc. A6-2</a>
                                </li>
                                <li>
                                    <a href="/stamping/dashboard-by-machine/A6-3">Mc. A6-3</a>
                                </li>
                                <li>
                                    <a href="/stamping/dashboard-by-machine/A6-4">Mc. A6-4</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">
                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>ASSY</span>
                </h2>

                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === '5H45' }"
                                @click="activeDropdown === '5H45' ? activeDropdown = null : activeDropdown = '5H45'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Line
                                        5H45</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === '5H45' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === '5H45'" x-collapse class="sub-menu text-gray-500">
                                <li>
                                    <a href="/assy/page/RBT-5H45">Page</a>
                                </li>
                                {{-- <li>
                                    <a href="/assy/summary-by-line/RBT-5H45">Summary</a>
                                </li> --}}
                                <li>
                                    <a href="/assy/machine/RBT-5H45">Machine</a>
                                </li>
                                <li>
                                    <a href="/assy/trial-time-entry/RBT-5H45">Time Entry</a>
                                </li>
                                {{-- <li>
                                    <a href="/dashboard/summary/assy/RBT-5H45">5H45 Summary</a>
                                </li> --}}
                                {{-- <li>
                                    <a href="/dashboard/summary/dresser/assy/RBT-5H45">Dresser Summary</a>
                                </li> --}}
                                {{-- <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5H45-01">Mc. ROBOT 5H45 01</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5H45-02">Mc. ROBOT 5H45 02</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5H45-03">Mc. ROBOT 5H45 03</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5H45-04">Mc. ROBOT 5H45 04</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5H45-05">Mc. ROBOT 5H45 05</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5H45-06">Mc. ROBOT 5H45 06</a>
                                </li> --}}
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5H45-07">Mc. ROBOT 5H45 07</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5H45-08">Mc. ROBOT 5H45 08</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5H45-09">Mc. ROBOT 5H45 09</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5H45-10">Mc. ROBOT 5H45 10</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5H45-11">Mc. ROBOT 5H45 11</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5H45-12">Mc. ROBOT 5H45 12</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === '5J45' }"
                                @click="activeDropdown === '5J45' ? activeDropdown = null : activeDropdown = '5J45'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Line
                                        5J45</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === '5J45' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === '5J45'" x-collapse class="sub-menu text-gray-500">
                                <li>
                                    <a href="/assy/page/RBT-5J45">Page</a>
                                </li>
                                <li>
                                    <a href="/assy/confirm/RBT-5J45">Confirm JO</a>
                                </li>
                                {{-- <li>
                                    <a href="/assy/summary-by-line/RBT-5J45">Summary</a>
                                </li> --}}
                                {{-- <li>
                                    <a href="/assy/machine/RBT-5J45">Machine</a>
                                </li> --}}
                                {{-- <li>
                                    <a href="/assy/trial-time-entry/RBT-5J45">Trial Time Entry</a>
                                </li> --}}
                                {{-- <li>
                                    <a href="/dashboard/summary/assy/RBT-5J45">5J45 Summary</a>
                                </li> --}}
                                {{-- <li>
                                    <a href="/dashboard/summary/dresser/assy/RBT-5J45">Dresser Summary</a>
                                </li> --}}
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5J45-01">Mc. ROBOT 5J45 - 01</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5J45-02">Mc. ROBOT 5J45 - 02</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5J45-03">Mc. ROBOT 5J45 - 03</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5J45-04">Mc. ROBOT 5J45 - 04</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5J45-05">Mc. ROBOT 5J45 - 05</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5J45-06">Mc. ROBOT 5J45 - 06</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5J45-07">Mc. ROBOT 5J45 - 07</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5J45-08">Mc. ROBOT 5J45 - 08</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/RSW-5J45-09">Mc. ROBOT 5J45 - 09</a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'SSW' }"
                                @click="activeDropdown === 'SSW' ? activeDropdown = null : activeDropdown = 'SSW'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Line
                                        SSW</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'SSW' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'SSW'" x-collapse class="sub-menu text-gray-500">
                                <li>
                                    <a href="/assy/page/SSW">Page</a>
                                </li>
                                <li>
                                    <a href="/assy/confirm/SSW">Confirm JO</a>
                                </li>
                                {{-- <li>
                                    <a href="/assy/summary-by-line/SSW">Summary</a>
                                </li> --}}
                                {{-- <li>
                                    <a href="/assy/machine/SSW">Machine</a>
                                </li>
                                <li>
                                    <a href="/assy/trial-time-entry/SSW">Time Entry</a>
                                </li> --}}
                                {{-- <li>
                                    <a href="/dashboard/summary/assy/RBT-5J45">5J45 Summary</a>
                                </li> --}}
                                {{-- <li>
                                    <a href="/dashboard/summary/dresser/assy/RBT-5J45">Dresser Summary</a>
                                </li> --}}
                                {{-- <li>
                                    <a href="/assy/dashboard-by-machine/SSW-A-5">Mc. SSW-A - 05</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/SSW-A-14">Mc. SSW-A - 14</a>
                                </li> --}}
                                <li>
                                    <a href="/assy/dashboard-by-machine/SSW-B-1">SSW-B-1</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/SSW-B-2">SSW-B-2</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/SSW-B-3">SSW-B-3</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/SSW-B-4">SSW-B-4</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/SSW-B-5">SSW-B-5</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/SSW-B-7">SSW-B-7</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/SSW-TG4R-4">SSW-TG4R-4</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/SSW-TG4R-2">SSW-TG4R-2</a>
                                </li>
                                {{-- <li>
                                    <a href="/assy/dashboard-by-machine/SSW-FS-1-2">SSW-FS-1-2</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/SSW-FS-1-3">SSW-FS-1-3</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/SSW-B-1">SSW-FSI-6-3</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/SSW-PLANTB-1">SSW-PLANTB-1</a>
                                </li>
                                <li>
                                    <a href="/assy/dashboard-by-machine/SSW-PLANTB-2">SSW-PLANTB-2</a>
                                </li> --}}
                            </ul>
                        </li>
                    </ul>
                </li>

                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">
                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Config Monitoring</span>
                </h2>

                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'config-monitor' }"
                                @click="activeDropdown === 'config-monitor' ? activeDropdown = null : activeDropdown = 'config-monitor'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Configuration</span>
                                </div>
                                <div class="rtl:rotate-180"
                                    :class="{ '!rotate-90': activeDropdown === 'config-monitor' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'config-monitor'" x-collapse
                                class="sub-menu text-gray-500">
                                <li>
                                    <a href="{{ url('configuration/standard-setup') }}">Standard Setup</a>
                                </li>
                                <li>
                                    <a href="{{ url('configuration/special-setup') }}">Special Setup</a>
                                </li>
                                {{-- <li>
                                    <a href="{{ url('configuration/scan-setup') }}">Scan Setup</a>
                                </li> --}}
                                <li>
                                    <a href="{{ url('standard_operational_procedure') }}">SOP</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">
                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Maintenance</span>
                </h2>

                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'ppm-monitor' }"
                                @click="activeDropdown === 'ppm-monitor' ? activeDropdown = null : activeDropdown = 'ppm-monitor'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">PPM
                                        Activity</span>
                                </div>
                                <div class="rtl:rotate-180"
                                    :class="{ '!rotate-90': activeDropdown === 'ppm-monitor' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'ppm-monitor'" x-collapse
                                class="sub-menu text-gray-500">
                                <li>
                                    <a href="/dashboard/ppm-dashboard">PPM Dashboard</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">
                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Finance</span>
                </h2>

                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'profitability' }"
                                @click="activeDropdown === 'profitability' ? activeDropdown = null : activeDropdown = 'profitability'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Finance
                                        Report</span>
                                </div>
                                <div class="rtl:rotate-180"
                                    :class="{ '!rotate-90': activeDropdown === 'profitability' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'profitability'" x-collapse
                                class="sub-menu text-gray-500">
                                <li class=hidden>
                                    <a href="/dashboard/profitability" id="#">
                                        Profitability</a>
                                </li>
                                <li>
                                    <a href="/dashboard/profitability/invoice" id="invoice-dashboard-finance">Invoiced
                                        Profit</a>
                                </li>
                                <li>
                                    <a href="/dashboard/profitability/Model" id="invoice-dashboard-model">Profit
                                        Model</a>
                                </li>
                                <li>
                                    <a href="/dashboard/profitability/rcd" id="rcd-dashboard-finance">RCD</a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">
                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Sales</span>
                </h2>

                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'sales' }"
                                @click="activeDropdown === 'sales' ? activeDropdown = null : activeDropdown = 'sales'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Sales
                                        Report</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'sales' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'sales'" x-collapse class="sub-menu text-gray-500">
                                <li>
                                    <a href="/dashboard/sales" id="sales-dashboard">Sales Dashboard</a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </li> -->

                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">
                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Delivery</span>
                </h2>

                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'delivery' }"
                                @click="activeDropdown === 'delivery' ? activeDropdown = null : activeDropdown = 'delivery'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Forcast
                                        Report</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'delivery' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'delivery'" x-collapse
                                class="sub-menu text-gray-500">
                                <li>
                                    <a href="/dashboard/delivery" id="delivery-dashboard">Forecast Dashboard</a>
                                </li>
                                <li>
                                    <a href="/dashboard/delivery/delivery_monitoring"
                                        id="delivery-delivery-monitoring">Delivery Monitoring</a>
                                </li>
                                <li>
                                    <a href="/dashboard/delivery/job_monitoring" id="delivery-job-monitoring">Job
                                        Monitoring</a>
                                </li>
                                <li>
                                    <a href="/dashboard/delivery/finish_good" id="delivery-finish-good">Stock Finish
                                        Good</a>
                                </li>
                                <li>
                                    <a href="/dashboard/delivery/mit_dashboard" id="delivery-mit-dashboard">MIT
                                        Dashboard</a>
                                </li>
                                <li>
                                    <a href="/dashboard/delivery/cgr_monitoring" id="delivery-cgr_monitoring">CGR
                                        Monitoring</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'on-point-lesson' }"
                                @click="activeDropdown === 'on-point-lesson' ? activeDropdown = null : activeDropdown = 'on-point-lesson'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">One
                                        Point Lesson</span>
                                </div>
                                <div class="rtl:rotate-180"
                                    :class="{ '!rotate-90': activeDropdown === 'on-point-lesson' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'on-point-lesson'" x-collapse
                                class="sub-menu text-gray-500">
                                <li>
                                    <a href="{{ url('one-point-lesson/part') }}" id="part">Part</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">
                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Production</span>
                </h2>

                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'production' }"
                                @click="activeDropdown === 'production' ? activeDropdown = null : activeDropdown = 'production'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Archievment</span>
                                </div>
                                <div class="rtl:rotate-180"
                                    :class="{ '!rotate-90': activeDropdown === 'production' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'production'" x-collapse
                                class="sub-menu text-gray-500">
                                <li>
                                    <a href="/dashboard/production" id="production-dashboard">Production Achiev</a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </li>
                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">
                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>PPIC</span>
                </h2>
                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'ppic' }"
                                @click="activeDropdown === 'ppic' ? activeDropdown = null : activeDropdown = 'ppic'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Monitoring</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'ppic' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'ppic'" x-collapse class="sub-menu text-gray-500">
                                <li>
                                    <a href="/dashboard/ppic" id="job-dashboard-ppic">Job Monitoring </a>
                                </li>

                            </ul>
                            <ul x-cloak x-show="activeDropdown === 'ppic'" x-collapse class="sub-menu text-gray-500">
                                <li>
                                    <a href="/dashboard/ppic/stock" id="stock-dashboard-ppic">Stock Monitoring</a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </li>
                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">
                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Purchasing</span>
                </h2>
                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'purchasing' }"
                                @click="activeDropdown === 'purchasing' ? activeDropdown = null : activeDropdown = 'purchasing'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Purchasing
                                        Report</span>
                                </div>
                                <div class="rtl:rotate-180"
                                    :class="{ '!rotate-90': activeDropdown === 'purchasing' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'purchasing'" x-collapse
                                class="sub-menu text-gray-500">
                                <li>
                                    <a href="/dashboard/purchasing/monitoring_pr"
                                        id="purchase-dashboard-monitoring-pr">Monitoring PR </a>
                                </li>
                                <li>
                                    <a href="/dashboard/purchasing/report" id="purchase-dashboard-purchase">Purchase
                                        Dashboard </a>
                                </li>
                                <li>
                                    <a href="/dashboard/purchasing/po_project_monitoring"
                                        id="purchase-dashboard-po-project">PO Project </a>
                                </li>
                                <li>
                                    <a href="/dashboard/purchasing/po_ppic_monitoring"
                                        id="purchase-dashboard-po-ppic">PO PPIC </a>
                                </li>
                                <li>
                                    <a href="/dashboard/purchasing/po_reguler_monitoring"
                                        id="purchase-dashboard-po-reguler">PO Reguler </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">
                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>QMS</span>
                </h2>
                <li class="nav-item">
                    <ul>
                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'qms' }"
                                @click="activeDropdown === 'qms' ? activeDropdown = null : activeDropdown = 'qms'">
                                <div class="flex items-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.5"
                                            d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Genba
                                        Report</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'qms' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'qms'" x-collapse class="sub-menu text-gray-500">
                                <li>
                                    <a href="/dashboard/qms/genba_monitoring" id="qms-dashboard-genba">Genba
                                        Monitoring </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <div id="pin-popup-overlay"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.75); z-index: 10; justify-content: center; align-items: center; font-family: 'Inter', 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;">
        <div
            style="position: relative; background-color: #1e293b; padding: 40px; border-radius: 16px; text-align: center; box-shadow: 0 12px 30px rgba(0,0,0,0.5); width: 360px; border: 1px solid #334155;">
            <div style="margin-bottom: 25px; display: flex; justify-content: center;">
                <div
                    style="background-color: #0ea5e9; width: 60px; height: 60px; border-radius: 12px; display: flex; justify-content: center; align-items: center;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                    </svg>
                </div>
            </div>

            <div id="default-form">
                <h2 style="color: #f8fafc; margin-bottom: 20px; font-size: 1.75rem; font-weight: 600;">Secure Access
                </h2>
                <p style="color: #94a3b8; margin-bottom: 30px; font-size: 0.95rem;">Please enter your PIN to continue
                </p>

                <div style="margin-bottom: 20px;">
                    <label for="department-select"
                        style="display: block; text-align: left; margin-bottom: 8px; color: #cbd5e1; font-size: 0.9rem; font-weight: 500;">Dashboard</label>
                    <select id="department-select"
                        style="width: 100%; padding: 14px; border: 1px solid #475569; background-color: #0f172a; color: #e2e8f0; font-size: 1rem; border-radius: 8px; transition: all 0.2s ease; outline: none;">
                        <option value="sales">Sales</option>
                        <option value="delivery">Delivery - Forecast</option>
                        <option value="delivery_monitoring">Delivery - Monitoring</option>
                        <option value="delivery_job">Delivery - Job Monitoring</option>
                        <option value="delivery_finish_good">Delivery - Finish Good</option>
                        <option value="delivery_mit">Delivery - MIT</option>
                        <option value="delivery_cgr">Delivery - CGR</option>
                        <option value="finance_profit">Finance - Profit</option>
                        <option value="finance_invoice">Finance - Invoice Profit</option>
                        <option value="finance_model">Finance - Profit Model</option>
                        <option value="finance_rcd">Finance - RCD</option>
                        <option value="production">Production</option>
                        <option value="ppic_job">PPIC - Job Monitoring</option>
                        <option value="ppic_stock">PPIC - Stock Monitoring</option>
                        <option value="production">Production</option>
                        <option value="purchasing">Purchasing</option>
                        <option value="purchasing_pr">Purchasing PR</option>
                        <option value="purchasing_project">Purchasing PO Project</option>
                        <option value="purchasing_ppic">Purchasing PO PPIC</option>
                        <option value="purchasing_reguler">Purchasing PO Reguler</option>
                    </select>
                </div>

                <div style="margin-bottom: 30px;">
                    <label for="pin-input"
                        style="display: block; text-align: left; margin-bottom: 8px; color: #cbd5e1; font-size: 0.9rem; font-weight: 500;">PIN
                        Code</label>
                    <input type="password" id="pin-input" maxlength="4" placeholder="••••"
                        style="width: 100%; padding: 14px; border: 1px solid #475569; background-color: #0f172a; color: #e2e8f0; font-size: 1.25rem; border-radius: 8px; letter-spacing: 8px; text-align: center; transition: all 0.2s ease; outline: none;"
                        autocomplete="off">
                </div>

                <button id="pin-submit"
                    style="width: 100%; padding: 14px; background-color: #0ea5e9; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 1rem; font-weight: 600; transition: all 0.2s ease; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    Authenticate
                </button>
            </div>

            <div id="reset-form" style="display: none;">
                <h2 style="color: #f8fafc; margin-bottom: 20px; font-size: 1.75rem; font-weight: 600;">Reset PIN</h2>
                <p style="color: #94a3b8; margin-bottom: 30px; font-size: 0.95rem;">Enter your current PIN and create a
                    new one</p>

                <div style="margin-bottom: 20px;">
                    <label for="old-pin-input"
                        style="display: block; text-align: left; margin-bottom: 8px; color: #cbd5e1; font-size: 0.9rem; font-weight: 500;">Current
                        PIN</label>
                    <input type="password" id="old-pin-input" maxlength="4" placeholder="••••"
                        style="width: 100%; padding: 14px; border: 1px solid #475569; background-color: #0f172a; color: #e2e8f0; font-size: 1.25rem; border-radius: 8px; letter-spacing: 8px; text-align: center; transition: all 0.2s ease; outline: none;">
                </div>

                <div style="margin-bottom: 30px;">
                    <label for="new-pin-input"
                        style="display: block; text-align: left; margin-bottom: 8px; color: #cbd5e1; font-size: 0.9rem; font-weight: 500;">New
                        PIN</label>
                    <input type="password" id="new-pin-input" maxlength="4" placeholder="••••"
                        style="width: 100%; padding: 14px; border: 1px solid #475569; background-color: #0f172a; color: #e2e8f0; font-size: 1.25rem; border-radius: 8px; letter-spacing: 8px; text-align: center; transition: all 0.2s ease; outline: none;">
                </div>

                <button id="save-changes"
                    style="width: 100%; padding: 14px; background-color: #10b981; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 1rem; font-weight: 600; transition: all 0.2s ease; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    Update PIN
                </button>

                <button id="cancel-reset"
                    style="width: 100%; padding: 12px; background-color: transparent; color: #94a3b8; border: 1px solid #475569; border-radius: 8px; cursor: pointer; font-size: 0.95rem; margin-top: 12px; transition: all 0.2s ease;">
                    Cancel
                </button>
            </div>

            <p id="pin-error"
                style="color: #ef4444; margin-top: 16px; height: 1.2em; font-size: 0.9rem; font-weight: 500;"></p>

            <button id="reset-button" type="button"
                style="position: absolute; top: 16px; right: 16px; cursor: pointer; padding: 8px; border: none; background: transparent; border-radius: 6px; transition: all 0.2s ease; display: flex; align-items: center; color: #94a3b8; font-size: 0.8rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path
                        d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
                </svg>
                <span style="margin-left: 6px;">Reset PIN</span>
            </button>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const resetButton = document.getElementById('reset-button');
        const defaultForm = document.getElementById('default-form');
        const resetForm = document.getElementById('reset-form');
        const cancelReset = document.getElementById('cancel-reset');
        const pinError = document.getElementById('pin-error');
        const pinInput = document.getElementById('pin-input');
        const oldPinInput = document.getElementById('old-pin-input');
        const newPinInput = document.getElementById('new-pin-input');
        const departmentSelect = document.getElementById('department-select');
        const saveChangesButton = document.getElementById('save-changes');
        const pinSubmitButton = document.getElementById('pin-submit');
        const salesDashboardLink = document.getElementById('sales-dashboard');
        const deliveryDashboardLink = document.getElementById('delivery-dashboard');
        const deliveryStockDashboardLink = document.getElementById('delivery-delivery-monitoring');
        const deliveryJobDashboardLink = document.getElementById('delivery-job-monitoring');
        const deliveryFinishGoodLink = document.getElementById('delivery-finish-good');
        const deliveryMITDashboardLink = document.getElementById('delivery-mit-dashboard');
        const deliveryCGRMonitoringLink = document.getElementById('delivery-cgr-monitoring');
        const productionDashboardLink = document.getElementById('production-dashboard');
        const invoiceDashboardFinanceLink = document.getElementById('invoice-dashboard-finance');
        const profitModelFinanceLink = document.getElementById('invoice-dashboard-model');
        const rcdDashboardFinanceLink = document.getElementById('rcd-dashboard-finance');
        const jobDashboardPpicLink = document.getElementById('job-dashboard-ppic');
        const stockDashboardPpicLink = document.getElementById('stock-dashboard-ppic');
        const purchaseDashboardPurchaseLink = document.getElementById('purchase-dashboard-purchase');
        const purchaseDashboardPurchasePRLink = document.getElementById('purchase-dashboard-monitoring-pr');
        const purchaseDashboardPurchasePOProjectLink = document.getElementById('purchase-dashboard-po-project');
        const purchaseDashboardPurchasePOPPICLink = document.getElementById('purchase-dashboard-po-ppic');
        const purchaseDashboardPurchasePORegulerLink = document.getElementById('purchase-dashboard-po-reguler');
        const pinPopupOverlay = document.getElementById('pin-popup-overlay');

        const validatePIN = (pin, fieldName = 'PIN') => {
            if (pin.length !== 4 || !/^\d+$/.test(pin)) {
                return `${fieldName} harus 4 digit angka`;
            }
            return '';
        };

        const setButtonLoadingState = (button, isLoading, loadingText = 'Memproses...') => {
            if (!button) return;

            const loadingLayoutClasses = ['inline-flex', 'items-center', 'justify-center'];

            if (isLoading) {
                if (!button.dataset.originalText) {
                    button.dataset.originalText = button.textContent.trim();
                }
                button.disabled = true;
                button.classList.add('opacity-70', 'cursor-wait', ...loadingLayoutClasses);

                button.innerHTML = '';

                const spinner = document.createElement('span');
                spinner.classList.add(
                    'animate-spin',
                    'inline-block',
                    'h-4',
                    'w-4',
                    'mr-2',
                    'border-2',
                    'border-solid',
                    'border-current',
                    'border-t-transparent',
                    'rounded-full'
                );

                const loadingTextSpan = document.createElement('span');
                loadingTextSpan.textContent = loadingText;

                button.appendChild(spinner);
                button.appendChild(loadingTextSpan);

            } else {
                button.disabled = false;
                button.classList.remove('opacity-70', 'cursor-wait', ...loadingLayoutClasses);

                button.innerHTML = '';

                if (button.dataset.originalText) {
                    button.textContent = button.dataset.originalText;
                    delete button.dataset.originalText;
                }
            }
        };

        const setupFormTransitions = () => {
            if (resetButton && defaultForm && resetForm) {
                resetButton.addEventListener('click', () => {
                    defaultForm.style.display = 'none';
                    resetForm.style.display = 'block';
                    if (pinError) pinError.textContent = '';
                    if (oldPinInput) oldPinInput.focus();
                });
            }

            if (cancelReset && resetForm && defaultForm) {
                cancelReset.addEventListener('click', () => {
                    resetForm.style.display = 'none';
                    defaultForm.style.display = 'block';
                    if (pinError) pinError.textContent = '';
                    if (pinInput) pinInput.focus();
                });
            }
        };

        const setupInputStyling = () => {
            document.querySelectorAll('input').forEach(input => {
                const focusClasses = ['border-sky-500', 'ring-2', 'ring-sky-500',
                    'ring-opacity-20'
                ];
                const normalBorderClass = 'border-slate-600';

                input.addEventListener('focus', () => {
                    input.classList.remove(normalBorderClass);
                    input.classList.add(...focusClasses);
                });

                input.addEventListener('blur', () => {
                    input.classList.remove(...focusClasses);
                    input.classList.add(normalBorderClass);
                });
            });
        };

        const setupButtonHover = () => {
            document.querySelectorAll('button').forEach(button => {
                let hoverClasses = [];
                let baseClassesToRemoveOnHover = [];

                switch (button.id) {
                    case 'pin-submit':
                        hoverClasses = ['bg-sky-600'];
                        baseClassesToRemoveOnHover = ['bg-sky-500'];
                        break;
                    case 'save-changes':
                        hoverClasses = ['bg-emerald-600'];
                        baseClassesToRemoveOnHover = ['bg-emerald-500'];
                        break;
                    case 'cancel-reset':
                        hoverClasses = ['bg-white/5', 'border-slate-500'];
                        baseClassesToRemoveOnHover = ['bg-transparent', 'border-slate-600'];
                        break;
                    case 'reset-button':
                        hoverClasses = ['bg-white/5', 'text-slate-200'];
                        baseClassesToRemoveOnHover = ['text-slate-400'];
                        break;
                    default:
                        return;
                }

                button.addEventListener('mouseenter', () => {
                    if (!button.disabled) {
                        button.classList.remove(...baseClassesToRemoveOnHover);
                        button.classList.add(...hoverClasses);
                    }
                });

                button.addEventListener('mouseleave', () => {
                    if (!button.disabled) {
                        button.classList.remove(...hoverClasses);
                        button.classList.add(...baseClassesToRemoveOnHover);
                    }
                });
            });
        };

        const setupPinOperations = () => {
            if (saveChangesButton) {
                saveChangesButton.addEventListener('click', async () => {
                    setButtonLoadingState(saveChangesButton, true, 'Menyimpan...');
                    if (pinError) pinError.textContent = '';

                    const errorMessage = validatePIN(oldPinInput?.value, 'PIN lama') ||
                        validatePIN(newPinInput?.value, 'PIN baru');

                    if (errorMessage) {
                        if (pinError) {
                            pinError.style.color = '#ef4444';
                            pinError.textContent = errorMessage;
                        }
                        setButtonLoadingState(saveChangesButton, false);
                        return;
                    }

                    try {
                        const response = await fetch(
                            `/api/update_pin_${departmentSelect?.value}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    Department: departmentSelect?.value,
                                    OldPin: oldPinInput?.value,
                                    NewPin: newPinInput?.value
                                })
                            });

                        const data = await response.json();

                        if (pinError) {
                            pinError.style.color = data.success ? '#10b981' : '#ef4444';
                            pinError.textContent = data.message;
                        }

                        if (data.success) {
                            setTimeout(() => {
                                if (resetForm) resetForm.style.display = 'none';
                                if (defaultForm) defaultForm.style.display = 'block';
                                if (oldPinInput) oldPinInput.value = '';
                                if (newPinInput) newPinInput.value = '';
                                if (pinInput) pinInput.focus();
                                setButtonLoadingState(saveChangesButton, false);
                            }, 2000);
                        } else {
                            setButtonLoadingState(saveChangesButton, false);
                        }
                    } catch (error) {
                        if (pinError) {
                            pinError.style.color = '#ef4444';
                            pinError.textContent = error.message ||
                                'Terjadi kesalahan jaringan.';
                        }
                        setButtonLoadingState(saveChangesButton, false);
                    }
                });
            }

            if (pinSubmitButton && pinInput) {
                // Trigger authenticate on Enter key press
                pinInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        pinSubmitButton.click();
                    }
                });

                pinSubmitButton.addEventListener('click', async () => {
                    setButtonLoadingState(pinSubmitButton, true, 'Memverifikasi...');
                    if (pinError) pinError.textContent = '';

                    const errorMessage = validatePIN(pinInput?.value);

                    if (errorMessage) {
                        if (pinError) pinError.textContent = errorMessage;
                        setButtonLoadingState(pinSubmitButton, false);
                        return;
                    }

                    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute(
                        'content') : null;

                    try {
                        const response = await fetch(
                            `/api/check_pin_${departmentSelect?.value}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    ...(csrfToken && {
                                        'X-CSRF-TOKEN': csrfToken
                                    })
                                },
                                body: JSON.stringify({
                                    Department: departmentSelect?.value,
                                    Pin: pinInput?.value
                                }),
                                credentials: 'include'
                            });

                        const data = await response.json();

                        if (data.success) {
                            if (pinPopupOverlay) pinPopupOverlay.style.display = 'none';
                            window.location.href =
                                departmentSelect?.value === 'finance_invoice' ?
                                '/dashboard/profitability/invoice' :
                                departmentSelect?.value === 'finance_model' ?
                                '/dashboard/profitability/Model' :
                                departmentSelect?.value === 'finance_rcd' ?
                                '/dashboard/profitability/rcd' :
                                departmentSelect?.value === 'delivery' ? '/dashboard/delivery' :
                                departmentSelect?.value === 'delivery_monitoring' ?
                                '/dashboard/delivery/delivery_monitoring' :
                                departmentSelect?.value === 'delivery_job' ?
                                '/dashboard/delivery/job_monitoring' :
                                departmentSelect?.value === 'delivery_finish_good' ?
                                '/dashboard/delivery/finish_good' :
                                departmentSelect?.value === 'delivery_mit' ?
                                '/dashboard/delivery/mit_dashboard' :
                                departmentSelect?.value === 'delivery_cgr' ?
                                '/dashboard/delivery/cgr_monitoring' :
                                departmentSelect?.value === 'sales' ? '/dashboard/sales' :
                                departmentSelect?.value === 'ppic_job' ? '/dashboard/ppic' :
                                departmentSelect?.value === 'ppic_stock' ?
                                '/dashboard/ppic/stock' :
                                departmentSelect?.value === 'production' ?
                                '/dashboard/production' :
                                departmentSelect?.value === 'purchasing_project' ?
                                '/dashboard/purchasing/po_project_monitoring' :
                                departmentSelect?.value === 'purchasing_pr' ?
                                '/dashboard/purchasing/monitoring_pr' :
                                departmentSelect?.value === 'purchasing_ppic' ?
                                '/dashboard/purchasing/po_ppic_monitoring' :
                                departmentSelect?.value === 'purchasing_reguler' ?
                                '/dashboard/purchasing/po_reguler_monitoring' :
                                '/dashboard/purchasing/report';
                        } else {
                            if (pinError) pinError.textContent = data.message ||
                                'PIN akses salah';
                            setButtonLoadingState(pinSubmitButton, false);
                        }
                    } catch (error) {
                        if (pinError) pinError.textContent = error.message ||
                            'Terjadi kesalahan jaringan.';
                        setButtonLoadingState(pinSubmitButton, false);
                    }
                });
            }
        };

        const setupDashboardLinks = () => {
            const setupLink = (linkElement, departmentValue) => {
                if (linkElement) {
                    linkElement.addEventListener('click', e => {
                        e.preventDefault();
                        if (departmentSelect) departmentSelect.value = departmentValue;
                        if (pinPopupOverlay) pinPopupOverlay.style.display = 'flex';
                        if (pinInput) {
                            pinInput.value = '';
                            pinInput.focus();
                        }
                        if (pinError) pinError.textContent = '';
                    });
                }
            };

            setupLink(salesDashboardLink, 'sales');
            setupLink(deliveryDashboardLink, 'delivery');
            setupLink(deliveryStockDashboardLink, 'delivery_monitoring');
            setupLink(deliveryJobDashboardLink, 'delivery_job');
            setupLink(deliveryFinishGoodLink, 'delivery_finish_good');
            setupLink(deliveryMITDashboardLink, 'delivery_mit');
            setupLink(deliveryCGRMonitoringLink, 'delivery_cgr');
            setupLink(productionDashboardLink, 'production');
            setupLink(profitModelFinanceLink, 'finance_model');
            setupLink(invoiceDashboardFinanceLink, 'finance_invoice');
            setupLink(rcdDashboardFinanceLink, 'finance_rcd');
            setupLink(jobDashboardPpicLink, 'ppic_job');
            setupLink(stockDashboardPpicLink, 'ppic_stock');
            setupLink(purchaseDashboardPurchasePRLink, 'purchasing_pr');
            setupLink(purchaseDashboardPurchaseLink, 'purchasing');
            setupLink(purchaseDashboardPurchasePOProjectLink, 'purchasing_project');
            setupLink(purchaseDashboardPurchasePOPPICLink, 'purchasing_ppic');
            setupLink(purchaseDashboardPurchasePORegulerLink, 'purchasing_reguler');
        };

        if (pinInput) pinInput.focus();
        setupFormTransitions();
        setupInputStyling();
        setupButtonHover();
        setupPinOperations();
        setupDashboardLinks();
    });

    // Alpine.js initialization

    document.addEventListener("alpine:init", () => {
        Alpine.data("sidebar", () => ({
            init() {
                const selector = document.querySelector('.sidebar ul a[href="' + window.location
                    .pathname + '"]');
                if (selector) {
                    selector.classList.add('active');
                    const ul = selector.closest('ul.sub-menu');
                    if (ul) {
                        let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                        if (ele) {
                            ele = ele[0];
                            setTimeout(() => {
                                ele.click();
                            });
                        }
                    }
                }
            },
        }));
    });
</script>
