<x-admin-layout>
    <x-slot name="header">Pengaturan Profil</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="separator">/</span>
        <span>Pengaturan Profil</span>
    </x-slot>

    {{-- Profile Header Card --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                <span class="text-2xl font-black text-blue-700">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold mb-1">{{ Auth::user()->name }}</h3>
                <p class="text-blue-100 text-sm mb-2">{{ Auth::user()->email }}</p>
                <div class="flex gap-2">
                    <span class="badge" style="background:rgba(255,255,255,0.15);color:white;">{{ ucfirst(Auth::user()->role) }}</span>
                    <span class="badge" style="background:rgba(34,197,94,0.2);color:#bbf7d0;">Active</span>
                </div>
            </div>
            <div class="text-right hidden md:block">
                <p class="text-sm text-blue-100">Member Since</p>
                <p class="text-lg font-bold">{{ Auth::user()->created_at->format('M Y') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Account Info
                    </h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-500">Role</span>
                            <span class="font-bold text-slate-800 capitalize">{{ Auth::user()->role }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-500">Status</span>
                            <span class="font-bold text-green-600">Active</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-slate-500">Last Login</span>
                            <span class="font-bold text-slate-800">{{ now()->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="background:#eff6ff;border-color:#bfdbfe;">
                <div class="card-body">
                    <h4 class="font-bold text-blue-900 mb-2">Security Tips</h4>
                    <ul class="text-xs text-blue-700 space-y-2">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="true"></path>
                            </svg>
                            <span>Gunakan password yang kuat</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="true"></path>
                            </svg>
                            <span>Update password secara berkala</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Right Content - Forms --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Profile Information --}}
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Profile Information</h3>
                            <p class="text-sm text-slate-500">Update your account's profile information and email address.</p>
                        </div>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-5">
                            <div class="form-group">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="form-input @error('name') border-red-500 @enderror" required>
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="form-input @error('email') border-red-500 @enderror" required>
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="btn btn-primary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                    </svg>
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Update Password --}}
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Update Password</h3>
                            <p class="text-sm text-slate-500">Ensure your account is using a long, random password to stay secure.</p>
                        </div>
                    </div>

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-5">
                            <div class="form-group">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" placeholder="••••••••" class="form-input @error('current_password') border-red-500 @enderror" required>
                                @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" placeholder="••••••••" class="form-input @error('password') border-red-500 @enderror" required>
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" placeholder="••••••••" class="form-input @error('password_confirmation') border-red-500 @enderror" required>
                                @error('password_confirmation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="btn" style="background:#9333ea;color:white;">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Update Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>