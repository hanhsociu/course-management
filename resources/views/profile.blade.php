<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-0">Hồ sơ cá nhân</h2>
    </x-slot>

    <div class="container py-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <livewire:profile.update-profile-information-form />
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                        <livewire:profile.update-password-form />
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <livewire:profile.delete-user-form />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
