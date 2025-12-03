<x-app-layout>
     <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Product') }}
        </h2>
    </x-slot>

    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                @if (session('success'))
                    <div id="success-alert"
                        class="mb-4 flex items-center justify-between rounded-md bg-green-100 p-4 text-green-800 border border-green-300" style="background: green;color: white;">

                        <span>{{ session('success') }}</span>

                        <button id="close-alert" class="text-green-900 font-bold text-xl leading-none">
                            &times;
                        </button>
                    </div>

                    <script>
                        document.getElementById('close-alert').addEventListener('click', function () {
                            document.getElementById('success-alert').style.display = 'none';
                        });
                    </script>
                @endif
                <div class="max-w-xl">

                <form method="post" action="{{ route('product_upload.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="update_password_current_password" :value="__('Product CSV')" />
                            <input  type="file"   id="product_csv"   name="product_csv"   class="mt-1 block w-full border rounded p-2"/>                        
                            @error('product_csv')   <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                        </div>
                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
          </div>
    </div>

</x-app-layout>