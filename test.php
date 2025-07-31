<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Discord Style</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --discord-dark-1: #202225; /* Very dark background */
            --discord-dark-2: #2f3136; /* Card/panel background */
            --discord-dark-3: #36393f; /* Input/lighter panel background */
            --discord-blue: #5865f2; /* Primary blue for buttons/links */
            --discord-green: #3ba55c; /* Save button */
            --discord-red: #ed4245; /* Delete/Danger */
            --discord-text-primary: #dcddde;
            --discord-text-secondary: #b9bbbe;
            --discord-text-placeholder: #8e9297;
            --discord-border: rgba(0,0,0,0.15);
        }

        body {
            background-color: var(--discord-dark-1);
            color: var(--discord-text-primary);
            font-family: 'Inter', sans-serif;
        }

        .discord-sidebar {
            background-color: var(--discord-dark-2);
            color: var(--discord-text-secondary);
            min-height: 100vh;
            padding: 20px;
        }

        .discord-sidebar .nav-link {
            color: var(--discord-text-secondary);
            padding: 8px 15px;
            border-radius: 4px;
            transition: background-color 0.2s ease;
            font-weight: 500;
        }

        .discord-sidebar .nav-link:hover,
        .discord-sidebar .nav-link.active {
            background-color: var(--discord-dark-3);
            color: var(--discord-text-primary);
        }

        .discord-sidebar .nav-link.active {
            color: #fff; /* Active link often brighter */
            background-color: var(--discord-blue);
        }

        .discord-sidebar .logout-link {
            color: var(--discord-red);
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: 500;
        }
        .discord-sidebar .logout-link:hover {
            background-color: rgba(237, 66, 69, 0.2);
        }


        .discord-content {
            background-color: var(--discord-dark-2);
            border-radius: 8px;
            padding: 25px;
            margin-left: 20px; /* Adjust as needed */
            margin-right: 20px; /* Adjust as needed */
        }

        .discord-card {
            background-color: var(--discord-dark-3);
            border: none;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .form-label {
            color: var(--discord-text-secondary);
            font-size: 0.85rem;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-control {
            background-color: var(--discord-dark-1);
            border: 1px solid var(--discord-border);
            color: var(--discord-text-primary);
            padding: 10px 12px;
            font-size: 1rem;
        }

        .form-control::placeholder {
            color: var(--discord-text-placeholder);
        }

        .form-control:focus {
            background-color: var(--discord-dark-1);
            color: var(--discord-text-primary);
            border-color: var(--discord-blue);
            box-shadow: 0 0 0 0.25rem rgba(88, 101, 242, 0.25); /* Focus ring */
        }

        .btn-discord-primary {
            background-color: var(--discord-blue);
            border-color: var(--discord-blue);
            color: #fff;
            font-weight: 600;
            padding: 10px 20px;
        }

        .btn-discord-primary:hover {
            background-color: #4752c4; /* Darker blue on hover */
            border-color: #4752c4;
            color: #fff;
        }

        .btn-discord-secondary {
            background-color: var(--discord-dark-3);
            border-color: var(--discord-dark-3);
            color: var(--discord-text-primary);
            font-weight: 600;
            padding: 10px 20px;
        }

        .btn-discord-secondary:hover {
            background-color: #2e3035; /* Darker grey on hover */
            border-color: #2e3035;
            color: var(--discord-text-primary);
        }

        .btn-discord-red {
            background-color: var(--discord-red);
            border-color: var(--discord-red);
            color: #fff;
            font-weight: 600;
            padding: 10px 20px;
        }

        .btn-discord-red:hover {
            background-color: #cc3739;
            border-color: #cc3739;
            color: #fff;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .profile-avatar-container {
            position: relative;
            width: 96px; /* Discord's default avatar size */
            height: 96px;
            border-radius: 50%;
            overflow: hidden;
            background-color: #6a7480; /* Default avatar background */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0; /* Prevent shrinking */
        }

        .profile-avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.2s ease;
            cursor: pointer;
        }

        .profile-avatar-container:hover .avatar-overlay {
            opacity: 1;
        }

        .avatar-overlay svg {
            color: #fff;
            font-size: 2rem;
            margin-bottom: 5px;
        }

        .avatar-overlay span {
            color: #fff;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .profile-details {
            margin-left: 20px;
        }

        .profile-username {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #fff; /* White for username */
        }

        .profile-discriminator {
            color: var(--discord-text-secondary);
            font-size: 1.1rem;
            font-weight: 500;
        }

        .preview-section {
            background-color: var(--discord-dark-3);
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }

        .preview-card {
            background-color: var(--discord-dark-1); /* Darker background for the preview card itself */
            border-radius: 8px;
            padding: 20px;
            position: relative;
            overflow: hidden; /* For banner */
        }

        .preview-banner {
            height: 80px; /* Example banner height */
            background-color: var(--discord-blue); /* Example banner color */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }

        .preview-avatar-wrap {
            position: relative;
            margin-top: 40px; /* To account for banner */
            margin-left: 15px;
            border: 6px solid var(--discord-dark-1); /* Border color matching the card background */
            border-radius: 50%;
            width: 90px;
            height: 90px;
            z-index: 1; /* Above banner */
            background-color: var(--discord-dark-1); /* Fallback */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .preview-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        .preview-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #fff;
            margin-top: 10px;
            margin-left: 15px;
        }

        .preview-bio {
            color: var(--discord-text-secondary);
            font-size: 0.9rem;
            margin-top: 10px;
            margin-left: 15px;
        }

        .input-group-text {
            background-color: var(--discord-dark-1);
            border: 1px solid var(--discord-border);
            color: var(--discord-text-secondary);
        }

        /* Responsive adjustments for smaller screens */
        @media (max-width: 767.98px) {
            .discord-sidebar {
                min-height: auto;
                padding: 15px;
            }
            .discord-sidebar .nav-item {
                display: inline-block;
                margin-right: 10px;
                margin-bottom: 5px;
            }
            .discord-content {
                margin-left: 0;
                margin-right: 0;
                margin-top: 20px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3K4G92d+R+f+7+5+0w+l+A/g/d/Q/J/k/p/N/V/w/x/y/z/A/B/C/D/E/F/G/H/I/J/K/L/M/N/O/P/Q/R/S/T/U/V/W/X/Y/Z/a/b/c/d/e/f/g/h/i/j/k/l/m/n/o/p/q/r/s/t/u/v/w/x/y/z/0/1/2/3/4/5/6/7/8/9/+/=" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

    <div class="d-flex" style="min-height: 100vh;">
        <nav class="discord-sidebar col-md-3 col-lg-2 d-md-block sticky-top">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <h6 class="text-uppercase text-white-50 mt-3 mb-2">My Account</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">User Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Profiles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Privacy & Safety</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Authorized Apps</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Connections</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Friend Requests</a>
                </li>

                <li class="nav-item">
                    <h6 class="text-uppercase text-white-50 mt-4 mb-2">App Settings</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Voice & Video</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Notifications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Keybinds</a>
                </li>
                <li class="nav-item mt-auto pt-3"> <a class="nav-link logout-link" href="#">Log Out <i class="fas fa-sign-out-alt ms-2"></i></a>
                </li>
            </ul>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 p-4">
            <div class="discord-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0 text-white">My Account</h1>
                    <button type="button" class="btn btn-sm btn-outline-secondary text-white-50 border-0">
                        <i class="fas fa-times"></i> ESC
                    </button>
                </div>

                <div class="discord-card">
                    <h4 class="text-white mb-4">User Profile</h4>

                    <div class="profile-header">
                        <div class="profile-avatar-container" id="profileAvatarContainer">
                            <img src="https://i.pravatar.cc/96?img=68" alt="User Avatar" class="profile-avatar" id="currentProfileAvatar">
                            <label for="avatarUpload" class="avatar-overlay">
                                <i class="fas fa-camera"></i>
                                <span>CHANGE AVATAR</span>
                            </label>
                            <input type="file" id="avatarUpload" class="d-none" accept="image/*">
                        </div>
                        <div class="profile-details">
                            <div class="profile-username">JohnDoe<span class="profile-discriminator">#1234</span></div>
                            <small class="text-white-50">This is how you appear on Discord.</small>
                        </div>
                    </div>

                    <form>
                        <div class="mb-3">
                            <label for="displayName" class="form-label">Display Name <small class="text-white-50 ms-2">(Optional)</small></label>
                            <input type="text" class="form-control" id="displayName" value="John Doe">
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="username" value="johndoe">
                                <span class="input-group-text">#1234</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="aboutMe" class="form-label">About Me</label>
                            <textarea class="form-control" id="aboutMe" rows="3" placeholder="A short description about yourself..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="pronouns" class="form-label">Pronouns <small class="text-white-50 ms-2">(Optional)</small></label>
                            <input type="text" class="form-control" id="pronouns" placeholder="e.g., he/him, they/them">
                        </div>

                        <div class="mb-4">
                            <label for="bannerColor" class="form-label">Profile Banner <small class="text-white-50 ms-2">(Nitro required)</small></label>
                            <input type="color" class="form-control form-control-color" id="bannerColor" value="#5865f2" title="Choose your banner color">
                        </div>

                        <div class="d-flex justify-content-end align-items-center mt-4 p-3 rounded" style="background-color: var(--discord-dark-1); border-top: 1px solid var(--discord-border);">
                            <span class="text-white-50 me-3">You have unsaved changes.</span>
                            <button type="button" class="btn btn-discord-secondary me-2">Reset</button>
                            <button type="submit" class="btn btn-discord-primary">Save Changes</button>
                        </div>
                    </form>
                </div>

                <div class="preview-section">
                    <h5 class="text-white mb-3">Profile Preview</h5>
                    <div class="preview-card">
                        <div class="preview-banner" id="previewBanner"></div>
                        <div class="preview-avatar-wrap">
                            <img src="https://i.pravatar.cc/96?img=68" alt="Preview Avatar" class="preview-avatar" id="previewProfileAvatar">
                        </div>
                        <h4 class="preview-name" id="previewDisplayName">John Doe</h4>
                        <p class="preview-bio" id="previewAboutMe">A short description about yourself...</p>
                        </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-discord-red">Disable Account</button>
                    <button type="button" class="btn btn-outline-secondary text-white-50 ms-2">Delete Account</button>
                </div>

            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcSanvYdY2dftrR7beyb" crossorigin="anonymous"></script>
    <script>
        // JavaScript for Avatar and Banner preview
        const avatarUpload = document.getElementById('avatarUpload');
        const currentProfileAvatar = document.getElementById('currentProfileAvatar');
        const previewProfileAvatar = document.getElementById('previewProfileAvatar');
        const displayNameInput = document.getElementById('displayName');
        const aboutMeInput = document.getElementById('aboutMe');
        const previewDisplayName = document.getElementById('previewDisplayName');
        const previewAboutMe = document.getElementById('previewAboutMe');
        const bannerColorInput = document.getElementById('bannerColor');
        const previewBanner = document.getElementById('previewBanner');

        avatarUpload.addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                const imageUrl = URL.createObjectURL(file);
                currentProfileAvatar.src = imageUrl;
                previewProfileAvatar.src = imageUrl;
            }
        });

        displayNameInput.addEventListener('input', function() {
            previewDisplayName.textContent = displayNameInput.value || 'John Doe'; // Fallback
        });

        aboutMeInput.addEventListener('input', function() {
            previewAboutMe.textContent = aboutMeInput.value || 'A short description about yourself...'; // Fallback
        });

        bannerColorInput.addEventListener('input', function() {
            previewBanner.style.backgroundColor = bannerColorInput.value;
        });

        // Initialize preview values
        document.addEventListener('DOMContentLoaded', () => {
            previewDisplayName.textContent = displayNameInput.value;
            previewAboutMe.textContent = aboutMeInput.value;
            previewBanner.style.backgroundColor = bannerColorInput.value;
        });
    </script>
</body>
</html>