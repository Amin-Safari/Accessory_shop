<div>
    <h1 class="text-2xl font-bold mb-6">پروفایل کاربری</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Form --}}
        <div class="lg:col-span-2">
            <livewire:account.profile.profile-form />
        </div>

        {{-- Sidebar Actions --}}
        <div class="space-y-6">
            {{-- Phone Change Card --}}
            <livewire:account.profile.change-phone />

            {{-- Delete Account Card --}}
            <livewire:account.profile.delete-account />
        </div>
    </div>
</div>
