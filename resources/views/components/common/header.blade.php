 <header class="z-40" :class="{ 'dark': $store.app.semidark && $store.app.menu === 'horizontal' }">
     <div class="shadow-sm">
         <div class="relative bg-white flex w-full items-center px-5 py-2.5 dark:bg-[#0e1726]">
             <div class="horizontal-logo flex lg:hidden justify-between items-center ltr:mr-2 rtl:ml-2">
                 <a href="/" class="main-logo flex items-center shrink-0">
                     <img class="w-8 ltr:-ml-1 rtl:-mr-1 inline" src="/assets/images/favicon.ico" alt="image" />
                     <span
                         class="text-2xl ltr:ml-1.5 rtl:mr-1.5  font-semibold  align-middle hidden md:inline dark:text-white-light transition-all duration-300">SAI</span>
                 </a>

                 <a href="javascript:;"
                     class="collapse-icon flex-none dark:text-[#d0d2d6] hover:text-primary dark:hover:text-primary flex lg:hidden ltr:ml-2 rtl:mr-2 p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:bg-white-light/90 dark:hover:bg-dark/60"
                     @click="$store.app.toggleSidebar()">
                     <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                         <path d="M20 7L4 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                         <path opacity="0.5" d="M20 12L4 12" stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" />
                         <path d="M20 17L4 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                     </svg>
                 </a>
             </div>
             <div class="ltr:mr-2 rtl:ml-2 hidden sm:block">
             </div>
             <div x-data="header"
                 class="sm:flex-1 ltr:sm:ml-0 ltr:ml-auto sm:rtl:mr-0 rtl:mr-auto flex items-center space-x-1.5 lg:space-x-2 rtl:space-x-reverse dark:text-[#d0d2d6]">
                 <div class="sm:ltr:mr-auto sm:rtl:ml-auto" x-data="{ search: false }" @click.outside="search = false">
                     <span
                         class="text-2xl ltr:ml-1.5 rtl:mr-1.5  font-semibold  align-middle hidden md:inline dark:text-white-light transition-all duration-300">
                         REAL-TIME MONITORING SYSTEM<span id="inpt_machine_code"></span>
                     </span>
                 </div>
                 <button
                    class="flex items-center justify-center w-10 h-10 rounded-full transition hover:bg-gray-200 dark:hover:bg-gray-700"
                    @click="$store.app.theme = ($store.app.theme === 'dark' ? 'light' : 'dark')">
                    <!-- Icon Sun -->
                    <svg x-show="$store.app.theme === 'dark'" xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 18a6 6 0 100-12 6 6 0 000 12z" />
                        <path d="M12 2v2m0 16v2m10-10h-2M4 12H2m15.364-7.364l-1.414 1.414M7.05 16.95l-1.414 1.414m0-13.9l1.414 1.414M16.95 16.95l1.414 1.414" />
                    </svg>

                    <!-- Icon Moon -->
                    <svg x-show="$store.app.theme === 'light'" xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 text-gray-800 dark:text-gray-200" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
                    </svg>
                </button>

                 <div class="dropdown shrink-0" x-data="dropdown" @click.outside="open = false">
                     <span
                         class="align-middle text-2xl font-semibold transition-all duration-300 dark:text-white-light md:inline"
                         id="inpt_shift"></span>
                     <div id="MyClockDisplay"
                         class="align-middle text-2xl font-semibold transition-all duration-300 dark:text-white-light md:inline"
                         onload="showTime()"></div>
                 </div>
                 <div class="dropdown" x-data="dropdown" @click.outside="open = false"></div>
                 <div class="dropdown" x-data="dropdown" @click.outside="open = false"></div>
                 <div class="dropdown flex-shrink-0" x-data="dropdown" @click.outside="open = false"></div>
             </div>
         </div>

     </div>
 </header>
 <script>
     function showTime() {
         var date = new Date();
         var h = date.getHours(); // 0 - 23
         var m = date.getMinutes(); // 0 - 59
         var s = date.getSeconds(); // 0 - 59
         var day = date.getDate(); // 1 - 31
         var month = date.getMonth() + 1; // 0 - 11 (months are zero-based)
         var year = date.getFullYear();

         var hours = String(date.getHours()).padStart(2, '0');
         var minutes = String(date.getMinutes()).padStart(2, '0');
         var seconds = String(date.getSeconds()).padStart(2, '0');

         var session = "AM";
         var sess = parseInt(hours + minutes + seconds);

         if (h === 0) {
             h = 12;
         }
         if (sess > 115959) {
             h = h - 12;
             session = "PM";
         }
         h = (h < 10) ? "0" + h : h;
         m = (m < 10) ? "0" + m : m;
         s = (s < 10) ? "0" + s : s;
         var time = h + ":" + m + ":" + s + " " + session;
         var fullDate = day + "/" + month + "/" + year;
         var dateTime = fullDate + " " + time;
         document.getElementById("MyClockDisplay").innerText = dateTime;
         document.getElementById("MyClockDisplay").textContent = dateTime;
         setTimeout(showTime, 1000);
     }

     showTime();

     document.addEventListener("alpine:init", () => {
         Alpine.data("header", () => ({
             init() {
                 const selector = document.querySelector('ul.horizontal-menu a[href="' + window
                     .location.pathname + '"]');
                 if (selector) {
                     selector.classList.add('active');
                     const ul = selector.closest('ul.sub-menu');
                     if (ul) {
                         let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                         if (ele) {
                             ele = ele[0];
                             setTimeout(() => {
                                 ele.classList.add('active');
                             });
                         }
                     }
                 }
             },
         }));
     });
 </script>
