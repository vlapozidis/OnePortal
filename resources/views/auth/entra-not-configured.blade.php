<x-guest-layout>
    <div class="max-w-md mx-auto">
        <div class="bg-red-900 bg-opacity-20 border border-red-500 rounded-none p-6">
            <div class="flex items-start gap-4">
                <div class="text-red-500 text-3xl">⚠️</div>
                <div>
                    <h2 class="text-lg font-bold text-red-400 mb-2">{{ __('Entra ID Not Configured') }}</h2>
                    <p class="text-red-300 text-sm mb-4">
                        {{ __('Microsoft Entra ID credentials are not configured yet.') }}
                    </p>
                    <div class="bg-black bg-opacity-40 rounded-none p-3 mb-4 text-xs text-gray-300 font-mono">
                        <p>ENTRA_CLIENT_ID not set</p>
                        <p>ENTRA_CLIENT_SECRET not set</p>
                        <p>ENTRA_TENANT_ID not set</p>
                    </div>
                    <p class="text-red-300 text-sm mb-3">
                        {{ __('To enable Entra ID login:') }}
                    </p>
                    <ol class="text-red-300 text-sm list-decimal list-inside space-y-1">
                        <li>{{ __('See :file for instructions', ['file' => 'ENTRA_ID_SETUP.md']) }}</li>
                        <li>{{ __('Register an app in Azure Portal') }}</li>
                        <li>{{ __('Add credentials to your :file file', ['file' => '.env']) }}</li>
                        <li>{{ __('Restart the application') }}</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-400 hover:text-blue-300">
                <i class="bi bi-arrow-left mr-1"></i>{{ __('Back to Login') }}
            </a>
        </div>
    </div>
</x-guest-layout>
