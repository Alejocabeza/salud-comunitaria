<div x-data="toastComponent(@json(session('toast')))" x-init="init()"
    class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 z-50">
    <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
        <template x-if="show">
            <div x-transition:enter="transform ease-out duration-300 transition"
                x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="max-w-sm w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                <div class="p-4 flex items-start">
                    <div class="flex-shrink-0">
                        <template x-if="type === 'success'">
                            <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <template x-if="type === 'danger'">
                            <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </template>
                        <template x-if="type === 'info'">
                            <svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01" />
                            </svg>
                        </template>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="message"></p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-text="time"></p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="close()"
                            class="inline-flex text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        function toastComponent(initial) {
            return {
                show: false,
                message: '',
                type: 'info',
                time: '',
                timeout: null,

                init() {
                    if (initial) {
                        this.showToast(initial);
                    }

                    window.addEventListener('toast', (e) => {
                        this.showToast(e.detail || e);
                    });
                },

                showToast(payload) {
                    this.type = payload.type || 'info';
                    this.message = payload.message || '';
                    this.time = payload.time || new Date().toLocaleString();
                    this.show = true;

                    if (this.timeout) clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => this.close(), 5000);
                },

                close() {
                    this.show = false;
                    if (this.timeout) {
                        clearTimeout(this.timeout);
                        this.timeout = null;
                    }
                }
            }
        }
    </script>
</div>
