<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/header-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bookshelf.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Login page custom CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-12/assets/css/login-12.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .profile-avatar img {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5px solid #eaf1fb;
            background: #f4f8fc;
            box-shadow: 0 2px 12px rgba(60, 100, 210, 0.08);
            transition: outline 0.2s;
            cursor: pointer;
        }

        .avatar-options .avatar-option.selected {
            border-color: #5e81fa;
            box-shadow: 0 2px 10px rgba(60, 100, 210, 0.13);
        }

        .avatar-options .avatar-option img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-title {
            text-align: center;
            font-size: 1.35rem;
            font-weight: 600;
            color: #232b43;
            margin-bottom: 2px;
        }

        .profile-email {
            font-size: 1rem;
            color: #6c7a93;
            text-align: center;
            margin-bottom: 16px;
        }

        .address-card {
            border: 1.5px solid #e3e8f4;
            border-radius: 10px;
            padding: 18px 20px 10px 20px;
            margin-bottom: 18px;
            background: #f9fbfc;
        }

        .address-card .fa {
            color: #5e81fa;
            margin-right: 8px;
        }

        .address-actions {
            display: flex;
            gap: 10px;
            margin-top: 6px;
        }

        .address-actions button {
            border: none;
            background: none;
            color: #5e81fa;
            font-weight: 500;
            cursor: pointer;
            font-size: 0.97rem;
        }

        .add-address-btn {
            background: #5e81fa;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 7px;
            font-size: 1.02rem;
            font-weight: 600;
            margin-bottom: 15px;
            transition: background 0.17s;
        }

        .add-address-btn:hover {
            background: #3b63e8;
        }

        @media (max-width: 600px) {
            .profile-container {
                padding: 1rem 0.35rem;
            }

            .address-card {
                padding: 12px 9px 5px 9px;
            }
        }
    </style>
</head>

