<x-guest-layout>
    <div class="max-w-md mx-auto">
        <div class="bg-red-900 bg-opacity-20 border border-red-500 rounded-none p-6">
            <div class="flex items-start gap-4">
                <div class="text-red-500 text-3xl">⚠️</div>
                <div>
                    <h2 class="text-lg font-bold text-red-400 mb-2">Entra ID Not Configured</h2>
                    <p class="text-red-300 text-sm mb-4">
                        Microsoft Entra ID credentials are not configured yet.
                    </p>
                    <div class="bg-black bg-opacity-40 rounded-none p-3 mb-4 text-xs text-gray-300 font-mono">
                        <p>ENTRA_CLIENT_ID not set</p>
                        <p>ENTRA_CLIENT_SECRET not set</p>
                        <p>ENTRA_TENANT_ID not set</p>
                    </div>
                    <p class="text-red-300 text-sm mb-3">
                        To enable Entra ID login:
                    </p>
                    <ol class="text-red-300 text-sm list-decimal list-inside space-y-1">
                        <li>See <strong>ENTRA_ID_SETUP.md</strong> for instructions</li>
                        <li>Register an app in Azure Portal</li>
                        <li>Add credentials to your <strong>.env</strong> file</li>
                        <li>Restart the application</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-400 hover:text-blue-300">
                ← Back to Login
            </a>
        </div>
    </div>
</x-guest-layout>
