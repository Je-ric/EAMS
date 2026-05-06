# EAMS — Social Login Flow (Google / Facebook)

This document explains how Google and Facebook OAuth login works in EAMS using Laravel Socialite.

---

## Flow Overview

```
[User clicks Google/Facebook login button]
    │
    GET /auth/google or /auth/facebook
    │
    SocialAuthController@redirectGoogle / redirectFacebook
    │
    └── Socialite redirects user to provider login page
            │
            [User logs in on Google/Facebook and grants permissions]
            │
    GET /auth/google/callback or /auth/facebook/callback
            │
    SocialAuthController@handleGoogleCallback / handleFacebookCallback
            │
            ├── Fetch user info from provider (name, email, provider_id, token)
            ├── findOrCreateSocialUser()
            │       ├── If email exists in DB → update provider_id + provider_token
            │       └── If not → create new User (role = employee)
            ├── Auth::login($user) → log in and regenerate session
            └── Redirect to home page
```

---

## Step-by-Step

### Step 1 — User Clicks Login Button

The login button links to the named route for the provider:
- Google: `route('auth.google')` → `GET /auth/google`
- Facebook: `route('auth.facebook')` → `GET /auth/facebook`

---

### Step 2 — Redirect to Provider

**Controller method:** `redirectGoogle()` / `redirectFacebook()`

The controller uses the Socialite driver to redirect the user to the provider's OAuth login page.

The redirect URL is configured in `config/services.php`, which reads from `.env`:

```php
// config/services.php
'google' => [
    'client_id'     => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect'      => env('GOOGLE_REDIRECT_URI'),
],
'facebook' => [
    'client_id'     => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect'      => env('FACEBOOK_REDIRECT_URI'),
],
```

---

### Step 3 — User Logs In on Provider

The user logs in on Google or Facebook and grants the requested permissions. The provider then redirects back to the callback URL defined in `REDIRECT_URI`.

---

### Step 4 — Callback Received

**Controller method:** `handleGoogleCallback()` / `handleFacebookCallback()`

The controller receives the OAuth response and fetches the user's information from the provider.

---

### Step 5 — Find or Create User

**Helper method:** `findOrCreateSocialUser($socialUser, $provider)`

```
Check if a User with the same email exists in the database
    │
    ├── If YES → update provider_id and provider_token
    └── If NO  → create new User:
                    ├── name from provider
                    ├── email from provider
                    ├── random password (not used for login)
                    └── role = 'employee'
```

---

### Step 6 — Log In and Redirect

```php
Auth::login($user);
$request->session()->regenerate();
return redirect()->route('index');
```

The user is logged in, the session is regenerated (prevents session fixation), and they are redirected to the home page.

---

## Environment Variables

```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback
```

---

## Routes

| Method | URI | Controller | Action |
|---|---|---|---|
| GET | `/auth/google` | SocialAuthController | redirectToGoogle |
| GET | `/auth/google/callback` | SocialAuthController | handleGoogleCallback |
| GET | `/auth/facebook` | SocialAuthController | redirectToFacebook |
| GET | `/auth/facebook/callback` | SocialAuthController | handleFacebookCallback |

---

## Notes

- `stateless()` is used to disable session-based OAuth state. This means Socialite does not store the OAuth state in the session. Useful when sessions may not be persistent, but you take responsibility for security.
- Social login creates users with `role = employee`. They can time in/out but cannot access admin features.
- If a user already exists with the same email (registered normally), their account is linked to the social provider on first social login.
