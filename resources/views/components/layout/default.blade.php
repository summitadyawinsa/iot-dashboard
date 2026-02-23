<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset='utf-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SAI - Monitoring System' }}</title>

    <meta name='viewport' content='width=device-width, initial-scale=1' />
    <link rel="icon" type="image/svg" href="/assets/images/favicon.ico" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <script src="/assets/js/perfect-scrollbar.min.js"></script>
    <script defer src="/assets/js/popper.min.js"></script>
    <script defer src="/assets/js/tippy-bundle.umd.min.js"></script>
    <script defer src="/assets/js/sweetalert.min.js"></script>
    <script src="/assets/js/gauge.js"></script>
    @vite(['resources/css/app.css'])
    @vite('resources/js/app.js')
</head>


<body x-data="main" class="antialiased relative font-nunito text-sm font-normal overflow-x-hidden"
    :class="[$store.app.sidebar ? 'toggle-sidebar' : '', $store.app.theme === 'dark' || $store.app.isDarkMode ? 'dark' : '',
        $store.app.menu, $store.app.layout, $store.app
        .rtlClass
    ]">
    <!-- sidebar menu overlay -->
    <div x-cloak class="fixed inset-0 bg-[black]/60 z-50 lg:hidden" :class="{ 'hidden': !$store.app.sidebar }"
        @click="$store.app.toggleSidebar()"></div>

    <!-- screen loader -->
    <div
        class="screen_loader fixed inset-0 bg-[#fafafa] dark:bg-[#060818] z-[60] grid place-content-center animate__animated">
        <svg width="64" height="64" viewBox="0 0 135 135" xmlns="http://www.w3.org/2000/svg" fill="#4361ee">
            <path
                d="M67.447 58c5.523 0 10-4.477 10-10s-4.477-10-10-10-10 4.477-10 10 4.477 10 10 10zm9.448 9.447c0 5.523 4.477 10 10 10 5.522 0 10-4.477 10-10s-4.478-10-10-10c-5.523 0-10 4.477-10 10zm-9.448 9.448c-5.523 0-10 4.477-10 10 0 5.522 4.477 10 10 10s10-4.478 10-10c0-5.523-4.477-10-10-10zM58 67.447c0-5.523-4.477-10-10-10s-10 4.477-10 10 4.477 10 10 10 10-4.477 10-10z">
                <animateTransform attributeName="transform" type="rotate" from="0 67 67" to="-360 67 67" dur="2.5s"
                    repeatCount="indefinite" />
            </path>
            <path
                d="M28.19 40.31c6.627 0 12-5.374 12-12 0-6.628-5.373-12-12-12-6.628 0-12 5.372-12 12 0 6.626 5.372 12 12 12zm30.72-19.825c4.686 4.687 12.284 4.687 16.97 0 4.686-4.686 4.686-12.284 0-16.97-4.686-4.687-12.284-4.687-16.97 0-4.687 4.686-4.687 12.284 0 16.97zm35.74 7.705c0 6.627 5.37 12 12 12 6.626 0 12-5.373 12-12 0-6.628-5.374-12-12-12-6.63 0-12 5.372-12 12zm19.822 30.72c-4.686 4.686-4.686 12.284 0 16.97 4.687 4.686 12.285 4.686 16.97 0 4.687-4.686 4.687-12.284 0-16.97-4.685-4.687-12.283-4.687-16.97 0zm-7.704 35.74c-6.627 0-12 5.37-12 12 0 6.626 5.373 12 12 12s12-5.374 12-12c0-6.63-5.373-12-12-12zm-30.72 19.822c-4.686-4.686-12.284-4.686-16.97 0-4.686 4.687-4.686 12.285 0 16.97 4.686 4.687 12.284 4.687 16.97 0 4.687-4.685 4.687-12.283 0-16.97zm-35.74-7.704c0-6.627-5.372-12-12-12-6.626 0-12 5.373-12 12s5.374 12 12 12c6.628 0 12-5.373 12-12zm-19.823-30.72c4.687-4.686 4.687-12.284 0-16.97-4.686-4.686-12.284-4.686-16.97 0-4.687 4.686-4.687 12.284 0 16.97 4.686 4.687 12.284 4.687 16.97 0z">
                <animateTransform attributeName="transform" type="rotate" from="0 67 67" to="360 67 67" dur="8s"
                    repeatCount="indefinite" />
            </path>
        </svg>
    </div>

    <div class="fixed bottom-6 ltr:right-6 rtl:left-6 z-50" x-data="scrollToTop">
        <template x-if="showTopButton">
            <button type="button"
                class="btn btn-outline-primary rounded-full p-2 animate-pulse bg-[#fafafa] dark:bg-[#060818] dark:hover:bg-primary"
                @click="goToTop">
                <svg width="24" height="24" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd"
                        d="M12 20.75C12.4142 20.75 12.75 20.4142 12.75 20L12.75 10.75L11.25 10.75L11.25 20C11.25 20.4142 11.5858 20.75 12 20.75Z"
                        fill="currentColor" />
                    <path
                        d="M6.00002 10.75C5.69667 10.75 5.4232 10.5673 5.30711 10.287C5.19103 10.0068 5.25519 9.68417 5.46969 9.46967L11.4697 3.46967C11.6103 3.32902 11.8011 3.25 12 3.25C12.1989 3.25 12.3897 3.32902 12.5304 3.46967L18.5304 9.46967C18.7449 9.68417 18.809 10.0068 18.6929 10.287C18.5768 10.5673 18.3034 10.75 18 10.75L6.00002 10.75Z"
                        fill="currentColor" />
                </svg>
            </button>
        </template>
    </div>

    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("scrollToTop", () => ({
                showTopButton: false,
                init() {
                    window.onscroll = () => {
                        this.scrollFunction();
                    };
                },

                scrollFunction() {
                    if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                        this.showTopButton = true;
                    } else {
                        this.showTopButton = false;
                    }
                },

                goToTop() {
                    document.body.scrollTop = 0;
                    document.documentElement.scrollTop = 0;
                },
            }));
        });
    </script>

    <x-common.theme-customiser />

    <div class="main-container text-black dark:text-white-dark min-h-screen" :class="[$store.app.navbar]">

        <x-common.sidebar />

        <div class="main-content flex flex-col min-h-screen">
            <x-common.header />

            <div class="p-6 animate__animated" :class="[$store.app.animation]">
                {{ $slot }}
            </div>

            <x-common.footer />
        </div>
    </div>

    <script src="/assets/js/alpine-collaspe.min.js"></script>
    <script defer src="/assets/js/alpine-ui.min.js"></script>
    <script defer src="/assets/js/alpine-focus.min.js"></script>
    <script defer src="/assets/js/alpine.min.js"></script>
    <script src="/assets/js/alpine-persist.min.js"></script>
    <script src="/assets/js/custom.js"></script>

    <script>
        async function showAlert(type) {
            if (type === 1) {
                new window.Swal({
                    title: 'Saved succesfully',
                    padding: '2em',
                    customClass: 'sweet-alerts'
                });
            } else if (type === 2) {
                new window.Swal({
                    icon: 'success',
                    title: 'Good job!',
                    text: 'You clicked the!',
                    padding: '2em',
                    customClass: 'sweet-alerts'
                });
            } else if (type === 3) {
                const ipAPI = 'https://api.ipify.org?format=json';
                new window.Swal({
                    title: 'Your public IP',
                    confirmButtonText: 'Show my public IP',
                    text: 'Your public IP will be received ' + 'via AJAX request',
                    showLoaderOnConfirm: true,
                    customClass: 'sweet-alerts',
                    preConfirm: () => {
                        return fetch(ipAPI)
                            .then((response) => {
                                return response.json();
                            })
                            .then((data) => {
                                new window.Swal({
                                    title: data.ip,
                                    customClass: 'sweet-alerts',
                                });
                            })
                            .catch(() => {
                                new window.Swal({
                                    type: 'error',
                                    title: 'Unable to get your public IP',
                                    customClass: 'sweet-alerts',
                                });
                            });
                    },
                });
            } else if (type === 4) {
                new window.Swal({
                    icon: 'question',
                    title: 'The Internet?',
                    text: 'That thing is still around?',
                    padding: '2em',
                    customClass: 'sweet-alerts',
                });
            } else if (type === 5) {
                const steps = ['1', '2', '3'];
                const swalQueueStep = window.Swal.mixin({
                    confirmButtonText: 'Next →',
                    showCancelButton: true,
                    progressSteps: steps,
                    input: 'text',
                    inputAttributes: {
                        required: true,
                    },
                    validationMessage: 'This field is required',
                    padding: '2em',
                    customClass: 'sweet-alerts'
                });

                const values = [];
                let currentStep;

                for (currentStep = 0; currentStep < steps.length;) {
                    const result = await swalQueueStep.fire({
                        title: `Question ${steps[currentStep]}`,
                        text: currentStep == 0 ? 'Chaining swal modals is easy.' : '',
                        inputValue: values[currentStep] || '',
                        showCancelButton: currentStep > 0,
                        currentProgressStep: currentStep,
                        customClass: 'sweet-alerts'
                    });
                    if (result.value) {
                        values[currentStep] = result.value;
                        currentStep++;
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        currentStep--;
                    } else {
                        break;
                    }
                }

                if (currentStep === steps.length) {
                    window.Swal.fire({
                        title: 'All done!',
                        padding: '2em',
                        html: 'Your answers: <pre>' + JSON.stringify(values) + '</pre>',
                        confirmButtonText: 'Lovely!',
                        customClass: 'sweet-alerts'
                    });
                }
            } else if (type === 6) {
                new window.Swal({
                    title: 'Custom animation with Animate.css',
                    showClass: {
                        popup: 'animate__animated animate__flip'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    },
                    padding: '2em',
                    customClass: 'sweet-alerts'
                });
            } else if (type === 7) {
                let timerInterval;

                new window.Swal({
                    title: 'Auto close alert!',
                    html: 'I will close in <b></b> milliseconds.',
                    timer: 2000,
                    timerProgressBar: true,
                    customClass: 'sweet-alerts',
                    didOpen: () => {
                        window.Swal.showLoading();
                        const b = window.Swal.getHtmlContainer().querySelector('b');
                        timerInterval = setInterval(() => {
                            b.textContent = window.Swal.getTimerLeft();
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    },
                }).then((result) => {
                    if (result.dismiss === window.Swal.DismissReason.timer) {
                        console.log('I was closed by the timer');
                    }
                });
            } else if (type === 8) {
                new window.Swal({
                    title: 'Sweet!',
                    text: 'Modal with a custom image.',
                    imageUrl: ("/assets/images/custom-swal.svg"),
                    imageWidth: 224,
                    imageHeight: 'auto',
                    imageAlt: 'Custom image',
                    animation: false,
                    padding: '2em',
                    customClass: 'sweet-alerts'
                });
            } else if (type === 9) {
                new window.Swal({
                    icon: 'info',
                    title: '<i>HTML</i> <u>example</u>',
                    html: 'You can use <b>bold text</b>, ' + '<a href="//github.com">links</a> ' +
                        'and other HTML tags',
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: '<i class="flaticon-checked-1"></i> Great!',
                    confirmButtonAriaLabel: 'Thumbs up, great!',
                    cancelButtonText: '<i class="flaticon-cancel-circle"></i> Cancel',
                    cancelButtonAriaLabel: 'Thumbs down',
                    padding: '2em',
                    customClass: 'sweet-alerts'
                });
            } else if (type === 10) {
                new window.Swal({
                    icon: 'warning',
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    padding: '2em',
                    customClass: 'sweet-alerts'
                }).then((result) => {
                    if (result.value) {
                        new window.Swal({
                            title: 'Deleted!',
                            text: 'Your file has been deleted.',
                            icon: 'success',
                            customClass: 'sweet-alerts'
                        });
                    }
                });
            } else if (type === 11) {
                const swalWithBootstrapButtons = window.Swal.mixin({
                    customClass: {
                        popup: 'sweet-alerts',
                        confirmButton: 'btn btn-secondary',
                        cancelButton: 'btn btn-dark ltr:mr-3 rtl:ml-3',
                    },
                    buttonsStyling: false,
                });
                swalWithBootstrapButtons
                    .fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true,
                        padding: '2em',
                    })
                    .then((result) => {
                        if (result.value) {
                            swalWithBootstrapButtons.fire('Deleted!', 'Your file has been deleted.', 'success');
                        } else if (result.dismiss === window.Swal.DismissReason.cancel) {
                            swalWithBootstrapButtons.fire('Cancelled', 'Your imaginary file is safe :)', 'error');
                        }
                    });
            } else if (type === 12) {
                new window.Swal({
                    title: 'Custom width, padding, background.',
                    width: 600,
                    padding: '7em',
                    customClass: 'background-modal sweet-alerts',
                    background: '#fff url(' + ("/assets/images/sweet-bg.jpg") +
                        ') no-repeat 100% 100%',
                });
            } else if (type === 13) {
                new window.Swal({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                    footer: '<a href="javascript:;">Why do I have this issue?</a>',
                    padding: '2em',
                    customClass: 'sweet-alerts'
                });
            } else if (type === 14) {
                new window.Swal({
                    title: 'هل تريد الاستمرار؟',
                    confirmButtonText: 'نعم',
                    cancelButtonText: 'لا',
                    showCancelButton: true,
                    showCloseButton: true,
                    padding: '2em',
                    customClass: 'sweet-alerts'
                });
            } else if (type === 15) {
                const toast = window.Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                });
                toast.fire({
                    icon: 'success',
                    title: 'Signed in successfully',
                    padding: '10px 20px',
                });
            }
        };

        document.addEventListener("alpine:init", () => {
            Alpine.data("form", () => ({

                // highlightjs
                codeArr: [],
                toggleCode(name) {
                    if (this.codeArr.includes(name)) {
                        this.codeArr = this.codeArr.filter((d) => d != name);
                    } else {
                        this.codeArr.push(name);

                        setTimeout(() => {
                            document.querySelectorAll('pre.code').forEach(el => {
                                hljs.highlightElement(el);
                            });
                        });
                    }
                }

            }));
        });
    </script>


</body>

</html>