<body class="bg-[#f8f9fb] font-inter min-h-screen">
    {{-- Header --}}
    @include('components.header')

    <div class="container py-32 profile-container" style="max-width:480px;">
        <!-- Avatar Selection -->
        <div class="profile-avatar d-flex justify-content-center mb-3">
            <img id="main-avatar"
                src="@if($user->avatar ?? false){{ asset('storage/' . $user->avatar) }}@else{{ 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=96&background=5e81fa&color=fff' }}@endif"
                alt="Avatar">
        </div>
        <!-- Cool Avatar Choices -->
        <div class="avatar-options d-flex justify-content-center gap-2 mb-2" id="avatar-options">
            <div class="avatar-option selected border p-1 rounded-circle"
                data-avatar="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=96&background=5e81fa&color=fff">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=96&background=5e81fa&color=fff"
                    alt="Default">
            </div>
            <div class="avatar-option border p-1 rounded-circle"
                data-avatar="https://api.dicebear.com/7.x/bottts-neutral/svg?seed={{ urlencode($user->name) }}">
                <img src="https://api.dicebear.com/7.x/bottts-neutral/svg?seed={{ urlencode($user->name) }}"
                    alt="Bot Avatar">
            </div>
            <div class="avatar-option border p-1 rounded-circle"
                data-avatar="https://api.dicebear.com/7.x/pixel-art/svg?seed={{ urlencode($user->name) }}">
                <img src="https://api.dicebear.com/7.x/pixel-art/svg?seed={{ urlencode($user->name) }}"
                    alt="Pixel Avatar">
            </div>
            <div class="avatar-option border p-1 rounded-circle"
                data-avatar="https://api.dicebear.com/7.x/adventurer/svg?seed={{ urlencode($user->name) }}">
                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed={{ urlencode($user->name) }}"
                    alt="Adventurer Avatar">
            </div>
        </div>
        <!-- Profile Title -->
        <div class="profile-title mb-1">{{ $user->name }}</div>
        <div class="profile-email mb-3">
            {{ $user->email }}
            @if(!$user->hasVerifiedEmail())
                <a href="#" class="verify-btn ms-2"><i class="fa fa-exclamation-circle"></i> Verify</a>
            @else
                <span class="badge bg-success ms-2"><i class="fa fa-check-circle"></i> Verified</span>
            @endif
        </div>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0 text-primary fw-bold"><i class="fa fa-map-marker"></i> Addresses</h6>
                <button class="add-address-btn" type="button" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                    <i class="fa fa-plus"></i> Add New
                </button>
            </div>
            {{-- List of addresses --}}
            @forelse($addresses as $address)
                <div class="address-card mb-2">
                    <div>
                        <strong>{{ $address->name }}</strong>
                        <span class="badge bg-light text-dark ms-2">{{ $address->type }}</span>
                    </div>
                    <div class="small mb-1">
                        <span>{{ $address->full_name }}</span><br>
                        {{ $address->street_address }}<br>
                        {{ $address->city }}, {{ $address->state }} {{ $address->zip }}<br>
                        {{ $address->country }}
                        <br>Phone: {{ $address->phone }}
                    </div>
                    <div class="address-actions">
                        <button type="button"
                            onclick="window.location='{{ route('profile.addresses.edit', $address->id) }}'">
                            <i class="fa fa-pencil"></i> Edit
                        </button>
                        <form method="POST" action="{{ route('profile.addresses.destroy', $address->id) }}"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-danger" onclick="return confirm('Delete this address?')">
                                <i class="fa fa-trash"></i> Remove
                            </button>
                        </form>
                        @if($address->is_default)
                            <span class="badge bg-primary ms-2">Default</span>
                        @else
                            <form method="POST" action="{{ route('profile.addresses.set-default', $address->id) }}"
                                style="display:inline;">
                                @csrf
                                <button type="submit" class="text-success"><i class="fa fa-check"></i> Set Default</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-muted text-center py-2">No address added yet.</div>
            @endforelse
        </div>

        <!-- Add Address Modal (Bootstrap 5) -->
        <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAddressModalLabel"><i class="fa fa-map-marker"></i> Add New
                            Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addressForm" method="POST" action="{{ route('profile.addresses.store') }}">
                            @csrf

                            <div class="mb-2">
                                <label class="form-label" for="full_name">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name"
                                    placeholder="Full Name" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="type">Type</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="home">Home</option>
                                    <option value="office">Office</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="street_address">Street Address</label>
                                <input type="text" class="form-control" id="street_address" name="street_address"
                                    placeholder="Street, house no., etc." required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="city">City</label>
                                <input type="text" class="form-control" id="city" name="city" placeholder="City"
                                    required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="state">State</label>
                                <input type="text" class="form-control" id="state" name="state" placeholder="State"
                                    required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="zip">ZIP / Postal Code</label>
                                <input type="text" class="form-control" id="zip" name="zip"
                                    placeholder="ZIP / Postal Code" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="country">Country</label>
                                <input type="text" class="form-control" id="country" name="country"
                                    placeholder="Country" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone"
                                    required>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="1" id="is_default"
                                    name="is_default">
                                <label class="form-check-label" for="is_default">
                                    Set as default address
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Save Address</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <form class="profile-form mt-3" autocomplete="off">
            <!-- Name -->
            <div class="form-group mb-3">
                <label class="form-label" for="name"><i class="fa fa-user"></i> Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
            </div>
            <!-- Gender -->
            <div class="form-group mb-3">
                <label class="form-label" for="gender"><i class="fa fa-venus-mars"></i> Gender</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="">Select Gender</option>
                    <option value="male" @if($user->gender == 'male') selected @endif>Male</option>
                    <option value="female" @if($user->gender == 'female') selected @endif>Female</option>
                    <option value="other" @if($user->gender == 'other') selected @endif>Other</option>
                    <option value="prefer_not_say" @if($user->gender == 'prefer_not_say') selected @endif>Prefer not to
                        say</option>
                </select>
            </div>
            <!-- Phone -->
            <div class="form-group mb-3">
                <label class="form-label" for="phone"><i class="fa fa-phone"></i> Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone ?? '' }}"
                    placeholder="Enter your phone number">
            </div>
            <!-- Password -->
            <div class="password-update-link text-end mb-2">
                <a href="#">Update Password</a>
            </div>
            <!-- Save Button -->
            <div class="form-actions d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Save Changes</button>
                <button type="button" class="btn btn-outline-primary flex-grow-1">Cancel</button>
            </div>
        </form>
    </div>

    {{-- Footer --}}
    @include('components.footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/CustomEase.min.js"></script>
    <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/hero-gsap.js') }}"></script>
    <script>
        // Avatar chooser (frontend only)
        document.querySelectorAll('.avatar-option').forEach(opt => {
            opt.addEventListener('click', function () {
                document.querySelectorAll('.avatar-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('main-avatar').src = this.dataset.avatar;
            });
        });
    </script>
</body>

</html>